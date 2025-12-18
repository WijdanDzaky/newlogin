<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
        $sql = "SELECT id, username, password, is_verified FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Check if email is verified
            if ($row['is_verified'] == 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "Please verify your email first before logging in"
                ]);
                mysqli_stmt_close($stmt);
                exit;
            }
            
            if (password_verify($password, $row["password"])) {
                // Set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                
                // Create persistent login cookie (30 hari)
                $cookie_value = json_encode([
                    "id" => $row["id"],
                    "username" => $row["username"],
                    "hash" => hash("sha256", $row["password"])
                ]);
                setcookie("login_token", $cookie_value, time() + (30 * 24 * 60 * 60), "/", "", false, true);
                
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful!",
                    "redirect" => "dashboard.php"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Username not found"
            ]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid request method"
        ]);
    }
}
