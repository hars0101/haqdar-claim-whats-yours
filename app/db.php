<?php
declare(strict_types=1);

/**
 * Returns a shared PDO connection.
 * Looks for config.php one level above the web root first, then next to this folder.
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $candidates = [
        __DIR__ . '/../../config.php',
        __DIR__ . '/../config.php',
        __DIR__ . '/config.php',
    ];
    $cfg = null;
    foreach ($candidates as $path) {
        if (is_file($path)) {
            $cfg = require $path;
            break;
        }
    }
    if (!is_array($cfg)) {
        error_log('db.php: config.php not found');
        http_response_code(500);
        exit('Server configuration error.');
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $cfg['db_host'],
        $cfg['db_name']
    );
    $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}
