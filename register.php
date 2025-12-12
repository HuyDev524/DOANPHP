<?php
session_start();
require 'db.php';

$error = '';
$success = '';

$username = '';
$fullname = '';
$phone = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($username) || empty($password) || empty($fullname) || empty($phone)) {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc!";
    } elseif (strlen($password) < 6) { 
        $error = "Mật khẩu phải có ít nhất 6 ký tự.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // 2. Kiểm tra tên đăng nhập đã tồn tại chưa
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Tên đăng nhập này đã có người sử dụng!";
            } else {
                $sql = "INSERT INTO users (username, password, full_name, phone, address, role) VALUES (?, ?, ?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute([$username, $password, $fullname, $phone, $address])) {
                    $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
                    // Reset form sau khi thành công
                    $username = $fullname = $phone = $address = ''; 
                } else {
                    $error = "Có lỗi xảy ra, vui lòng thử lại!";
                }
            }
        } catch (PDOException $e) {
            error_log("Registration DB Error: " . $e->getMessage());
            $error = "Lỗi hệ thống: Không thể đăng ký tài khoản.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS riêng cho form đăng ký dài hơn chút */
        .auth-container { max-width: 500px; margin: 50px auto; }
        .msg { padding: 15px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
        .msg.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .msg.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        /* ĐIỀU CHỈNH MÀU CHO LINK ĐĂNG NHẬP */
        .msg.success a { 
            color: #155724 !important; /* Màu xanh đậm của text chính */
            text-decoration: underline; 
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Đăng Ký Thành Viên</h2>
        
        <?php if($error): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="msg success">
                <?php echo $success; ?> 
                <br> <a href="login.php">Bấm vào đây để Đăng nhập</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Họ và tên (*)</label>
                <input type="text" name="full_name" required value="<?php echo htmlspecialchars($fullname); ?>" placeholder="Ví dụ: Nguyễn Văn A">
            </div>

            <div class="form-group">
                <label>Số điện thoại (*)</label>
                <input type="text" name="phone" required value="<?php echo htmlspecialchars($phone); ?>" placeholder="09xxxxxxxx">
            </div>

            <div class="form-group">
                <label>Địa chỉ nhận hàng</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Số nhà, đường, phường/xã...">
            </div>

            <div class="form-group">
                <label>Tên đăng nhập (*)</label>
                <input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>">
            </div>

            <div class="form-group">
                <label>Mật khẩu (*)</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Nhập lại mật khẩu (*)</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-submit">ĐĂNG KÝ</button>
        </form>
        
        <div class="auth-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a> <br><br>
            <a href="index.php">← Về trang chủ</a>
        </div>
    </div>
</body>
</html>