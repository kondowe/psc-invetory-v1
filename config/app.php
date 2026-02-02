<?php
/**
 * Application Configuration
 *
 * General application settings
 */

return [
    // Application Settings
    'name' => 'Inventory Management System',
    'version' => '1.0.0',
    'env' => getenv('APP_ENV') ?: 'development', // development, production
    'debug' => getenv('APP_DEBUG') === 'true' ?: true,
    'url' => getenv('APP_URL') ?: 'http://localhost/int/public',

    // Timezone
    'timezone' => 'UTC',

    // Session Settings
    'session' => [
        'name' => 'IMS_SESSION',
        'lifetime' => 3600, // 1 hour in seconds
        'cookie_httponly' => true,
        'cookie_secure' => false, // Set to true in production with HTTPS
        'cookie_samesite' => 'Strict',
    ],

    // Security
    'csrf_token_name' => 'csrf_token',
    'password_bcrypt_cost' => 12,
    'max_login_attempts' => 5,
    'lockout_duration' => 900, // 15 minutes in seconds

    // Pagination
    'pagination_limit' => 20,

    // File Uploads (for future use)
    'max_upload_size' => 10485760, // 10MB in bytes
    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],

    // Logging
    'log_file' => __DIR__ . '/../logs/app.log',
    'error_log_file' => __DIR__ . '/../logs/error.log',
    'activity_log_file' => __DIR__ . '/../logs/access.log',

    // Paths
    'base_path' => dirname(__DIR__),
    'public_path' => dirname(__DIR__) . '/public',
    'upload_path' => dirname(__DIR__) . '/uploads',
];
