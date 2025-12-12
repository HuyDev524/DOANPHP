<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $tong=0;
    for($i= 2;$i<=100;$i+=2)
    {
        echo "i = ".$i.", tong = ".$tong."<br>";
            $tong+=$i;
    }
    echo "Tong cac so tu 2 den 100 la so chan la: ".$tong;
    ?>
</body>
</html>