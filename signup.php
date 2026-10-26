<?php
header("Content-Type: application/json");
require "db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success"=>false, "error"=>"Please submit the signup form."]);
    exit;
}
$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Enter a valid email and a password with at least 6 characters."]);
    exit;
}
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO student_login (email, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hash);
if ($stmt->execute()) {
    echo json_encode(["success"=>true, "message"=>"Account created. Please log in."]);
} else {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>$stmt->errno === 1062 ? "This email is already registered." : "Could not create account. Check the database setup."]);
}
$stmt->close();
?>
