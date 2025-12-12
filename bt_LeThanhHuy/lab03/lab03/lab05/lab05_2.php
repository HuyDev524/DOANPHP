<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <div>
            <p>Nhap chuoi ky tu:</p>
            <input type="text" name="text_value">
            <button type="submit" name="submit_btn">Send</button>
        </div>
    </form>
<?php
    // function
    function kiemTraDoiXung($str)
    {
        $clean_str = strtolower($str);
        $reversed_str = strrev($str);
        return ($clean_str===$reversed_str);
    }
    if(isset($_POST['submit_btn']))
    {
        $str=$_POST['text_value'];
        echo "<div class='result'>";
        
        if (empty($str)) {
             echo "<p style='color: orange;'>Vui lòng nhập chuỗi ký tự!</p>";
        } else {
             echo "<h3>Kết quả kiểm tra chuỗi:</h3>";
             echo "<p>Chuỗi đã nhập: **" . htmlspecialchars($str) . "**</p>";

             if (kiemTraDoiXung($str)) {
                 echo "<p class='palindrome'>Chuỗi này là **ĐỐI XỨNG (PALINDROME)**.</p>";
             } else {
                 echo "<p class='not-palindrome'>Chuỗi này **KHÔNG ĐỐI XỨNG**.</p>";
             }
        }
        echo "</div>";
    }
?>
</body>
</html>