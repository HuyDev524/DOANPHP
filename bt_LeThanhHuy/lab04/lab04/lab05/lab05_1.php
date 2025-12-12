<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
        $A = array();
        echo "Khoi tao mang  "; print_r($A);
    ?>
    <form action="" method="post">
        <div>
            <p>Nhap gia tri: </p>
            <input type="text" name="value_input">
        </div>
        <div>
            <p>Nhap key: </p>
            <input type="text" name="key_input">
        </div>
        <button type="submit" name="send_value_btn" style="margin-top: 10px;">Send Array</button>
        <button type="submit" name="show_value" style="margin-top: 10px;">Show Array</button>
    </form>
    <?php
        if(isset($_POST['send_value_btn']))
        {
            $get_Value = (int)$_POST['value_input'];
            $get_Key = trim($_POST['key_input']);
            if(isset($get_Key))
            {
                $A[$get_Key] = $get_Value;
                echo "<p> Đã thêm phần tử với Key: [$get_Key] và Value: $get_Value</p>";
            } 
            else
            {
                $A[] = $get_Value;
            echo "<p> Đã thêm phần tử (tự động) với Value: $get_Value</p>";
            }
        }
        if(isset($_POST['show_value']))
    {
        echo "<h3>Kết quả: Hiển thị mảng dưới dạng Bảng</h3>";
        
        if (isset($A)) {
            echo "<p>Mảng rỗng. Hãy thêm dữ liệu trước.</p>";
        } else {
            echo "<table>";
            echo "<tr><th>Key (Chỉ mục)</th><th>Value (Giá trị)</th></tr>";
            
            // Dùng foreach để duyệt qua từng cặp key => value
            foreach ($A as $key => $value) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($key) . "</td>";
                echo "<td>" . htmlspecialchars($value) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
      
    }
    ?>
</body>
</html>