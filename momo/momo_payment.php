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

// 1. Lấy thông tin đơn hàng từ Database
try {
    // ĐÃ SỬA: Lấy total_money (dựa trên DB dump của bạn)
    $stmt = $conn->prepare("SELECT total_money, fullname FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi truy vấn DB: " . $e->getMessage());
}

if (!$order || $order['total_money'] <= 0) {
    die("Đơn hàng không tồn tại (ID: {$orderId}) hoặc giá trị không hợp lệ.");
}

// 2. Cấu hình dữ liệu gửi sang MoMo
$amount = (string)intval($order['total_money']); // Ép kiểu thành số nguyên, rồi thành string
$requestId = (string)time();
$orderInfo = "Thanh toan don hang #" . $orderId . " cho " . ($order['fullname'] ?? 'Khach hang');
$momoOrderId = time() . "_" . $orderId; 

// Đường dẫn trả về
$redirectUrl = $DOMAIN . "/momo/momo_return.php";
$ipnUrl = $DOMAIN . "/momo/momo_return.php"; 

// Lưu ID thật (realOrderId) vào extraData
$extraData = base64_encode(json_encode(['realOrderId' => $orderId])); 

// DEBUGGING: Kiểm tra các giá trị QUAN TRỌNG
$check_vars = ['amount' => $amount, 'orderId' => $momoOrderId, 'orderInfo' => $orderInfo, 'extraData' => $extraData, 'redirectUrl' => $redirectUrl];
foreach ($check_vars as $key => $value) {
    if (empty($value)) {
        die("LỖI DEBUG: Tham số bắt buộc '{$key}' bị rỗng hoặc không hợp lệ. Vui lòng kiểm tra lại dữ liệu DB.");
    }
}
// END DEBUGGING

// 3. Tạo chữ ký (Signature) - Cần đảm bảo thứ tự tham số đúng A-Z
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

// 4. Tạo gói dữ liệu JSON
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

// 5. Gửi request sang MoMo bằng cURL (Giữ nguyên)
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

// 6. Xử lý kết quả trả về
$jsonResult = json_decode($result, true);

if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']); 
    exit;
} else {
    // Nếu vẫn lỗi 20, hiển thị chi tiết request gửi đi
    echo "<h3>Lỗi MoMo (ResultCode 20): Yêu cầu định dạng xấu</h3>";
    echo "<p>Vui lòng kiểm tra lại giá trị Total Money (Phải là số nguyên dương và không có dấu thập phân).</p>";
    echo "<h4>Dữ liệu gửi đi (Request Data):</h4>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}
?>