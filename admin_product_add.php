<?php
session_start();
require 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { header("Location: index.php"); exit(); }

$cats = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat_id = $_POST['category_id'];
    $image = '../images/book-stack.png';

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $file_name;
        } else {
            $error = "Lỗi upload ảnh!";
        }
    }

    if (!$error) {
        $sql = "INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $price, $cat_id, $image]);
        
        header("Location: admin_products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header"><div class="container"><div class="logo">ADMIN</div></div></div>
    
    <div class="container">
        <div class="main-content">
            <h2 class="section-title">THÊM SẢN PHẨM MỚI</h2>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <?php if($error): ?><p style="color:red"><?php echo $error; ?></p><?php endif; ?>

                <label>Tên sản phẩm:</label>
                <input type="text" name="name" required>

                <label>Giá tiền (VNĐ):</label>
                <input type="number" name="price" required>

                <label>Danh mục:</label>
                <select name="category_id">
                    <?php foreach($cats as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Hình ảnh:</label>
                <input type="file" name="image">

                <button type="submit" class="btn-submit">LƯU SẢN PHẨM</button>
                <br><br>
                <a href="admin_products.php">Quay lại danh sách</a>
            </form>
        </div>
    </div>
</body>
</html>