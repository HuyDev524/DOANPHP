<?php
session_start();
require 'db.php'; 

// Lấy dữ liệu cho thanh điều hướng
$stmt_cats = $conn->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);
$total_items = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// --- HÀM ĐỆ QUY ĐỂ QUÉT TOÀN BỘ THƯ MỤC CON VÀ FILE (Đã tích hợp thẻ <details>) ---
/**
 * Quét thư mục đệ quy và tạo HTML cho các file/thư mục, sử dụng <details> cho thư mục.
 * @param string $dir Đường dẫn vật lý đến thư mục cần quét.
 * @param string $base_url Đường dẫn web tương đối cho các liên kết.
 * @param bool $is_root Xác định đây có phải là thư mục gốc cần hiển thị hay không.
 */
function display_directory_contents($dir, $base_url, $is_root = false) {
    if (!is_dir($dir) || !($handle = opendir($dir))) {
        return "<p style='color: red;'>Không thể đọc thư mục: " . htmlspecialchars($dir) . "</p>";
    }

    $files = [];
    $folders = [];

    // Đọc tất cả các mục
    while (false !== ($item = readdir($handle))) {
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

    // Sắp xếp thư mục và file
    usort($folders, function($a, $b) { return strcmp($a['name'], $b['name']); });
    usort($files, function($a, $b) { return strcmp($a['name'], $b['name']); });

    $html = '<ul>';

    // 1. Hiển thị tất cả các file trong cấp độ hiện tại
    foreach ($files as $file) {
        $html .= '<li>';
        $html .= '<a href="' . htmlspecialchars($file['web_path']) . '" target="_blank">';
        $html .= '<span class="file-icon">📄</span> ' . htmlspecialchars($file['name']);
        $html .= '</a>';
        $html .= '</li>';
    }

    // 2. Hiển thị tất cả các thư mục con (Sử dụng <details> để SỔ/GẤP)
    foreach ($folders as $folder) {
        $display_name = htmlspecialchars($folder['name']);
        $is_css = strtolower($folder['name']) === 'css';
        
        // Bắt đầu thẻ <details>
        $html .= '<li class="is-folder">';
        $html .= '<details>';
        
        // Thẻ <summary> là tiêu đề, khi click sẽ sổ/gấp nội dung
        $html .= '<summary class="folder-title ' . ($is_css ? 'css-folder' : '') . '">';
        $html .= '<span class="folder-icon">📁</span> **' . $display_name . '**';
        $html .= '</summary>';
        
        // Gọi đệ quy để quét nội dung bên trong thư mục con này
        $html .= display_directory_contents($folder['path'], $folder['web_path']);
        
        // Kết thúc thẻ <details>
        $html .= '</details>';
        $html .= '</li>';
    }

    $html .= '</ul>';
    
    // Nếu đây là thư mục gốc, chỉ trả về nội dung UL, không bọc trong <details>
    if ($is_root) {
        return $html;
    }
    
    // Ngược lại, trả về nội dung bên trong <details> (đã được gọi đệ quy)
    return $html;
}

// Thiết lập thư mục gốc cần quét
$ROOT_FOLDER_NAME = "bt_LeThanhHuy";
$ROOT_DIR = __DIR__ . '/' . $ROOT_FOLDER_NAME;
$ROOT_URL = $ROOT_FOLDER_NAME;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Bài Tập - <?php echo htmlspecialchars($_SESSION['username'] ?? 'Sinh Viên'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* CSS Điều chỉnh cho <details>/<summary> */
        .homework-list { max-width: 900px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.05); }
        
        /* Ẩn dấu mũi tên mặc định của <details> trên các trình duyệt hiện đại */
        .homework-list summary { list-style: none; }
        .homework-list summary::-webkit-details-marker { display: none; }
        
        .homework-list ul { list-style: none; padding-left: 0; }
        
        .homework-list .is-folder { margin: 10px 0; border: 1px solid #e9ecef; border-radius: 4px; padding: 0; }
        
        .folder-title {
            display: block;
            cursor: pointer;
            padding: 8px 10px;
            background-color: #f8f9fa;
            color: #007bff;
            font-weight: bold;
            border-radius: 4px;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s;
        }
        .folder-title:hover {
            background-color: #e2e6ea;
        }
        
        /* Thư mục CSS nổi bật */
        .css-folder {
            color: #dc3545;
            background-color: #ffeaea;
        }

        /* Ẩn/Hiện nội dung của thư mục con */
        .is-folder details[open] > summary {
            border-bottom: 1px solid #ced4da;
        }
        
        /* Cấp độ file bên trong thư mục */
        .is-folder ul { 
            padding-left: 20px; 
            margin-top: 0; 
            margin-bottom: 0;
            border-left: 2px dashed #ced4da; /* Đường kẻ phân cấp */
        }
        .is-folder ul li a {
             padding: 5px 10px;
        }
        
        .file-icon { color: #28a745; margin-right: 5px; }
        .folder-icon { color: #ffc107; margin-right: 5px; }
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
            <p style="font-style: italic; color: #555;">Nhấn vào tên thư mục để sổ (mở) danh sách file bên trong.</p>
            
            <?php 
            // Gọi hàm đệ quy để bắt đầu quét từ thư mục gốc. 
            echo display_directory_contents($ROOT_DIR, $ROOT_URL, true); 
            ?>
            
        </div>
    </div>
    
</body>
</html>