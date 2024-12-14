<?php
//models/ChucVuModel.php
require_once './config/Database.php';

class ChucVuModel {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllChucVu() {
        $query = "SELECT * FROM chucvu";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addChucVu($tenCV) {
        $query = "INSERT INTO chucvu (tenCV) VALUES (:tenCV)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tenCV', $tenCV);
        return $stmt->execute();
    }

    public function updateChucVu($maCV, $tenCV) {
        $query = "UPDATE chucvu SET tenCV = :tenCV WHERE maCV = :maCV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maCV', $maCV);
        $stmt->bindParam(':tenCV', $tenCV);
        return $stmt->execute();
    }

    public function deleteChucVu($maCV) {
        $query = "DELETE FROM chucvu WHERE maCV = :maCV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maCV', $maCV);
        return $stmt->execute();
    }

    public function checkTenCvExists($tenCV, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM chucvu WHERE tenCV = :tenCV";
        if ($excludeId) {
            $query .= " AND maCV != :maCV";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tenCV', $tenCV);
        if ($excludeId) {
            $stmt->bindParam(':maCV', $excludeId);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

?>
