<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>My Horses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="profile-body">
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <section class="profile-nav" id="profile-nav">
            <a href="profile.php">Profile</a>
            <a href="my-rents.php">My rents</a>
            <a href="my-horses.php">My horses</a>
        </section>
        <a href="profile.php" class="profile-btn">Profile</a>
        <a href="#" id="logout-btn" class="logout-btn">Logout</a>
    </nav>
    <h1 class="h1-rent">My Horses</h1>

    <div class="my-horses">
        <button id="add-horse-btn">Add Horse</button>
        <div id="add-horse-form" style="display: none;">
            <h2 class="h2-new">New horse</h2>

            <form id="horse-form">
                <label for="horseName">Horse Name:</label><br>
                <input type="text" id="horseName" name="horseName" required><br>
                <label for="horseAge">Age:</label><br>
                <input type="number" id="horseAge" name="horseAge" required><br>
                <label for="horseBreed">Breed:</label><br>
                <input type="text" id="horseBreed" name="horseBreed" required><br>
                <label for="location">Location:</label><br>
                <input type="text" id="location" name="location" required><br>
                <label for="terrain">Terrain:</label><br>
                <input type="text" id="terrain" name="terrain" required><br>
                <label for="horseImage">Horse Image:</label><br>
                <input type="file" id="horseImage" name="horseImage" accept="image/*" required><br>


                <button type="submit" class="subm">Submit</button>
            </form>
        </div>


        <div id="horse-list">

        </div>
    </div>
    <script src="scripts/horsesjs.js"></script>
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