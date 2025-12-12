<?php
// Nạp file cấu hình (chứa thông tin kết nối CSDL, hằng số, v.v.)
include "config/config.php";

// Nạp file chứa các hàm tiện ích dùng chung
include ROOT."/include/function.php";

// Đăng ký hàm autoload: khi khởi tạo một class, PHP sẽ tự động tìm và load file class tương ứng
spl_autoload_register("loadClass");

// Khởi tạo đối tượng kết nối CSDL (class Db sẽ được tự động load từ file Db.class.php)
$db = new Db();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database!</title>
<!-- Nạp file CSS để định dạng giao diện -->
<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>

<body>
<!-- Bố cục trang web sử dụng bảng -->
<table width="90%" border="1" align="center">
  <tr>
    <!-- Hàng đầu tiên: phần header -->
    <td colspan="3">
        <?php
        // Nạp file header (chứa logo, menu, tiêu đề trang...)
        include "include/header.php";
        ?>
    </td>
  </tr>
  <tr>
    <!-- Cột bên trái: hiển thị danh mục và nhà xuất bản -->
    <td width="29%" valign="top">
        <div class='boxleft'>
            <?php
            // Hiển thị danh mục sách
            include "include/category.php";
            ?>
        </div>
        <div class='boxleft'>
            <?php
            // Hiển thị danh sách nhà xuất bản
            include "include/publisher.php";
            ?>
        </div>
    </td>

    <!-- Cột giữa: hiển thị nội dung chính (sách hoặc tin tức) -->
    <td width="42%" valign="top">
        <?php
            // Lấy giá trị tham số 'mod' từ URL, mặc định là 'book'
            $mod = getIndex("mod","book");
            
            // Nếu mod = book thì hiển thị module sách
            if ($mod=="book")
                include "module/book/index.php";

            // Nếu mod = news thì hiển thị module tin tức
            if ($mod=="news")
                include "module/news/index.php";

            // Có thể mở rộng thêm các module khác...
        ?>
    </td>

    <!-- Cột bên phải: hiển thị danh sách tin tức nổi bật -->
    <td width="29%" valign="top">
        <?php
        // Lấy 10 tin tức nổi bật (hot=1) từ bảng news
        $news = $db->select("select * from news where hot=1 limit 0, 10");

        // Duyệt qua từng tin và hiển thị tiêu đề dưới dạng link
        foreach($news as $r)
        {
        ?>
            <div class="news">
                <a href="index.php?mod=news&ac=detail&id=<?php echo $r["id"];?>">
                    <?php echo $r["title"];?>
                </a>
            </div>
        <?php   
        }
        ?>
    </td>
  </tr>

  <!-- Hàng cuối cùng: phần footer (ở đây để trống) -->
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</body>
</html>
