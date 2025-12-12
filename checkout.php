<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: cart_view.php');
    exit();
}

$total = 0;
$cart_items = [];

foreach ($cart as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $product['quantity'] = $quantity;
        $product['subtotal'] = $product['price'] * $quantity;
        $cart_items[] = $product;
        $total += $product['subtotal'];
    }
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, full_name, phone, address FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$fullname_value = $user['full_name'] ?? '';
$phone_value = $user['phone'] ?? '';
$address_value = $user['address'] ?? ''; 
$email_value = ''; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán - Cửa hàng online</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/checkout_styles.css"> 
</head>
<body>
    <div class="checkout-wrapper">
        <a href="cart_view.php" class="back-link">← Quay lại giỏ hàng</a>
        
        <h1 class="page-title">THANH TOÁN ĐƠN HÀNG</h1>
        
        <form method="POST" action="checkout_process.php">
            <div class="checkout-grid">
                <div>
                    <div class="checkout-form-section">
                        <h3>📍 THÔNG TIN GIAO HÀNG</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Họ tên <span class="required">*</span></label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname_value); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Điện thoại <span class="required">*</span></label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone_value); ?>" required pattern="[0-9]{10,11}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Địa chỉ giao hàng <span class="required">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Nhập địa chỉ đầy đủ"><?php echo htmlspecialchars($address_value); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="checkout-form-section">
                        <h3>💳 PHƯƠNG THỨC THANH TOÁN</h3>
                        
                        <div class="payment-section">
                            <div class="payment-option">
                                <input type="radio" id="momo" name="payment_method" value="momo" checked required>
                                <label for="momo">
                                    <strong>💰 Thanh toán qua MoMo</strong>
                                    <div class="payment-note">Quét mã QR hoặc nhập số dư</div>
                                </label>
                            </div>
                            
                            <div class="payment-option">
                                <input type="radio" id="cod" name="payment_method" value="cod" required>
                                <label for="cod">
                                    <strong>🚚 Thanh toán khi nhận hàng (COD)</strong>
                                    <div class="payment-note">Thanh toán tiền mặt khi nhận hàng</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">TIẾP TỤC THANH TOÁN →</button>
                </div>
                
                <div class="order-sidebar">
                    <h3>📦 CHI TIẾT ĐƠN HÀNG</h3>
                    
                    <div>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <span class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                <span class="order-item-qty">×<?php echo $item['quantity']; ?></span>
                                <span class="order-item-price"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Tổng tiền hàng:</span>
                            <span><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="summary-row total">
                            <span>TỔNG CỘNG:</span>
                            <span><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>