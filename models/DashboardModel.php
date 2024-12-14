<?php
// Include the Database configuration file
require_once './config/Database.php';

class DashboardModel {
    
    private $db;

    public function __construct() {
        // Create a new instance of the Database class to establish a connection
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Get total number of orders
    public function getTotalOrders() {
        $query = "SELECT COUNT(*) AS total_orders FROM donhang";
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total_orders'];
    }

    // Get total sales (sum of all orders' total amounts)
    public function getTotalSales() {
        $query = "SELECT SUM(tongTien) AS total_sales FROM donhang where trangThai='1'";
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total_sales'];
    }

    // Get number of active users (staff and clients)
    public function getActiveUsers() {
        $query = "SELECT COUNT(*) AS total_users FROM nhanvien WHERE matKhau IS NOT NULL";
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total_users'];
    }

    // Get number of completed orders
    public function getCompletedOrders() {
        $query = "SELECT COUNT(*) AS completed_orders FROM donhang WHERE trangThai = 1"; // Assuming 1 is the 'Delivered' status
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['completed_orders'];
    }

    // Get total pending orders
    public function getPendingOrders() {
        $query = "SELECT COUNT(*) AS pending_orders FROM donhang WHERE trangThai = 0"; // Assuming 0 is the 'In Transit' status
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['pending_orders'];
    }

    // Get the total number of items sold
    public function getTotalItemsSold() {
        $query = "SELECT SUM(soLuong) AS total_items_sold FROM chitietdonhang";
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total_items_sold'];
    }

    // Get statistics for dashboard
    public function getDashboardStatistics() {
        return [
            'total_orders' => $this->getTotalOrders(),
            'total_sales' => $this->getTotalSales(),
            'active_users' => $this->getActiveUsers(),
            'completed_orders' => $this->getCompletedOrders(),
            'pending_orders' => $this->getPendingOrders(),
            'total_items_sold' => $this->getTotalItemsSold()
        ];
    }
    // Get monthly revenue for the last 12 months
    public function getMonthlyRevenue() {
        $query = "
            SELECT 
                DATE_FORMAT(ngayDatHang, '%Y-%m') AS month, 
                SUM(tongTien) AS revenue 
            FROM 
                donhang 
            WHERE 
                trangThai = 1 
            GROUP BY 
                DATE_FORMAT(ngayDatHang, '%Y-%m') 
            ORDER BY 
                DATE_FORMAT(ngayDatHang, '%Y-%m') DESC
            LIMIT 12";
        
        $result = $this->db->query($query);
        $monthlyRevenue = [];
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $monthlyRevenue[$row['month']] = $row['revenue'];
        }
        
        return $monthlyRevenue;
    }


}
?>
