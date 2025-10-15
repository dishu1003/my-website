<?php
require_once __DIR__ . '/../includes/init.php';

// Load database credentials from environment or use defaults
// For Hostinger hosting, use these credentials:
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'u782093275_awpl'));
define('DB_PASS', env('DB_PASS', 'Vktmdp@2025'));
define('DB_NAME', env('DB_NAME', 'u782093275_awpl'));

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );
} catch(PDOException $e) {
    // Log error securely (don't expose details in production)
    error_log("Database connection failed: " . $e->getMessage());

    if (env('APP_DEBUG', false) === 'true') {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please contact support.");
    }
}
?>