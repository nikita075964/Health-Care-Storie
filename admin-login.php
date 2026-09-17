<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health_stories';

$login_error = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $login_error = "❌ Email and password are required.";
    } else {
        $sql = "SELECT id, drName, email, aadhaarNo, bloodGroup, hashedPassword FROM admin_signup WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $admin = $result->fetch_assoc();
                if (password_verify($password, $admin['hashedPassword'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['drName'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_aadhaar'] = $admin['aadhaarNo'];
                    $_SESSION['admin_blood_group'] = $admin['bloodGroup'];

                    header("Location: adminprofile.php");
                    exit();
                } else {
                    $login_error = "❌ Invalid password.";
                }
            } else {
                $login_error = "❌ Admin user not found.";
            }
            $stmt->close();
        } else {
            $login_error = "❌ Database error occurred.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Health Stories</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">
                <img src="./Images/logo.png" alt="Health Stories Logo"
                    style="border-radius: 30%; height: 50px; width: 55px; padding-left: 5px;">
            </a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="adminprofile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h1>Admin Login</h1>
            <div class="form-container">
                <?php if (!empty($login_error)): ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form action="admin-login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn primary-btn">Login</button>
                </form>
                <div class="form-divider">
                    <span>OR</span>
                </div>
                <div class="alternate-action">
                    <p>Don't have an admin account?</p>
                    <a href="admin-signup.php" class="btn secondary-btn">Sign Up</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Health Stories</h3>
                <p>A globally accessible medical service platform</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="admin-signup.php">Sign Up</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: Healthstories@gmail.com</p>
                <p>College: ......</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>