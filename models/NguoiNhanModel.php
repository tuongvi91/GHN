<?php
//models/NguoiNhanModel.php
require_once './config/Database.php';

class NguoiNhanModel {
    private $conn;

    public function __construct() {
        // Create a database connection using the Database class
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to get all nguoi_nhan (recipients)
    public function getAllNguoiNhan() {
        $query = "SELECT * FROM nguoinhan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Method to add a new nguoi_nhan (recipient)
    public function addNguoiNhan($tenNN, $sdt, $soNha, $tenDuong, $phuongXa, $quanHuyen, $tinhThanhpho) {
        $query = "INSERT INTO nguoinhan (tenNN, sdt, soNha, tenDuong, phuongXa, quanHuyen, tinhThanhpho) 
                  VALUES (:tenNN, :sdt, :soNha, :tenDuong, :phuongXa, :quanHuyen, :tinhThanhpho)";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters to prevent SQL injection
        $stmt->bindParam(':tenNN', $tenNN);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':soNha', $soNha);
        $stmt->bindParam(':tenDuong', $tenDuong);
        $stmt->bindParam(':phuongXa', $phuongXa);
        $stmt->bindParam(':quanHuyen', $quanHuyen);
        $stmt->bindParam(':tinhThanhpho', $tinhThanhpho);

        return $stmt->execute(); // returns true if insertion was successful
    }

    // Method to update an existing nguoi_nhan (recipient)
    public function updateNguoiNhan($maNN, $tenNN, $sdt, $soNha, $tenDuong, $phuongXa, $quanHuyen, $tinhThanhpho) {
        $query = "UPDATE nguoinhan 
                  SET tenNN = :tenNN, sdt = :sdt, soNha = :soNha, tenDuong = :tenDuong, 
                      phuongXa = :phuongXa, quanHuyen = :quanHuyen, tinhThanhpho = :tinhThanhpho 
                  WHERE maNN = :maNN";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':maNN', $maNN);
        $stmt->bindParam(':tenNN', $tenNN);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':soNha', $soNha);
        $stmt->bindParam(':tenDuong', $tenDuong);
        $stmt->bindParam(':phuongXa', $phuongXa);
        $stmt->bindParam(':quanHuyen', $quanHuyen);
        $stmt->bindParam(':tinhThanhpho', $tinhThanhpho);

        return $stmt->execute(); // returns true if update was successful
    }

    // Method to delete a nguoi_nhan (recipient) by ID
    public function deleteNguoiNhan($maNN) {
        $query = "DELETE FROM nguoinhan WHERE maNN = :maNN";
        $stmt = $this->conn->prepare($query);

        // Bind the parameter
        $stmt->bindParam(':maNN', $maNN);

        return $stmt->execute(); // returns true if deletion was successful
    }
}
?>
