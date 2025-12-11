<?php
session_start(); // 1. Bắt buộc phải khởi động session để biết đang hủy phiên nào

// 2. Xóa tất cả các biến session đã lưu (như $_SESSION['username'], $_SESSION['user_id'])
session_unset(); 

// 3. Hủy hoàn toàn phiên làm việc trên server
session_destroy(); 

// 4. Chuyển hướng người dùng về trang chủ (hoặc trang login.php tùy bạn)
header("Location: index.php");
exit();
?>