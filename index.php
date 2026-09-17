<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Stories - Hospital Services</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .main-container {
            width: 100%;
            height: 60vh;
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
            background-image: url('../Images/bg.jpg');
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .main-container h1 {
            font-size: 3rem;
            color: rgb(23, 14, 14);
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">
                <img src="images/logo.png" alt="Health Stories Logo" 
                    style="border-radius: 50%; height: 100px; width: 110px; padding-left: 10px;">
            </a>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Account <i class="fas fa-caret-down"></i></a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php">Profile</a>
                            <a href="medical_history.php">Medical History Update</a>
                            <a href="logout.php">Logout</a>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                            <a href="signup.php">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </li>
                <li><a href="admin-login.php">Emergency Access</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="main-container">
            <h1 class="animate-slide-left">HEALTH STORIES</h1>
            <section class="hero">
                <p class="animate-slide-right"><b>Your complete healthcare solution for managing medical records and emergency services.</b></p>
                <div class="hero-buttons animate-slide-bottom">
                    <a href="signup.php" class="btn primary-btn">Get Started</a>
                    <a href="about.php" class="btn secondary-btn">Learn More</a>
                </div>
            </section>
        </div>

        <section class="health-stories">
            <h4 class="animate-fade-in">Service Provision System for Hospital Management</h4>
            <div class="features-benefits">
                <div class="features">
                    <h3>Features of Health Stories</h3>
                    <div class="cards">
                        <div class="card animate-fade-in">
                            <i class="fas fa-user-md animate-pulse"></i>
                            <h4>Emergency Access</h4>
                            <p>Quick access to critical medical information during emergencies.</p>
                        </div>
                        <div class="card animate-fade-in">
                            <i class="fas fa-database animate-pulse"></i>
                            <h4>Secure Storage</h4>
                            <p>Encrypted storage of your sensitive medical data.</p>
                        </div>
                        <div class="card animate-fade-in">
                            <i class="fas fa-globe animate-pulse"></i>
                            <h4>Global Accessibility</h4>
                            <p>Access your medical records from anywhere in the world.</p>
                        </div>
                    </div>
                </div>
                <div class="benefits">
                    <h3>Benefits of Health Stories</h3>
                    <div class="cards">
                        <div class="card animate-fade-in">
                            <i class="fas fa-heartbeat animate-pulse"></i>
                            <h4>Better Care</h4>
                            <p>Doctors can make informed decisions with your complete medical history.</p>
                        </div>
                        <div class="card animate-fade-in">
                            <i class="fas fa-clock animate-pulse"></i>
                            <h4>Time Saving</h4>
                            <p>No waiting for test results in emergency situations.</p>
                        </div>
                        <div class="card animate-fade-in">
                            <i class="fas fa-shield-alt animate-pulse"></i>
                            <h4>Peace of Mind</h4>
                            <p>Know that your medical information is always available when needed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Health Stories</h3>
                <p>A globally accessible medical service platform.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: Healthstories@gmail.com</p>
                <p>College: xyz </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>