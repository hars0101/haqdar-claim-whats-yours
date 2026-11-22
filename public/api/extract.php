<?php
declare(strict_types=1);
require_once __DIR__ . '/../../app/aadhaar.php';
require_once __DIR__ . '/../../app/config_loader.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

const PROFILE_FIELDS = ['age', 'gender', 'category', 'family_income', 'education_level', 'course', 'institution_type', 'state', 'disability', 'marks_percent'];
const MAX_TEXT_CHARS = 5000;

// Values must match seed.sql rules (category, education_level, institution_type, state, gender, disability).
const CATEGORIES = ['SC' => 'SC', 'ST' => 'ST', 'BC' => 'BC', 'EWS' => 'EWS', 'MINORITY' => 'Minority', 'OC' => 'OC'];
const EDUCATION_LEVELS = ['class_8', 'class_9_10', 'intermediate', 'diploma', 'ug', 'pg', 'phd'];
const INSTITUTION_TYPES = ['government', 'aided', 'private'];

const LLM_SYSTEM_PROMPT = <<<'TXT'
You extract a student's scholarship eligibility profile from text written by the student (English or Telugu).
Return ONLY a JSON object with exactly these keys:
- age: integer or null
- gender: "male", "female" or null
- category: "SC", "ST", "BC", "EWS", "Minority", "OC" or null
- family_income: total annual family income in rupees as an integer, or null
- education_level: "class_8", "class_9_10", "intermediate", "diploma", "ug", "pg", "phd" or null
- course: the course name in English (e.g. "B.Tech", "Diploma"), or null
- institution_type: "government", "aided", "private" or null
- state: Indian state name in English, or null
- disability: true, false or null
- marks_percent: number from 0 to 100, or null
Use null for anything not stated. Do not guess.
TXT;

