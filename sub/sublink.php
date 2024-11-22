<?php
require '../assets/db.php';

$remark = isset($_GET['remark']) ? $_GET['remark'] : '';

$stmt = $pdo->prepare("SELECT remark, volume, duration, config_file FROM users WHERE remark = :remark");
$stmt->bindParam(':remark', $remark);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('کاربر یافت نشد.');
}

$volume = $user['volume'];
$duration = $user['duration'];
$config_file = $user['config_file'];

$domain = $_SERVER['HTTP_HOST'];
$project_folder = basename(dirname(dirname(__FILE__)));
$config_link = "https://$domain/$project_folder/$config_file";

$qrcodeURL = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($config_link) . '&size=200x200';
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اطلاعات سرویس کاربر</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="./js/qrcode.main.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</head>
<style>

    body {background: url('https://4kwallpapers.com/images/walls/thumbs_3t/8985.jpg') no-repeat center;
            background-size: cover;
        }
</style>
<body>

<div class="box">
 <div class="container mt-5">
    <h2>اطلاعات سرویس کاربر</h2>
    <p>حجم: <?php echo htmlspecialchars($volume); ?></p>
    <p>مدت: <?php echo htmlspecialchars($duration); ?></p>
    <p>توضیحات: <?php echo htmlspecialchars($remark); ?></p>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#qrcodeModal">
        <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($qrcodeURL)); ?>" alt="QR Code" style="width: 20px; height: 20px;"> نمایش QR Code
    </button>

    <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrcodeModalLabel">QR Code لینک کانفینگ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo $qrcodeURL; ?>" alt="QR Code" style="width: 200px; height: 200px;">
                    <p class="mt-3">لینک فایل کانفینگ: <span id="configLink"><?php echo htmlspecialchars($config_link); ?></span></p>
                    <button id="copyButton" class="btn btn-secondary">کپی لینک</button>
                </div>
            </div>
        </div>
    </div>
 </div>
</div>

<script>
    document.getElementById('copyButton').onclick = function() {
        const link = document.getElementById('configLink').innerText;
        navigator.clipboard.writeText(link).then(function() {
            alert('لینک کپی شد: ' + link);
        }, function(err) {
            console.error('خطا در کپی کردن لینک: ', err);
        });
    };
</script>

</body>
</html>
