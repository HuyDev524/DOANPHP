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
            <p>Nhap d:</p>
            <input type="text" name="d_value">
        </div>
        <div>
            <p>Nhap r:</p>
            <input type="text" name="r_value">
            <button type="submit" name="submit_btn">Send</button>
        </div>
    </form>
    <?php
function xuatHinhChuNhatRong($d, $r) {
    if ($d < 2 || $r < 2) {
        echo " Lỗi: Chiều dài và chiều rộng phải lớn hơn hoặc bằng 2.<br>";
        return;
    }
    echo "Kết quả hình chữ nhật rỗng (Dài: $d, Rộng: $r):<br><br>";
    for ($i = 1; $i <= $r; $i++) {
        for ($j = 1; $j <= $d; $j++) {
            if ($i == 1 || $i == $r || $j == 1 || $j == $d) {
                echo "* "; 
            } else {
                echo "&nbsp;&nbsp;"; 
            }
        }
        echo "<br>"; 
    }
}
if (isset($_POST['submit_btn']) && isset($_POST['d_value']) && isset($_POST['r_value'])) {
    $d = (int)$_POST['d_value'];
    $r = (int)$_POST['r_value'];
    xuatHinhChuNhatRong($d, $r);
}
?>
</body>
</html>