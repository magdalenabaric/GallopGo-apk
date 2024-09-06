<?php
session_start();

$response = [];

if (!isset($_SESSION['user_id'])) {
    $response['error'] = 'Korisnik nije prijavljen';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gallopgo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $response['error'] = "Povezivanje nije uspjelo: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM horses WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    } else {
        $response['error'] = "Nema pronađenih konja";
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
