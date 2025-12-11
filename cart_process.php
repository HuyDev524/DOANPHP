<?php
session_start();

// Kiểm tra xem có dữ liệu product_id gửi lên không
if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $qty = 1; // Mặc định mỗi lần bấm là mua 1 cái

    // Nếu giỏ hàng chưa tồn tại thì tạo mới
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu sản phẩm đã có trong giỏ -> Tăng số lượng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        // Nếu chưa có -> Thêm mới vào giỏ
        $_SESSION['cart'][$id] = $qty;
    }
}

// Quay lại trang chủ (hoặc trang vừa đứng)
header("Location: index.php");
exit();
?>