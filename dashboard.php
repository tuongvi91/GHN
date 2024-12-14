<?php
// Bao gồm các tệp cần thiết
include 'header.php';
include 'sidebar.php';
require_once './config/Database.php';
require_once './models/DashboardModel.php';

// Khởi tạo DashboardModel
$database = new Database();
$db = $database->getConnection();
$dashboardModel = new DashboardModel($db);

// Lấy dữ liệu từ DashboardModel
$dashboardStatistics = $dashboardModel->getDashboardStatistics();
$monthlyRevenue = $dashboardModel->getMonthlyRevenue();
$labels = json_encode(array_keys($monthlyRevenue));
$data = json_encode(array_values($monthlyRevenue));

?>

<div class="content">
    <!-- Tiêu đề Bảng điều khiển -->
    <div class="text-center mb-4">
        <h1>Bảng Điều Khiển - Tổng Quan Doanh Thu</h1>
    </div>

    <!-- Hàng chứa các thẻ -->
    <div class="row">

        <!-- Thẻ Tổng Quan Đơn Hàng -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-boxes fa-3x mb-3" style="color: #28a745;"></i>
                    <h5 class="card-title">Tổng Số Đơn Hàng</h5>
                    <p class="card-text"><?php echo $dashboardStatistics['total_orders']; ?> đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: #17a2b8;"></i>
                    <h5 class="card-title">Đơn Hàng Hoàn Thành</h5>
                    <p class="card-text"><?php echo $dashboardStatistics['completed_orders']; ?> đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-3x mb-3" style="color: #ffc107;"></i>
                    <h5 class="card-title">Đơn Hàng Đang Chờ</h5>
                    <p class="card-text"><?php echo $dashboardStatistics['pending_orders']; ?> đơn hàng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hàng chứa các thống kê bổ sung -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-dollar-sign"></i> Tổng Doanh Thu</h5>
                </div>
                <div class="card-body">
                    <p class="text-info font-weight-bold">$<?php echo number_format($dashboardStatistics['total_sales'], 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5><i class="fas fa-boxes"></i> Tổng Số Sản Phẩm Đã Bán</h5>
                </div>
                <div class="card-body">
                    <p class="text-warning font-weight-bold"><?php echo $dashboardStatistics['total_items_sold']; ?> sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5><i class="fas fa-users"></i> Người Dùng Hoạt Động</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger font-weight-bold"><?php echo $dashboardStatistics['active_users']; ?> người</p>
                </div>
            </div>
        </div>
                <!-- Thẻ Biểu đồ Doanh Thu -->
                <div class="col-md-12 mt-4">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-chart-bar"></i> Doanh Thu Hàng Tháng</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <?php require('widget/footer.php');?>
</div>
<!-- Include Chart.js from a CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script defer>
    // Khởi tạo Chart.js cho biểu đồ doanh thu
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels; ?>,
            datasets: [{
                label: 'Doanh Thu (VNĐ)',
                data: <?php echo $data; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


