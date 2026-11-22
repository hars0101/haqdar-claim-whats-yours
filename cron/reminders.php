<?php
declare(strict_types=1);

// Run from the command line only, once a day, e.g.:
//   php /home/YOUR_USER/haqdar/cron/reminders.php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only.');
}

require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/config_loader.php';

const REMINDER_DAYS = [7, 3, 1];

date_default_timezone_set('Asia/Kolkata');

function log_line(string $msg): void
{
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
}

function build_message(array $row, int $daysBefore, string $appName, string $baseUrl): array
{
    $when = $daysBefore === 1 ? 'tomorrow' : 'in ' . $daysBefore . ' days';
    $subject = '[' . $appName . '] ' . $row['name'] . ' deadline ' . $when;

    $body = "Hello,\r\n\r\n"
        . 'The application deadline for ' . $row['name'] . ' is ' . $when
        . ' (' . $row['deadline_date'] . ').' . "\r\n"
        . 'Deadline: ' . $row['label'] . "\r\n\r\n"
        . "Check your documents and submit before the deadline.\r\n";
    if (!empty($row['official_url'])) {
        $body .= 'Official site: ' . $row['official_url'] . "\r\n";
    }
    $body .= "\r\nTrack your progress: " . rtrim($baseUrl, '/') . "/\r\n\r\n"
        . "This is an automatic reminder from $appName.\r\n";

    return [$subject, $body];
}

$cfg = app_config();
$appName = (string)($cfg['app_name'] ?? 'HaqDar');
$mailFrom = trim((string)($cfg['mail_from'] ?? ''));
$baseUrl = (string)($cfg['base_url'] ?? '');

if ($mailFrom === '' || filter_var($mailFrom, FILTER_VALIDATE_EMAIL) === false) {
    log_line('ERROR: mail_from is missing or invalid in config.php. Nothing sent.');
    exit(1);
}

try {
    $pdo = db();

    // Only people who gave an email AND still track a scheme that is not yet received.
    $sql = 'SELECT a.sid, a.scheme_id, se.email, s.name, s.official_url,
                   d.deadline_date, d.label
            FROM applications a
            JOIN sessions se ON se.sid = a.sid
            JOIN schemes s ON s.id = a.scheme_id AND s.is_active = 1
            JOIN scheme_deadlines d ON d.scheme_id = a.scheme_id AND d.deadline_date = ?
            WHERE se.email IS NOT NULL AND se.email <> ""
              AND a.status <> "received"
              AND NOT EXISTS (
                  SELECT 1 FROM reminder_log r
                  WHERE r.sid = a.sid AND r.scheme_id = a.scheme_id
                    AND r.deadline_date = d.deadline_date AND r.days_before = ?
              )';
    $query = $pdo->prepare($sql);
    $log = $pdo->prepare(
        'INSERT IGNORE INTO reminder_log (sid, scheme_id, deadline_date, days_before)
         VALUES (?, ?, ?, ?)'
    );

    $today = new DateTimeImmutable('today', new DateTimeZone('Asia/Kolkata'));
    $sent = 0;
    $failed = 0;
    $skipped = 0;

    foreach (REMINDER_DAYS as $daysBefore) {
        // Compare on dates computed in PHP, so the cron run and the SQL agree on "today".
        $target = $today->modify('+' . $daysBefore . ' days')->format('Y-m-d');
        $query->execute([$target, $daysBefore]);
        $rows = $query->fetchAll();
        log_line("Deadlines on $target ($daysBefore day(s) away): " . count($rows) . ' reminder(s) due');

        foreach ($rows as $row) {
            $email = trim((string)$row['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $skipped++;
                continue;
            }

            [$subject, $body] = build_message($row, $daysBefore, $appName, $baseUrl);
            $headers = [
                'From: ' . $appName . ' <' . $mailFrom . '>',
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                'X-Mailer: PHP/' . PHP_VERSION,
            ];
            $ok = mail(
                $email,
                mb_encode_mimeheader($subject, 'UTF-8', 'B'),
                $body,
                implode("\r\n", $headers)
            );

            if ($ok) {
                // Record only after a successful send, so a failed send is retried tomorrow.
                $log->execute([$row['sid'], (int)$row['scheme_id'], $row['deadline_date'], $daysBefore]);
                $sent++;
            } else {
                $failed++;
                error_log('reminders.php: mail() failed for scheme ' . $row['scheme_id']);
            }
        }
    }

    log_line("Done. sent=$sent failed=$failed skipped_bad_email=$skipped");
    exit($failed > 0 ? 1 : 0);
} catch (Throwable $e) {
    error_log('reminders.php: ' . $e->getMessage());
    log_line('ERROR: ' . $e->getMessage());
    exit(1);
}