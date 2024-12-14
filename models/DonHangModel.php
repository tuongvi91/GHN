<?php
// models/DonHangModel.php
require_once './config/Database.php';

class DonHangModel
{
    private $conn;

    public function __construct()
    {
        // Create a database connection using the Database class
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all orders with recipient and sender names
    public function getAllDonHang()
    {
        $query = "SELECT donhang.*, nguoigui.tenNG, nguoinhan.tenNN, nhanvien.tenNV AS tenNVPhuTrach
                  FROM donhang
                  INNER JOIN nguoigui ON donhang.maNG = nguoigui.maNG
                  INNER JOIN nguoinhan ON donhang.maNN = nguoinhan.maNN
                  LEFT JOIN nhanvien ON donhang.maNVPhuTrach = nhanvien.maNV";  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll();
    }
    
    public function searchDonHang($searchQuery = null)
    {
        $query = "SELECT donhang.*, nguoigui.tenNG, nguoinhan.tenNN, nhanvien.tenNV AS tenNVPhuTrach
                  FROM donhang
                  INNER JOIN nguoigui ON donhang.maNG = nguoigui.maNG
                  INNER JOIN nguoinhan ON donhang.maNN = nguoinhan.maNN
                  LEFT JOIN nhanvien ON donhang.maNVPhuTrach = nhanvien.maNV
                  WHERE 1=1"; // Start with always true condition to build the dynamic query
        
        // If search query is provided, add it to the WHERE clause dynamically
        if ($searchQuery) {
            $query .= " AND (nguoigui.tenNG LIKE :search OR nguoinhan.tenNN LIKE :search OR nhanvien.tenNV LIKE :search OR donhang.maDH LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind the search parameter to prevent SQL injection
        if ($searchQuery) {
            $searchParam = "%" . $searchQuery . "%"; // To match any part of the name
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }
    
        // Execute the query
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function updateEmployeeAndStatus($maDH, $trangThai, $maNVPhuTrach)
    {
        $query = "UPDATE donhang 
              SET maNVPhuTrach = :maNVPhuTrach, trangThai = :trangThai
              WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maDH', $maDH);
        $stmt->bindParam(':maNVPhuTrach', $maNVPhuTrach);
        $stmt->bindParam(':trangThai', $trangThai);

        return $stmt->execute();
    }

    public function getDonHangById($maDH)
    {
        $query = "SELECT * FROM donhang WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maDH', $maDH);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getChiTietDonHang($maDH)
    {
        $query = "SELECT * FROM chitietdonhang WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maDH', $maDH);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Add a new order
    public function addDonHang($maNN, $maNG, $ngayDatHang, $ngayGiaoHangDuKien, $tongTien)
    {
        $query = "INSERT INTO donhang (maNN, maNG, ngayDatHang, ngayGiaoHangDuKien,  tongTien) 
                  VALUES (:maNN, :maNG, :ngayDatHang, :ngayGiaoHangDuKien,  :tongTien)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maNN', $maNN);
        $stmt->bindParam(':maNG', $maNG);
        $stmt->bindParam(':ngayDatHang', $ngayDatHang);
        $stmt->bindParam(':ngayGiaoHangDuKien', $ngayGiaoHangDuKien);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':tongTien', $tongTien);
       

        return $stmt->execute();
    }

    // Update order details
    public function updateDonHang($maDH, $maNN, $maNG, $ngayDatHang, $ngayGiaoHangDuKien, $trangThai, $tongTien, $maNVPhuTrach = null)
    {
        $query = "UPDATE donhang 
                  SET maNN = :maNN, maNG = :maNG, ngayDatHang = :ngayDatHang, ngayGiaoHangDuKien = :ngayGiaoHangDuKien, 
                      trangThai = :trangThai,
                      tongTien = :tongTien, maNVPhuTrach = :maNVPhuTrach
                  WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maDH', $maDH);
        $stmt->bindParam(':maNN', $maNN);
        $stmt->bindParam(':maNG', $maNG);
        $stmt->bindParam(':ngayDatHang', $ngayDatHang);
        $stmt->bindParam(':ngayGiaoHangDuKien', $ngayGiaoHangDuKien);
        $stmt->bindParam(':trangThai', $trangThai);
        $stmt->bindParam(':tongTien', $tongTien);
        $stmt->bindParam(':maNVPhuTrach', $maNVPhuTrach);

        return $stmt->execute();
    }
    // Delete an order by its ID
    public function deleteDonHang($maDH)
    {
        // Start a transaction to ensure that both the order and its details are deleted
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Delete all order details associated with this order
            $query = "DELETE FROM chitietdonhang WHERE maDH = :maDH";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDH', $maDH);
            $stmt->execute();

            // Delete the order itself
            $query = "DELETE FROM donhang WHERE maDH = :maDH";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maDH', $maDH);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // If an error occurs, rollback the transaction
            $this->conn->rollBack();
            return false;
        }
    }

    // Update order status
    public function updateTrangThaiDonHang($maDH, $trangThai)
    {
        $query = "UPDATE donhang SET trangThai = :trangThai WHERE maDH = :maDH";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maDH', $maDH);
        $stmt->bindParam(':trangThai', $trangThai);

        return $stmt->execute();
    }

    // Add a new order detail
    public function addChiTietDonHang($maDH, $tenHang, $loaiHang, $soLuong, $canNang, $tienThuHo)
    {
        $query = "INSERT INTO chitietdonhang (maDH, tenHang, loaiHang, soLuong, canNang, tienThuHo) 
                  VALUES (:maDH, :tenHang, :loaiHang, :soLuong, :canNang, :tienThuHo)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maDH', $maDH);
        $stmt->bindParam(':tenHang', $tenHang);
        $stmt->bindParam(':loaiHang', $loaiHang);
        $stmt->bindParam(':soLuong', $soLuong);
        $stmt->bindParam(':canNang', $canNang);
        $stmt->bindParam(':tienThuHo', $tienThuHo);

        return $stmt->execute();
    }

    // Update an order detail
    public function updateChiTietDonHang($maCTDH, $maDH, $tenHang, $loaiHang, $soLuong, $canNang, $tienThuHo)
    {
        $query = "UPDATE chitietdonhang 
                  SET maDH = :maDH, tenHang = :tenHang, loaiHang = :loaiHang, soLuong = :soLuong, canNang = :canNang, tienThuHo = :tienThuHo
                  WHERE maCTDH = :maCTDH";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maCTDH', $maCTDH);
        $stmt->bindParam(':maDH', $maDH);
        $stmt->bindParam(':tenHang', $tenHang);
        $stmt->bindParam(':loaiHang', $loaiHang);
        $stmt->bindParam(':soLuong', $soLuong);
        $stmt->bindParam(':canNang', $canNang);
        $stmt->bindParam(':tienThuHo', $tienThuHo);

        return $stmt->execute();
    }

    // Delete an order detail
    public function deleteChiTietDonHang($maCTDH)
    {
        $query = "DELETE FROM chitietdonhang WHERE maCTDH = :maCTDH";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':maCTDH', $maCTDH);

        return $stmt->execute();
    }
}
