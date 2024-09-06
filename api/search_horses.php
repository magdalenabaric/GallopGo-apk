<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["isError" => true, "message" => "User not logged in"]);
    exit();
}

include "../connect.php";

$conn = connect_db();

if ($conn->connect_error) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["isError" => true, "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$user_id = $_SESSION['user_id'];
$location = $conn->real_escape_string($_GET['location']);
$date = $conn->real_escape_string($_GET['date']);

$sql = "SELECT horses.id, horses.horseName, horses.horseAge, horses.horseBreed, horses.location, horses.terrain, horses.horseImage
        FROM horses
        LEFT JOIN rentals ON horses.id = rentals.horse_id AND rentals.rental_date = '$date'
        WHERE horses.location = '$location' 
        AND rentals.id IS NULL
        AND horses.user_id != '$user_id'";

$result = $conn->query($sql);

$horses = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $horses[] = $row;
    }
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($horses);

$conn->close();
