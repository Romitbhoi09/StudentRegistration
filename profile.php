<?php
header("Content-Type: application/json");
require "db.php";
if (!isset($_SESSION["student_id"])) {
    http_response_code(401);
    echo json_encode(["success"=>false, "error"=>"Please log in first."]);
    exit;
}
$id = (int)$_SESSION["student_id"];
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $conn->prepare("SELECT full_name, dob, phone, course, address FROM student_registration WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode(["success"=>true, "profile"=>$row ?: new stdClass()]);
    $stmt->close();
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["full_name"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $address = trim($_POST["address"] ?? "");
    if ($name === "") {
        http_response_code(400);
        echo json_encode(["success"=>false, "error"=>"Full name is required."]);
        exit;
    }
    $dobValue = $dob === "" ? null : $dob;
    $stmt = $conn->prepare("INSERT INTO student_registration (student_id, full_name, dob, phone, course, address) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), dob=VALUES(dob), phone=VALUES(phone), course=VALUES(course), address=VALUES(address)");
    $stmt->bind_param("isssss", $id, $name, $dobValue, $phone, $course, $address);
    if ($stmt->execute()) echo json_encode(["success"=>true, "message"=>"Profile updated."]);
    else { http_response_code(500); echo json_encode(["success"=>false, "error"=>"Could not update profile."]); }
    $stmt->close();
    exit;
}
http_response_code(405);
echo json_encode(["success"=>false, "error"=>"Method not allowed."]);
?>
