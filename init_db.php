<?php
function connect_db()
{
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$conn = connect_db();

$sql = "CREATE DATABASE IF NOT EXISTS gallopgo";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("gallopgo");

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL,
    is_admin TINYINT(1) DEFAULT 0
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS horses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    horseName VARCHAR(255) NOT NULL,
    horseAge INT NOT NULL,
    horseBreed VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    terrain VARCHAR(255) NOT NULL,
    horseImage VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'horses' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS horse_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    horse_id INT NOT NULL,
    available_date DATE NOT NULL,
    available BOOLEAN NOT NULL DEFAULT 1,
    FOREIGN KEY (horse_id) REFERENCES horses(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'horse_availability' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$sql = "CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    horse_id INT NOT NULL,
    user_id INT NOT NULL,
    rental_date DATE NOT NULL,
    FOREIGN KEY (horse_id) REFERENCES horses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'rentals' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$adminFName = 'Admin';
$adminLName = 'Admin';
$adminEmail = 'admin@gmail.com';
$adminPassword = password_hash('mbmbmb', PASSWORD_DEFAULT);
$isAdmin = 1;

$sql = "INSERT INTO users (fName, lName, email, password, is_admin) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $adminFName, $adminLName, $adminEmail, $adminPassword, $isAdmin);

if ($stmt->execute() === TRUE) {
    echo "Admin user created successfully<br>";
} else {
    echo "Error creating admin user: " . $stmt->error . "<br>";
}

$stmt->close();
$conn->close();
