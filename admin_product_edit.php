<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { header("Location: index.php"); exit(); }

if (!isset($_GET['id'])) { header("Location: admin_products.php"); exit(); }
$id = $_GET['id'];

// Lấy thông tin sản phẩm hiện tại
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) { die("Sản phẩm không tồn tại!"); }

$cats = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat_id = $_POST['category_id'];
    $image = $product['image'];

    // Nếu có chọn ảnh mới thì upload và thay thế
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $file_name; 
        }
    }

    $sql = "UPDATE products SET name=?, price=?, category_id=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $price, $cat_id, $image, $id]);
    
    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header"><div class="container"><div class="logo">ADMIN</div></div></div>
    
    <div class="container">
        <div class="main-content">
            <h2 class="section-title">SỬA SẢN PHẨM: <?php echo $product['name']; ?></h2>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                
                <label>Tên sản phẩm:</label>
                <input type="text" name="name" value="<?php echo $product['name']; ?>" required>

                <label>Giá tiền:</label>
                <input type="number" name="price" value="<?php echo $product['price']; ?>" required>

                <label>Danh mục:</label>
                <select name="category_id">
                    <?php foreach($cats as $c): ?>
                        <option value="<?php echo $c['id']; ?>" 
                            <?php if($c['id'] == $product['category_id']) echo 'selected'; ?>>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Hình ảnh hiện tại:</label>
                <img src="images/<?php echo $product['image']; ?>" height="100"><br><br>
                
                <label>Chọn ảnh mới (Nếu muốn thay đổi):</label>
                <input type="file" name="image">

                <button type="submit" class="btn-submit">CẬP NHẬT</button>
                <br><br>
                <a href="admin_products.php">Quay lại</a>
            </form>
        </div>
    </div>
</body>
</html>