<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 4_7b</title>
</head>

<body>
<?php
    echo "Comment dòng 10 sẽ bị lỗi vì không load dữ liệu của file 4_4a dẫn đến x không có giá trị <br/>";
	require("lab4_4a.php");
    require("lab4_4b.php");
    require_once("lab4_4b.php");
    echo "Nếu trùng tên file include thì sẽ chỉ include 1 lần mà thôi <br/>";
	if(isset($x))
     {
		echo "Giá trị của x là: $x <br/>";
        echo "Sau khi include thêm file 4_4b.php vào thì giá trị sẽ + thêm 10, bởi vì giá trị nó là x = x + 10";
     }
        else
		echo "Biến x không tồn tại";
?>
</body>
</html>