<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gallopgo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT horses.id, horses.horseName, horses.horseAge, horses.horseBreed, horses.location, horses.terrain, users.fName, users.lName
        FROM horses
        JOIN users ON horses.user_id = users.id";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Horses</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <nav>
        <a href=""><img src="images/Slika19.png" alt="" class="logo"></a>

        <section class="profile-nav" id="profile-nav">
            <a href="admin_profile.php">All users</a>
            <a href="all_rents.php">All Rents</a>
            <a href="all_horses.php">All Horses</a>
        </section>

        <a href="logout.php" class="logout-btn2" id="logout-btn2">Logout</a>
    </nav>
    <div class="container-horses">
        <h1 class="h1-rent">All Horses</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Horse Name</th>
                    <th>Age</th>
                    <th>Breed</th>
                    <th>Location</th>
                    <th>Terrain</th>
                    <th>Owner</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['horseName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['horseAge']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['horseBreed']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['terrain']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fName'] . " " . $row['lName']) . "</td>";
                        echo '<td>
                                <form method="post" action="delete_horse2.php" style="display:inline;">
                                    <input type="hidden" name="horse_id" value="' . htmlspecialchars($row['id']) . '">
                                    <button type="submit" class="delete-btn">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No horses found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const horseId = this.closest('form').querySelector('input[name="horse_id"]').value;

                    if (confirm('Are you sure you want to delete this horse?')) {
                        fetch('delete_horse2.php', {
                                method: 'POST',
                                body: new URLSearchParams({
                                    'horse_id': horseId
                                }),
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Horse deleted successfully');
                                    this.closest('tr').remove();
                                } else {
                                    alert('Error deleting horse: ' + data.error);
                                }
                            })
                            .catch(error => console.error('Error deleting horse:', error));
                    }
                });
            });
        });
    </script>
</body>

</html>