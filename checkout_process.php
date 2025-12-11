<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Lấy dữ liệu POST từ form
$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment_method = $_POST['payment_method'] ?? 'momo';

// Validate input
if (empty($fullname) || empty($phone) || empty($email) || empty($address)) {
    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin giao hàng';
    header('Location: checkout.php');
    exit();
}

// Get cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: cart_view.php');
    exit();
}

try {
    // 1. Lấy ID người dùng (Dựa trên DB dump: cột ID trong bảng users là 'id')
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Kiểm tra xem có lấy được ID không
    if (!$user) {
        throw new Exception("Không tìm thấy thông tin người dùng.");
    }
    $user_id = $user['id'];
    
    // Bắt đầu Transaction
    $conn->beginTransaction();
    
    // 2. Tính toán tổng tiền
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $stmt_product = $conn->prepare("SELECT price, name FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    
    // 3. Xác định trạng thái đơn hàng ban đầu (Dựa trên logic MoMo)
    // Cột trong DB là 'status'
    $order_status = ($payment_method === 'momo') ? 'pending' : 'pending'; // COD cũng là pending, chờ admin xác nhận
    
    // 4. Tạo đơn hàng (Dựa trên cấu trúc bảng orders đã sửa: user_id, fullname, phone, address, total_money, status, payment_method)
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, fullname, phone, address, total_money, status, payment_method, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $fullname,
        $phone,
        $address,
        $total,
        $order_status,
        $payment_method
    ]);
    
    $order_id = $conn->lastInsertId();
    
    // 5. Thêm chi tiết đơn hàng (order_items)
    foreach ($cart as $product_id => $quantity) {
        $stmt_product = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Cột trong DB order_items có 'product_name'
            $stmt_item = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt_item->execute([$order_id, $product_id, $product['name'], $quantity, $product['price']]);
        }
    }
    
    // Commit Transaction
    $conn->commit();
    
    // 6. Dọn dẹp và Chuyển hướng
    
    // Clear cart
    unset($_SESSION['cart']);
    
    // Redirect based on payment method
    if ($payment_method === 'momo') {
        // Chuyển hướng đến cổng thanh toán MoMo và truyền order_id
        header('Location: momo/momo_payment.php?orderId=' . $order_id);
    } else {
        // COD success page
        header('Location: order_success.php?orderId=' . $order_id); 
    }
    
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Ghi lỗi chi tiết vào log
    error_log('Checkout error: ' . $e->getMessage());
    
    // Hiển thị lỗi ra màn hình
    $_SESSION['error'] = 'Lỗi tạo đơn hàng: ' . $e->getMessage(); 
    header('Location: checkout.php');
}

exit();
?>