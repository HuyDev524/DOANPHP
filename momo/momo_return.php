<?php
session_start();
require_once '../db.php';
require_once 'config.php';

if (empty($_GET['signature'])) {
    die("Truy cập không hợp lệ.");
}

$partnerCode = $_GET['partnerCode'];
$orderId = $_GET['orderId'];
$requestId = $_GET['requestId'];
$amount = $_GET['amount'];
$orderInfo = $_GET['orderInfo'];
$orderType = $_GET['orderType'];
$transId = $_GET['transId'];
$resultCode = $_GET['resultCode'];
$message = $_GET['message'];
$payType = $_GET['payType'];
$responseTime = $_GET['responseTime'];
$extraData = $_GET['extraData'];
$momoSignature = $_GET['signature'];

$rawHash = "accessKey=" . $config['accessKey'] . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
$partnerSignature = hash_hmac("sha256", $rawHash, $config['secretKey']);

if ($momoSignature !== $partnerSignature) {
    die("<h3>Cảnh báo: Chữ ký không hợp lệ!</h3>");
}
//
$decodedData = json_decode(base64_decode($extraData), true);
$realOrderId = $decodedData['realOrderId'];

// 
if ($resultCode == '0') {
    try {
        //
        $stmt = $conn->prepare("UPDATE orders SET status = 'confirmed', payment_method = 'momo' WHERE id = ?");
        $stmt->execute([$realOrderId]);
        
        // Xóa giỏ hàng
        unset($_SESSION['cart']);
        $msg = "Thanh toán thành công!";
        $icon = "✅";
        $color = "#28a745"; // Màu xanh
    } catch(PDOException $e) {
        $msg = "Lỗi cập nhật DB: " . $e->getMessage();
        $icon = "⚠️";
        $color = "#ffc107";
    }
} else {
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $stmt->execute([$realOrderId]);
        
        $msg = "Thanh toán thất bại: " . $message;
        $icon = "❌";
        $color = "#dc3545"; 
    } catch(PDOException $e) {
        $msg = "Lỗi DB: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán</title>
    <link rel="stylesheet" href="../css/style.css"> <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%; }
        .icon { font-size: 60px; margin-bottom: 20px; display: block; }
        .btn { display: inline-block; padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 5px; margin-top: 25px; transition: 0.3s; }
        .btn:hover { background: #555; }
    </style>
</head>
<body>
    <div class="card">
        <span class="icon"><?php echo $icon; ?></span>
        <h2 style="color: <?php echo $color; ?>"><?php echo $msg; ?></h2>
        
        <div style="text-align: left; margin-top: 20px; background: #f1f1f1; padding: 15px; border-radius: 5px; font-size: 14px;">
            <p><strong>Mã đơn hàng:</strong> #<?php echo $realOrderId; ?></p>
            <p><strong>Số tiền:</strong> <?php echo number_format($amount); ?> đ</p>
            <p><strong>Thời gian:</strong> <?php echo date("H:i:s d/m/Y"); ?></p>
        </div>

        <a href="../index.php" class="btn">Quay về trang chủ</a>
    </div>

    <?php if ($resultCode == '0'): ?>
    <script>
        localStorage.removeItem('cart');
    </script>
    <?php endif; ?>
</body>
</html>