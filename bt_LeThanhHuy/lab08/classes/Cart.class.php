<?php
class Cart extends Db {
    // Hiển thị giỏ hàng (Giả sử lấy từ bảng orders/order_detail)
    public function getCart($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? AND status = 'pending'";
        return $this->select($sql, [$userId]);
    }

    // Thêm vào giỏ (DB)
    public function addToCart($userId, $bookId, $quantity) {
        // Logic kiểm tra xem đã có chưa, nếu có update số lượng, chưa có thì insert
        $sql = "INSERT INTO orders (user_id, book_id, quantity) VALUES (?, ?, ?)";
        return $this->query($sql, [$userId, $bookId, $quantity]);
    }
}
?>