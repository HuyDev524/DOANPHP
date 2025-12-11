<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get cart from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: cart_view.php');
    exit();
}

// Calculate total
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

// Get user info
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id, email, fullname, phone FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán - Cửa hàng online</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .checkout-wrapper {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        
        .checkout-form-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .checkout-form-section h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #d9534f;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d9534f;
            box-shadow: 0 0 5px rgba(217, 83, 79, 0.3);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .payment-section {
            margin-top: 25px;
        }
        
        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            border-color: #d9534f;
            background-color: #fef9f9;
        }
        
        .payment-option input[type="radio"] {
            width: auto;
            margin-right: 15px;
            cursor: pointer;
            accent-color: #d9534f;
        }
        
        .payment-option label {
            margin: 0;
            font-weight: 600;
            cursor: pointer;
            flex: 1;
            color: #333;
        }
        
        .order-sidebar {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .order-sidebar h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #d9534f;
            padding-bottom: 10px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item-name {
            flex: 1;
            color: #555;
        }
        
        .order-item-qty {
            color: #888;
            margin: 0 10px;
        }
        
        .order-item-price {
            font-weight: 600;
            color: #d9534f;
            text-align: right;
            min-width: 80px;
        }
        
        .order-summary {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
        }
        
        .summary-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #d9534f;
            padding: 12px 0;
            border-top: 1px solid #eee;
            margin-top: 10px;
        }
        
        .btn-submit {
            background: #d9534f;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background 0.3s ease;
        }
        
        .btn-submit:hover {
            background: #c9302c;
        }
        
        .required {
            color: #d9534f;
        }
        
        .back-link {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .back-link:hover {
            color: #d9534f;
        }
        
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .order-sidebar {
                position: relative;
                top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-wrapper">
        <a href="cart_view.php" class="back-link">← Quay lại giỏ hàng</a>
        
        <h1 style="text-align: center; margin-bottom: 30px; color: #333;">THANH TOÁN ĐƠN HÀNG</h1>
        
        <form method="POST" action="checkout_process.php">
            <div class="checkout-grid">
                <!-- Left: Checkout Form -->
                <div>
                    <!-- Shipping Information -->
                    <div class="checkout-form-section">
                        <h3>📍 THÔNG TIN GIAO HÀNG</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Họ tên <span class="required">*</span></label>
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Điện thoại <span class="required">*</span></label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required pattern="[0-9]{10,11}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Địa chỉ giao hàng <span class="required">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Nhập địa chỉ đầy đủ"></textarea>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="checkout-form-section">
                        <h3>💳 PHƯƠNG THỨC THANH TOÁN</h3>
                        
                        <div class="payment-section">
                            <div class="payment-option">
                                <input type="radio" id="momo" name="payment_method" value="momo" checked required>
                                <label for="momo">
                                    <strong>💰 Thanh toán qua MoMo</strong>
                                    <div style="font-size: 12px; color: #888; margin-top: 3px;">Quét mã QR hoặc nhập số dư</div>
                                </label>
                            </div>
                            
                            <div class="payment-option">
                                <input type="radio" id="cod" name="payment_method" value="cod" required>
                                <label for="cod">
                                    <strong>🚚 Thanh toán khi nhận hàng (COD)</strong>
                                    <div style="font-size: 12px; color: #888; margin-top: 3px;">Thanh toán tiền mặt khi nhận hàng</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">TIẾP TỤC THANH TOÁN →</button>
                </div>
                
                <!-- Right: Order Summary -->
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
        
        <div class="checkout-container">
            <h1>THANH TOÁN ĐƠN HÀNG</h1>
            
            <form method="POST" action="checkout_process.php" class="checkout-form">
                <h3>THÔNG TIN GIAO HÀNG</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Họ tên *</label>
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Điện thoại *</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group form-row-full">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group form-row-full">
                    <label>Địa chỉ giao hàng *</label>
                    <textarea name="address" rows="3" required><?php echo htmlspecialchars($_SESSION['checkout_address'] ?? ''); ?></textarea>
                </div>
                
                <h3 style="margin-top: 30px;">PHƯƠNG THỨC THANH TOÁN</h3>
                
                <div class="payment-method">
                    <div class="payment-option">
                        <input type="radio" id="momo" name="payment_method" value="momo" checked>
                        <label for="momo">💰 Thanh toán qua MoMo (Quét QR)</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="cod" name="payment_method" value="cod">
                        <label for="cod">🚚 Thanh toán khi nhận hàng (COD)</label>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">TỔNG CỘNG</h3>
                
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Tổng tiền:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí giao hàng:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-row total">
                        <span>TỔNG CỘNG:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">TIẾP TỤC THANH TOÁN →</button>
            </div>
        </form>
    </div>
</body>
</html>
