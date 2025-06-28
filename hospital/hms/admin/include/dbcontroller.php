<?php
// Legacy PDO controller used by some admin pages.
// Credentials are loaded from environment variables — see .env.example.
$DB_host = getenv('DB_SERVER') ?: 'localhost';
$DB_user = getenv('DB_USER')   ?: 'root';
$DB_pass = getenv('DB_PASS')   ?: '';
$DB_name = getenv('DB_NAME')   ?: 'hms';  // Fixed: was incorrectly set to "ingram"

try {
    $DB_con = new PDO(
        "mysql:host={$DB_host};dbname={$DB_name};charset=utf8mb4",
        $DB_user,
        $DB_pass
    );
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB_con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Log server-side; never expose connection details to the browser.
    error_log('HMS PDO connection failed: ' . $e->getMessage());
    die('Database connection error. Please try again later.');
}
?>
