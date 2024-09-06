<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Home</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore.js"></script>

</head>

<body>
    <div class="container">
        <img src="images/pozadina.png" alt="" class="pozadina">
    </div>
    <nav>
        <a href="index.php" class="logo-cont"><img src="images/Slika19.png" alt="" class="logo"></a>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a href="profile.php" class="profile-btn2">Profile</a>';
            echo '<a href="logout.php" class="logout-btn">Logout</a>';
        } else {
            echo '<a href="login.php" class="login-btn">Login</a>';
            echo '<a href="register.php" class="register-btn">Register</a>';
        }
        ?>
    </nav>
    <h1 class="main-h1">GallopGo</h1>
    <h2 class="main-h2">Rent best horses wherever you are</h2>
    <?php
    if (isset($_SESSION['user_id'])) {
        echo '<a href="rent.php" class="rent-btn">Rent now</a>';
    } else {
        echo '<a href="login.php" class="rent-btn">Rent now</a>';
    }
    ?>
</body>

</html>