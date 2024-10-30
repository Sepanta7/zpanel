<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!file_exists('data')) {
    mkdir('data', 0777, true);
}

if (!file_exists('data/users.txt')) {
    file_put_contents('data/users.txt', "");
}

if (!file_exists('data/configs')) {
    mkdir('data/configs', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_user') {
    $remark = $_POST['remark'];
    $duration = $_POST['duration'];
    $volume = $_POST['volume'];
    $configs = $_POST['configs'];

    $configFileName = 'data/configs/' . $remark . '.txt';
    $userDetails = "$remark,$duration,$volume,$configFileName\n";

    file_put_contents($configFileName, $configs);
    file_put_contents('data/users.txt', $userDetails, FILE_APPEND);

    echo json_encode(['status' => 'success']);
    exit;
}

$users = file('data/users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>z-panel</title>
    <style>
        body {
            background-color: darkgray;
        }
        .userspanel {
            background-color: darkblue;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            margin-bottom: 20px;
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
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 10px;
        }
        .button {
            background-color: green;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .button:disabled {
            background-color: gray;
            cursor: not-allowed;
        }
        .input-field {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .user-card {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .user-actions {
            display: flex;
            flex-direction: column;
        }
        .user-actions button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="userspanel">
        <button id="createUserBtn" class="button">ایجاد یوزر جدید +</button>
        <div id="modal" class="modal">
            <div class="modal-content">
                <h2>ایجاد یوزر جدید</h2>
                <label for="remark">ریمارک یوزر:</label>
                <input type="text" id="remark" class="input-field" required>
                
                <label for="duration">مدت زمان اشتراک:</label>
                <input type="text" id="duration" class="input-field" required>
                
                <label for="volume">حجم اشتراک:</label>
                <input type="text" id="volume" class="input-field" required>
                
                <label for="configs">کانفینگ‌ها:</label>
                <textarea id="configs" class="input-field" rows="4" required></textarea>
                
                <button id="submitUser" class="button" disabled>ایجاد یوزر</button>
            </div>
        </div>
    </div>

    <div class="userspanel">
        <h3>یوزرهای موجود:</h3>
        <?php foreach ($users as $user): ?>
            <?php list($remark, $duration, $volume, $configFileName) = explode(',', $user); ?>
            <div class="user-card">
                <div class="user-info">
                    <div style="flex: 1; text-align: left;">ریمارک سرویس: <?php echo htmlspecialchars($remark); ?></div>
                    <div style="flex: 1; text-align: center;">حجم: <?php echo htmlspecialchars($volume); ?></div>
                    <div style="flex: 1; text-align: center;">مدت زمان: <?php echo htmlspecialchars($duration); ?></div>
                </div>
                <div class="user-actions">
                    <button onclick="alert('Actions for <?php echo htmlspecialchars($remark); ?>')">...</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.getElementById('createUserBtn').onclick = function() {
            document.getElementById('modal').style.display = 'block';
        }

        document.getElementById('remark').oninput = validateFields;
        document.getElementById('duration').oninput = validateFields;
        document.getElementById('volume').oninput = validateFields;
        document.getElementById('configs').oninput = validateFields;

        function validateFields() {
            const remark = document.getElementById('remark').value.trim();
            const duration = document.getElementById('duration').value.trim();
            const volume = document.getElementById('volume').value.trim();
            const configs = document.getElementById('configs').value.trim();

            document.getElementById('submitUser').disabled = !(remark && duration && volume && configs);
        }

        document.getElementById('submitUser').onclick = function() {
            const remark = document.getElementById('remark').value;
            const duration = document.getElementById('duration').value;
            const volume = document.getElementById('volume').value;
            const configs = document.getElementById('configs').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('یوزر جدید با موفقیت ایجاد شد!');
                        document.getElementById('modal').style.display = 'none';
                        location.reload();
                    }
                }
            };
            xhr.send(`action=create_user&remark=${encodeURIComponent(remark)}&duration=${encodeURIComponent(duration)}&volume=${encodeURIComponent(volume)}&configs=${encodeURIComponent(configs)}`);
        }
    </script>
</body>
</html>
