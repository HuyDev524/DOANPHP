<?php
session_start();
require 'db.php';

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']); 
}

// Kiểm tra giỏ hàng có trống không
$cart_empty = true;
$products = [];
$total_money = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $cart_empty = false;
    
    $ids = implode(',', array_keys($_SESSION['cart']));
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($ids)");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS NHANH CHO THÔNG BÁO */
        .msg-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">PHP-Office</a></div>
        </div>
    </div>

    <div class="container">
        <div class="main-content" style="width: 100%;">
            <h2 class="section-title">GIỎ HÀNG CỦA BẠN</h2>

            <?php if ($msg != ""): ?>
                <div class="msg-success">
                    ✔ <?php echo $msg; ?>
                </div>
            <?php endif; ?>
            <?php if ($cart_empty): ?>
                <div style="text-align: center; padding: 50px;">
                    <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="100" style="opacity: 0.5;">
                    <p style="margin-top: 20px; color: #666;">Giỏ hàng đang trống!</p>
                    <a href="index.php" class="btn-checkout" style="background: #007bff;">QUAY LẠI MUA HÀNG</a>
                </div>
                
            <?php else: ?>
                <form action="cart_update.php" method="POST">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): 
                                // Lấy số lượng từ session
                                $qty = $_SESSION['cart'][$p['id']];
                                // Tính thành tiền của từng món
                                $subtotal = $p['price'] * $qty;
                                // Cộng vào tổng tiền đơn hàng
                                $total_money += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <img src="images/<?php echo !empty($p['image']) ? $p['image'] : 'no-image.jpg'; ?>">
                                </td>
                                <td style="text-align: left; font-weight: bold;">
                                    <?php echo $p['name']; ?>
                                </td>
                                <td><?php echo number_format($p['price'], 0, ',', '.'); ?> đ</td>
                                <td>
                                    <input type="number" name="qty[<?php echo $p['id']; ?>]" value="<?php echo $qty; ?>" min="1" class="qty-input">
                                </td>
                                <td style="font-weight: bold; color: #d9534f;">
                                    <?php echo number_format($subtotal, 0, ',', '.'); ?> đ
                                </td>
                                <td>
                                    <a href="cart_update.php?del_id=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-actions">
                        <a href="index.php" class="btn-continue">← Tiếp tục mua sắm</a>
                        <button type="submit" name="update_cart" class="btn-update">Cập nhật số lượng</button>
                    </div>
                </form>

                <div class="total-section">
                    Tổng cộng: <span style="color: red;"><?php echo number_format($total_money, 0, ',', '.'); ?> đ</span>
                </div>

                <div style="text-align: right;">
                    <?php if(isset($_SESSION['username'])): ?>
                        <a href="checkout.php" class="btn-checkout">TIẾN HÀNH THANH TOÁN ►</a>
                    <?php else: ?>
                        <p style="color: red; margin-bottom: 10px; font-style: italic;">Bạn cần đăng nhập để thanh toán</p>
                        <a href="login.php" class="btn-checkout" style="background: #007bff;">ĐĂNG NHẬP NGAY</a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>