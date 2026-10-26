<?php
header("Content-Type: application/json");
require "db.php";
if (!isset($_SESSION["student_id"])) {
    http_response_code(401);
    echo json_encode(["success"=>false, "error"=>"Please log in first."]);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["document"])) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Choose a file to upload."]);
    exit;
}
$file = $_FILES["document"];
if ($file["error"] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Upload failed. Please try again."]);
    exit;
}
if ($file["size"] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Maximum file size is 5 MB."]);
    exit;
}
$allowed = ["pdf"=>"application/pdf", "jpg"=>"image/jpeg", "jpeg"=>"image/jpeg", "png"=>"image/png"];
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file["tmp_name"]);
if (!isset($allowed[$ext]) || $allowed[$ext] !== $mime) {
    http_response_code(400);
    echo json_encode(["success"=>false, "error"=>"Only PDF, JPG, and PNG files are allowed."]);
    exit;
}
$dir = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    http_response_code(500);
    echo json_encode(["success"=>false, "error"=>"Could not create uploads folder."]);
    exit;
}
$stored = bin2hex(random_bytes(16)) . "." . $ext;
$destination = $dir . DIRECTORY_SEPARATOR . $stored;
if (!move_uploaded_file($file["tmp_name"], $destination)) {
    http_response_code(500);
    echo json_encode(["success"=>false, "error"=>"Could not save uploaded file."]);
    exit;
}
$name = basename($file["name"]);
$path = "uploads/" . $stored;
$id = (int)$_SESSION["student_id"];
$stmt = $conn->prepare("INSERT INTO document_track (student_id, document_name, file_path) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $id, $name, $path);
if ($stmt->execute()) echo json_encode(["success"=>true, "message"=>"Document uploaded successfully."]);
else { @unlink($destination); http_response_code(500); echo json_encode(["success"=>false, "error"=>"Could not save document details."]); }
$stmt->close();
?>
