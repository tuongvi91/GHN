<?php
include 'connect.php';
require('widget/headerNV.php');

// Xử lý cập nhật trạng thái đơn hàng
/*if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deliver'])) {
    $orderId = $_POST['orderId'];
	$currentTime = date('Y-m-d H:i:s'); 
	$updateQuery = "UPDATE donHang SET trangThai = '1', thoiGianGiao = '$currentTime' WHERE maDH = '$orderId'";
    
    mysqli_query($conn, $updateQuery);
}
*/
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deliver'])) {
    $orderId = $_POST['orderId'];
    $currentTime = date('Y-m-d H:i:s'); 

    if (isset($_FILES['delivery_image']) && $_FILES['delivery_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; 
        $targetFile = $targetDir . basename($_FILES["delivery_image"]["name"]);

        if (move_uploaded_file($_FILES["delivery_image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
            $updateQuery = "UPDATE donHang SET trangThai = '1', thoiGianGiao = '$currentTime', hinhAnhGH='$imagePath' WHERE maDH = '$orderId'";

            mysqli_query($conn, $updateQuery);}
        }
    }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
    $orderId = $_POST['orderId'];
    $updateQuery = "UPDATE donHang SET trangThai = '2' WHERE maDH = '$orderId'";
    mysqli_query($conn, $updateQuery);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delay'])) {
    $orderId = $_POST['orderId'];
    $deliveryDate = $_POST['delayDate'];
    $updateQuery = "UPDATE donHang SET ngayGiaoHangDuKien = '$deliveryDate' WHERE maDH = '$orderId' ";
    mysqli_query($conn, $updateQuery);
}

// Lấy các đơn hàng có ngày giao dự kiến trong vòng 1 ngày
$currentDate = date('Y-m-d');
$dayLater = date('Y-m-d', strtotime($currentDate . ' + 1 days'));
$layTT = "SELECT * FROM donHang WHERE maNVPhuTrach = '$employeeId' AND trangThai = '0' AND ngayGiaoHangDuKien BETWEEN '$currentDate' AND '$dayLater'";
$rsTT = mysqli_query($conn, $layTT);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management Interface</title>
    <link rel="stylesheet" href="css/donHang1.css"/>

</head>
<body>
    
    <div class="thanh">
        <?php 
        if ($rsTT->num_rows <= 0) echo "<h3>HÀNG CẦN GIAO ĐÃ HẾT</h3>";
        else {
            echo "<h3>Hiện đang có " . $rsTT->num_rows . " đơn hàng đã nhận</h3>";
        }
        ?>

        <div class="search-bar">
            <form action="" method="post">
            <button type="submit" name="OK" value="OK" class="btn btn-search">Tìm kiếm</button>
            <input type="text" name="search" class="search" placeholder="Tìm kiếm">
            </form>
        </div>
    </div>

    <?php 
    if (isset($_POST['OK']) && !empty($_POST['OK'])) {
    $key = $_POST['search'];
    $laythongtin = "SELECT * FROM donHang dh INNER JOIN chiTietDonHang ctdh INNER JOIN nguoiNhan nn ON dh.maDH = ctdh.maDH and dh.MaNN = nn.maNN WHERE ((dh.maDH LIKE '%$key%' or ctdh.tenHang LIKE '%$key%' or nn.tenNN LIKE '%$key%') AND dh.maNVPhuTrach = '" . $employeeId . "' and dh.trangThai ='0' AND dh.ngayGiaoHangDuKien BETWEEN '$currentDate' AND '$dayLater')";
} else {
    $laythongtin = "SELECT * FROM donHang, chiTietDonHang, nguoiNhan WHERE ((donHang.maDH = chiTietDonHang.maDH AND nguoiNhan.maNN = donHang.MaNN) AND donHang.maNVPhuTrach = '" . $employeeId . "' AND donHang.trangThai ='0' AND donHang.ngayGiaoHangDuKien BETWEEN '$currentDate' AND '$dayLater')";
}
$kq = mysqli_query($conn, $laythongtin);
    if (!$kq) {
        die("Error executing query: " . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_array($kq)) {
        echo "<div class='order-details'>";
        echo "<table>";
        echo "<tr><td><strong>Mã đơn hàng:</strong></td><td>" . $row['maDH'] . "</td></tr>";
        echo "<tr><td><strong>Tên đơn hàng:</strong></td><td>" . $row['tenHang'] . "</td></tr>";
        echo "<tr><td><strong>Tên khách hàng:</strong></td><td>" . $row['tenNN'] . "</td></tr>";
        echo "<tr><td><strong>Số điện thoại:</strong></td><td>" . $row['sdt'] . "</td></tr>";
        echo "<tr><td><strong>Địa chỉ:</strong></td><td>" . $row['soNha'] . ", " . $row['tenDuong'] . ", " . $row['phuongXa'] . ", " . $row['quanHuyen'] . ", " . $row['tinhThanhpho'] . "</td></tr>";
        echo "<tr><td><strong>Thu hộ:</strong></td><td>" . $row['tienThuHo'] . " VND</td></tr>";
        echo "<tr><td><strong>Ghi chú:</strong></td><td>" . $row['ghiChu'] . "</td></tr>";
        echo "</table>";
        ?>
        <div class="buttons">
            <button onclick="openDeliveredPopup('<?php echo $row['maDH']; ?>', '<?php echo $row['tenNN']; ?>', '<?php echo $row['sdt']; ?>', '<?php echo $row['soNha'] . ', ' . $row['tenDuong'] . ', ' . $row['phuongXa'] . ', ' . $row['quanHuyen'] . ', ' . $row['tinhThanhpho']; ?>')">Giao hàng</button>
            <button type="submit" name="cancel" onclick="openCancelPopup('<?php echo $row['maDH']; ?>')">Hủy giao hàng</button>
            <button class="actionButton" data-order-id="<?php echo $row['maDH']; ?>" onclick="openDelayPopup('<?php echo $row['maDH']; ?>')">Hẹn giao hàng</button>
        </div>      
        </div>
    <?php } ?>


    <!-- Modal Popup for Delivered -->
    
<div class="overlay" id="deliveredOverlay" onclick="closePopup('deliveredPopup', 'deliveredOverlay')"></div>
<div class="actions-popup" id="deliveredPopup">
    <h3>Xác nhận giao hàng</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype ="multipart/form-data">
        <table>
            <tr>
                <td>Mã đơn hàng:</td>
                <td><input type="text" id="deliveredOrderId" name="orderId" readonly></td>
            </tr>
            <tr>
                <td>Tên khách hàng:</td>
                <td><input type="text" id="deliveredCustomerName" readonly></td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td><input type="text" id="deliveredPhone" readonly></td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td><input type="text" id="deliveredAddress" readonly></td>
            </tr>
            <tr>
                <td>Ảnh xác nhận:</td>
                <td><input type="file" name="delivery_image" ></td>
            </tr>
        </table>
        <div class="popup-buttons">
            <button type="submit" name="deliver">Xác nhận đơn hàng</button>
            <button type="button" onclick="closePopup('deliveredPopup', 'deliveredOverlay')">Đóng</button>
        </div>
    </form>
</div>
	<!-- Popup for Cancel -->
    <!-- Overlay và Popup for Cancel -->
<div class="overlay" id="cancelOverlay" onclick="closePopup('cancelPopup', 'cancelOverlay')"></div>
<div class="actions-popup" id="cancelPopup">
    <h3>Hủy đơn hàng</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div style = "display: flex;"><label for="">Bạn có chắc muốn hủy đơn hàng mã: </label>
            <input type="text" id="cancelOrderId" name="orderId" readonly></div>
        <div class="popup-buttons">
        <button type="submit" name="cancel">Hủy giao hàng</button>
        <button type="button" onclick="closePopup('cancelPopup', 'cancelOverlay')">Đóng</button>
        </div>
    </form>
</div>

	<!-- Popup for Delay -->
   <!-- Overlay và Popup for Delay -->
<div class="overlay" id="delayOverlay" onclick="closePopup('delayPopup', 'delayOverlay')"></div>
<div class="actions-popup" id="delayPopup">
    <h3>Hẹn giao hàng</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr>
                <td>Đơn hàng mã:</td>
                <td><input type="text" id="delayOrderId" name="orderId" readonly></td>
            </tr>
            <tr>
                <td>Hẹn giao vào:</td>
                <td><input type="date" id="delayDate" name="delayDate"></td>
            </tr>
        </table>
        <div class="popup-buttons">
            <button type="submit" name="delay">Đặt lịch hẹn</button>
            <button type="button" onclick="closePopup('delayPopup', 'delayOverlay')">Đóng</button>
        </div>
    </form>
</div>


	
	<script>
       // Hàm đóng popup
function closePopup(popupId, overlayId) {
    document.getElementById(overlayId).style.display = 'none';
    document.getElementById(popupId).style.display = 'none';
}

// Hàm mở popup giao hàng
function openDeliveredPopup(orderId, customerName, phone, address) {
    document.getElementById('deliveredOrderId').value = orderId;
    document.getElementById('deliveredCustomerName').value = customerName;
    document.getElementById('deliveredPhone').value = phone;
    document.getElementById('deliveredAddress').value = address;

    document.getElementById('deliveredOverlay').style.display = 'block';
    document.getElementById('deliveredPopup').style.display = 'block';
}

// Hàm mở popup hủy giao hàng
function openCancelPopup(orderId) {
    document.getElementById('cancelOrderId').value = orderId;

    document.getElementById('cancelOverlay').style.display = 'block';
    document.getElementById('cancelPopup').style.display = 'block';
}

// Hàm mở popup hẹn giao hàng
function openDelayPopup(orderId) {
    document.getElementById('delayOrderId').value = orderId;

    document.getElementById('delayOverlay').style.display = 'block';
    document.getElementById('delayPopup').style.display = 'block';
}

    </script>
</body>
<?php require('widget/footer.php');?>
</html>
<style>
    /* Overlay covering the whole page */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: none; /* Initially hidden */
    z-index: 999;
}

/* Popup container */
.actions-popup {
    display: none; /* Ẩn các hộp thoại mặc định */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 600px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    text-align: center; /* Center align the content inside the popup */
}

/* Table styling */
table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

table td {
    padding: 8px;
    text-align: left;
}

input[type="text"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Button container styling */
.popup-buttons {
    display: flex;
    justify-content: center; /* Align buttons in the center */
    gap: 15px; /* Add space between the buttons */
    margin-top: 20px; /* Add space above the buttons */
}

/* Style for buttons */
.popup-buttons button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.popup-buttons button[type="button"] {
    background-color: #f44336;
}

.popup-buttons button:hover {
    opacity: 0.8;
}

</style>