<?php
session_start();
require 'db.php'; // Đảm bảo file này tồn tại và kết nối PDO thành công

// --- XỬ LÝ LOGIC ---
$tu_khoa = "";
$tieu_de = "SẢN PHẨM NỔI BẬT";

// 1. Tìm kiếm
if (isset($_GET['timkiem']) && !empty($_GET['timkiem'])) {
    $tu_khoa = $_GET['timkiem'];
    // Sử dụng Prepared Statement để chống SQL Injection
    $sql = "SELECT * FROM products WHERE name LIKE :keyword";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['keyword' => "%$tu_khoa%"]);
    $tieu_de = "Kết quả tìm kiếm: '" . htmlspecialchars($tu_khoa) . "'";

// 2. Lọc theo Danh mục (Dùng ID)
} elseif (isset($_GET['danhmuc'])) {
    $cat_id = $_GET['danhmuc']; 
    
    // Lấy tên danh mục
    $sql_name = "SELECT name FROM categories WHERE id = :id";
    $stmt_name = $conn->prepare($sql_name);
    $stmt_name->execute(['id' => $cat_id]);
    $cat_name = $stmt_name->fetchColumn(); 
    
    if ($cat_name) {
        $tieu_de = "Danh mục: " . htmlspecialchars($cat_name);
        $sql = "SELECT * FROM products WHERE category_id = :cid";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['cid' => $cat_id]);
    } else {
        $tieu_de = "Danh mục không tồn tại";
        $products = []; 
    }

// 3. Mặc định (Hiển thị tất cả sản phẩm)
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

if (!isset($products)) {
    // Chỉ fetchAll nếu $products chưa được gán giá trị ở khối lọc danh mục không tồn tại
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh sách MENU Danh mục
$stmt_cats = $conn->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

// Đếm tổng số lượng sản phẩm trong giỏ hàng (chỉ tính số lượng, không tính loại sản phẩm)
$total_items = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Văn Phòng Phẩm Thiên Long</title>
    <link rel="stylesheet" href="css/style.css"> 
    </head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">PHP-Office</a></div>
            
            <form action="index.php" method="GET" class="search-box">
                <input type="text" name="timkiem" placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($tu_khoa); ?>">
                <button type="submit">TÌM</button>
            </form>

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
        <h2 class="section-title"><?php echo htmlspecialchars($tieu_de); ?></h2>
        
        <?php if (count($products) == 0): ?>
            <div style="text-align: center; margin: 50px;">
                <p>Chưa có sản phẩm nào trong mục này!</p>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <img src="images/<?php echo !empty($p['image']) ? htmlspecialchars($p['image']) : 'no-image.jpg'; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    
                    <div class="product-name"><?php echo htmlspecialchars($p['name']); ?></div>
                    <div class="product-price"><?php echo number_format($p['price'], 0, ',', '.'); ?> đ</div>
                    
                    <form action="cart_process.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                        <button type="submit" class="btn-add">Thêm Vào Giỏ</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_GET['dat_hang']) && $_GET['dat_hang'] == 'thanh_cong'): ?>
    <div class="overlay">
        <div class="success-popup">
            <span class="checkmark">✔</span>
            <h3>ĐẶT HÀNG THÀNH CÔNG!</h3>
            <a href="index.php" class="btn-close">Tiếp tục mua sắm</a>
        </div>
    </div>
    <style>
        /* CSS đơn giản cho Popup */
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
        .success-popup { background: white; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); }
        .checkmark { font-size: 50px; color: green; display: block; margin-bottom: 10px; }
        .btn-close { display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
    <?php endif; ?>
</body>
</html>