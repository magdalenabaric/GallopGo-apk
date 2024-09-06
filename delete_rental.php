<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['rental_id'])) {
    die("Invalid request");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rental_id = $conn->real_escape_string($_POST['rental_id']);
$sql = "DELETE FROM rentals WHERE id = '$rental_id'";

if ($conn->query($sql) === TRUE) {
    $conn->close();
    header("Location: all_rents.php");
    exit();
} else {
    echo "Error: " . $conn->error;
    $conn->close();
}
