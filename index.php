<?php
session_start();
include 'assets/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remark VARCHAR(255) NOT NULL,
    duration VARCHAR(50),
    volume VARCHAR(50),
    config_file VARCHAR(255)
)";

try {
    $pdo->exec($query);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

function generateRandomFileName($length = 30) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action']) && $_POST['action'] === 'create_user') {
            $remark = $_POST['remark'];
            $duration = $_POST['duration'];
            $volume = $_POST['volume'];
            $configs = $_POST['configs'];

            $randomFileName = generateRandomFileName() . '.txt';
            $configFilePath = 'sub/' . $randomFileName;

            file_put_contents($configFilePath, $configs);

            $stmt = $pdo->prepare("INSERT INTO users (remark, duration, volume, config_file) VALUES (?, ?, ?, ?)");
            $stmt->execute([$remark, $duration, $volume, $configFilePath]);

            echo json_encode(['status' => 'success']);
            exit;
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete_user') {
            $remark = $_POST['remark'];

            $stmt = $pdo->prepare("SELECT config_file FROM users WHERE remark = ?");
            $stmt->execute([$remark]);
            $configFilePath = $stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM users WHERE remark = ?");
            $stmt->execute([$remark]);

            if (file_exists($configFilePath)) {
                unlink($configFilePath);
            }

            echo json_encode(['status' => 'success']);
            exit;
        }

        if (isset($_POST['action']) && $_POST['action'] === 'edit_user') {
            $remark = $_POST['remark'];
            $duration = $_POST['duration'];
            $volume = $_POST['volume'];
            $configs = $_POST['configs'];

            $stmt = $pdo->prepare("SELECT config_file FROM users WHERE remark = ?");
            $stmt->execute([$remark]);
            $configFilePath = $stmt->fetchColumn();

            $stmt = $pdo->prepare("UPDATE users SET duration = ?, volume = ? WHERE remark = ?");
            $stmt->execute([$duration, $volume, $remark]);

            file_put_contents($configFilePath, $configs);

            echo json_encode(['status' => 'success']);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

$result = $pdo->query("SELECT remark, duration, volume, config_file FROM users");
$users = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>z-panel</title>
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
            <div class="user-card">
                <div class="user-info">
                    <div>ریمارک سرویس: <?php echo htmlspecialchars($user['remark']); ?></div>
                    <div>حجم: <?php echo htmlspecialchars($user['volume']); ?></div>
                    <div>مدت زمان: <?php echo htmlspecialchars($user['duration']); ?></div>
                </div>
                <div class="user-actions">
                    <button onclick="showDropdown(event, '<?php echo htmlspecialchars($user['remark']); ?>')">...</button>
                    <div class="dropdown" id="dropdown-<?php echo htmlspecialchars($user['remark']); ?>">
                        <button class="red" onclick="deleteUser('<?php echo htmlspecialchars($user['remark']); ?>')">حذف سرویس</button>
                        <button onclick="editUser('<?php echo htmlspecialchars($user['remark']); ?>', '<?php echo htmlspecialchars($user['duration']); ?>', '<?php echo htmlspecialchars($user['volume']); ?>', '<?php echo htmlspecialchars(file_get_contents($user['config_file'])); ?>')">ویرایش سرویس</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="assets/panel.script.js"></script>
</body>
</html>
