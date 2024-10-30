<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $file = fopen("data/admins.txt", "r");
    $login_success = false;

    while (($line = fgets($file)) !== false) {
        list($stored_username, $stored_password) = explode(":", trim($line));
        if ($username === $stored_username && $password === $stored_password) {
            $login_success = true;
            $_SESSION['loggedin'] = true;
            break;
        }
    }

    fclose($file);

    if ($login_success) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p>نام کاربری یا رمز عبور اشتباه است.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>z-panel</title>
</head>
<body>
    <h2>z panel</h2>
    <form action="login.php" method="post">
        <label for="username">نام کاربری:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">رمز عبور:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button type="submit">ورود</button>
    </form>
</body>
</html>
