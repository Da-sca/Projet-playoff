<?php
session_start();
include 'functions.php';
$conn = connectDatabase();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM trainers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $trainer = mysqli_fetch_assoc($result);

    if ($trainer && password_verify($password, $trainer['password'])) {
        $_SESSION['trainer_id'] = $trainer['id'];
        $_SESSION['team_id'] = $trainer['team_id']; // Store team access
        
        if ($trainer['is_admin'] == 1) {
            header("Location: acceuil.php"); // Redirect Admin
        } else {
            header("Location: dashboard.php"); // Redirect Trainer
        }
        exit;
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, rgb(255, 247, 0), rgb(203, 124, 5));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }
        button {
            background: rgb(254, 127, 0);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 18px;
        }
        button:hover {
            background: #6a11cb;
        }
        p {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2> Login</h2>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>

        <p><?= $error; ?></p>
    </form>
</div>

</body>
</html>
