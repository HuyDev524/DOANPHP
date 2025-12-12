<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab8_1 - PDO - MySQL</title>
</head>

<body>
    <?php
    // ------------------- KẾT NỐI CSDL -------------------
    try {
        // Tạo đối tượng PDO kết nối đến database 'bookstore' với user 'root'
        $pdh = new PDO("mysql:host=localhost; dbname=bookstore", "root", "");
        // Thiết lập bộ mã UTF-8 để hiển thị tiếng Việt đúng
        $pdh->query("set names 'utf8'");
    } catch (Exception $e) {
        // Nếu kết nối thất bại thì báo lỗi và dừng chương trình
        echo $e->getMessage();
        exit;
    }

    // ------------------- TRUY VẤN CATEGORY -------------------
    $stm = $pdh->query("select * from category"); // thực hiện truy vấn
    echo "Số dòng:" . $stm->rowCount(); // số dòng kết quả
    $rows1 = $stm->fetchAll(PDO::FETCH_ASSOC); // lấy tất cả kết quả dưới dạng mảng kết hợp

    // Duyệt qua từng dòng và in ra mã loại + tên loại
    foreach ($rows1 as $row) {
        echo "<br>" . $row["cat_id"] . "-" . $row["cat_name"];
    }
    ?>
    <hr />
    <?php
    // ------------------- TRUY VẤN PUBLISHER -------------------
    $stm = $pdh->query("select * from publisher");
    echo "Số dòng:" . $stm->rowCount();
    $rows2 = $stm->fetchAll(PDO::FETCH_OBJ); // lấy kết quả dưới dạng đối tượng
    // print_r($rows2); // có thể dùng để kiểm tra dữ liệu

    // Duyệt qua từng dòng và in ra mã NXB + tên NXB
    foreach ($rows2 as $row) {
        echo "<br>" . $row->pub_id . "-" . $row->pub_name;
    }
    ?>

    <hr />
    <?php
    // ------------------- TRUY VẤN BOOK -------------------
    $sql = "select * from book where book_name like '%a%' "; // tìm sách có chữ 'a' trong tên
    $stm = $pdh->query($sql);
    echo "Số dòng:" . $stm->rowCount();
    $rows3 = $stm->fetchAll(PDO::FETCH_NUM); // lấy kết quả dưới dạng mảng số

    // Duyệt qua từng dòng và in ra cột 0 (id) và cột 1 (tên sách)
    foreach ($rows3 as $row) {
        echo "<br>" . $row[0] . "-" . $row[1];
    }

    // ------------------- FETCH TỪNG DÒNG -------------------
    echo "<hr>";
    $stm = $pdh->query("select * from category");
    echo "Số dòng:" . $stm->rowCount();
    $row = $stm->fetch(PDO::FETCH_ASSOC); // lấy 1 dòng đầu tiên
    print_r($row);
    $row = $stm->fetch(PDO::FETCH_ASSOC); // lấy dòng tiếp theo
    print_r($row);

    // ------------------- VÒNG LẶP WHILE -------------------
    echo "<hr>";
    $stm = $pdh->query("select * from publisher");
    // Duyệt qua từng dòng bằng vòng lặp while
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
        echo "<br>" . $row["pub_id"] . "-" . $row["pub_name"];
    }
    ?>
</body>

</html>
