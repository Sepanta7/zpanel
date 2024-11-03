<?php
session_start();
include 'assets/db.php';

$table_sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$pdo->exec($table_sql);

$check_sql = "SELECT * FROM admins WHERE username = 'admin'";
$stmt = $pdo->prepare($check_sql);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    $insert_sql = "INSERT INTO admins (username, password) VALUES ('admin', :password)";
    $stmt = $pdo->prepare($insert_sql);
    $stmt->execute(['password' => password_hash('admin', PASSWORD_DEFAULT)]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT password FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        $stored_password = $stmt->fetchColumn();

        if (password_verify($password, $stored_password)) {
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
            exit;
        } else {
            echo "<p style='color: red; text-align: center;'>نام کاربری یا رمز عبور اشتباه است.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>نام کاربری یا رمز عبور اشتباه است.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود به سایت</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        body {
            background: linear-gradient(-45deg, #ff5733, #ffbd33, #33ff57, #3385ff);
            background-size: 400% 400%;
            animation: gradient 10s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
            color: #fff;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            outline: none;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>zpanel</h2>
        <form action="login.php" method="post">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">رمز عبور:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">ورود</button>
        </form>
    </div>
</body>
</html>
