<?php
class News extends Db {
    // Lấy danh sách tin tức
    public function getList() {
        $sql = "SELECT * FROM news";
        return $this->select($sql); 
    }

    // Xem chi tiết 1 tin theo ID
    public function getDetail($id) {
        $sql = "SELECT * FROM news WHERE news_id = ?";
        // Giả sử class Db có hàm selectQuery hoặc bạn dùng prepare
        // Ở đây mình viết theo style PDO chuẩn dựa trên class Db thường gặp
        $arr = $this->select($sql, [$id]);
        return $arr ? $arr[0] : null;
    }
}
?>