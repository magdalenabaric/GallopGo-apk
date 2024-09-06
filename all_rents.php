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

$sql = "SELECT rentals.id, rentals.rental_date, users.fName AS userFName, users.lName AS userLName, horses.horseName
        FROM rentals
        JOIN users ON rentals.user_id = users.id
        JOIN horses ON rentals.horse_id = horses.id";
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
    <title>All Rents</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
    <div class="container-rent">
        <h1 class="h1-rent">All Rents</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rental Date</th>
                    <th>User Name</th>
                    <th>Horse Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rental_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['userFName'] . " " . $row['userLName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['horseName']) . "</td>";
                        echo '<td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="rental_id" value="' . htmlspecialchars($row['id']) . '">
                                    <button type="submit" class="delete-btn">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No rentals found</td></tr>";
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
                    const rentalId = this.closest('form').querySelector('input[name="rental_id"]').value;

                    if (confirm('Are you sure you want to delete this rental?')) {
                        fetch('api/delete_rental.php', {
                                method: 'POST',
                                body: new URLSearchParams({
                                    'rental_id': rentalId
                                }),
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Rental deleted successfully');
                                    this.closest('tr').remove();
                                } else {
                                    alert('Error deleting rental: ' + data.error);
                                }
                            })
                            .catch(error => console.error('Error deleting rental:', error));
                    }
                });
            });
        });
    </script>
</body>

</html>