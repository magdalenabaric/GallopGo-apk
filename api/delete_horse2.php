<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['horse_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$horse_id = $conn->real_escape_string($_POST['horse_id']);
$sql = "DELETE FROM horses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $horse_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
