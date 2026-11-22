<?php
declare(strict_types=1);
require_once __DIR__ . '/../../app/db.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

const SID_COOKIE = 'haqdar_sid';
const STATUSES = ['not_started', 'documents_ready', 'applied', 'under_verification', 'received'];

function send_json(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function read_json_body(): array
{
    $data = json_decode(file_get_contents('php://input') ?: '', true);
    if (!is_array($data)) {
        send_json(400, ['error' => 'Invalid JSON body']);
    }
    return $data;
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

/** Returns the anonymous session id from the cookie, creating a new one if missing or malformed. */
function get_sid(): string
{
    $sid = $_COOKIE[SID_COOKIE] ?? '';
    if (is_string($sid) && preg_match('/^[a-f0-9]{64}$/', $sid) === 1) {
        return $sid;
    }
    $sid = bin2hex(random_bytes(32));
    setcookie(SID_COOKIE, $sid, [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    return $sid;
}

function ensure_session(PDO $pdo, string $sid): void
{
    $stmt = $pdo->prepare('INSERT INTO sessions (sid) VALUES (?) ON DUPLICATE KEY UPDATE sid = sid');
    $stmt->execute([$sid]);
}

function scheme_id_from(array $body): int
{
    $id = filter_var($body['scheme_id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        send_json(400, ['error' => 'scheme_id must be a positive integer']);
    }
    return (int)$id;
}

function status_from(array $body, ?string $default): string
{
    if (!array_key_exists('status', $body)) {
        if ($default === null) {
            send_json(400, ['error' => 'status is required']);
        }
        return $default;
    }
    $status = $body['status'];
    if (!is_string($status) || !in_array($status, STATUSES, true)) {
        send_json(400, ['error' => 'Invalid status', 'allowed' => STATUSES]);
    }
    return $status;
}

function scheme_is_active(PDO $pdo, int $schemeId): bool
{
    $stmt = $pdo->prepare('SELECT id FROM schemes WHERE id = ? AND is_active = 1');
    $stmt->execute([$schemeId]);
    return $stmt->fetch() !== false;
}

function get_application(PDO $pdo, string $sid, int $schemeId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT a.scheme_id, a.status, a.created_at, a.updated_at,
                s.name, s.benefit_text, s.official_url
         FROM applications a
         JOIN schemes s ON s.id = a.scheme_id
         WHERE a.sid = ? AND a.scheme_id = ?'
    );
    $stmt->execute([$sid, $schemeId]);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

// ---- main ----
$method = $_SERVER['REQUEST_METHOD'];

try {
    $pdo = db();
    $sid = get_sid();
    ensure_session($pdo, $sid);

    switch ($method) {
        case 'GET':
            $stmt = $pdo->prepare(
                'SELECT a.scheme_id, a.status, a.created_at, a.updated_at,
                        s.name, s.benefit_text, s.official_url
                 FROM applications a
                 JOIN schemes s ON s.id = a.scheme_id
                 WHERE a.sid = ?
                 ORDER BY COALESCE(a.updated_at, a.created_at) DESC, a.id DESC'
            );
            $stmt->execute([$sid]);
            send_json(200, [
                'applications' => $stmt->fetchAll(),
                'statuses' => STATUSES,
            ]);
            break;

        case 'POST':
            $body = read_json_body();
            $schemeId = scheme_id_from($body);
            $status = status_from($body, 'not_started');

            if (!scheme_is_active($pdo, $schemeId)) {
                send_json(404, ['error' => 'Scheme not found']);
            }

            // Adding the same scheme twice does not create a duplicate and does not change its status.
            $stmt = $pdo->prepare(
                'INSERT INTO applications (sid, scheme_id, status) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE sid = sid'
            );
            $stmt->execute([$sid, $schemeId, $status]);
            $created = $stmt->rowCount() === 1;

            send_json($created ? 201 : 200, [
                'created' => $created,
                'application' => get_application($pdo, $sid, $schemeId),
            ]);
            break;

        case 'PUT':
        case 'PATCH':
            $body = read_json_body();
            $schemeId = scheme_id_from($body);
            $status = status_from($body, null);

            if (get_application($pdo, $sid, $schemeId) === null) {
                send_json(404, ['error' => 'Application not found. Add it first.']);
            }

            $stmt = $pdo->prepare('UPDATE applications SET status = ? WHERE sid = ? AND scheme_id = ?');
            $stmt->execute([$status, $sid, $schemeId]);

            send_json(200, [
                'application' => get_application($pdo, $sid, $schemeId),
            ]);
            break;

        default:
            header('Allow: GET, POST, PUT, PATCH');
            send_json(405, ['error' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    error_log('track.php: ' . $e->getMessage());
    send_json(500, ['error' => 'Could not update your applications right now.']);
}