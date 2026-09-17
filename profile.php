<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect if not logged in
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "health_stories";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$aadhaar = $_SESSION['aadhaar'];
$blood_group = $_SESSION['blood_group'];

// Fetch medical history from the database
$medical_history = [];
try {
    $stmt = $conn->prepare("SELECT name, birth_date, age, gender, blood_group, allergies, chronic_conditions, current_medications, emergency_name, emergency_contact FROM medical_history WHERE user_id = ?");
    if (!$stmt) {
        throw new Exception("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $medical_history = $result->fetch_assoc();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Health Stories</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.html">
                <img src="images/logo.png" alt="Health Stories Logo"
                     style="border-radius: 50%; height: 100px; width: 110px; padding-left: 10px;">
            </a>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="medical_history.php">Medical History Update</a></li>
                <li><a href="logout.php">Logout</a></li> <!-- Logout option added -->
            </ul>
        </nav>
    </header>

    <main>
        <section class="profile-section">
            <h1>User Profile</h1>
            <div class="profile-details">
                <div class="detail">
                    <span class="label">Username:</span>
                    <span class="value"><?php echo htmlspecialchars($username); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Aadhaar:</span>
                    <span class="value"><?php echo htmlspecialchars($aadhaar); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Blood Group:</span>
                    <span class="value"><?php echo htmlspecialchars($blood_group); ?></span>
                </div>
            </div>

            <!-- Display Medical History -->
            <div class="medical-history">
                <h2>Medical History</h2>
                <?php if (!empty($medical_history)): ?>
                    <div class="detail">
                        <span class="label">Name:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['name']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Date of Birth:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['birth_date']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Age:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['age']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Gender:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['gender']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Blood Group:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['blood_group']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Allergies:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['allergies']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Chronic Conditions:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['chronic_conditions']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Current Medications:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['current_medications']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Emergency Contact Name:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['emergency_name']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Emergency Contact Number:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['emergency_contact']); ?></span>
                    </div>
                <?php else: ?>
                    <p>No medical history found.</p>
                <?php endif; ?>
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="signup.html">Sign Up</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: kasliwaljagruti@gmail.com</p>
                <p>College:......</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>