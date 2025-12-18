<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data - hanya username dan password
    $username = trim($_POST["username"] ?? '');
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
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username already exists";
        }
        mysqli_stmt_close($check_stmt);
    }
    
    // Register user if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user - tanpa OTP, langsung verified
        $email = $username . "@localhost";  // Generate email otomatis
        $insert_sql = "INSERT INTO users (username, email, password, is_verified) VALUES (?, ?, ?, 1)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        
        if (!$insert_stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . mysqli_error($conn)
            ]);
            exit;
        }
        
        mysqli_stmt_bind_param($insert_stmt, "sss", $username, $email, $hashed_password);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            // Langsung login tanpa perlu verifikasi OTP
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $username;
            
            // Create persistent login cookie (30 hari)
            $cookie_value = json_encode([
                "id" => $_SESSION['id'],
                "username" => $username,
                "hash" => hash("sha256", $hashed_password)
            ]);
            setcookie("login_token", $cookie_value, time() + (30 * 24 * 60 * 60), "/", "", false, true);
            
            echo json_encode([
                "success" => true,
                "message" => "Registration successful! You are now logged in.",
                "redirect" => "dashboard.php"
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
