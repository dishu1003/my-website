<?php
require_once __DIR__ . '/env.php';

// Start session with secure settings
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => env('SITE_DOMAIN'),
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Site Configuration
define('SITE_URL', env('SITE_URL'));
define('SITE_NAME', env('SITE_NAME'));
define('WEBHOOK_SECRET', env('WEBHOOK_SECRET'));

// Abacus AI Webhook URL
define('ABACUS_WEBHOOK_URL', env('ABACUS_WEBHOOK_URL'));

// Timezone
date_default_timezone_set(env('TIMEZONE', 'Asia/Kolkata'));

// Security
define('ENCRYPTION_KEY', env('ENCRYPTION_KEY'));

// App Environment
define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', env('APP_DEBUG', 'false') === 'true');

/**
 * Encrypt data using AES-256-CBC
 */
function encrypt_data($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * Decrypt data using AES-256-CBC
 */
function decrypt_data($data) {
    try {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    } catch (Exception $e) {
        error_log("Decryption failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Generate secure random string
 */
function generate_random_string($length = 32) {
    return bin2hex(random_bytes($length / 2));
}
?>