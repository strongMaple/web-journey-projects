<?php

declare(strict_types=1);

/* ==========================
   ENVIRONMENT
========================== */
define('APP_ENV', 'local'); // change to 'production' on InfinityFree

/* ==========================
   DATABASE
========================== */
if (APP_ENV === 'local') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'strongMaple_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('DB_HOST', 'sqlXXX.infinityfree.com');
    define('DB_NAME', 'if0_xxxxx_db');
    define('DB_USER', 'if0_xxxxx');
    define('DB_PASS', 'YOUR_DB_PASSWORD');
}

/* ==========================
   AUTH / SESSION
========================== */
define('SESSION_NAME', 'strongMaple_admin');
define('REMEMBER_COOKIE', 'strongMaple_admin_remember');
define('REMEMBER_EXPIRY', 60 * 60 * 24 * 30);

/* session hardening */
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', APP_ENV === 'production' ? '1' : '0');
ini_set('session.cookie_samesite', 'Strict');

session_name(SESSION_NAME);

/* ==========================
   SESSION START
========================== */
function secure_session_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

secure_session_start();

/* ==========================
   PDO CONNECTION
========================== */
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException) {
    http_response_code(500);
    exit('Database error');
}

/* ==========================
   HELPERS
========================== */
function is_admin_logged_in(): bool
{
    return isset($_SESSION['admin_id']);
}
