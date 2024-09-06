<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Morate biti prijavljeni da biste iznajmili konja.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Povezivanje nije uspjelo: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$selected_date = isset($_GET['date']) ? $_GET['date'] : '';

if ($id === 0) {
    die("Nevažeći ID konja.");
}

$sql = "SELECT horses.*, users.fName, users.lName FROM horses
        JOIN users ON horses.user_id = users.id
        WHERE horses.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $horse = $result->fetch_assoc();
    $owner_name = $horse['fName'] . ' ' . $horse['lName'];
} else {
    die("Konj nije pronađen.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rent Horse Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="profile-body">
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <a href="profile.php" class="profile-btn2">Profile</a>
        <a href="#" class="logout-btn">Logout</a>
    </nav>

    <h1 class="h1-horse"><?php echo htmlspecialchars($horse['horseName']); ?></h1>
    <div class="horse-detail">
        <img src="uploads/<?php echo htmlspecialchars($horse['horseImage']); ?>" alt="Horse Image" class="horse-image2">
        <div class="horse-detail2">
            <p class="loc"><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($horse['location']); ?></p>
            <p class="own" id="own">Owner: <?php echo htmlspecialchars($owner_name); ?></p>
            <p>Age: <?php echo htmlspecialchars($horse['horseAge']); ?></p>
            <p>Breed: <?php echo htmlspecialchars($horse['horseBreed']); ?></p>
            <p>Terrain: <?php echo htmlspecialchars($horse['terrain']); ?></p>
            <div class="date">
                <p><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($selected_date); ?></p>
                <button id="rent-btn" data-id="<?php echo $horse['id']; ?>" data-date="<?php echo htmlspecialchars($selected_date); ?>">Rent Now</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('rent-btn').addEventListener('click', function() {
            const horseId = this.getAttribute('data-id');
            const selectedDate = this.getAttribute('data-date');
            const userId = <?php echo $_SESSION['user_id']; ?>;

            fetch('rent_horse.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        horse_id: horseId,
                        date: selectedDate,
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Konj je uspješno iznajmljen!');
                        window.location.href = 'profile.php';
                    } else {
                        alert('Greška pri iznajmljivanju konja: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Greška:', error);
                });
        });
    </script>

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