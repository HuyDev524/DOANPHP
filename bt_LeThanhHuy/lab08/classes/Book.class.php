<?php
class Book extends Db {
    // Lấy tất cả sách
    public function getAll() {
        return $this->select("SELECT * FROM book");
    }

    // Thêm sách
    public function add($id, $name, $price, $img) {
        $sql = "INSERT INTO book(book_id, book_name, price, img) VALUES(:id, :name, :price, :img)";
        // Gọi hàm query của lớp cha (Db)
        return $this->query($sql, [
            ':id' => $id, ':name' => $name, ':price' => $price, ':img' => $img
        ]);
    }

    // Xóa sách
    public function delete($id) {
        $sql = "DELETE FROM book WHERE book_id = :id";
        return $this->query($sql, [':id' => $id]);
    }
    public function getTotalBooks() {
        $sql = "SELECT count(*) as total FROM book"; // Sửa 'book' thành tên bảng của bạn nếu khác
        $rows = $this->select($sql);
        return $rows[0]['total'];
    }

    // 2. Hàm lấy sách có phân trang
    // $offset: vị trí bắt đầu lấy
    // $limit: số lượng lấy
    public function getBooksPaging($offset, $limit) {
        $sql = "SELECT * FROM book LIMIT $offset, $limit";
        return $this->select($sql);
    }
}
?>