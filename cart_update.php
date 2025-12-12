<?php
session_start();

if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]); 
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    $_SESSION['msg'] = "Đã cập nhật giỏ hàng thành công!";
}

if (isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    unset($_SESSION['cart'][$id]);
    
    $_SESSION['msg'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
}

header("Location: cart_view.php");
exit();
?>