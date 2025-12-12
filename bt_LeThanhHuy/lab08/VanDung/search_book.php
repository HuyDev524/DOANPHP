<!DOCTYPE html>
<html>
<head><title>Tìm kiếm sách</title><meta charset="utf-8"></head>
<body>
    <form method="get">
        Tên sách: <input type="text" name="keyword" value="<?php echo $_GET['keyword'] ?? ''; ?>">
        <input type="submit" value="Tìm">
    </form>
    <?php
    if (isset($_GET['keyword'])) {
        $pdh = new PDO("mysql:host=localhost; dbname=bookstore; charset=utf8", "root", "");
        $stm = $pdh->prepare("SELECT * FROM book WHERE book_name LIKE :ten");
        $stm->execute([':ten' => "%" . $_GET['keyword'] . "%"]);
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        echo "<ul>";
        foreach ($rows as $r) echo "<li>{$r['book_name']} - " . number_format($r['price']) . "đ</li>";
        echo "</ul>";
    }
    ?>
</body>
</html>