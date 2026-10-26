<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("localhost", "root", "", "student_registration");
if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed. Check XAMPP MySQL and database.sql.");
}
$conn->set_charset("utf8mb4");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
