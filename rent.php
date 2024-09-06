<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Rent a Horse</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body class="rent-body">
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <a href="profile.php" class="profile-btn2">Profile</a>
        <a href="#" id="logout-btn" class="logout-btn">Logout</a>
    </nav>
    <section class="top-img">
        <img src="images/pozadina3.png" alt="" class="img-konji">
        <h1 id="top-h1">Find your perfect horse match</h1>
        <h3 id="tp-h3">on every location</h3>
        <div class="search-cont-main">
            <input type="text" id="location-input" placeholder="Enter location">
            <input type="date" id="date-input">
            <button id="search-btn"><i class="fa fa-search"></i></button>
        </div>
    </section>

    <section id="horse-list2">
    </section>

    <script>
        document.getElementById('search-btn').addEventListener('click', function() {
            const location = document.getElementById('location-input').value;
            const date = document.getElementById('date-input').value;

            if (location === "" || date === "") {
                alert("Please enter both a location and a date");
                return;
            }

            fetch(`search_horses.php?location=${location}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    const horseList = document.getElementById('horse-list2');
                    horseList.innerHTML = '';

                    if (data.length === 0) {
                        horseList.innerHTML = '<p>No horses found for this location and date.</p>';
                    } else {
                        data.forEach(horse => {
                            const horseElement = document.createElement('div');
                            horseElement.classList.add('horse-item');
                            horseElement.innerHTML = `
                                <div class="horse-img-cont">
                                    <img src="uploads/${horse.horseImage}" alt="${horse.horseName}" class="horse-image">
                                </div>
                                <div class="horse-cont-cont">
                                    <h2 class="horse-name", id="horse-name">${horse.horseName}</h2>
                                    <p>Age: ${horse.horseAge}</p>
                                    <p>Breed: ${horse.horseBreed}</p>
                                    <p>Location: ${horse.location}</p>
                                    <p>Terrain: ${horse.terrain}</p>
                                    <button class="rent-btn3" data-id="${horse.id}" data-date="${date}">Rent Now</button>
                                </div>
                            `;
                            horseList.appendChild(horseElement);
                        });

                        document.querySelectorAll('.rent-btn3').forEach(button => {
                            button.addEventListener('click', function() {
                                const horseId = this.getAttribute('data-id');
                                const selectedDate = this.getAttribute('data-date');
                                window.location.href = `rent_details.php?id=${horseId}&date=${selectedDate}`;
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            fetch('api/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
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