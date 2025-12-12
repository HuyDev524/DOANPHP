<?php
// Tự động load class
function loadClass($c) { include "../classes/$c.class.php"; }
spl_autoload_register("loadClass");

$bookObj = new Book();
$msg = "";

// XỬ LÝ XÓA
if (isset($_GET['del'])) {
    $bookObj->delete($_GET['del']);
    echo "<script>alert('Đã xóa!'); window.location='lab8_4_3.php';</script>";
}

// XỬ LÝ THÊM
if (isset($_POST['sm_insert'])) {
    try {
        $bookObj->add($_POST['book_id'], $_POST['book_name'], $_POST['price'], $_POST['img']);
        $msg = "Thêm thành công!";
    } catch (Exception $e) {
        $msg = "Lỗi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Sách (OOP)</title>
    <meta charset="utf-8">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top:20px; }
        td, th { border: 1px solid #ccc; padding: 6px; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h3>QUẢN LÝ SÁCH (MÔ HÌNH OOP)</h3>
    <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

    <form method="post">
        Mã: <input type="text" name="book_id" required>
        Tên: <input type="text" name="book_name" required>
        Giá: <input type="number" name="price" required>
        Ảnh: <input type="text" name="img" placeholder="b1.jpg">
        <input type="submit" name="sm_insert" value="Thêm">
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Xóa</th>
        </tr>
        <?php
        $list = $bookObj->getAll(); 
        foreach ($list as $r) {
            echo "<tr>
                    <td>{$r['book_id']}</td>
                    <td>{$r['book_name']}</td>
                    <td>{$r['price']}</td>
                    <td style='text-align:center'>
                        <img src='books/{$r['img']}' alt='Book Img' width='80' height='auto' style='object-fit:cover'>
                    </td>
                    <td>
                        <a href='?del={$r['book_id']}' onclick='return confirm(\"Bạn chắc chắn muốn xóa sách này?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>