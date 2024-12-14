<html>
<style>
    .navbar {
	background-color: #333;
	display: flex;
	justify-content: space-around; /* Giãn đều các phần tử trong navbar */
	align-items: center;
	width: 1200px; /* Chiều dài cố định 1200px */
	margin: 0 auto; /* Căn giữa navbar */
	padding: 10px 0; /* Thêm padding cho navbar */
}
.navbar a {
	color: white;
	text-align: center;
	padding: 14px 10px;
	text-decoration: none;
	font-size: 16px;
}
.navbar a:hover {
	background-color: #ddd;
	color: black;
}
</style>
<?php 
session_start();

if (isset($_SESSION['tennguoidung']) && isset($_SESSION['tdn'])) {
    $userName = $_SESSION['tennguoidung'];
    $employeeId = $_SESSION['tdn'];
} else {
    $userName = "";
    $employeeId = "";
}?>
<header>
<div class="navbar">
        <a href="donHangNV.php"><img src="Hinhanh/logo.png" alt="" style="width: 100px;"></a>
        <a href="donHangNV.php">Trang chủ</a>
        <a href="lichSuGiaoHangNV.php">Lịch sử</a>
        <a href="logout.php">Đăng xuất</a>
        <a href="ThongTinCaNhan.php"><?php echo $userName . " (" . $employeeId . ")"; ?></a>
    </div>
</header>
</html>