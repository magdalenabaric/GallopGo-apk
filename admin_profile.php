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
    die("Povezivanje nije uspjelo: " . $conn->connect_error);
}

$sql = "SELECT id, fName, lName, email FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
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

    <div class="profile-container">
        <h1 class="h1-rent">All Users</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['fName']); ?></td>
                        <td><?php echo htmlspecialchars($user['lName']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <form method="post" action="api/delete_user.php">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <button type="submit" class="delete-btn">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const userId = this.closest('form').querySelector('input[name="user_id"]').value;

                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch('api/delete_user.php', {
                                method: 'POST',
                                body: new URLSearchParams({
                                    'user_id': userId
                                }),
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('User deleted successfully');
                                    this.closest('tr').remove();
                                } else {
                                    alert('Error deleting user: ' + data.error);
                                }
                            })
                            .catch(error => console.error('Error deleting user:', error));
                    }
                });
            });
        });
    </script>
</body>

</html>