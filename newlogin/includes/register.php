<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';
    
    // Initialize error array
    $errors = [];
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if user already exists
    if (empty($errors)) {
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username or email already exists";
        }
        mysqli_stmt_close($check_stmt);
    }
    
    // Register user if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate OTP (6 digit random number)
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_expires = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Extend to 30 minutes for testing
        
        // Insert user dengan OTP
        $insert_sql = "INSERT INTO users (username, email, password, otp, otp_expires_at) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        
        if (!$insert_stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . mysqli_error($conn)
            ]);
            exit;
        }
        
        mysqli_stmt_bind_param($insert_stmt, "sssss", $username, $email, $hashed_password, $otp, $otp_expires);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            // Simpan email ke session untuk verifikasi
            $_SESSION['pending_email'] = $email;
            $_SESSION['pending_username'] = $username;
            
            // Log OTP untuk testing
            $log_message = date('Y-m-d H:i:s') . " | Email: $email | OTP: $otp | Username: $username\n";
            $log_file = __DIR__ . '/otp_log.txt';
            file_put_contents($log_file, $log_message, FILE_APPEND);
            
            // Kirim OTP ke email
            $email_sent = sendOTPEmail($email, $otp, $username);
            
            echo json_encode([
                "success" => true,
                "message" => "Registration successful! OTP has been sent. Check your email or OTP log.",
                "redirect" => "verify_otp.php",
                "email_sent" => $email_sent
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error during registration: " . mysqli_error($conn)
            ]);
        }
        mysqli_stmt_close($insert_stmt);
    } else {
        echo json_encode([
            "success" => false,
            "errors" => $errors
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
