<?php
include 'connect.php';

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];
    $laythongtin = "SELECT dh.maDH, nn.tenNN, CONCAT(nn.soNha, ', ', nn.tenDuong, ', ', nn.phuongXa, ', ', nn.quanHuyen, ', ', nn.tinhThanhpho) AS diaChi, dh.thoiGianGiao, dh.trangThai, dh.hinhAnhGH FROM donHang dh INNER JOIN nguoiNhan nn ON dh.MaNN = nn.maNN WHERE dh.maDH = '$orderId'";
    $kq = mysqli_query($conn, $laythongtin);

    if ($row = mysqli_fetch_assoc($kq)) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Không tìm thấy đơn hàng"]);
    }
}
?>
