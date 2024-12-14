
<?php
class Database {
    // config/Database.php
    private $host = "localhost"; // Địa chỉ máy chủ
    private $db_name = "ghntest"; // Tên cơ sở dữ liệu
    private $username = "root"; // Tên người dùng
    private $password = ""; // Mật khẩu
    private $conn; // Kết nối PDO
    

    // Phương thức để lấy kết nối PDO
    public function getConnection() {
        $this->conn = null;
        try {
            // Tạo kết nối PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Hiển thị lỗi
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Đặt kiểu fetch mặc định
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" // Đặt mã hóa UTF-8
                )
            );
        } catch (PDOException $exception) {
            // Xử lý lỗi kết nối
            die("Lỗi kết nối cơ sở dữ liệu: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
