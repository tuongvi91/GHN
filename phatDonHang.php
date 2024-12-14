<?php
if (isset($_POST['phatDonHang'])) {
    $conn = mysqli_connect ("localhost", "root", "", "ghntest") or die ("!!");
    mysqli_query($conn,"SET NAMES 'utf8'");
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
   
    $sql = "
        UPDATE donHang dh
        JOIN nguoiNhan nn ON dh.maNN = nn.maNN
        JOIN nhanVien nv ON nn.phuongXa = nv.khuVucPhuTrach
        SET dh.maNVPhuTrach = nv.maNV where nv.trangThaiTK = 1 AND dh.maNVPhuTrach IS NULL;
    ";

   
    if ($conn->query($sql) === TRUE) {
    
    } else {
        echo "Lỗi: " . $conn->error;
    }

   
    $conn->close();
    header('Location: ' . $_SERVER['HTTP_REFERER']); 
    exit();
}
?>