function send_json(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function to_num(mixed $v): ?float
{
    if (is_int($v) || is_float($v)) {
        return (float)$v;
    }
    if (!is_string($v)) {
        return null;
    }
    $s = str_replace(',', '', trim($v));
    return is_numeric($s) ? (float)$s : null;
}

function clean_str(mixed $v): ?string
{
    if (!is_string($v)) {
        return null;
    }
    $s = trim($v);
    return ($s === '' || strtolower($s) === 'null') ? null : $s;
}

/** Coerces any extractor output (LLM or regex) to the agreed profile types. */
function normalize_profile(array $raw): array
{
    $p = array_fill_keys(PROFILE_FIELDS, null);

    $age = to_num($raw['age'] ?? null);
    $p['age'] = ($age !== null && $age >= 1 && $age <= 120) ? (int)$age : null;

    $income = to_num($raw['family_income'] ?? null);
    $p['family_income'] = ($income !== null && $income >= 0) ? (int)round($income) : null;

    $marks = to_num($raw['marks_percent'] ?? null);
    $p['marks_percent'] = ($marks !== null && $marks >= 0 && $marks <= 100) ? round($marks, 2) : null;

    $gender = strtolower((string)clean_str($raw['gender'] ?? null));
    $p['gender'] = in_array($gender, ['male', 'female', 'other'], true) ? $gender : null;

    $cat = strtoupper((string)clean_str($raw['category'] ?? null));
    if (str_starts_with($cat, 'BC')) {
        $cat = 'BC';
    }
    $p['category'] = CATEGORIES[$cat] ?? null;

    $edu = strtolower((string)clean_str($raw['education_level'] ?? null));
    if ($edu === 'degree' || $edu === 'graduation') {
        $edu = 'ug';
    }
    $p['education_level'] = in_array($edu, EDUCATION_LEVELS, true) ? $edu : null;

    $course = clean_str($raw['course'] ?? null);
    $p['course'] = $course === null ? null : mb_substr($course, 0, 100, 'UTF-8');

    $inst = strtolower((string)clean_str($raw['institution_type'] ?? null));
    $p['institution_type'] = in_array($inst, INSTITUTION_TYPES, true) ? $inst : null;

    $p['state'] = clean_str($raw['state'] ?? null);

    $d = $raw['disability'] ?? null;
    if (is_bool($d)) {
        $p['disability'] = $d;
    } elseif (is_string($d)) {
        $ds = strtolower(trim($d));
        if (in_array($ds, ['true', 'yes'], true)) {
            $p['disability'] = true;
        } elseif (in_array($ds, ['false', 'no'], true)) {
            $p['disability'] = false;
        }
    }

    return $p;
}

function parse_money(string $num, string $unit): ?int
{
    $n = to_num($num);
    if ($n === null) {
        return null;
    }
    if (preg_match('/lakh|lac|లక్ష/u', $unit)) {
        $n *= 100000;
    } elseif (preg_match('/thousand|వేల|k/u', $unit)) {
        $n *= 1000;
    }
    return (int)round($n);
}

/** Keyword / regex extraction, English + Telugu. Used when the LLM is off or fails. */
function regex_extract(string $text): array
{
    $t = mb_strtolower($text, 'UTF-8');
    $start = '(?<![\p{L}\p{M}\d.])';
    $end = '(?![\p{L}\p{M}])';
    $raw = [];

    // Age
    if (preg_match('/' . $start . '(?:age|వయస్సు|వయసు)' . $end . '\s*[:=-]?\s*(\d{1,3})/u', $t, $m)
        || preg_match('/' . $start . '(\d{1,3})\s*(?:years?|yrs?|సంవత్సరాల|సంవత్సరం|ఏళ్ల|ఏళ్ళ|ఏళ్లు)' . $end . '/u', $t, $m)) {
        $raw['age'] = $m[1];
    }

    // Gender (female checked first, so "female" is not read as "male")
    if (preg_match('/(?:' . $start . '(?:female|woman|women|girl|daughter)' . $end . '|అమ్మాయి|ఆడపిల్ల|మహిళ|స్త్రీ)/u', $t)) {
        $raw['gender'] = 'female';
    } elseif (preg_match('/(?:' . $start . '(?:male|man|boy|son)' . $end . '|అబ్బాయి|కొడుకు|పురుష|మగ)/u', $t)) {
        $raw['gender'] = 'male';
    }

    // Category
    $categoryPatterns = [
        'EWS' => '(?:' . $start . 'ews' . $end . '|ఈడబ్ల్యూఎస్)',
        'SC' => '(?:' . $start . 'sc' . $end . '|ఎస్సీ|షెడ్యూల్డ్\s*కులం)',
        'ST' => '(?:' . $start . 'st' . $end . '|ఎస్టీ|షెడ్యూల్డ్\s*తెగ)',
        'BC' => '(?:' . $start . 'bc(?:[-\s]?[a-e])?' . $end . '|బీసీ)',
        'Minority' => '(?:' . $start . 'minorit(?:y|ies)' . $end . '|మైనారిటీ)',
        'OC' => '(?:' . $start . 'oc' . $end . '|ఓసీ)',
    ];
    foreach ($categoryPatterns as $code => $pattern) {
        if (preg_match('/' . $pattern . '/u', $t)) {
            $raw['category'] = $code;
            break;
        }
    }

    // Annual family income: number + optional unit (lakh / thousand), near an income word
    $incomeWord = '(?:annual\s+|family\s+|yearly\s+)*(?:income|ఆదాయం|ఆదాయ)';
    $num = '(\d[\d,]*(?:\.\d+)?)';
    $unit = '\s*(?:(lakhs?|lacs?|లక్షలు|లక్ష|thousands?|వేలు|వేల|k)' . $end . ')?';
    $income = null;
    if (preg_match('/' . $incomeWord . '[^\d\n]{0,40}?' . $num . $unit . '/u', $t, $m)) {
        $income = parse_money($m[1], $m[2] ?? '');
    } elseif (preg_match('/' . $num . $unit . '[^\d\n]{0,40}?' . $incomeWord . '/u', $t, $m)) {
        $income = parse_money($m[1], $m[2] ?? '');
    } elseif (preg_match('/' . $incomeWord . '[^\n]{0,40}?(?:లక్ష|lakh)/u', $t)) {
        $income = 100000;
    }
    if ($income !== null) {
        $raw['family_income'] = $income;
    }

    // Education level (highest first)
    $educationPatterns = [
        'phd' => '(?:' . $start . 'ph\.?\s?d' . $end . ')',
        'pg' => '(?:' . $start . '(?:pg|m\.?\s?tech|m\.?\s?sc|mba|mca|post\s?graduat\w*)' . $end . '|పీజీ|ఎంటెక్)',
        'ug' => '(?:' . $start . '(?:degree|graduat\w*|b\.?\s?tech|b\.?\s?sc|b\.?\s?com|b\.?\s?e)' . $end . '|డిగ్రీ|బీటెక్|బీఎస్సీ|బీకామ్)',
        'diploma' => '(?:' . $start . '(?:diploma|polytechnic)' . $end . '|డిప్లొమా|పాలిటెక్నిక్)',
        'intermediate' => '(?:' . $start . '(?:inter(?:mediate)?|12th|twelfth|\+2|plus\s?two)' . $end . '|ఇంటర్మీడియట్|ఇంటర్|ఇంటర)',
        'class_9_10' => '(?:' . $start . '(?:9th|10th|tenth|ninth|class\s?(?:9|10))' . $end . '|పదో\s*తరగతి|పదవ\s*తరగతి|9వ\s*తరగతి|10వ\s*తరగతి)',
        'class_8' => '(?:' . $start . '(?:8th|eighth|class\s?8)' . $end . '|8వ\s*తరగతి|ఎనిమిదో\s*తరగతి|ఎనిమిదవ\s*తరగతి)',
    ];
    foreach ($educationPatterns as $code => $pattern) {
        if (preg_match('/' . $pattern . '/u', $t)) {
            $raw['education_level'] = $code;
            break;
        }
    }

    // Course
    $coursePatterns = [
        'B.Tech' => '(?:' . $start . 'b\.?\s?tech' . $end . '|బీటెక్|బిటెక్)',
        'M.Tech' => '(?:' . $start . 'm\.?\s?tech' . $end . '|ఎంటెక్)',
        'Engineering' => '(?:' . $start . 'engineering' . $end . '|ఇంజనీరింగ్|ఇంజినీరింగ్)',
        'Diploma' => '(?:' . $start . 'diploma' . $end . '|డిప్లొమా)',
        'MBBS' => '(?:' . $start . 'mbbs' . $end . ')',
        'B.Sc' => '(?:' . $start . 'b\.?\s?sc' . $end . '|బీఎస్సీ)',
        'B.Com' => '(?:' . $start . 'b\.?\s?com' . $end . '|బీకామ్)',
        'MBA' => '(?:' . $start . 'mba' . $end . ')',
        'Pharmacy' => '(?:' . $start . 'pharmacy' . $end . '|ఫార్మసీ)',
        'ITI' => '(?:' . $start . 'iti' . $end . '|ఐటీఐ)',
    ];
    foreach ($coursePatterns as $label => $pattern) {
        if (preg_match('/' . $pattern . '/u', $t)) {
            $raw['course'] = $label;
            break;
        }
    }

    // Institution type
    if (preg_match('/government|govt|ప్రభుత్వ/u', $t)) {
        $raw['institution_type'] = 'government';
    } elseif (preg_match('/private|ప్రైవేట్|ప్రైవేటు/u', $t)) {
        $raw['institution_type'] = 'private';
    }

    // State
    if (preg_match('/telangana|తెలంగాణ/u', $t)) {
        $raw['state'] = 'Telangana';
    } elseif (preg_match('/andhra\s*pradesh|andhra|ఆంధ్ర/u', $t)) {
        $raw['state'] = 'Andhra Pradesh';
    }

    // Disability (ignores "non-disabled" / "non disabled")
    if (preg_match('/(?<!non-)(?<!non )(?:disab|handicap|దివ్యాంగ|వికలాంగ)/u', $t)) {
        $raw['disability'] = true;
    }

    // Marks: "85%", "85 percent", "85 శాతం", or "marks 85"
    if (preg_match('/(\d{1,3}(?:\.\d+)?)\s*(?:%|percent|శాతం)/u', $t, $m)
        || preg_match('/(?:marks|percentage|score|మార్కులు)\s*[:=-]?\s*(\d{1,3}(?:\.\d+)?)/u', $t, $m)) {
        $raw['marks_percent'] = $m[1];
    }

    return normalize_profile($raw);
}

/** Calls the OpenAI-compatible endpoint from config. Returns null on any failure. */
function llm_extract(string $text): ?array
{
    $cfg = app_config();
    $key = trim((string)($cfg['llm_api_key'] ?? ''));
    $endpoint = trim((string)($cfg['llm_endpoint'] ?? ''));
    if ($key === '' || $endpoint === '' || $text === '') {
        return null;
    }

    $payload = json_encode([
        'model' => (string)($cfg['llm_model'] ?? 'gpt-4o-mini'),
        'temperature' => 0,
        'response_format' => ['type' => 'json_object'],
        'messages' => [
            ['role' => 'system', 'content' => LLM_SYSTEM_PROMPT],
            ['role' => 'user', 'content' => $text],
        ],
    ], JSON_UNESCAPED_UNICODE);
    if ($payload === false) {
        return null;
    }

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 25,
    ]);
    $resp = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($resp === false || $code !== 200) {
        error_log('extract.php: LLM call failed (HTTP ' . $code . ') ' . $err);
        return null;
    }

    $data = json_decode((string)$resp, true);
    $content = is_array($data) ? ($data['choices'][0]['message']['content'] ?? null) : null;
    $parsed = is_string($content) ? json_decode($content, true) : null;
    if (!is_array($parsed)) {
        error_log('extract.php: LLM returned non-JSON content');
        return null;
    }
    return normalize_profile($parsed);
}

