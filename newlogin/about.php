<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Check if persistent login cookie exists
    if (isset($_COOKIE["login_token"])) {
        $cookie_data = json_decode($_COOKIE["login_token"], true);
        
        if ($cookie_data && isset($cookie_data["id"]) && isset($cookie_data["username"])) {
            // Verify cookie hash dengan password di database
            $sql = "SELECT id, username, password FROM users WHERE id = ? AND username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "is", $cookie_data["id"], $cookie_data["username"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                // Verify hash cocok
                if ($cookie_data["hash"] === hash("sha256", $row["password"])) {
                    // Restore session dari cookie
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                } else {
                    // Cookie invalid - redirect ke login
                    header("Location: index.php");
                    exit;
                }
            } else {
                // User tidak ditemukan - redirect ke login
                header("Location: index.php");
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {
            // Cookie invalid - redirect ke login
            header("Location: index.php");
            exit;
        }
    } else {
        // Tidak ada session dan tidak ada cookie - redirect ke login
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <script src="assets/js/hamburger.js" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="navbar-content">
            <h1>About WDZ</h1>
            <button class="hamburger" id="hamburger-menu">☰</button>
            <div class="nav-buttons" id="nav-buttons">
                <a href="contact.php" class="about-btn">Contact</a>
                <a href="dashboard.php" class="dashboard-btn">Home</a>
                <a href="includes/logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="welcome-box">
            <h2>Hello, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
            <p>You have successfully logged</p>
            <div class="user-info">
                <!-- user info id username-->
                 
            </div>
            <div class="main-content">
                <h3>Join komitas kami</h3>
                <div class="content-buttons">
                    <a href="https://discord.gg/sZDbg4aMek" class="contact-btn">Discord</a>
                     <a href="#" id="wa-btn" class="contact-btn">Whatsapp Community</a>
                </div>
                <!-- POPUP -->
<div class="popup" id="popup">
    <div class="popup-box">
        <h3>Whatsapp Community</h3>
        <p>Link Whatsapp akan segera hadir!</p>
        <button id="close-popup">Tutup</button>
    </div>
</div>
                <!-- Add your main web content here -->

                <!-- MAIN CONTENT -->

            </div>
        </div>
        </div>
    </div>

    <script>
    const waBtn = document.getElementById("wa-btn");
    const popup = document.getElementById("popup");
    const closeBtn = document.getElementById("close-popup");

    waBtn.addEventListener("click", function(e){
        e.preventDefault(); 
        popup.style.display = "flex";
    });

    closeBtn.addEventListener("click", function(){
        popup.style.display = "none";
    });
</script>





<!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!--GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
</head>
<body>
<footer>
<div class="footer">
<div class="row">
<a href="#"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-instagram"></i></a>
<a href="#"><i class="fa fa-youtube"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
</div>

<div class="row">
<ul>
<li><a href="#">Contact us</a></li>
<li><a href="#">Our Services</a></li>
<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Terms & Conditions</a></li>
<li><a href="#">Career</a></li>
</ul>
</div>

<div class="row">
Copyright © 2025 Wijdan Dzaky - All rights reserved || Designed By: Wijdan Dzaky 
</div>
</div>
</footer>
</body>
</html>
