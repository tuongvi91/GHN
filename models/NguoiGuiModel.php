<?php
// models/NguoiGuiModel.php
require_once './config/Database.php';

class NguoiGuiModel {
    private $conn;
    
    public function __construct() {
        // Create a database connection using the Database class
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to get all nguoi_gui (senders)
    public function getAllNguoiGui() {
        $query = "SELECT * FROM nguoigui";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Method to add a new nguoi_gui (sender)
    public function addNguoiGui($tenNG, $sdt, $CCCD, $diaChi) {
        $query = "INSERT INTO nguoigui (tenNG, sdt, CCCD, diaChi) VALUES (:tenNG, :sdt, :CCCD, :diaChi)";
        $stmt = $this->conn->prepare($query);
        
        // Bind the parameters to prevent SQL injection
        $stmt->bindParam(':tenNG', $tenNG);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':CCCD', $CCCD);
        $stmt->bindParam(':diaChi', $diaChi);
        
        return $stmt->execute(); // returns true if insertion was successful
    }

    // Method to update an existing nguoi_gui (sender)
    public function updateNguoiGui($maNG, $tenNG, $sdt, $CCCD, $diaChi) {
        $query = "UPDATE nguoigui SET tenNG = :tenNG, sdt = :sdt, CCCD = :CCCD, diaChi = :diaChi WHERE maNG = :maNG";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':maNG', $maNG);
        $stmt->bindParam(':tenNG', $tenNG);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':CCCD', $CCCD);
        $stmt->bindParam(':diaChi', $diaChi);
        
        return $stmt->execute(); // returns true if update was successful
    }

    // Method to delete a nguoi_gui (sender) by ID
    public function deleteNguoiGui($maNG) {
        $query = "DELETE FROM nguoigui WHERE maNG = :maNG";
        $stmt = $this->conn->prepare($query);

        // Bind the parameter
        $stmt->bindParam(':maNG', $maNG);
        
        return $stmt->execute(); // returns true if deletion was successful
    }
}
?>
