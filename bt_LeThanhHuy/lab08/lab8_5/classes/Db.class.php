<?php
// Lớp Db dùng để thao tác với CSDL MySQL thông qua PDO
class Db
{
    // Thuộc tính lưu số dòng bị ảnh hưởng sau khi thực thi SQL
    private $_numRow;
    // Thuộc tính lưu đối tượng kết nối PDO
    private $dbh = null;

    // Hàm khởi tạo: tự động chạy khi tạo đối tượng Db
    public function __construct()
    {
        // Chuỗi kết nối (DSN) gồm host và tên database
        $driver = "mysql:host=" . HOST . "; dbname=" . DB_NAME;
        try {
            // Tạo đối tượng PDO kết nối CSDL với user và password
            $this->dbh = new PDO($driver, DB_USER, DB_PASS);
            // Thiết lập bộ mã UTF-8 để hiển thị tiếng Việt đúng
            $this->dbh->query("set names 'utf8' ");
        } catch (PDOException $e) {
            // Nếu kết nối thất bại thì báo lỗi và dừng chương trình
            echo "Err:" . $e->getMessage();
            exit();
        }
    }

    // Hàm hủy: tự động chạy khi đối tượng bị hủy
    public function __destruct()
    {
        // Giải phóng kết nối PDO
        $this->dbh = null;
    }

    // Hàm trả về số dòng bị ảnh hưởng sau khi thực thi SQL
    public function getRowCount()
    {
        return $this->_numRow;
    }

    // Hàm thực thi câu lệnh SQL chung (private, chỉ dùng nội bộ)
    private function query($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
    {
        // Chuẩn bị câu lệnh SQL
        $stm = $this->dbh->prepare($sql);
        // Thực thi với mảng tham số
        if (!$stm->execute($arr)) {
            echo "Sql lỗi.";
            exit;
        }
        // Lưu số dòng bị ảnh hưởng
        $this->_numRow = $stm->rowCount();
        // Trả về kết quả truy vấn theo mode (ASSOC, OBJ, NUM...)
        return $stm->fetchAll($mode);
    }

    // ------------------- CÁC HÀM PUBLIC -------------------

    // Hàm SELECT dữ liệu
    public function select($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
    {
        return $this->query($sql, $arr, $mode);
    }

    // Hàm INSERT dữ liệu
    public function insert($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
    {
        $this->query($sql, $arr, $mode);
        return $this->getRowCount(); // trả về số dòng thêm được
    }

    // Hàm UPDATE dữ liệu
    public function update($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
    {
        $this->query($sql, $arr, $mode);
        return $this->getRowCount(); // trả về số dòng cập nhật được
    }

    // Hàm DELETE dữ liệu
    public function delete($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
    {
        $this->query($sql, $arr, $mode);
        return $this->getRowCount(); // trả về số dòng xóa được
    }
}
?>
