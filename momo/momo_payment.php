<?php
// momo/momo_payment.php
session_start();
require_once '../db.php'; 
require_once 'config.php';

// Cập nhật Domain của bạn
$DOMAIN = "http://thanhhuyle.infinityfree.me"; 

if (!isset($_GET['orderId'])) {
    die("Lỗi: Không tìm thấy mã đơn hàng.");
}

$orderId = intval($_GET['orderId']);

//
try {

    $stmt = $conn->prepare("SELECT total_money, fullname FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi truy vấn DB: " . $e->getMessage());
}

if (!$order || $order['total_money'] <= 0) {
    die("Đơn hàng không tồn tại (ID: {$orderId}) hoặc giá trị không hợp lệ.");
}

//
$amount = (string)intval($order['total_money']); 
$requestId = (string)time();
$orderInfo = "Thanh toan don hang #" . $orderId . " cho " . ($order['fullname'] ?? 'Khach hang');
$momoOrderId = time() . "_" . $orderId; 

$redirectUrl = $DOMAIN . "/momo/momo_return.php";
$ipnUrl = $DOMAIN . "/momo/momo_return.php"; 

// Lưu ID thật vào extraData
$extraData = base64_encode(json_encode(['realOrderId' => $orderId])); 

$check_vars = ['amount' => $amount, 'orderId' => $momoOrderId, 'orderInfo' => $orderInfo, 'extraData' => $extraData, 'redirectUrl' => $redirectUrl];
foreach ($check_vars as $key => $value) {
    if (empty($value)) {
        die("LỖI DEBUG: Tham số bắt buộc '{$key}' bị rỗng hoặc không hợp lệ. Vui lòng kiểm tra lại dữ liệu DB.");
    }
}

// 
$rawHash = "accessKey=" . $config['accessKey'] . 
           "&amount=" . $amount . 
           "&extraData=" . $extraData . 
           "&ipnUrl=" . $ipnUrl . 
           "&orderId=" . $momoOrderId . 
           "&orderInfo=" . $orderInfo . 
           "&partnerCode=" . $config['partnerCode'] . 
           "&redirectUrl=" . $redirectUrl . 
           "&requestId=" . $requestId . 
           "&requestType=captureWallet";

$signature = hash_hmac("sha256", $rawHash, $config['secretKey']);

//
$data = [
    'partnerCode' => $config['partnerCode'],
    'partnerName' => 'Web Shop',
    'storeId'     => 'MomoTestStore',
    'requestId'   => $requestId,
    'amount'      => $amount,
    'orderId'     => $momoOrderId,
    'orderInfo'   => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl'      => $ipnUrl,
    'lang'        => 'vi',
    'extraData'   => $extraData,
    'requestType' => 'captureWallet',
    'signature'   => $signature
];

// 
$ch = curl_init($config['endpoint']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// ... cURL options ...
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data))
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$result = curl_exec($ch);
curl_close($ch);

// 
$jsonResult = json_decode($result, true);

if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']); 
    exit;
} else {
    echo "<h3>Lỗi MoMo (ResultCode 20): Yêu cầu định dạng xấu</h3>";
    echo "<p>Vui lòng kiểm tra lại giá trị Total Money (Phải là số nguyên dương và không có dấu thập phân).</p>";
    echo "<h4>Dữ liệu gửi đi (Request Data):</h4>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}
?>