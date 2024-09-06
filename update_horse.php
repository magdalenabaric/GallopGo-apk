<?php
header('Content-Type: application/json');
$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gallopgo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $response['error'] = "Connection failed: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    $horseId = intval($_POST['horseId']);
    $horseName = $conn->real_escape_string($_POST['horseName']);
    $horseAge = intval($_POST['horseAge']);
    $horseBreed = $conn->real_escape_string($_POST['horseBreed']);
    $location = $conn->real_escape_string($_POST['location']);
    $terrain = $conn->real_escape_string($_POST['terrain']);
    $horseImage = $conn->real_escape_string($_POST['horseImage']);

    if (isset($_FILES['horseImage']) && $_FILES['horseImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["horseImage"]["name"]);
        $uploadOk = 1;

        $check = getimagesize($_FILES["horseImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($targetFile)) {
            $response['error'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        if ($_FILES["horseImage"]["size"] > 500000) {
            $response['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $response['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $response['error'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["horseImage"]["tmp_name"], $targetFile)) {
                $horseImage = basename($_FILES["horseImage"]["name"]);
            } else {
                $response['error'] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    $sql = "UPDATE horses SET horseName='$horseName', horseAge='$horseAge', horseBreed='$horseBreed', location='$location', terrain='$terrain', horseImage='$horseImage' WHERE id='$horseId'";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
    } else {
        $response['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

echo json_encode($response);
