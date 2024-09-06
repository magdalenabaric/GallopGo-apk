<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Register</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <img src="images/login.png" alt="" class="pozadina2">

    <nav>
        <a href="index.php"><img src="images/Slika19.png" alt="" class="logo"></a>
        <a href="login.php" class="login-btn">Login</a>
        <a href="register.php" class="register-btn">Register</a>
    </nav>

    <form id="signup-form" class="signup-form">
        <h2>Register</h2>

        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" id="register-btn">Register</button>
    </form>

    <script>
        document.getElementById('signup-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = {
                fname: document.querySelector('input[name="firstName"]').value,
                lname: document.querySelector('input[name="lastName"]').value,
                uemail: document.querySelector('input[name="email"]').value,
                upwd: document.querySelector('input[name="password"]').value
            };

            fetch('api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
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
                        window.location.href = 'login.php';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>