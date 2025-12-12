<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $tong =0;
        $up =1;
        do {
            echo $tong." dang be hon 1000, tiep tuc tang <br>";
            $tong +=$up;
            $up +=1;
        } while ($tong<=1000);
    echo "Tong hien tai dang la: ".$tong.", da >1000.";
    ?>
</body>
</html>