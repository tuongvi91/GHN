<?php
//models/NhanVienModel.php
require_once './connect.php';

class NhanVienModel {
    private $conn;
    
    public function __construct() {
        // Create a database connection using the Database class
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to get all nhanvien (employees) with their job role (ten_cv)
    public function getAllNhanVien() {
        $query = "
            SELECT nhanvien.*, chucvu.tenCV
            FROM nhanvien
            JOIN chucvu ON nhanvien.maCV = chucvu.maCV
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Method to get a specific nhanvien (employee) by their ID
    public function getNhanVienById($maNV) {
        $query = "SELECT * FROM nhanvien WHERE maNV = :maNV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maNV', $maNV);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Method to add a new nhanvien (employee)
    public function addNhanVien($tenNV, $ngaySinh, $sdt, $email, $queQuan, $CCCD, $maCV, $khuVucPhuTrach, $matKhau, $ngayCapNhatThongTin, $trangThaiTK ) {
        $query = "INSERT INTO nhanvien (tenNV, ngaySinh, sdt, email, queQuan, CCCD, maCV, khuVucPhuTrach, matKhau, ngayCapNhatThongTin, trangThaiTK) 
                  VALUES (:tenNV, :ngaySinh, :sdt, :email, :queQuan, :CCCD, :maCV, :khuVucPhuTrach, :matKhau, :ngayCapNhatThongTin, :trangThaiTK)";
        $stmt = $this->conn->prepare($query);
        
        // Bind the parameters to prevent SQL injection
        $stmt->bindParam(':tenNV', $tenNV);
        $stmt->bindParam(':ngaySinh', $ngaySinh);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':queQuan', $queQuan);
        $stmt->bindParam(':CCCD', $CCCD);
        $stmt->bindParam(':maCV', $maCV);
        $stmt->bindParam(':khuVucPhuTrach', $khuVucPhuTrach);
        $stmt->bindParam(':matKhau', $matKhau);
        $stmt->bindParam(':ngayCapNhatThongTin', $ngayCapNhatThongTin);
        $stmt->bindParam(':trangThaiTK', $trangThaiTK);
        
        return $stmt->execute(); // returns true if insertion was successful
    }

    // Method to update an existing nhanvien (employee)
    public function updateNhanVien($maNV, $tenNV, $ngaySinh, $sdt, $email, $queQuan, $CCCD, $maCV, $khuVucPhuTrach, $matKhau, $ngayCapNhatThongTin, $trangThaiTK) {
        // Initialize the base query
        $query = "UPDATE nhanvien SET 
                    maNV = :maNV,
                    tenNV = :tenNV, 
                    ngaySinh = :ngaySinh, 
                    sdt = :sdt, 
                    email = :email, 
                    queQuan = :queQuan, 
                    CCCD = :CCCD, 
                    maCV = :maCV, 
                    khuVucPhuTrach = :khuVucPhuTrach, 
                    ngayCapNhatThongTin = :ngayCapNhatThongTin,
                    trangThaiTK = :trangThaiTK";
     if (!empty($matKhau)) {
        $query .= ", matKhau = :matKhau"; // Add matKhau to the query if it's not empty
    }

    // Continue with the WHERE clause
    $query .= " WHERE maNV = :maNV";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':maNV', $maNV);
        $stmt->bindParam(':tenNV', $tenNV);
        $stmt->bindParam(':ngaySinh', $ngaySinh);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':queQuan', $queQuan);
        $stmt->bindParam(':CCCD', $CCCD);
        $stmt->bindParam(':maCV', $maCV);
        $stmt->bindParam(':khuVucPhuTrach', $khuVucPhuTrach);
        $stmt->bindParam(':ngayCapNhatThongTin', $ngayCapNhatThongTin);
        $stmt->bindParam(':trangThaiTK', $trangThaiTK);
       
        
        // Bind matKhau if it's not empty
        if (!empty($matKhau)) {
            $stmt->bindParam(':matKhau', $matKhau);
        }
        

        // Execute the query and return whether the update was successful
        return $stmt->execute();
    }

    // Method to delete a nhanvien (employee) by ID
    public function deleteNhanVien($maNV) {
        $query = "DELETE FROM nhanvien WHERE maNV = :maNV";
        $stmt = $this->conn->prepare($query);

        // Bind the parameter
        $stmt->bindParam(':maNV', $maNV);
        
        return $stmt->execute(); // returns true if deletion was successful
    }

    // Method for employee login
    public function loginNhanVien($email, $matKhau) {
        // Query to find the employee by email
        $query = "SELECT * FROM nhanvien WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        // Bind the email parameter
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the result
        $employee = $stmt->fetch();

        // Check if employee exists and verify the plain-text password
        if ($employee && $matKhau === $employee['matKhau']) {
            // Password is correct, return employee data (or any relevant info)
            return $employee;
        } else {
            // Invalid email or password
            return false;
        }
    }
}
?>
