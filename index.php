<?php
session_start();

// بررسی اینکه آیا کاربر وارد شده است
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// جلوگیری از هک سشن
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) { // 30 دقیقه
    session_unset(); // حذف تمام متغیرهای سشن
    session_destroy(); // نابود کردن سشن
    header("Location: login.php");
    exit;
}
$_SESSION['last_activity'] = time(); // بروزرسانی زمان آخرین فعالیت

// نام کاربری را از نشست بازیابی کنید
$username = $_SESSION['username'] ?? 'کاربر';

// مسیر ذخیره‌سازی اطلاعات
$usersFile = 'data/users.txt';
$configsDir = 'data/configs/';

// بررسی وجود پوشه configs و ایجاد آن در صورت عدم وجود
if (!is_dir($configsDir)) {
    mkdir($configsDir, 0755, true);
}

// بررسی اینکه آیا فرم ارسال شده است
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userRemark = $_POST['userRemark'];
    $subscriptionDuration = $_POST['subscriptionDuration'];
    $subscriptionVolume = $_POST['subscriptionVolume'];
    $configCount = $_POST['configCount'];

    // ذخیره اطلاعات یوزر در فایل users.txt
    $userData = "$userRemark | $subscriptionDuration | $subscriptionVolume\n";
    file_put_contents($usersFile, $userData, FILE_APPEND);

    // ایجاد فایل کانفینگ
    for ($i = 0; $i < $configCount; $i++) {
        $configFileName = "$configsDir/$userRemark-config" . ($i + 1) . ".txt";
        file_put_contents($configFileName, "کانفینگ برای $userRemark\n");
    }

    // بازگشت به صفحه اصلی پس از ایجاد یوزر
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            background-color: darkgray;
        }
        .userspanel {
            background-color: darkblue;
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            margin: 50px auto; /* مرکز کردن پنل */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        .button:disabled {
            background-color: gray;
            cursor: not-allowed;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <title>z-panel</title>
</head>
<body>
    <div class="userspanel">
        <h2>پنل مدیریت</h2>
        <button id="createUserButton" class="button">ایجاد یوزر جدید +</button>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>ایجاد یوزر جدید</h3>
                <form method="POST" id="userForm">
                    <label for="userRemark">ریمارک یوزر:</label>
                    <input type="text" id="userRemark" name="userRemark" required>
                    
                    <label for="subscriptionDuration">مدت زمان اشتراک:</label>
                    <input type="text" id="subscriptionDuration" name="subscriptionDuration" required>
                    
                    <label for="subscriptionVolume">حجم اشتراک:</label>
                    <input type="text" id="subscriptionVolume" name="subscriptionVolume" required>
                    
                    <label for="configCount">تعداد کانفینگ ها:</label>
                    <input type="number" id="configCount" name="configCount" min="1" required>

                    <button type="submit" class="button" id="submitButton" disabled>ایجاد یوزر</button>
                    <button type="button" class="button" id="confirmButton" disabled>تایید</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("modal");
        const createUserButton = document.getElementById("createUserButton");
        const closeButton = document.getElementsByClassName("close")[0];
        const formInputs = document.querySelectorAll("input");
        const submitButton = document.getElementById("submitButton");

        createUserButton.onclick = function() {
            modal.style.display = "block";
        }

        closeButton.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        const checkInputs = () => {
            let allFilled = true;
            formInputs.forEach(input => {
                if (!input.value) allFilled = false;
            });
            submitButton.disabled = !allFilled;
        }

        formInputs.forEach(input => {
            input.addEventListener("input", checkInputs);
        });
    </script>
</body>
</html>
