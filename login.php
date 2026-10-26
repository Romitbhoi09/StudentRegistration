<?php
header("Content-Type: application/json");
require "db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success"=>false, "error"=>"Please submit the login form."]);
    exit;
}
$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";
$stmt = $conn->prepare("SELECT student_id, password_hash FROM student_login WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if ($user && password_verify($password, $user["password_hash"])) {
    session_regenerate_id(true);
    $_SESSION["student_id"] = (int)$user["student_id"];
    $_SESSION["email"] = $email;
    echo json_encode(["success"=>true, "message"=>"Login successful."]);
} else {
    http_response_code(401);
    echo json_encode(["success"=>false, "error"=>"Incorrect email or password."]);
}
?>
