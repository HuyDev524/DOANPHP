<?php
session_start();
require 'db.php'; // Đảm bảo file này tồn tại và kết nối PDO thành công

// Lấy dữ liệu cho thanh điều hướng
$stmt_cats = $conn->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);
$total_items = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// --- HÀM ĐỆ QUY ĐỂ QUÉT TOÀN BỘ THƯ MỤC CON VÀ FILE ---
/**
 * Quét thư mục đệ quy và tạo HTML cho các file/thư mục.
 * @param string $dir Đường dẫn vật lý đến thư mục cần quét.
 * @param string $base_url Đường dẫn web tương đối cho các liên kết.
 */
function display_directory_contents($dir, $base_url) {
    // Chỉ xử lý các thư mục tồn tại và có thể đọc được
    if (!is_dir($dir) || !($handle = opendir($dir))) {
        return "<p style='color: red;'>Không thể đọc thư mục: " . htmlspecialchars($dir) . "</p>";
    }

    $html = '<ul>';
    $files = [];
    $folders = [];

    // Đọc tất cả các mục trong thư mục hiện tại
    while (false !== ($item = readdir($handle))) {
        // Bỏ qua các mục hệ thống
        if ($item == "." || $item == "..") {
            continue;
        }

        $full_path = $dir . '/' . $item;
        $web_path = $base_url . '/' . $item;

        if (is_dir($full_path)) {
            $folders[] = [
                'name' => $item,
                'path' => $full_path,
                'web_path' => $web_path
            ];
        } else if (is_file($full_path)) {
            $files[] = [
                'name' => $item,
                'web_path' => $web_path
            ];
        }
    }
    closedir($handle);

    // Sắp xếp thư mục và file theo tên
    usort($folders, function($a, $b) { return strcmp($a['name'], $b['name']); });
    usort($files, function($a, $b) { return strcmp($a['name'], $b['name']); });

    // 1. Hiển thị tất cả các file trong thư mục hiện tại
    foreach ($files as $file) {
        $html .= '<li>';
        $html .= '<a href="' . htmlspecialchars($file['web_path']) . '" target="_blank">';
        $html .= '<span class="file-icon">📄</span> ' . htmlspecialchars($file['name']);
        $html .= '</a>';
        $html .= '</li>';
    }

    // 2. Hiển thị tất cả các thư mục con (và gọi đệ quy)
    foreach ($folders as $folder) {
        $html .= '<li class="is-folder">';
        $html .= '<span class="folder-icon">📁</span> **' . htmlspecialchars($folder['name']) . '**';
        
        // Gọi đệ quy để quét thư mục con
        $html .= display_directory_contents($folder['path'], $folder['web_path']);
        
        $html .= '</li>';
    }

    $html .= '</ul>';
    return $html;
}

// Thiết lập thư mục gốc cần quét
$ROOT_FOLDER_NAME = "bt_LeThanhHuy";
$ROOT_DIR = __DIR__ . '/' . $ROOT_FOLDER_NAME; // Đường dẫn vật lý tuyệt đối
$ROOT_URL = $ROOT_FOLDER_NAME; // Đường dẫn web tương đối
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Bài Tập - <?php echo htmlspecialchars($_SESSION['username'] ?? 'Sinh Viên'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* CSS Cơ bản */
        .homework-list { max-width: 900px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.05); }
        .homework-list ul { list-style: none; padding-left: 20px; border-left: 1px solid #ccc; }
        .homework-list ul ul { margin-top: 5px; margin-bottom: 5px; } /* Thụt lề cho cấp độ con */
        .homework-list li { margin: 5px 0; }
        .homework-list li a { 
            text-decoration: none; color: #343a40; padding: 5px 10px; display: block; 
            border-bottom: 1px dashed #ced4da; transition: background-color 0.2s; 
        }
        .homework-list li a:hover { color: #0056b3; background-color: #e9ecef; }
        .file-icon, .folder-icon { margin-right: 5px; }
        .file-icon { color: #28a745; }
        .folder-icon { color: #ffc107; font-size: 1.1em; }
        .is-folder { font-weight: bold; color: #007bff; margin-top: 15px; }
        .is-folder span { color: #555; font-weight: normal; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">PHP-Office</a></div>
            
            <div style="display: flex; align-items: center;">
                <div class="cart-info">
                    🛒 Giỏ: <strong><?php echo $total_items; ?></strong>
                    <?php if($total_items > 0): ?> - <a href="cart_view.php" style="color: #fff;">Xem</a> <?php endif; ?>
                </div>
                <div class="user-auth">
                    <?php if(isset($_SESSION['username'])): ?>
                        Xin chào,<span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="logout.php" style="font-size: 12px; color: #ffaaaa;">(Thoát)</a>
                    <?php else: ?>
                        <a href="login.php">Đăng nhập</a> | <a href="register.php">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <nav class="category-nav">
        <a href="index.php">Tất cả</a>
        <?php foreach($categories as $cat): ?>
            <a href="index.php?danhmuc=<?php echo $cat['id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php endforeach; ?>
        <a href="bai_tap.php" style="background-color: #ffc107; color: #343a40; font-weight: bold;">📝 BÀI TẬP NỘP</a>
    </nav>

    <div class="main-content">
        <h2 class="section-title">📂 CẤU TRÚC FILE BÀI TẬP: <?php echo $ROOT_FOLDER_NAME; ?></h2>
        
        <div class="homework-list">
            <p style="font-style: italic; color: #555;">Hiển thị toàn bộ cấu trúc thư mục con và file bên trong thư mục **`<?php echo $ROOT_FOLDER_NAME; ?>`**.</p>
            
            <?php 
            // KHÔNG CẦN VÒNG LẶP FOREACH NỮA, chỉ cần gọi hàm đệ quy trên thư mục gốc
            echo display_directory_contents($ROOT_DIR, $ROOT_URL);
            ?>
            
        </div>
    </div>
    
</body>
</html>