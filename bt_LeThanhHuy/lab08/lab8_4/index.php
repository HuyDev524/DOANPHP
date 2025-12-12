<?php
// Nạp các file cấu hình và hàm cần thiết cho ứng dụng
include "config/config.php";              // File cấu hình (ví dụ: thông tin kết nối CSDL)
include ROOT . "/include/function.php";   // File chứa các hàm tiện ích dùng chung

// Đăng ký hàm tự động nạp class (autoload)
// Khi khởi tạo một đối tượng, PHP sẽ tự động tìm và load file class tương ứng
spl_autoload_register("loadClass");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab 8.4 - Hiển thị sách</title>
    
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
    <h3>Danh sách Thể loại (Lấy từ bảng Category)</h3>
    <?php
    $obj = new Db(); 
    $cats = $obj->select("select * from category");
    
    foreach ($cats as $cat) {
        echo "<span> - " . $cat["cat_name"] . "</span><br>";
    }
    ?>

    <div class="clear"></div>
    <br><br>

    <h3>Sách ngẫu nhiên (Lấy từ bảng Book)</h3>
    
    <?php
    $bookObj = new Book(); 
    // Lấy 5 cuốn ngẫu nhiên
    $listBooks = $bookObj->getRand(5);

    foreach ($listBooks as $b) {
    ?>
        <div class='book'>
            <span class="book-name">
                <?php echo $b["book_name"]; ?>
            </span>
            
            <hr style="width: 80%; border-top:1px solid #eee; margin: 0 auto;">
            
            <img src="image/book/<?php echo $b["img"]; ?>" alt="<?php echo $b["book_name"]; ?>" />
            
            <br>
            <small>ID: <?php echo $b["book_id"]; ?></small>
        </div>
    <?php
    }
    ?>
    
    <div class="clear"></div>
</body>

</html>
