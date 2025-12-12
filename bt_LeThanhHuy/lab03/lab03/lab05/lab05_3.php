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
function tinhTongChuSo($str) {
    $tong = 0;
    $ky_tu = str_split($str);
    foreach ($ky_tu as $char) {
        if (is_numeric($char)) {
            $tong += (int)$char;
        }
    }
    
    return $tong;
}
if (isset($_POST['submit_btn']) && isset($_POST['text_value'])) {
    $chuoi_nhap = $_POST['text_value'];
    echo "<h3>Kết quả tính toán:</h3>";
    if (empty($chuoi_nhap)) {
        echo "<p style='color: orange;'>Vui lòng nhập chuỗi ký tự!</p>";
    } else {
        $ket_qua = tinhTongChuSo($chuoi_nhap);
        
        echo "<p>Chuỗi nhập vào: <strong>" . htmlspecialchars($chuoi_nhap) . "</strong></p>";
        echo "<p>Tổng các chữ số có trong chuỗi là: <strong>{$ket_qua}</strong></p>";
    }
}
?>
</body>
</html>