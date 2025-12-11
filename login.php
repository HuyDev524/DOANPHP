<?php
session_start();
require 'db.php';

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // Tìm user trong database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // So sánh mật khẩu
    if ($user && $password_input === $user['password']) {
        
        // 1. Lưu thông tin vào Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['full_name'];
        $_SESSION['role'] = $user['role']; // Quan trọng: Lưu quyền để các trang khác kiểm tra

        // 2. PHÂN QUYỀN CHUYỂN HƯỚNG (LOGIC MỚI)
        if ($user['role'] == 1) {
            // Nếu là Admin (role = 1) -> Vào trang quản lý
            header("Location: admin_products.php");
        } else {
            // Nếu là Khách (role = 0) -> Vào trang chủ bán hàng
            header("Location: index.php");
        }
        exit();
        
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page"> 
    <div class="auth-container">
        <h2>Đăng Nhập</h2>
        
        <?php if(!empty($error)): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">ĐĂNG NHẬP</button>
        </form>
        
        <div class="auth-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a> <br><br>
            <a href="index.php">← Về trang chủ</a>
        </div>
    </div>
</body>
</html>