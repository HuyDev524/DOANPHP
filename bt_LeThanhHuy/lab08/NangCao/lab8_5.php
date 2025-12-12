<?php
// --- 1. CẤU HÌNH & KHỞI TẠO ---
function loadClass($c) {
    // Logic load class: Ưu tiên tìm ở thư mục ../classes/
    if (file_exists("../classes/$c.class.php")) include "../classes/$c.class.php";
    else if (file_exists("classes/$c.class.php")) include "classes/$c.class.php";
}
spl_autoload_register("loadClass");

$bookObj = new Book();
$msg = "";

// --- 2. XỬ LÝ FORM ---
// Xóa
if (isset($_GET['del'])) {
    $bookObj->delete($_GET['del']);
    echo "<script>alert('Đã xóa sách thành công!'); window.location='?';</script>";
}

// Thêm
if (isset($_POST['sm_insert'])) {
    try {
        $bookObj->add($_POST['book_id'], $_POST['book_name'], $_POST['price'], $_POST['img']);
        $msg = "Thêm sách mới thành công!";
    } catch (Exception $e) {
        $msg = "Lỗi: " . $e->getMessage();
    }
}

// --- 3. XỬ LÝ PHÂN TRANG (Cập nhật 10 cuốn) ---
$limit = 10; // <--- ĐÃ SỬA THÀNH 10 CUỐN / TRANG
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu
$totalBooks = $bookObj->getTotalBooks();
$totalPages = ceil($totalBooks / $limit);
$list = $bookObj->getBooksPaging($offset, $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sách</title>
    <style>
        /* --- CSS GIAO DIỆN ĐẸP --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0; padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px; margin: 0 auto;
            background: #fff; padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2, h3 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        
        /* Thông báo */
        .msg {
            background-color: #d4edda; color: #155724;
            padding: 15px; border-radius: 5px;
            margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb;
        }

        /* Form styling */
        .form-box {
            background: #fafafa; padding: 20px;
            border-radius: 8px; border: 1px solid #eee; margin-bottom: 30px;
        }
        .form-group { margin-bottom: 15px; display: flex; align-items: center; }
        .form-group label { width: 100px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"] {
            flex: 1; padding: 10px;
            border: 1px solid #ccc; border-radius: 4px;
            font-size: 14px;
        }
        .btn-submit {
            background-color: #007bff; color: white;
            padding: 10px 20px; border: none; border-radius: 4px;
            cursor: pointer; font-size: 16px; width: 100%; transition: 0.3s;
        }
        .btn-submit:hover { background-color: #0056b3; }

        /* Table styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; overflow: hidden; border-radius: 6px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #343a40; color: white; text-transform: uppercase; font-size: 14px; }
        tr:hover { background-color: #f1f1f1; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        .img-thumb {
            width: 70px; height: 90px; object-fit: cover;
            border-radius: 4px; border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-delete {
            color: #dc3545; font-weight: bold; text-decoration: none;
            padding: 5px 10px; border: 1px solid #dc3545; border-radius: 4px;
            transition: 0.2s;
        }
        .btn-delete:hover { background-color: #dc3545; color: white; }

        /* Pagination styling */
        .pagination { margin-top: 30px; text-align: center; display: flex; justify-content: center; gap: 5px; }
        .pagination a {
            padding: 8px 16px; border: 1px solid #dee2e6;
            color: #007bff; text-decoration: none; border-radius: 4px; background: #fff;
            transition: 0.2s;
        }
        .pagination a.active { background-color: #007bff; color: white; border-color: #007bff; }
        .pagination a:hover:not(.active) { background-color: #e9ecef; }
        .pagination span { padding: 8px 16px; color: #6c757d; }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 HỆ THỐNG QUẢN LÝ SÁCH</h2>
    
    <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

    <div class="form-box">
        <h3 style="margin-top:0">Thêm Sách Mới</h3>
        <form method="post">
            <div class="form-group">
                <label>Mã sách:</label>
                <input type="text" name="book_id" placeholder="VD: B001" required>
            </div>
            <div class="form-group">
                <label>Tên sách:</label>
                <input type="text" name="book_name" placeholder="Nhập tên sách..." required>
            </div>
            <div class="form-group">
                <label>Giá bán:</label>
                <input type="number" name="price" placeholder="Nhập giá tiền..." required>
            </div>
            <div class="form-group">
                <label>Ảnh bìa:</label>
                <input type="text" name="img" placeholder="VD: b1.jpg">
            </div>
            <input type="submit" name="sm_insert" class="btn-submit" value="+ Thêm vào kho">
        </form>
    </div>

    <h3>Danh sách sách (Trang <?php echo $page; ?>/<?php echo $totalPages > 0 ? $totalPages : 1; ?>)</h3>
    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tên sách</th>
                <th>Giá (VNĐ)</th>
                <th style="text-align: center;">Hình ảnh</th>
                <th style="text-align: center;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $r) { ?>
            <tr>
                <td><strong><?php echo $r['book_id']; ?></strong></td>
                <td><?php echo $r['book_name']; ?></td>
                <td style="color: #d35400; font-weight: bold;"><?php echo number_format($r['price']); ?></td>
                <td style="text-align: center;">
                    <img src="books/<?php echo $r['img']; ?>" class="img-thumb" alt="Book">
                </td>
                <td style="text-align: center;">
                    <a href="?del=<?php echo $r['book_id']; ?>" class="btn-delete" 
                       onclick="return confirm('Bạn chắc chắn muốn xóa sách <?php echo $r['book_name']; ?>?');">
                       🗑 Xóa
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php 
        // Nút Quay lại
        if ($page > 1) echo '<a href="?page='.($page-1).'">&laquo; Trước</a>';
        else echo '<span>&laquo; Trước</span>';

        // Các trang số
        for ($i = 1; $i <= $totalPages; $i++) {
            $cls = ($i == $page) ? 'active' : '';
            echo '<a class="'.$cls.'" href="?page='.$i.'">'.$i.'</a>';
        }

        // Nút Tiếp theo
        if ($page < $totalPages) echo '<a href="?page='.($page+1).'">Sau &raquo;</a>';
        else echo '<span>Sau &raquo;</span>';
        ?>
    </div>
</div>

</body>
</html>