<?php
declare(strict_types=1);
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/config_loader.php';
require_once __DIR__ . '/../../app/ics_helpers.php';

const ICS_TZ = 'Asia/Kolkata';

function ics_fail(int $code, string $message): void
{
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    echo $message;
    exit;
}

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD'], true)) {
    header('Allow: GET, HEAD');
    ics_fail(405, 'Use GET');
}

$schemeId = filter_var($_GET['scheme_id'] ?? null, FILTER_VALIDATE_INT);
if ($schemeId === false || $schemeId < 1) {
    ics_fail(400, 'scheme_id must be a positive integer');
}

try {
    $pdo = db();
    // Earliest upcoming deadline for an active scheme.
    $stmt = $pdo->prepare(
        'SELECT s.id, s.slug, s.name, s.official_url,
                d.id AS deadline_id, d.deadline_date, d.label
         FROM schemes s
         JOIN scheme_deadlines d ON d.scheme_id = s.id
         WHERE s.id = ? AND s.is_active = 1 AND d.deadline_date >= CURDATE()
         ORDER BY d.deadline_date ASC
         LIMIT 1'
    );
    $stmt->execute([$schemeId]);
    $row = $stmt->fetch();
} catch (Throwable $e) {
    error_log('ics.php: ' . $e->getMessage());
    ics_fail(500, 'Could not create the calendar file right now.');
}

if ($row === false) {
    ics_fail(404, 'No upcoming deadline for this scheme.');
}

$cfg = app_config();
$host = parse_url((string)($cfg['base_url'] ?? ''), PHP_URL_HOST) ?: 'haqdar.local';

// All-day event: DTEND is the day after DTSTART (exclusive end, per RFC 5545).
$day = new DateTimeImmutable((string)$row['deadline_date'], new DateTimeZone(ICS_TZ));
$dtStart = $day->format('Ymd');
$dtEnd = $day->modify('+1 day')->format('Ymd');
$dtStamp = gmdate('Ymd\THis\Z');

$uid = 'scheme-' . (int)$row['id'] . '-deadline-' . (int)$row['deadline_id'] . '@' . $host;
$summary = $row['name'] . ': ' . $row['label'];
$description = 'Deadline: ' . $row['label'] . '. Check the official site before applying.'
    . (!empty($row['official_url']) ? ' Official site: ' . $row['official_url'] : '');

$lines = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//HaqDar//Scholarship Deadlines//EN',
    'CALSCALE:GREGORIAN',
    'METHOD:PUBLISH',
    'BEGIN:VEVENT',
    'UID:' . ics_escape($uid),
    'DTSTAMP:' . $dtStamp,
    'DTSTART;VALUE=DATE:' . $dtStart,
    'DTEND;VALUE=DATE:' . $dtEnd,
    'SUMMARY:' . ics_escape($summary),
    'DESCRIPTION:' . ics_escape($description),
];
if (!empty($row['official_url'])) {
    $lines[] = 'URL:' . ics_escape((string)$row['official_url']);
}
$lines[] = 'TRANSP:TRANSPARENT';
$lines[] = 'END:VEVENT';
$lines[] = 'END:VCALENDAR';

$filename = 'haqdar-' . preg_replace('/[^a-z0-9-]/', '-', strtolower((string)$row['slug'])) . '.ics';

http_response_code(200);
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo implode("\r\n", $lines) . "\r\n";
}