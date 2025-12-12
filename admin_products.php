<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

//XỬ LÝ TÌM KIẾM
$keyword = '';
$sql = "SELECT p.*, c.name as cat_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = $_GET['search'];
    $sql .= " WHERE p.name LIKE :kw";
}

$sql .= " ORDER BY p.id DESC"; 

$stmt = $conn->prepare($sql);
if ($keyword) {
    $stmt->execute(['kw' => "%$keyword%"]);
} else {
    $stmt->execute();
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo">ADMIN PANEL</div>
            <div class="user-auth">
                Xin chào, <?php echo $_SESSION['username']; ?> | <a href="logout.php" style="color:#ffaaaa">Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content" style="width: 100%;">
            <h2 class="section-title">DANH SÁCH SẢN PHẨM</h2>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <a href="admin_product_add.php" class="btn-create">+ Thêm sản phẩm mới</a>
                
                <form method="GET" class="search-box" style="margin: 0;">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Tìm tên sản phẩm...">
                    <button type="submit">Tìm</button>
                </form>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td>
                            <img src="images/<?php echo !empty($p['image']) ? $p['image'] : 'no-image.jpg'; ?>">
                        </td>
                        <td style="text-align: left; font-weight: bold;"><?php echo $p['name']; ?></td>
                        <td><?php echo $p['cat_name']; ?></td>
                        <td style="color: red; font-weight: bold;"><?php echo number_format($p['price']); ?> đ</td>
                        <td>
                            <a href="admin_product_edit.php?id=<?php echo $p['id']; ?>" class="btn-admin btn-edit">Sửa</a>
                            <a href="admin_product_delete.php?id=<?php echo $p['id']; ?>" class="btn-admin btn-del" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>