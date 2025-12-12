<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quản lý Sách (Procedural)</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        .msg { color: green; font-weight: bold; }
        .err { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h3>QUẢN LÝ SÁCH (LAB 8.4.2)</h3>
    <?php
    // 1. KẾT NỐI
    try {
        $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
        $pdh->query("set names 'utf8'");
        $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) { echo "Lỗi: " . $ex->getMessage(); exit; }

    $msg = "";

    // 2. XỬ LÝ XÓA
    if (isset($_GET['del'])) {
        $stm = $pdh->prepare("DELETE FROM book WHERE book_id = :id");
        $stm->execute([':id' => $_GET['del']]);
        $msg = "Đã xoá sách mã: " . $_GET['del'];
    }

    // 3. XỬ LÝ THÊM
    if (isset($_POST['sm_insert'])) {
        try {
            $sql = "INSERT INTO book(book_id, book_name, price, img) VALUES(:id, :name, :price, :img)";
            $stm = $pdh->prepare($sql);
            $stm->execute([
                ':id' => $_POST['book_id'],
                ':name' => $_POST['book_name'],
                ':price' => $_POST['price'],
                ':img' => $_POST['img']
            ]);
            $msg = "Thêm sách thành công!";
        } catch (PDOException $e) {
            $msg = "<span class='err'>Lỗi: " . ($e->getCode() == 23000 ? "Trùng mã sách!" : $e->getMessage()) . "</span>";
        }
    }
    ?>

    <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>

    <form method="post" action="">
        <table>
            <tr><td>Mã sách:</td><td><input type="text" name="book_id" required></td></tr>
            <tr><td>Tên sách:</td><td><input type="text" name="book_name" required></td></tr>
            <tr><td>Giá:</td><td><input type="number" name="price" required></td></tr>
            <tr><td>Hình ảnh:</td><td><input type="text" name="img" placeholder="VD: b1.jpg"></td></tr>
            <tr><td colspan="2"><input type="submit" name="sm_insert" value="Thêm mới"></td></tr>
        </table>
    </form>

    <table>
        <tr style="background:#eee;">
            <th>Mã</th><th>Tên Sách</th><th>Giá</th><th>Hình</th><th>Hành động</th>
        </tr>
        <?php
        $rows = $pdh->query("SELECT * FROM book")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) {
            echo "<tr>";
            echo "<td>{$r['book_id']}</td>";
            echo "<td>{$r['book_name']}</td>";
            echo "<td>" . number_format($r['price']) . " đ</td>";
            echo "<td>{$r['img']}</td>";
            echo "<td><a href='?del={$r['book_id']}' onclick=\"return confirm('Xóa nhé?');\" style='color:red;'>Xóa</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>