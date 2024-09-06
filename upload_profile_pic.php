<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = getimagesize($_FILES["profilePic"]["tmp_name"]);
if ($check === false) {
    echo json_encode(["success" => false, "error" => "File is not an image."]);
    exit();
}

if ($_FILES["profilePic"]["size"] > 2000000) {
    echo json_encode(["success" => false, "error" => "File is too large."]);
    exit();
}

$allowedFormats = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowedFormats)) {
    echo json_encode(["success" => false, "error" => "Only JPG, JPEG, PNG & GIF files are allowed."]);
    exit();
}

if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
    $conn = new mysqli("localhost", "root", "", "gallopgo");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $profilePic = basename($_FILES["profilePic"]["name"]);

    $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
    $stmt->bind_param("si", $profilePic, $user_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "fileName" => $profilePic]);
    } else {
        echo json_encode(["success" => false, "error" => "Error updating profile picture in database."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Error uploading file."]);
}
