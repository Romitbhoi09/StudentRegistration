<?php
header("Content-Type: application/json");
require "db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success"=>false, "error"=>"Please submit the form."]);
    exit;
}
$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Enter a valid email and a new password of at least 6 characters."]);
    exit;
}
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE student_login SET password_hash = ? WHERE email = ?");
$stmt->bind_param("ss", $hash, $email);
$stmt->execute();
$changed = $stmt->affected_rows;
$stmt->close();
if ($changed > 0) {
    echo json_encode(["success"=>true, "message"=>"Password updated. Please log in."]);
} else {
    echo json_encode(["success"=>false, "error"=>"Email not found, or the new password is the same as the current one."]);
}
?>
