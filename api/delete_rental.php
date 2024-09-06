<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['rental_id'])) {
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

$rental_id = $conn->real_escape_string($_POST['rental_id']);
$sql = "DELETE FROM rentals WHERE id = '$rental_id'";

if ($conn->query($sql) === TRUE) {
    $conn->close();
    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
    $conn->close();
    exit();
}
