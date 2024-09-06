<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "gallopgo");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$update_fields = [];

if (isset($_POST['firstName'])) {
    $fName = $conn->real_escape_string($_POST['firstName']);
    $update_fields[] = "fName='$fName'";
}

if (isset($_POST['lastName'])) {
    $lName = $conn->real_escape_string($_POST['lastName']);
    $update_fields[] = "lName='$lName'";
}

if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $update_fields[] = "email='$email'";
}

if (!empty($update_fields)) {
    $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No data to update"]);
}

$conn->close();
