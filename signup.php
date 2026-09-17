<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health_stories';

$signup_message = ''; // To store success/error messages

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $aadhaar = trim($_POST['aadhaar']);
    $password = $_POST['password'];
    $blood_group = trim($_POST['bloodGroup']);

    // Validation
    if (empty($username) || empty($email) || empty($aadhaar) || empty($password) || empty($blood_group)) {
        $signup_message = "❌ All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_message = "❌ Invalid email format.";
    } elseif (!preg_match("/^[0-9]{12}$/", $aadhaar)) {
        $signup_message = "❌ Aadhaar must be a 12-digit number.";
    } elseif (strlen($password) < 6) {
        $signup_message = "❌ Password must be at least 6 characters long.";
    } else {
        // Check if user already exists
        $checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $checkUser->store_result();

        if ($checkUser->num_rows > 0) {
            $signup_message = "❌ Email already registered. <a href='login.php'>Login here</a>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (username, email, aadhaar, password, blood_group) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sssss", $username, $email, $aadhaar, $hashedPassword, $blood_group);
                
                if ($stmt->execute()) {
                    $signup_message = "✅ Sign Up Successful! <a href='login.php'>Login here</a>";
                } else {
                    $signup_message = "❌ Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $signup_message = "❌ Database error occurred.";
            }
        }
        $checkUser->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Health Stories</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
        }
        .message a {
            color: #0066cc;
            text-decoration: none;
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
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h1>Sign Up</h1>
            <div class="form-container">
                <?php if (!empty($signup_message)): ?>
                    <div class="message"><?php echo $signup_message; ?></div>
                <?php endif; ?>
                <form action="signup.php" method="POST">
                    <div class="form-group">
                        <label for="username">User Name:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="aadhaar">Aadhaar:</label>
                        <input type="text" id="aadhaar" name="aadhaar" required pattern="[0-9]{12}" 
                            title="Aadhaar must be a 12-digit number">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="bloodGroup">Blood Group:</label>
                        <select id="bloodGroup" name="bloodGroup" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <button type="submit" class="btn primary-btn">Sign Up</button>
                </form>
                <div class="form-divider">
                    <span>OR</span>
                </div>
                <div class="alternate-action">
                    <p>Already have an account?</p>
                    <a href="login.php" class="btn secondary-btn">Login</a>
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
                <p>College: .......</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>