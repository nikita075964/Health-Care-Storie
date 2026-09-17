<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

$user_id = $_SESSION['user_id'];
$update_message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['medicalName']);
    $birth_date = $_POST['birth_date'];
    $age = (int)$_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['medicalBloodGroup'];
    $allergies = trim($_POST['allergies']);
    $chronic_conditions = trim($_POST['chronicConditions']);
    $current_medications = trim($_POST['currentMedications']);
    $emergency_name = trim($_POST['emergency_name']);
    $emergency_contact = trim($_POST['emergency_contact']);

    // Basic validation
    if (empty($name) || empty($birth_date) || empty($age) || empty($gender) || empty($blood_group) || empty($emergency_name) || empty($emergency_contact)) {
        $update_message = "❌ All required fields must be filled.";
    } elseif (!preg_match("/^[0-9]{10}$/", $emergency_contact)) {
        $update_message = "❌ Emergency contact must be a 10-digit number.";
    } else {
        $stmt = $conn->prepare("INSERT INTO medical_history (user_id, name, birth_date, age, gender, blood_group, allergies, chronic_conditions, current_medications, emergency_name, emergency_contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=?, birth_date=?, age=?, gender=?, blood_group=?, allergies=?, chronic_conditions=?, current_medications=?, emergency_name=?, emergency_contact=?");
        
        if ($stmt) {
            $stmt->bind_param("isssisssssssissssssss", $user_id, $name, $birth_date, $age, $gender, $blood_group, $allergies, $chronic_conditions, $current_medications, $emergency_name, $emergency_contact, $name, $birth_date, $age, $gender, $blood_group, $allergies, $chronic_conditions, $current_medications, $emergency_name, $emergency_contact);
            
            if ($stmt->execute()) {
                $update_message = "✅ Medical history updated successfully!";
            } else {
                $update_message = "❌ Error updating medical history: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $update_message = "❌ Database error occurred.";
        }
    }
}

// Fetch existing medical history
$medical_history = [];
$stmt = $conn->prepare("SELECT name, birth_date, age, gender, blood_group, allergies, chronic_conditions, current_medications, emergency_name, emergency_contact FROM medical_history WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $medical_history = $result->fetch_assoc();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History Update - Health Stories</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .message {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
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
                <li class="dropdown">
                    <a href="#" class="dropbtn">Account <i class="fas fa-caret-down"></i></a>
                    <div class="dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="medical_history.php" class="active">Medical History Update</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h1>Medical History Update</h1>
            <div class="form-container">
                <?php if (!empty($update_message)): ?>
                    <div class="message"><?php echo $update_message; ?></div>
                <?php endif; ?>
                <form id="medicalHistoryForm" action="medical_history.php" method="POST">
                    <!-- Personal Information -->
                    <div class="form-group">
                        <label for="medicalName">Name:</label>
                        <input type="text" id="medicalName" name="medicalName" value="<?php echo isset($medical_history['name']) ? htmlspecialchars($medical_history['name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Date of Birth:</label>
                        <input type="date" id="birth_date" name="birth_date" value="<?php echo isset($medical_history['birth_date']) ? htmlspecialchars($medical_history['birth_date']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" min="0" max="150" value="<?php echo isset($medical_history['age']) ? htmlspecialchars($medical_history['age']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo (isset($medical_history['gender']) && $medical_history['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($medical_history['gender']) && $medical_history['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (isset($medical_history['gender']) && $medical_history['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <!-- Medical Information -->
                    <div class="form-group">
                        <label for="medicalBloodGroup">Blood Group:</label>
                        <select id="medicalBloodGroup" name="medicalBloodGroup" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo (isset($medical_history['blood_group']) && $medical_history['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="allergies">Allergies:</label>
                        <textarea id="allergies" name="allergies" rows="3"><?php echo isset($medical_history['allergies']) ? htmlspecialchars($medical_history['allergies']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="chronicConditions">Chronic Conditions:</label>
                        <textarea id="chronicConditions" name="chronicConditions" rows="3"><?php echo isset($medical_history['chronic_conditions']) ? htmlspecialchars($medical_history['chronic_conditions']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="currentMedications">Current Medications:</label>
                        <textarea id="currentMedications" name="currentMedications" rows="3"><?php echo isset($medical_history['current_medications']) ? htmlspecialchars($medical_history['current_medications']) : ''; ?></textarea>
                    </div>

                    <!-- Emergency Contact Information -->
                    <div class="form-group">
                        <label for="emergency_name">Emergency Contact Name:</label>
                        <input type="text" id="emergency_name" name="emergency_name" value="<?php echo isset($medical_history['emergency_name']) ? htmlspecialchars($medical_history['emergency_name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Number:</label>
                        <input type="tel" id="emergency_contact" name="emergency_contact" pattern="[0-9]{10}" value="<?php echo isset($medical_history['emergency_contact']) ? htmlspecialchars($medical_history['emergency_contact']) : ''; ?>" required title="Please enter a 10-digit phone number">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn primary-btn">Update Medical History</button>
                </form>
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