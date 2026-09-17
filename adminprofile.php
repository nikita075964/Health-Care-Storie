<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_stories";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch admin details from session
$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? 'N/A';
$admin_email = $_SESSION['admin_email'] ?? 'N/A';
$admin_aadhaar = $_SESSION['admin_aadhaar'] ?? 'N/A';
$admin_blood_group = $_SESSION['admin_blood_group'] ?? 'N/A';

// Handle Aadhaar search and medical history fetch
$user_info = [];
$medical_history = [];
$search_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchAadhaar'])) {
    $searchAadhaar = trim($_POST['searchAadhaar']); // Remove leading/trailing whitespace

    // Debug: Log the received Aadhaar number
    error_log("Received Aadhaar: '$searchAadhaar'");

    // Check if the input is exactly 12 digits
    if (!preg_match("/^[0-9]{12}$/", $searchAadhaar)) {
        $search_message = "❌ Aadhaar number must be exactly 12 digits with no spaces or special characters.";
    } else {
        // Fetch user info from users table
        $stmt = $conn->prepare("SELECT id, username, email, aadhaar, blood_group FROM users WHERE aadhaar = ?");
        $stmt->bind_param("s", $searchAadhaar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
            $searched_user_id = $user_info['id'];

            // Fetch medical history
            $stmt_history = $conn->prepare("SELECT name, birth_date, age, gender, blood_group, allergies, chronic_conditions, current_medications, emergency_name, emergency_contact FROM medical_history WHERE user_id = ?");
            $stmt_history->bind_param("i", $searched_user_id);
            $stmt_history->execute();
            $history_result = $stmt_history->get_result();

            if ($history_result->num_rows > 0) {
                $medical_history = $history_result->fetch_assoc();
            } else {
                $search_message = "✅ User found, but no medical history available.";
            }
            $stmt_history->close();
        } else {
            $search_message = "❌ No user found with this Aadhaar number.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Health Stories</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .message {
            text-align: center;
            margin: 15px 0;
            color: red;
        }
        .profile-section, .user-info, .medical-history {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .detail {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            margin-right: 10px;
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
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="#" onclick="toggleAccessForm()">Access User Info</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="profile-section">
            <h1>Admin Profile</h1>
            <div class="profile-details">
                <div class="detail">
                    <span class="label">Name:</span>
                    <span class="value"><?php echo htmlspecialchars($admin_name); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($admin_email); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Aadhaar:</span>
                    <span class="value"><?php echo htmlspecialchars($admin_aadhaar); ?></span>
                </div>
                <div class="detail">
                    <span class="label">Blood Group:</span>
                    <span class="value"><?php echo htmlspecialchars($admin_blood_group); ?></span>
                </div>
            </div>

            <!-- Access User Info Form -->
            <div id="accessForm" style="display: none;">
                <h2>Access User Information</h2>
                <?php if (!empty($search_message)): ?>
                    <div class="message"><?php echo $search_message; ?></div>
                <?php endif; ?>
                <form method="POST" action="adminprofile.php">
                    <div class="form-group">
                        <label for="searchAadhaar">Enter User Aadhaar Number:</label>
                        <input type="text" id="searchAadhaar" name="searchAadhaar" required maxlength="12" pattern="[0-9]{12}" title="Aadhaar must be exactly 12 digits">
                    </div>
                    <button type="submit" class="btn primary-btn">View User Info & Medical History</button>
                </form>
            </div>

            <!-- Display User Information -->
            <?php if (!empty($user_info)): ?>
                <div class="user-info">
                    <h2>User Information</h2>
                    <div class="detail">
                        <span class="label">Username:</span>
                        <span class="value"><?php echo htmlspecialchars($user_info['username']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Email:</span>
                        <span class="value"><?php echo htmlspecialchars($user_info['email']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Aadhaar:</span>
                        <span class="value"><?php echo htmlspecialchars($user_info['aadhaar']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Blood Group:</span>
                        <span class="value"><?php echo htmlspecialchars($user_info['blood_group']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Display Medical History -->
            <?php if (!empty($medical_history)): ?>
                <div class="medical-history">
                    <h2>Medical History</h2>
                    <div class="detail">
                        <span class="label">Name:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['name']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Birth Date:</span>
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
                        <span class="value"><?php echo htmlspecialchars($medical_history['allergies'] ?: 'None'); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Chronic Conditions:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['chronic_conditions'] ?: 'None'); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Current Medications:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['current_medications'] ?: 'None'); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Emergency Contact Name:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['emergency_name']); ?></span>
                    </div>
                    <div class="detail">
                        <span class="label">Emergency Contact Number:</span>
                        <span class="value"><?php echo htmlspecialchars($medical_history['emergency_contact']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
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
                    <li><a href="admin-signup.php">Sign Up</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: Healthstories@gmail.com</p>
                <p>College: ....</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Health Stories. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleAccessForm() {
            const accessForm = document.getElementById('accessForm');
            accessForm.style.display = accessForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>