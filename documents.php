<?php
header("Content-Type: application/json");
require "db.php";
if (!isset($_SESSION["student_id"])) {
    http_response_code(401);
    echo json_encode(["success"=>false, "error"=>"Please log in first."]);
    exit;
}
$id = (int)$_SESSION["student_id"];
$stmt = $conn->prepare("SELECT document_name, file_path, upload_date FROM document_track WHERE student_id = ? ORDER BY upload_date DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$documents = [];
while ($row = $result->fetch_assoc()) $documents[] = $row;
echo json_encode(["success"=>true, "documents"=>$documents]);
$stmt->close();
?>
