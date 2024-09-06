<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gallopgo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Povezivanje nije uspjelo: " . $conn->connect_error);
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "Korisnik uspješno obrisan";
    } else {
        echo "Greška prilikom brisanja korisnika: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_profile.php");
    exit();
}
