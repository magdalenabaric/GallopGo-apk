<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT rentals.rental_date, horses.horseName, horses.horseBreed, horses.horseAge, horses.horseImage, horses.location, users.fName, users.lName
FROM rentals
JOIN horses ON rentals.horse_id = horses.id
JOIN users ON horses.user_id = users.id
WHERE rentals.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rentals = [];
while ($row = $result->fetch_assoc()) {
    $rentals[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>My Rents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body class="profile-body">
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <section class="profile-nav" id="profile-nav">
            <a href="profile.php">Profile</a>
            <a href="my-rents.php">My rents</a>
            <a href="my-horses.php">My horses</a>
        </section>
        <a href="" class="profile-btn">Profile</a>
        <a href="#" id="logout-btn" class="logout-btn">Logout</a>
    </nav>

    <h1 class="h1-rent">My Rents</h1>
    <section id="rent-list">
        <?php if (count($rentals) > 0) : ?>
            <?php foreach ($rentals as $rental) : ?>
                <div class="rental-item">
                    <div class="horse-img-cont3">
                        <img src="uploads/<?php echo htmlspecialchars($rental['horseImage']); ?>" alt="<?php echo htmlspecialchars($rental['horseName']); ?>" class="horse-image2">
                    </div>
                    <div class="horse-cont-cont2">
                        <h2 class="horse-name2"><?php echo htmlspecialchars($rental['horseName']); ?></h2>
                        <p>Owner: <?php echo htmlspecialchars($rental['fName'] . ' ' . $rental['lName']); ?></p>

                        <div class="rent-det">
                            <p><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($rental['location']); ?></p>
                            <p><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($rental['rental_date']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No rentals found.</p>
        <?php endif; ?>
    </section>


    <script>
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            fetch('api/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    console.log("Response status:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("Response data:", data);
                    if (data.isError) {
                        alert(data.message);
                    } else {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>