<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get POST data
$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment_method = $_POST['payment_method'] ?? 'momo';

// Validate input
if (empty($fullname) || empty($phone) || empty($email) || empty($address)) {
    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
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
    // Get user info
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $user['user_id'];
    
    // Begin transaction
    $conn->beginTransaction();
    
    // Calculate total
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $stmt_product = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    
    // Determine order status based on payment method
    $order_status = ($payment_method === 'momo') ? 'pending' : 'completed';
    
    // Create order
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_amount, shipping_address, customer_name, customer_phone, customer_email, order_status, payment_method, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $total,
        $address,
        $fullname,
        $phone,
        $email,
        $order_status,
        $payment_method
    ]);
    
    $order_id = $conn->lastInsertId();
    
    // Add order items
    foreach ($cart as $product_id => $quantity) {
        $stmt_product = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $stmt_item = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt_item->execute([$order_id, $product_id, $quantity, $product['price']]);
        }
    }
    
    $conn->commit();
    
    // Save order info to session
    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_total'] = $total;
    $_SESSION['order_address'] = $address;
    $_SESSION['order_fullname'] = $fullname;
    $_SESSION['order_phone'] = $phone;
    $_SESSION['order_email'] = $email;
    $_SESSION['order_payment_method'] = $payment_method;
    
    // Clear cart
    unset($_SESSION['cart']);
    
    // Redirect based on payment method
    if ($payment_method === 'momo') {
        // Redirect to MoMo payment page
        header('Location: momo/momo_payment.php');
    } else {
        // COD success page
        header('Location: momo/order_success.php');
    }
    
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log error
    error_log('Checkout error: ' . $e->getMessage());
    
    $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại';
    header('Location: checkout.php');
}

exit();
