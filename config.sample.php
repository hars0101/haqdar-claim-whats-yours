<?php
/*
 * HaqDar – configuration template.
 *
 * HOW TO USE:
 *   1. Copy this file to config.php and place config.php ONE LEVEL ABOVE the
 *      web root (the folder that contains public/). Do NOT put it inside public/.
 *   2. Fill in the values below.
 *   3. NEVER commit config.php to git. It holds secrets.
 */

return [
    // Database host. On cPanel this is almost always 'localhost'.
    'db_host' => 'localhost',

    // Database name you created in cPanel "MySQL Databases".
    'db_name' => 'your_cpanel_user_haqdar',

    // Database user you created in cPanel "MySQL Databases".
    'db_user' => 'your_db_user',

    // Password for that database user.
    'db_pass' => 'change-me',

    // Optional AI (LLM) API key. Leave EMPTY to disable the LLM.
    // When empty, the built-in regex fallback is used instead (still works fine).
    'llm_api_key' => '',

    // LLM chat-completions endpoint (OpenAI-compatible).
    'llm_endpoint' => 'https://api.openai.com/v1/chat/completions',

    // LLM model name used for requests.
    'llm_model' => 'gpt-4o-mini',

    // Admin login password HASH (not the password itself).
    // Generate it on your computer with this command, then paste the output here:
    //   php -r "echo password_hash('your-password', PASSWORD_DEFAULT);"
    'admin_password_hash' => '',

    // "From" address used for reminder emails sent with mail().
    'mail_from' => 'haqdar@example.com',

    // Name of the app, shown in page titles and emails.
    'app_name' => 'HaqDar',

    // Full public address of the site, with no trailing slash.
    'base_url' => 'https://haqdar.example.com',
];
