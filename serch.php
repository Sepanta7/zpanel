<?php
function readDataFromFile($filePath) {
    $data = [];
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $data[] = explode('|', $line);
        }
    } else {
        echo "فایل $filePath یافت نشد.";
    }
    return $data;
}

function readConfigFile($configPath) {
    $fullPath = 'data/' . $configPath;
    if (file_exists($fullPath)) {
        return file_get_contents($fullPath);
    } else {
        return "فایل کانفیگ یافت نشد.";
    }
}

$remark = isset($_GET['remark']) ? trim($_GET['remark']) : '';
$services = readDataFromFile('data/users.txt');
$serviceInfo = null;
$configContent = '';
foreach ($services as $service) {
    if (strcasecmp(trim($service[0]), $remark) === 0) {
        $serviceInfo = $service;
        $configContent = readConfigFile(trim($service[3])); // خواندن فایل کانفیگ با استفاده از مسیر در ستون چهارم
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جستجوی سرویس</title>
    <link rel="stylesheet" href="styles.css">
    <script src="qrcode/main.js"></script>
    <script src="qrcode/qrcode.min.js"></script>
    <style>
        .panel {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .qrcode {
            display: none;
        }
        .copy-button {
            margin: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="panel">
    <h2>اطلاعات سرویس</h2>
    <?php if ($serviceInfo): ?>
        <p><strong>ریمـارک:</strong> <?php echo htmlspecialchars($serviceInfo[0]); ?></p>
        <p><strong>زمان سرویس:</strong> <?php echo htmlspecialchars($serviceInfo[1]); ?></p>
        <p><strong>حجم سرویس:</strong> <?php echo htmlspecialchars($serviceInfo[2]); ?></p>
        <div>
            <strong>کانفیگ:</strong>
            <pre id="configContent"><?php echo htmlspecialchars($configContent); ?></pre>
            <button class="copy-button" onclick="copyConfig()">کپی</button>
            <button class="copy-button" onclick="generateQRCode()">QR Code</button>
        </div>
        <div class="qrcode" id="qrcode"></div>
    <?php else: ?>
        <p>سرویس مورد نظر یافت نشد یا اطلاعات به درستی وارد نشده است.</p>
    <?php endif; ?>
</div>

<script>
function copyConfig() {
    const content = document.getElementById('configContent').innerText;
    navigator.clipboard.writeText(content).then(() => {
        alert("کانفیگ کپی شد!");
    });
}

function generateQRCode() {
    const content = document.getElementById('configContent').innerText;
    $("#qrcode").empty().qrcode({ text: content });
    document.getElementById('qrcode').style.display = 'block';
}
</script>

</body>
</html>
