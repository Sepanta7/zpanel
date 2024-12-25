<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dbname = $_POST['dbname'];
    $dbusername = $_POST['dbusername'];
    $dbpassword = $_POST['dbpassword'];

    // محتوای فایل db.php
    $content = "<?php\n";
    $content .= "// نمایش تمامی خطاها\n";
    $content .= "error_reporting(E_ALL);\n";
    $content .= "ini_set('display_errors', 1);\n";
    $content .= "\$host = 'localhost'; // یا آدرس IP سرور دیتابیس\n";
    $content .= "\$dbname = '$dbname'; // نام دیتابیس\n";
    $content .= "\$username = '$dbusername'; // نام کاربری دیتابیس\n";
    $content .= "\$password = '$dbpassword'; // رمز عبور دیتابیس\n\n";
    $content .= "try {\n";
    $content .= "    // ایجاد اتصال به دیتابیس\n";
    $content .= "    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8\", \$username, \$password);\n";
    $content .= "    // تنظیم حالت خطا\n";
    $content .= "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
    $content .= "} catch (PDOException \$e) {\n";
    $content .= "    // نمایش خطا در صورت عدم موفقیت در اتصال\n";
    $content .= "    die(\"Connection failed: \" . \$e->getMessage());\n";
    $content .= "}\n";
    $content .= "?>";

    
    file_put_contents('assets/db.php', $content);
    echo "فایل db.php با موفقیت ایجاد شد.";
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نصب پنل</title>
</head>
<body>
    <h1>نصب پنل</h1></h1>
    <form method="POST" action="">
        <label for="dbname">نام دیتابیس:</label>
        <input type="text" id="dbname" name="dbname" required><br><br>
        <label for="dbusername">نام کاربری دیتابیس:</label>
        <input type="text" id="dbusername" name="dbusername" required><br><br>
        <label for="dbpassword">رمز عبور دیتابیس:</label>
        <input type="password" id="dbpassword" name="dbpassword" required><br><br>
        <button type="submit">ایجاد فایل</button>
    </form>
</body>
</html>
