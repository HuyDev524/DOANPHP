<?php
class Db {
    protected $_pdo;
    function __construct() {
        try {
            $this->_pdo = new PDO("mysql:host=localhost; dbname=bookstore; charset=utf8", "root", "");
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) { die("Lỗi kết nối: " . $e->getMessage()); }
    }

    // Hàm lấy dữ liệu (SELECT)
    public function select($sql, $params = []) {
        $stm = $this->_pdo->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm thực thi (INSERT, UPDATE, DELETE) - Mới thêm
    public function query($sql, $params = []) {
        $stm = $this->_pdo->prepare($sql);
        return $stm->execute($params); // Trả về true/false
    }
}
?>