<?php
// Cấu hình cho InfinityFree
$host = 'sql207.infinityfree.com';      // MySQL Hostname (Bạn cung cấp)
$username = 'if0_40024415';             // MySQL Username (Bạn cung cấp)
$password = 'lth05022004huy'; // <-- Điền mật khẩu vPanel/Hosting
$dbname = 'if0_40024415_banhang';       // <-- Thay 'banhang' bằng tên bạn tạo trên host

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $e) {
    // Trên host thật, ta ẩn lỗi chi tiết để bảo mật
    die("Lỗi kết nối Database. Vui lòng kiểm tra lại cấu hình db.php");
}
?>