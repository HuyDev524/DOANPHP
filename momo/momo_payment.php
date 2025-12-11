<?php
session_start();
// Gọi file kết nối DB (Lưu ý đường dẫn ../db.php nếu file db.php nằm ở thư mục gốc)
require_once '../db.php'; 
require_once 'config.php';

if (!isset($_GET['orderId'])) {
    die("Lỗi: Không tìm thấy mã đơn hàng.");
}

$orderId = intval($_GET['orderId']);

// 1. Lấy thông tin đơn hàng từ Database
try {
    $stmt = $conn->prepare("SELECT total_money FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

if (!$order) {
    die("Đơn hàng không tồn tại!");
}

// 2. Cấu hình dữ liệu gửi sang MoMo
$amount = (string)$order['total_money']; // Số tiền (phải là string)
$requestId = (string)time();
$orderInfo = "Thanh toan don hang #" . $orderId;
$momoOrderId = time() . "_" . $orderId; // ID duy nhất cho MoMo

// --- CẤU HÌNH ĐƯỜNG DẪN QUAN TRỌNG CHO INFINITYFREE ---
$domain = "http://cesiijpi.infinityfree.com"; 
$redirectUrl = $domain . "/momo/momo_return.php";
$ipnUrl = $domain . "/momo/momo_return.php"; // IPN không chạy trên Free host, set giống redirect cho an toàn

$extraData = base64_encode(json_encode(['realOrderId' => $orderId])); // Lưu ID thật vào đây

// 3. Tạo chữ ký (Signature)
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

// 5. Gửi request sang MoMo bằng cURL
$ch = curl_init($config['endpoint']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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
    header('Location: ' . $jsonResult['payUrl']); // Chuyển hướng sang trang quét mã
    exit;
} else {
    echo "<h3>Lỗi kết nối MoMo:</h3>";
    echo "<pre>";
    print_r($jsonResult);
    echo "</pre>";
}
?>