<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health_stories';

$signup_message = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $drName = trim($_POST['drName']);
    $email = trim($_POST['email']);
    $hospitalName = trim($_POST['hospitalName']);
    $contactNo = trim($_POST['contactNo']);
    $address = trim($_POST['address']);
    $aadhaarNo = trim($_POST['aadhaarNo']);
    $city = trim($_POST['city']);
    $bloodGroup = trim($_POST['bloodGroup']);
    $password = $_POST['password']; // Don't trim password

    // Validation
    if (empty($drName) || empty($email) || empty($hospitalName) || empty($contactNo) || empty($address) || empty($aadhaarNo) || empty($city) || empty($bloodGroup) || empty($password)) {
        $signup_message = "❌ All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_message = "❌ Invalid email format.";
    } elseif (!preg_match("/^\d{10}$/", $contactNo)) {
        $signup_message = "❌ Contact number must be 10 digits.";
    } elseif (!preg_match("/^\d{12}$/", $aadhaarNo)) {
        $signup_message = "❌ Aadhaar number must be 12 digits.";
    } elseif (strlen($password) < 6) {
        $signup_message = "❌ Password must be at least 6 characters.";
    } else {
        // Check if email or Aadhaar already exists
        $checkUser = $conn->prepare("SELECT id FROM admin_signup WHERE email = ? OR aadhaarNo = ?");
        $checkUser->bind_param("ss", $email, $aadhaarNo);
        $checkUser->execute();
        $checkUser->store_result();

        if ($checkUser->num_rows > 0) {
            $signup_message = "❌ Email or Aadhaar already registered. <a href='admin-login.php'>Login here</a>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin_signup (drName, email, hospitalName, contactNo, address, aadhaarNo, city, bloodGroup, hashedPassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssssssss", $drName, $email, $hospitalName, $contactNo, $address, $aadhaarNo, $city, $bloodGroup, $hashedPassword);
                if ($stmt->execute()) {
                    $signup_message = "✅ Sign Up Successful! <a href='admin-login.php'>Login here</a>";
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
    <title>Admin Sign Up - Health Stories</title>
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
                <li><a href="admin-login.php">Emergency Access</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h1>Admin Sign Up</h1>
            <div class="form-container">
                <?php if (!empty($signup_message)): ?>
                    <div class="message"><?php echo $signup_message; ?></div>
                <?php endif; ?>
                <form id="signupForm" action="admin-signup.php" method="POST">
                    <div class="form-group">
                        <label for="drName">Dr. Name:</label>
                        <input type="text" id="drName" name="drName" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email ID:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="hospitalName">Hospital Name:</label>
                        <input type="text" id="hospitalName" name="hospitalName" required>
                    </div>
                    <div class="form-group">
                        <label for="contactNo">Contact No:</label>
                        <input type="tel" id="contactNo" name="contactNo" required pattern="[0-9]{10}" title="Contact number must be 10 digits">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="aadhaarNo">Aadhaar No:</label>
                        <input type="text" id="aadhaarNo" name="aadhaarNo" required pattern="[0-9]{12}" title="Aadhaar number must be 12 digits">
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" required>
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
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required minlength="6" title="Password must be at least 6 characters">
                    </div>
                    <button type="submit" class="btn primary-btn">Sign Up</button>
                </form>
                <div class="form-divider">
                    <span>OR</span>
                </div>
                <div class="alternate-action">
                    <p>Already have an account?</p>
                    <a href="admin-login.php" class="btn secondary-btn">Login</a>
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
                    <li><a href="admin-login.php">Emergency Access</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: Healthstories@gmail.com</p>
                <p>College: .....</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Basic client-side validation (replacing adminsignup.js)
        function validateSignupForm() {
            const contactNo = document.getElementById('contactNo').value;
            const aadhaarNo = document.getElementById('aadhaarNo').value;
            const password = document.getElementById('password').value;

            if (!/^\d{10}$/.test(contactNo)) {
                alert("Contact number must be 10 digits.");
                return false;
            }
            if (!/^\d{12}$/.test(aadhaarNo)) {
                alert("Aadhaar number must be 12 digits.");
                return false;
            }
            if (password.length < 6) {
                alert("Password must be at least 6 characters.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>