// ---- main ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(405, ['error' => 'Use POST']);
}

$raw = file_get_contents('php://input') ?: '';
$json = json_decode($raw, true);
$text = (is_array($json) && isset($json['text']) && is_string($json['text']))
    ? $json['text']
    : (string)($_POST['text'] ?? '');

$text = trim(mb_substr($text, 0, MAX_TEXT_CHARS, 'UTF-8'));
if ($text === '') {
    send_json(400, ['error' => 'Please paste some text about yourself.']);
}

// Aadhaar is removed before anything else; the LLM only ever sees $clean.
[$clean, $blocked] = strip_aadhaar($text);

$regexProfile = regex_extract($clean);
$llmProfile = llm_extract($clean);

if ($llmProfile !== null) {
    // LLM result wins; regex fills only the fields the LLM left null.
    $profile = $llmProfile;
    foreach (PROFILE_FIELDS as $f) {
        if ($profile[$f] === null) {
            $profile[$f] = $regexProfile[$f];
        }
    }
} else {
    $profile = $regexProfile;
}

$missing = [];
foreach (PROFILE_FIELDS as $f) {
    if ($profile[$f] === null) {
        $missing[] = $f;
    }
}

send_json(200, [
    'profile' => $profile,
    'blocked' => $blocked,
    'missing_fields' => $missing,
]);