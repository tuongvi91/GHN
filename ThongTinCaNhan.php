<?php
include 'connect.php';
require('widget/headerNV.php');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin nhân viên theo mã nhân viên
if (isset($_SESSION['tennguoidung']) && isset($_SESSION['tdn'])) {
    $userName = $_SESSION['tennguoidung'];
    $employeeId = $_SESSION['tdn'];
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

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenNV = $_POST['tenNV'] ?? $row['tenNV'];
    $ngaySinh = $_POST['ngaySinh'] ?? $row['ngaySinh'];  
    $sdt = $_POST['sdt'] ?? $row['sdt'];
    $email = $_POST['email'] ?? $row['email'];
    $queQuan = $_POST['queQuan'] ?? $row['queQuan'];
    $gioiTinh = $_POST['gioiTinh'] ?? $row['gioiTinh'];

    // Xử lý upload avatar
    $avatarPath = $row['avatar']; // Giữ nguyên avatar cũ nếu không upload mới
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarName = basename($_FILES['avatar']['name']);
        $targetDir = "uploads/";
        $avatarPath = $targetDir . $avatarName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
            // Cập nhật đường dẫn ảnh vào cơ sở dữ liệu
        } else {
            echo "Lỗi khi upload ảnh.";
        }
    }
    

    // Cập nhật dữ liệu nhân viên
    $updateSql = "UPDATE nhanvien SET 
                    tenNV='$tenNV', 
                    ngaySinh='$ngaySinh', 
                    sdt='$sdt', 
                    email='$email', 
                    queQuan='$queQuan', 
                    gioiTinh='$gioiTinh',
                    avatar='$avatarPath' 
                  WHERE maNV = $maNV";

    if ($conn->query($updateSql) === TRUE) {
        // Reload lại trang sau khi cập nhật
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Cập nhật thất bại: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tt.css">
    
    <title>Hồ Sơ Nhân Viên</title>
    <script>
         // Hàm hiển thị ảnh trước khi upload
    function previewAvatar(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var avatarPreview = document.getElementById('avatarPreview');
            avatarPreview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

        // Chuyển đổi chế độ giữa xem và chỉnh sửa
        function toggleEditMode() {
            document.getElementById('view-mode').style.display = 'none';
            document.getElementById('edit-mode').style.display = 'flex';
        }
    </script>
</head>
<body>
    <div class="profile-container">
        <h2 style="text-align: center;">Hồ Sơ Của Tôi</h2>
        <p style="text-align: center;">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

        <!-- Chế độ xem -->
        <div id="view-mode" style="display: flex; align-items: center;">
            <div class="profile-left" style="margin-right: 20px; text-align: center;">
            <img src="<?= htmlspecialchars($row['avatar'] ?: 'default-avatar.png') ?>" alt="Avatar" class="avatar">
            </div>
            <div class="profile-right">
                <table>
                    <tr><td><strong>Tên:</strong></td><td><?= htmlspecialchars($row['tenNV']) ?></td></tr>
                    <tr><td><strong>Ngày sinh:</strong></td><td><?= htmlspecialchars($row['ngaySinh']) ?></td></tr>
                    <tr><td><strong>SĐT:</strong></td><td><?= htmlspecialchars($row['sdt']) ?></td></tr>
                    <tr><td><strong>Email:</strong></td><td><?= htmlspecialchars($row['email']) ?></td></tr>
                    <tr><td><strong>Quê quán:</strong></td><td><?= htmlspecialchars($row['queQuan']) ?></td></tr>
                    <tr><td><strong>Giới tính:</strong></td><td><?= htmlspecialchars($row['gioiTinh']) ?></td></tr>
                </table>
                <button class="edit-btn" onclick="toggleEditMode()">Chỉnh sửa thông tin</button>
                
            </div>
        </div>

        <!-- Chế độ chỉnh sửa -->
        <form id="edit-mode" style="display: none;" method="POST" enctype="multipart/form-data">
        <div class="profile-left">
        <!-- Hiển thị ảnh trước khi upload -->
        <img src="<?= htmlspecialchars($row['avatar'] ?: 'default-avatar.png') ?>" alt="Avatar" class="avatar" id="avatarPreview">
        <label for="avatarUpload" class="avatar-label">Chọn Ảnh</label>
        <input type="file" id="avatarUpload" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(event)">
    </div>
            <div class="profile-right">
                <table>
                    <tr><td>Tên:</td><td><input type="text" name="tenNV" value="<?= htmlspecialchars($row['tenNV']) ?>"></td></tr>
                    <tr><td>Ngày sinh:</td><td><input type="date" name="ngaySinh" value="<?= htmlspecialchars($row['ngaySinh']) ?>"></td></tr>
                    <tr><td>SĐT:</td><td><input type="text" name="sdt" value="<?= htmlspecialchars($row['sdt']) ?>"></td></tr>
                    <tr><td>Email:</td><td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td></tr>
                    <tr><td>Quê quán:</td><td><input type="text" name="queQuan" value="<?= htmlspecialchars($row['queQuan']) ?>"></td></tr>
                    <tr>
                        <td>Giới tính:</td>
                        <td>
                            <label><input type="radio" name="gioiTinh" value="Nam" <?= $row['gioiTinh'] === 'Nam' ? 'checked' : '' ?>> Nam</label>
                            <label><input type="radio" name="gioiTinh" value="Nữ" <?= $row['gioiTinh'] === 'Nữ' ? 'checked' : '' ?>> Nữ</label>
                            <label><input type="radio" name="gioiTinh" value="Khác" <?= $row['gioiTinh'] === 'Khác' ? 'checked' : '' ?>> Khác</label>
                        </td>
                    </tr>
                </table>
                
                <div class="button-container">
                        <button type="submit" class="save-btn">Lưu thông tin</button>
                        <button type="button" class="edit-btn" onclick="window.location.href='donHangNV.php'">Thoát</button>
                </div>
            </div>
        </form>
    </div>
</body>
<?php require('widget/footer.php');?>
</html>
