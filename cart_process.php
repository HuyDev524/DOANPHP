<?php
session_start();

if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $qty = 1; 

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu sản phẩm đã có trong giỏ
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

header("Location: index.php");
exit();
?>