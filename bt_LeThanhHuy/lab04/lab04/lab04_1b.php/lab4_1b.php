<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
            $c = array("a"=>2, "b"=>4, "c"=>6);//mảng có 3 phần tử.Các index của mảng là chuỗi
            print_r($c);
    ?>
    <form action="" method="post">
        <div>
            <p>Nhap vao gia tri key muon sua gia tri:</p>
            <input type="text" name="update_key">
        </div>
        <div>
            <p>Nhap vao gia tri muon sua:</p>
            <input type="text" name="update_input">
        </div>
        <button type="submit" style="margin-top: 10px;" name="submit_button">Send</button>
    </form>
    <?php
    if(isset($_POST['submit_button']))
    {
        $get_value = (int) $_POST['update_input'];
        $get_key = trim($_POST['update_key']);
        if(isset($c[$get_key]))
        {
            $c[$get_key] = $get_value;
            echo "Da cap nhat '$get_key' voi gia tri la: $get_value <br>";
            print_r($c);
        }
        else
        {
            echo "Key [$get_key] , khong ton tai.";
        }
    }
    ?>
</body>
</html>