<?php
include 'connect.php';
require('widget/headerNV.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Giao Hàng</title>
    <style>
        
    </style>
     <link rel="stylesheet" href="css/lichSuGiaoHang.css"/>
    <script>
        function closePopup() {
            document.getElementById('orderDetailsPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function viewDetails(orderId) {
  // Gửi yêu cầu AJAX đến máy chủ để lấy thông tin chi tiết đơn hàng
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'chiTietDHNV.php?orderId=' + orderId, true);
  xhr.onload = function() {
    if (this.status == 200) {
        console.log('Phản hồi từ máy chủ:', this.responseText);
      const details = JSON.parse(this.responseText);

      // In ra toàn bộ đối tượng JSON để kiểm tra
      console.log('Chi tiết đơn hàng:', details);

      // Cập nhật thông tin vào popup
      document.getElementById('orderCode').innerText = details.maDH;
      document.getElementById('customerName').innerText = details.tenNN;
      document.getElementById('address').innerText = details.diaChi;
      document.getElementById('deliveryTime').innerText = details.thoiGianGiao;
      document.getElementById('orderStatus').innerText = details.trangThai == 1 ? "Đã giao" : "Đã hủy";

      // Kiểm tra hình ảnh sau khi phân tích dữ liệu
      if (details.hinhAnhGH) {
        // Kiểm tra thêm để đảm bảo details.hinhAnhGH là một chuỗi hợp lệ
        if (typeof details.hinhAnhGH === 'string') {
            document.getElementById('deliveryPic').innerHTML = '<img src="' + details.hinhAnhGH + '" alt="Hình ảnh giao hàng" width="100" height="100">';
        } else {
          document.getElementById('deliveryPic').innerHTML = 'Giá trị hình ảnh không hợp lệ';
        }
      } else {
        document.getElementById('deliveryPic').innerHTML = 'Không có hình ảnh giao hàng';
      }

      console.log('Giá trị của details.hinhAnhGH:', details.hinhAnhGH);

      document.getElementById('orderDetailsPopup').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }
  };
  xhr.send();
}

    </script>
</head>
<body>    
    <!-- Container -->
    <div class="container">
    <h2>Lịch Sử Giao Hàng</h2>
    <table class="history-table">
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Tên Khách Hàng</th>
                <th>Thời Gian Giao</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $laythongtin = "SELECT * FROM donHang, chiTietDonHang, nguoiNhan 
            WHERE ((donHang.maDH = chiTietDonHang.maDH AND nguoiNhan.maNN = donHang.maNN) 
            AND donHang.maNVPhuTrach = '" . $employeeId . "' AND (donHang.trangThai='1' OR donHang.trangThai='2'))";
            $kq = mysqli_query($conn, $laythongtin);

            if (!$kq) {
                die("Error executing query: " . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_array($kq)) {
            ?>
                <tr>
                    <td><?php echo $row['maDH']?></td>
                    <td><?php echo $row['tenNN']?></td>
                    <td><?php echo $row['thoiGianGiao'] ?></td>
                    <td class="status">
                        <?php
                        if ($row['trangThai'] == 1) {
                            echo "<span style='color: green;'>Đã giao</span>";
                        } elseif ($row['trangThai'] == 2) {
                            echo "<span style='color: red;'>Đã hủy</span>";
                        }
                        ?>
                    </td>
                    <td><button class="view-details-btn" onclick="viewDetails('<?php echo $row['maDH']; ?>')">Xem chi tiết</button></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
    
    <!-- Popup View Order Details -->
    <div id="orderDetailsPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); border-radius: 10px; width: 50%; z-index: 1000;">
    <h3 style = "text-align: center; margin-bottom: 20px;">Chi Tiết Đơn Hàng</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td><strong>Mã Đơn Hàng:</strong></td>
            <td><span id="orderCode"></span></td>
        </tr>
        <tr>
            <td><strong>Tên Khách Hàng:</strong></td>
            <td><span id="customerName"></span></td>
        </tr>
        <tr>
            <td><strong>Địa Chỉ:</strong></td>
            <td><span id="address"></span></td>
        </tr>
        <tr>
            <td><strong>Thời Gian Giao Hàng:</strong></td>
            <td><span id="deliveryTime"></span></td>
        </tr>
        <tr>
            <td><strong>Trạng Thái:</strong></td>
            <td><span id="orderStatus"></span></td>
        </tr>
        <tr>
            <td><strong>Hình ảnh giao hàng:</strong></td>
            <td>
                <div id="deliveryPic"></div>
            </td>
        </tr>
    </table>
    <div class="popup-buttons">
        <button onclick="closePopup()">Đóng</button>
    </div>
</div>

<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999;"></div>

</body>
<?php require('widget/footer.php');?>
</html>
<style>
    #deliveryPic {
  width: 100px; 
  height: 100px; 
}
    #orderDetailsPopup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    width: 50%;
    z-index: 1000;
}

#orderDetailsPopup table {
    width: 100%;
    border-collapse: collapse;
}

#orderDetailsPopup td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

#orderDetailsPopup td:first-child {
    font-weight: bold;
}

.popup-buttons {
    text-align: center;
    margin-top: 20px;
}

.popup-buttons button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

.popup-buttons button:hover {
    background-color: #0056b3;
}

#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

</style>