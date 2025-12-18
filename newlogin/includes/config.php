<?php
// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', '0');  // Don't display errors on screen
ini_set('log_errors', '1');      // Log errors to PHP error log

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login_system');

// Email configuration (gunakan SMTP Gmail)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'wijdandzaky28@gmail.com'); // Ganti dengan email Anda
define('SMTP_PASSWORD', 'vlsf wdzu mgtd mfpy');  // Ganti dengan app password Gmail
define('FROM_EMAIL', 'wijdandzaky28@gmail.com');  // Email pengirim
define('FROM_NAME', 'Login System');

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    // Database created or already exists
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Select database
mysqli_select_db($conn, DB_NAME);

// Create users table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    otp VARCHAR(6),
    otp_expires_at DATETIME,
    is_verified INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $create_table)) {
    // Table created or already exists
} else {
    die("Error creating table: " . mysqli_error($conn));
}

session_start();

// Function to send email with OTP using SMTP
function sendOTPEmail($email, $otp, $username) {
    // Log OTP untuk testing
    $log_message = date('Y-m-d H:i:s') . " | Email: $email | OTP: $otp | Username: $username\n";
    $log_file = __DIR__ . '/otp_log.txt';
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    // Untuk production, implementasikan PHPMailer atau SendGrid
    // Untuk sekarang, return true karena OTP sudah di-log untuk testing
    return true;
}

?>
