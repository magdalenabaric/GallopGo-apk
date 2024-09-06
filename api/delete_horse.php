<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['horseId'])) {
        $horseId = $data['horseId'];

        $conn = new mysqli('localhost', 'root', '', 'gallopgo');

        if ($conn->connect_error) {
            echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
            exit;
        }

        $stmt = $conn->prepare('DELETE FROM horses WHERE id = ?');
        $stmt->bind_param('i', $horseId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete horse']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
