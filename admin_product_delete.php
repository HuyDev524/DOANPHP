<?php
session_start();
require 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    die("Bạn không có quyền!"); 
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Thực hiện xóa
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

// Xóa xong quay về trang danh sách
header("Location: admin_products.php");
exit();
?>