<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Korisnik nije prijavljen']);
    exit;
}

if (!isset($data['horse_id'], $data['date'])) {
    echo json_encode(['success' => false, 'message' => 'Nedostaju podaci']);
    exit;
}

$horse_id = intval($data['horse_id']);
$date = $data['date'];
$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Povezivanje nije uspjelo: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT * FROM rentals WHERE horse_id = ? AND rental_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $horse_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Konj nije dostupan na odabrani datum.']);
    exit;
}

$sql = "INSERT INTO rentals (horse_id, user_id, rental_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $horse_id, $user_id, $date);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Konj je uspješno iznajmljen!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Došlo je do greške prilikom iznajmljivanja konja.']);
}

$stmt->close();
$conn->close();
