<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database Connection
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health_stories';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $blood_group = $_POST['medicalBloodGroup'];
    $allergies = $_POST['allergies'];
    $chronic_conditions = $_POST['chronicConditions'];
    $current_medications = $_POST['currentMedications'];

    // Update user medical history
    $sql = "UPDATE users SET blood_group=?, allergies=?, chronic_conditions=?, current_medications=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $blood_group, $allergies, $chronic_conditions, $current_medications, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Medical history updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
