<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["infoUsername"];
    $email = $_POST["infoEmail"];
    $aadhaar = $_POST["infoAadhaar"];
    $blood_group = $_POST["infoBloodGroup"];
    $birth_date = $_POST["birthDate"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $emergency_name = $_POST["emergencyName"];
    $emergency_contact = $_POST["emergencyContact"];
    $emergency_relation = $_POST["emergencyRelation"];

    $sql = "INSERT INTO additional_info (username, email, aadhaar, blood_group, birth_date, age, gender, emergency_name, emergency_contact, emergency_relation)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssisiss", $username, $email, $aadhaar, $blood_group, $birth_date, $age, $gender, $emergency_name, $emergency_contact, $emergency_relation);

    if ($stmt->execute()) {
        echo "<script>alert('Additional Information Submitted Successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
