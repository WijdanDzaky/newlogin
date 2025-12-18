<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Login Form -->
            <div class="form-box active" id="login-form">
                <h2>Login</h2>
                <form id="loginForm" method="POST" action="includes/login.php">
                    <div class="form-group">
                        <label for="login-username">Username:</label>
                        <input type="text" id="login-username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password:</label>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <div id="login-message" class="message"></div>
                </form>
                <p class="toggle-text">Don't have an account? <a href="javascript:void(0);" onclick="toggleForms()">Sign up</a></p>
            </div>

            <!-- Registration Form -->
            <div class="form-box active" id="register-form" style="display: none;">
                <h2>Sign Up</h2>
                <form id="registerForm" method="POST" action="includes/register_simple.php">
                    <div class="form-group">
                        <label for="register-username">Username:</label>
                        <input type="text" id="register-username" name="username" placeholder="Choose a username" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Password:</label>
                        <input type="password" id="register-password" name="password" placeholder="Choose a password (min 6 chars)" required>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm">Confirm Password:</label>
                        <input type="password" id="register-confirm" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                    <div id="register-message" class="message"></div>
                </form>
                <p class="toggle-text">Already have an account? <a href="javascript:void(0);" onclick="toggleForms()">Login</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
