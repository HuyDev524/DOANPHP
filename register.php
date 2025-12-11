<?php
session_start();
require 'db.php';

$error = '';
$success = '';

// Khởi tạo biến để giữ lại giá trị nếu nhập lỗi (Sticky form)
$username = '';
$fullname = '';
$phone = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = trim($_POST['full_name']);
    
    // Lấy thêm 2 thông tin mới
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // 1. Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password) || empty($fullname) || empty($phone)) {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc!";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // 2. Kiểm tra tên đăng nhập đã tồn tại chưa
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Tên đăng nhập này đã có người sử dụng!";
        } else {
            // 3. Thêm người dùng mới vào DB (Lưu password thường theo ý bạn)
            // Câu lệnh SQL thêm cột phone và address
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
                <br> <a href="login.php" style="color: white; text-decoration: underline;">Bấm vào đây để Đăng nhập</a>
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