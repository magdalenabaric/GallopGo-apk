<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["isError" => true, "message" => "User not logged in"]);
    exit();
}

include "../connect.php";

$conn = connect_db();

if ($conn->connect_error) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["isError" => true, "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT fName, lName, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["isError" => true, "message" => "Failed to prepare statement: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$stmt->close();
$conn->close();

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($userData);
