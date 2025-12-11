    <?php
    $host = 'localhost';
    $dbname = 'banhang';
    $username = 'root';
    $password = ''; // WampServer mặc định không có mật khẩu

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8");  
    } catch(PDOException $e) {
        die("Lỗi kết nối: " . $e->getMessage());
    }
    ?>