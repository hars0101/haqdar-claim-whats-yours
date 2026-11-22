<?php
declare(strict_types=1);

/** Loads config.php using the same search order as db.php. */
function app_config(): array
{
    static $cfg = null;
    if ($cfg !== null) {
        return $cfg;
    }
    $candidates = [
        __DIR__ . '/../../config.php',
        __DIR__ . '/../config.php',
        __DIR__ . '/config.php',
    ];
    foreach ($candidates as $path) {
        if (is_file($path)) {
            $loaded = require $path;
            if (is_array($loaded)) {
                $cfg = $loaded;
                return $cfg;
            }
        }
    }
    error_log('config_loader.php: config.php not found');
    $cfg = [];
    return $cfg;
}
