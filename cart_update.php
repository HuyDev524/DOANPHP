<?php
session_start();

// 1. XỬ LÝ CẬP NHẬT SỐ LƯỢNG
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]); 
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    // [THÊM DÒNG NÀY] Lưu thông báo vào session
    $_SESSION['msg'] = "Đã cập nhật giỏ hàng thành công!";
}

// 2. XỬ LÝ XÓA SẢN PHẨM
if (isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    unset($_SESSION['cart'][$id]);
    
    // [THÊM DÒNG NÀY] Lưu thông báo xóa
    $_SESSION['msg'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
}

header("Location: cart_view.php");
exit();
?>