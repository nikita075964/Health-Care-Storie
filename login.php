<?php
session_start(); // Start the session

// Database Connection
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health_stories';

$login_error = ''; // Variable to store error messages

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $email = trim($_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables for the user
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['aadhaar'] = $user['aadhaar'];
                $_SESSION['blood_group'] = $user['blood_group'];

                // Redirect to profile.php
                header("Location: profile.php");
                exit();
            } else {
                $login_error = "❌ Invalid password.";
            }
        } else {
            $login_error = "❌ User not found.";
        }
        $stmt->close();
    } else {
        $login_error = "❌ Database error occurred.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Health Stories</title>
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
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h1>Login</h1>
            <div class="form-container">
                <?php if (!empty($login_error)): ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="loginEmail">Email:</label>
                        <input type="email" id="loginEmail" name="loginEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password:</label>
                        <input type="password" id="loginPassword" name="loginPassword" required>
                    </div>
                    <button type="submit" class="btn primary-btn">Login</button>
                </form>
                <div class="form-divider">
                    <span>OR</span>
                </div>
                <div class="alternate-action">
                    <p>Don't have an account?</p>
                    <a href="signup.php" class="btn secondary-btn">Sign Up</a>
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
                <p>College: xyz</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>