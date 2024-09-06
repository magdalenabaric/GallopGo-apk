<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$target_dir = "uploads/";
$user_id = $_SESSION['user_id'];
$file_name = basename($_FILES["profilePic"]["name"]);
$target_file = $target_dir . $file_name;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = getimagesize($_FILES["profilePic"]["tmp_name"]);
if ($check === false) {
    echo json_encode(["success" => false, "error" => "File is not an image."]);
    exit();
}

if ($_FILES["profilePic"]["size"] > 5000000) {
    echo json_encode(["success" => false, "error" => "File is too large."]);
    exit();
}

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo json_encode(["success" => false, "error" => "Only JPG, JPEG, PNG & GIF files are allowed."]);
    exit();
}

if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
    $conn = new mysqli("localhost", "root", "", "gallopgo");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET profile_pic='$file_name' WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "fileName" => $file_name]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Error uploading file."]);
}
