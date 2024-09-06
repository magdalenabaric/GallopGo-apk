<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $horse_id = intval($_POST['horse_id']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gallopgo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM horses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $horse_id);

    if ($stmt->execute()) {
        echo "Horse deleted successfully";
    } else {
        echo "Error deleting horse: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: all_horses.php");
    exit();
}
