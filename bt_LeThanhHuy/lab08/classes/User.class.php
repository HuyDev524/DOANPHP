<?php
class User extends Db {
    // Đăng ký
    public function register($username, $password, $email) {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        // Lưu ý: Password nên được mã hóa bằng password_hash trước khi truyền vào
        return $this->query($sql, [$username, $password, $email]); 
    }

    // Đăng nhập (Kiểm tra user tồn tại)
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $data = $this->select($sql, [$username]);
        
        if (count($data) > 0) {
            // Kiểm tra pass (giả sử pass đã hash)
            if (password_verify($password, $data[0]['password'])) {
                return $data[0];
            }
        }
        return false;
    }
    
    // Sửa thông tin
    public function updateInfo($id, $email, $fullname) {
        $sql = "UPDATE users SET email = ?, fullname = ? WHERE user_id = ?";
        return $this->query($sql, [$email, $fullname, $id]);
    }
}
?>