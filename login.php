<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Login</title>
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

    <form id="login-form" class="login-form">
        <h2>Login</h2>

        <input type="email" name="email" id="email-login" placeholder="Email" required>
        <input type="password" name="password" id="password-login" placeholder="Password" required>
        <button type="submit" name="login" id="login-btn">Login</button>
    </form>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = {
                email: document.querySelector('input[name="email"]').value,
                password: document.querySelector('input[name="password"]').value
            };

            fetch('api/login.php', {
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
                        if (data.isAdmin) {
                            window.location.href = 'admin_profile.php';
                        } else {
                            window.location.href = 'profile.php';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>