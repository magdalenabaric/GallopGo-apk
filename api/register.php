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

$fname = $phpObject->fname ?? null;
$lname = $phpObject->lname ?? null;
$usermail = $phpObject->uemail ?? null;
$userpassword = $phpObject->upwd ?? null;

if (!$fname || !$lname || !$usermail || !$userpassword) {
    $response = ["isError" => true, "message" => "Missing fields"];
    echo json_encode($response);
    exit();
}

$userpassword = password_hash($userpassword, PASSWORD_DEFAULT);
$response = [];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $usermail);
$stmt->execute();
$result = $stmt->get_result();
$numRows = $result->num_rows;

if ($numRows > 0) {
    $response["isError"] = true;
    $response["message"] = "Email already exists!";
} else {
    $stmt = $conn->prepare(
        "INSERT INTO users (fName, lName, email, password) VALUES (?,?,?,?)"
    );
    $stmt->bind_param("ssss", $fname, $lname, $usermail, $userpassword);
    if ($stmt->execute()) {
        $response["isError"] = false;
        $response["message"] = "Registration successful!";
    } else {
        $response["isError"] = true;
        $response["message"] = "Error: " . $stmt->error;
    }
}

$conn->close();
echo json_encode($response);
