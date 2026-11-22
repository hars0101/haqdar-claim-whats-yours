<?php
declare(strict_types=1);
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/rules_engine.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

const PROFILE_FIELDS = ['age', 'gender', 'category', 'family_income', 'education_level', 'course', 'institution_type', 'state', 'disability', 'marks_percent'];

function send_json(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(405, ['error' => 'Use POST']);
}

$body = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($body)) {
    send_json(400, ['error' => 'Invalid JSON body']);
}
$input = isset($body['profile']) && is_array($body['profile']) ? $body['profile'] : $body;

$profile = [];
foreach (PROFILE_FIELDS as $f) {
    $profile[$f] = $input[$f] ?? null;
}

try {
    $pdo = db();

    $schemes = $pdo->query(
        'SELECT id, name, provider, level, benefit_text, official_url, last_verified
         FROM schemes WHERE is_active = 1 ORDER BY name'
    )->fetchAll();

    $rulesQ = $pdo->prepare('SELECT field, operator, value, label FROM scheme_rules WHERE scheme_id = ? ORDER BY sort_order');
    $docsQ = $pdo->prepare('SELECT document_name, notes, cond_field, cond_value FROM scheme_documents WHERE scheme_id = ? ORDER BY sort_order');
    $stepsQ = $pdo->prepare('SELECT step_no, body FROM scheme_steps WHERE scheme_id = ? ORDER BY step_no');
    $deadQ = $pdo->prepare('SELECT deadline_date, label FROM scheme_deadlines WHERE scheme_id = ? AND deadline_date >= CURDATE() ORDER BY deadline_date');

    $out = [];
    foreach ($schemes as $s) {
        $id = (int)$s['id'];

        $rulesQ->execute([$id]);
        $rules = $rulesQ->fetchAll();
        $eval = evaluate_scheme($rules, $profile);

        $docsQ->execute([$id]);
        $documents = [];
        foreach ($docsQ->fetchAll() as $d) {
            $show = true;
            if (!empty($d['cond_field'])) {
                $have = $profile[$d['cond_field']] ?? null;
                $show = $have !== null && strcasecmp((string)$have, (string)$d['cond_value']) === 0;
            }
            if ($show) {
                $documents[] = ['name' => $d['document_name'], 'notes' => $d['notes']];
            }
        }

        $stepsQ->execute([$id]);
        $steps = array_map(fn($r) => ['step_no' => (int)$r['step_no'], 'body' => $r['body']], $stepsQ->fetchAll());

        $deadQ->execute([$id]);
        $deadlines = $deadQ->fetchAll();

        $out[] = [
            'id' => $id,
            'name' => $s['name'],
            'provider' => $s['provider'],
            'level' => $s['level'],
            'benefit' => $s['benefit_text'],
            'status' => $eval['status'],
            'rules' => $eval['rules'],
            'documents' => $documents,
            'steps' => $steps,
            'deadline' => $deadlines[0] ?? null,
            'deadlines' => $deadlines,
            'source_url' => $s['official_url'],
            'last_verified' => $s['last_verified'],
        ];
    }

    $rank = ['likely' => 0, 'maybe' => 1, 'no' => 2];
    usort($out, fn($a, $b) => $rank[$a['status']] <=> $rank[$b['status']]);

    send_json(200, ['profile' => $profile, 'results' => $out]);
} catch (Throwable $e) {
    error_log('match.php: ' . $e->getMessage());
    send_json(500, ['error' => 'Could not load schemes right now.']);
}