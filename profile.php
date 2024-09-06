<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body class="profile-body">
    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <section class="profile-nav" id="profile-nav">
            <a href="profile.php" id="profile-link">Profile</a>
            <a href="my-rents.php" id="rents-link">My rents</a>
            <a href="my-horses.php" id="horses-link">My horses</a>
        </section>
        <a href="" class="profile-btn">Profile</a>
        <a href="#" id="logout-btn" class="logout-btn">Logout</a>
    </nav>
    <h1 class="h1-rent">My Profile</h1>
    <div class="profile-info">
        <div class="profile-pic-container">
            <img id="profile-pic" src="" alt="Profile Picture">
        </div>
        <div class="upload-section">
            <label for="profile-pic-upload" class="upload-icon">
                <i class="fa fa-edit"></i>
            </label>
            <input type="file" id="profile-pic-upload" accept="image/*" style="display:none;">
        </div>
        <div class="profile-info-cont">
            <div class="display-data">
                <div class="profile-item">
                    <p><strong>Name:</strong></p>
                    <span id="firstName"></span>
                    <input type="text" id="firstName-input" class="edit-input" style="display:none;">
                </div>
                <i class="fa fa-edit edit-icon" onclick="enableEdit('firstName')"></i>
            </div>
            <div class="display-data">
                <div class="profile-item">
                    <p><strong>Surname:</strong></p>
                    <span id="lastName"></span>
                    <input type="text" id="lastName-input" class="edit-input" style="display:none;">
                </div>
                <i class="fa fa-edit edit-icon" onclick="enableEdit('lastName')"></i>
            </div>
            <div class="display-data">
                <div class="profile-item">
                    <p><strong>Email:</strong></p>
                    <span id="email"></span>
                    <input type="text" id="email-input" class="edit-input" style="display:none;">
                </div>
                <i class="fa fa-edit edit-icon" onclick="enableEdit('email')"></i>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("api/fetch_profile.php")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("firstName").innerText = data.fName;
                    document.getElementById("lastName").innerText = data.lName;
                    document.getElementById("email").innerText = data.email;
                    if (data.profile_pic) {
                        document.getElementById("profile-pic").src = "uploads/" + data.profile_pic;
                    }
                })
                .catch(error => console.error("Error fetching profile data:", error));
        });

        document.getElementById('profile-pic-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const formData = new FormData();
            formData.append('profilePic', file);

            fetch('upload_profile_pic.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profile-pic').src = "uploads/" + data.fileName;
                    } else {
                        console.error('Error uploading profile picture:', data.error);
                    }
                })
                .catch(error => console.error('Error uploading profile picture:', error));
        });

        function enableEdit(field) {
            const displayElement = document.getElementById(field);
            const inputElement = document.getElementById(`${field}-input`);
            const editIcon = document.querySelector(`.edit-icon[onclick="enableEdit('${field}')"]`);

            if (inputElement.style.display === "none" || inputElement.style.display === "") {
                displayElement.style.display = "none";
                inputElement.style.display = "inline-block";
                inputElement.value = displayElement.innerText;
                inputElement.focus();
                editIcon.classList.add("fa-save");
                editIcon.classList.remove("fa-edit");
            } else {
                displayElement.innerText = inputElement.value;
                inputElement.style.display = "none";
                displayElement.style.display = "block";
                editIcon.classList.remove("fa-save");
                editIcon.classList.add("fa-edit");
                saveChanges(field, inputElement.value);
            }
        }

        function saveChanges(field, value) {
            const formData = new FormData();
            formData.append(field, value);

            fetch('api/update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Error updating profile:', data.error);
                    }
                })
                .catch(error => console.error('Error updating profile:', error));
        }

        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            fetch('logout.php', {
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