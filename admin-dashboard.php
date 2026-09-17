<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html"); // Redirect to login if not logged in
    exit();
}
?>