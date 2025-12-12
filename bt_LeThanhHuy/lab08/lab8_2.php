<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab8_2 - PDO - MySQL - select - insert - parameter</title>
</head>

<body>
    <h3>Tìm kiếm sách</h3>
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

    // ------------------- TRUY VẤN SELECT -------------------
    $search = "a"; // từ khóa tìm kiếm
    $sql = "select * from publisher where pub_name like :ten"; // câu lệnh SQL có tham số
    $stm = $pdh->prepare($sql); // chuẩn bị câu lệnh
    $stm->bindValue(":ten", "%$search%"); // gán giá trị cho tham số :ten
    $stm->execute(); // thực thi câu lệnh
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC); // lấy tất cả kết quả dưới dạng mảng kết hợp

    // In kết quả ra màn hình
    echo "<pre>";
    print_r($rows); // hiển thị mảng kết quả
    echo "</pre>";
    echo "<hr>";

    // ------------------- TRUY VẤN INSERT -------------------
    $ma = "LS2";       // mã loại sách
    $ten = "Lịch sử"; // tên loại sách
    $sql = "insert into category(cat_id, cat_name) values(:maloai, :tenloai)"; // câu lệnh SQL có tham số
    $arr = array(":maloai" => $ma, ":tenloai" => $ten); // mảng ánh xạ tham số với giá trị

    $stm = $pdh->prepare($sql); // chuẩn bị câu lệnh
    $stm->execute($arr);        // thực thi với mảng tham số
    $n = $stm->rowCount();      // số dòng bị ảnh hưởng (số bản ghi thêm được)

    // In thông báo kết quả
    echo "Đã thêm $n loại sách";
    ?>
</body>

</html>
