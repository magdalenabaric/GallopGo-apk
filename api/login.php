<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("HTTP/1.1 405 Method Not Allowed");
    $response = ["isError" => true, "message" => "Invalid request method"];
    echo json_encode($response);
    exit();
}

include "../connect.php";
$conn = connect_db();

$jsonData = file_get_contents("php://input");
$phpObject = json_decode($jsonData);

if (!$phpObject) {
    $response = ["isError" => true, "message" => "Invalid JSON"];
    echo json_encode($response);
    exit();
}

$email = $phpObject->email ?? null;
$password = $phpObject->password ?? null;

if (!$email || !$password) {
    $response = ["isError" => true, "message" => "Missing fields"];
    echo json_encode($response);
    exit();
}

$stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed_password, $is_admin);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_email'] = $email;
        $_SESSION['is_admin'] = $is_admin;

        $response = ["isError" => false, "isAdmin" => $is_admin];
    } else {
        $response = ["isError" => true, "message" => "Invalid email or password"];
    }
} else {
    $response = ["isError" => true, "message" => "Invalid email or password"];
}

$stmt->close();
$conn->close();
echo json_encode($response);
