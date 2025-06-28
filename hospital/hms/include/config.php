<?php
// Load DB credentials from environment variables with safe fallbacks for local dev.
// Copy .env.example to .env and set your values before running.
define('DB_SERVER', getenv('DB_SERVER') ?: 'localhost');
define('DB_USER',   getenv('DB_USER')   ?: 'root');
define('DB_PASS',   getenv('DB_PASS')   ?: '');
define('DB_NAME',   getenv('DB_NAME')   ?: 'hms');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$con || mysqli_connect_errno()) {
    // Log the real error server-side; never expose it to the browser.
    error_log('HMS DB connection failed: ' . mysqli_connect_error());
    die('Database connection error. Please try again later.');
}

// Use UTF-8 throughout.
mysqli_set_charset($con, 'utf8mb4');
?>
