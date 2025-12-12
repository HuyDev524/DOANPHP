<?php
// Đây là file bai_tap.php, sử dụng cấu trúc tương tự index.php để giữ thanh điều hướng
session_start();
require 'db.php'; // Đảm bảo file này tồn tại và kết nối PDO thành công

// Lấy danh sách MENU Danh mục (để hiển thị menu điều hướng)
$stmt_cats = $conn->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

// Đếm giỏ hàng (để hiển thị giỏ hàng)
$total_items = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// DANH SÁCH THƯ MỤC BÀI TẬP CẦN HIỂN THỊ
$bai_taps = [
    'lab02',
    'lab03',
    'lab04',
    'lab05',
    'lab06',
    'lab07',
    'lab08',
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Bài Tập - <?php echo htmlspecialchars($_SESSION['username'] ?? 'Sinh Viên'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .homework-list {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        .lab-folder { 
            margin-bottom: 25px; 
            padding: 15px; 
            border: 1px solid #dee2e6; 
            border-radius: 5px; 
            background-color: #f8f9fa;
        }
        .lab-folder h3 { 
            font-size: 1.25em; 
            color: #007bff; 
            margin-top: 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #007bff;
        }
        .lab-folder ul { 
            list-style-type: none; 
            padding-left: 0; 
        }
        .lab-folder li a { 
            text-decoration: none; 
            color: #343a40; 
            padding: 8px 10px; 
            display: block; 
            border-bottom: 1px dashed #ced4da;
            transition: background-color 0.2s;
            font-size: 0.95em;
        }
        .lab-folder li a:hover { 
            color: #0056b3; 
            background-color: #e9ecef; 
        }
        .file-icon {
            margin-right: 5px;
            color: #28a745;
        }
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
        <h2 class="section-title">📂 BÀI TẬP CỦA SINH VIÊN</h2>
        
        <div class="homework-list">
            <p style="font-style: italic; color: #555;">Dưới đây là danh sách các file bài tập theo từng Lab. Nhấn vào tên file để xem nội dung.</p>
            
            <?php foreach ($bai_taps as $lab_folder): ?>
            
                <div class="lab-folder">
                    <h3>Thư mục **<?php echo strtoupper($lab_folder); ?>** (bt_LeThanhHuy/<?php echo $lab_folder; ?>)</h3>
                    
                    <ul>
                        <?php
                        // Đường dẫn vật lý tới thư mục Lab
                        $path = "bt_LeThanhHuy/" . $lab_folder;
                        
                        // Kiểm tra và đọc thư mục
                        if (is_dir($path) && $handle = opendir($path)) {
                            while (false !== ($file = readdir($handle))) {
                                // Bỏ qua các file hệ thống (. và ..) và các file ẩn
                                if ($file != "." && $file != ".." && $file[0] != '.') {
                                    $full_path = $path . "/" . $file;
                                    $web_path = $full_path; 

                                    // Chỉ hiển thị các file (bỏ qua các thư mục con khác)
                                    if (is_file($full_path)): 
                        ?>
                                        <li>
                                            <a href="<?php echo htmlspecialchars($web_path); ?>" target="_blank">
                                                <span class="file-icon">📄</span> <?php echo htmlspecialchars($file); ?>
                                            </a>
                                        </li>
                        <?php 
                                    endif;
                                }
                            }
                            closedir($handle);
                        } else {
                            echo "<li style='color: red;'>Lỗi: Không tìm thấy hoặc không đọc được thư mục '{$path}'.</li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <div class="lab-folder">
                 <h3>Thư mục **CSS Chung** (bt_LeThanhHuy/css)</h3>
                 <ul>
                    <li>
                        <a href="<?php echo htmlspecialchars("bt_LeThanhHuy/css/style.css"); ?>" target="_blank">
                             <span class="file-icon">📄</span> style.css
                        </a>
                    </li>
                 </ul>
            </div>
            
        </div>
    </div>
    
</body>
</html>