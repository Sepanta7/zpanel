<?php
session_start(); 


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include 'assets/db.php';

function getStats() {
    global $pdo;

    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    $usedRam = 1; 
    $totalRam = 20; 
    $usedTraffic = 200;
    $totalTraffic = 1000;
    $activeProcesses = 1;
    $processLimit = 20; 

    return [
        'userCount' => $userCount,
        'ramUsage' => [$usedRam, $totalRam],
        'trafficUsage' => [$usedTraffic, $totalTraffic],
        'processUsage' => [$activeProcesses, $processLimit]
    ];
}

$stats = getStats();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>z panel - داشبورد</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #202020;
            color: #00ff62;
            font-family: Arial, sans-serif;
        }
        .top {
            border-radius: 5px;
            background-color: #1a1a1a;
            margin: 20px;
            padding: 10px;
            max-width: 95%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top h2 {
            margin: 0;
            font-size: 24px;
            text-align: center;
            color: #00ff62;
        }
        .hamberger-menu {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 25px;
            height: 20px;
            cursor: pointer;
        }
        .hamberger-menu div {
            width: 100%;
            height: 3px;
            background-color: #fff;
        }
        .am {
            border-radius: 5px;
            background-color: #1a1a1a;
            margin: 20px;
            padding: 20px;
            max-width: 95%;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .am .users,
        .am .ram,
        .am .trafick,
        .am .procec {
            text-align: center;
            font-size: 16px;
            color: #00ff62;
        }
        .backup {
            border-radius: 5px;
            background-color: #1a1a1a;
            margin: 20px;
            padding: 10px;
            max-width: 95%;
            text-align: center;
        }
        .backup button {
            border-radius: 10px;
            width: 10%;
            border: 0;
            padding: 10px;
            background-color: #00ff62;
            color: #202020;
            font-weight: bold;
            margin: 10px;
            cursor: pointer;
        }
        .backup span {
            color: #00ff62;
            font-size: 18px;
            display: block;
            margin-bottom: 20px;
        }
        .sidebar {
            width: 0;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #00ff62;
            color: white;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .sidebar-btn {
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        .active {
            background-color: #00ff62;
            color: white;
        }
    </style>
</head>
<body>
    <div class="top">
        <h2>z panel</h2>
        <div class="hamberger-menu" onclick="toggleSidebar()">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php" class="active">صفحه اصلی</a>
        <a href="users.php">کاربران <i class="fa fa-user"></i></a>
        <a href="settings.php">تنظیمات <i class="fa fa-gear"></i></a>
        <a href="logout.php">خروج <i class="fa fa-sign-out-alt"></i></a>
    </div>
    <div class="am">
        <div class="users">تعداد کاربران: <?php echo $stats['userCount']; ?></div>
        <div class="ram">
            مصرف رم: <?php echo $stats['ramUsage'][0]; ?>GB / <?php echo $stats['ramUsage'][1]; ?>GB
        </div>
        <div class="trafick">
            مصرف ترافیک: <?php echo $stats['trafficUsage'][0]; ?>MB / <?php echo $stats['trafficUsage'][1]; ?>MB
        </div>
        <div class="procec">
            پروسس‌های فعال: <?php echo $stats['processUsage'][0]; ?> / <?php echo $stats['processUsage'][1]; ?>
        </div>
    </div>

    <div class="backup">
        <span>ابزار های بکاپ</span><br><br>
        <button>sql</button>
        <button>bot</button>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            if (sidebar.style.width === "0px" || sidebar.style.width === "") {
                sidebar.style.width = "50%";
            } else {
                sidebar.style.width = "0";
            }
        }
        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</body>
</html>
