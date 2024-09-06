<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Horses</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <a href="login.php" class="login-btn">Login</a>
        <a href="register.php" class="register-btn">Register</a>
        <a href="#" id="logout-btn" class="logout-btn">Logout</a>
    </nav>

    <div id="horses-container" class="horses-container">
    </div>

    <script>
        function loadHorses() {
            fetch('api/load_horses.php')
                .then(response => response.json())
                .then(data => {
                    if (data.isError) {
                        console.error(data.message);
                    } else {
                        console.log(data.horses);
                        displayHorses(data.horses);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function displayHorses(horses) {
            const horsesContainer = document.getElementById('horses-container');
            horsesContainer.innerHTML = '';

            horses.forEach(horse => {
                const horseElement = document.createElement('div');
                horseElement.className = 'horse';
                horseElement.innerHTML = `
                <h3>${horse.horseName}</h3>
                <p>Age: ${horse.horseAge}</p>
                <p>Breed: ${horse.horseBreed}</p>
                <p>Location: ${horse.location}</p>
                <p>Terrain: ${horse.terrain}</p>
                <img src="${horse.horseImage}" alt="${horse.horseName}">
            `;
                horsesContainer.appendChild(horseElement);
            });
        }

        document.addEventListener('DOMContentLoaded', loadHorses);
    </script>
</body>

</html>