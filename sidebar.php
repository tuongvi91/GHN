<?php
    include 'connect.php';
    
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    
    // Lấy thông tin nhân viên theo mã nhân viên
    if (isset($_SESSION['ten_nv']) && isset($_SESSION['ma_nv'])) {
        $userName = $_SESSION['ten_nv'];
        $employeeId = $_SESSION['ma_nv'];
    } else {
        $userName = "";
        $employeeId = "";
    }
    $maNV = $employeeId ;
    $sql = "SELECT * FROM nhanvien WHERE maNV = $maNV";
    $result = $conn->query($sql);
    
    // Kiểm tra dữ liệu nhân viên
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Không tìm thấy nhân viên với mã $maNV");
    }
?>
<div class="sidebar">
    <a href="don-hang.php"><img src="Hinhanh/logo.png" alt="GHN Express"></a>
    <hr>
    <div class="taiKhoan">
        <div class="user-avatar">
        <img src="<?= htmlspecialchars($row['avatar'] ?: 'default-avatar.png') ?>" alt="Avatar" class="avatar" id="avatarPreview">
           
        </div>
        <!-- Display employee name from session -->
        <?php    
            if (isset($_SESSION['ten_nv']) && isset($_SESSION['ma_nv'])) {
                $userName = $_SESSION['ten_nv'];
                $maNV =  $_SESSION['ma_nv'];
            }?>
        <p><a href="ThongTinCaNhanadmin.php"><?php echo $userName; ?></a></p>
        <p>Chào mừng bạn trở lại</p>
    </div>
    <hr>
    <nav>
        <a href="dashboard.php" <?php if(basename($_SERVER['PHP_SELF']) == 'dashboard.php') echo 'class="active"'; ?>>Dashboard</a>
        <a href="don-hang.php" <?php if(basename($_SERVER['PHP_SELF']) == 'don-hang.php') echo 'class="active"'; ?>>Quản lý đơn hàng</a>
        <a href="nguoi-gui.php" <?php if(basename($_SERVER['PHP_SELF']) == 'nguoi-gui.php') echo 'class="active"'; ?>>Quản lý người gửi</a>
        <a href="nguoi-nhan.php" <?php if(basename($_SERVER['PHP_SELF']) == 'nguoi-nhan.php') echo 'class="active"'; ?>>Quản lý người nhận</a>
        <a href="nhan-vien.php" <?php if(basename($_SERVER['PHP_SELF']) == 'nhan-vien.php') echo 'class="active"'; ?>>Quản lý nhân viên</a>
        <a href="logout.php" <?php if(basename($_SERVER['PHP_SELF']) == 'logout.php') echo 'class="active"'; ?>>Đăng xuất</a>
    </nav>
</div>
