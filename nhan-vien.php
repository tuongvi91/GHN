<?php
// Include the NhanVienModel and ChucVuModel
require_once 'models/NhanVienModel.php';
require_once 'models/ChucVuModel.php';
// Create instances of the models
$nhanVienModel = new NhanVienModel();
$chucVuModel = new ChucVuModel();

// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Handle Add Employee
if (isset($_POST['add']) && isset($_POST['trangThaiTK'])) {
    $ten_nv = $_POST['ten_nv'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $que_quan = $_POST['que_quan'];
    $cccd = $_POST['cccd'];
    $ma_cv = $_POST['ma_cv'];
    $khu_vuc_phu_trach = $_POST['khu_vuc_phu_trach'];
    $mat_khau = $_POST['mat_khau']; // Hash the password
    $ngay_cap_nhat_thong_tin = date('Y-m-d H:i:s'); // Use current timestamp
    $trangThaiTK = $_POST['trangThaiTK'];
    // Call the add method from NhanVienModel
    $result = $nhanVienModel->addNhanVien($ten_nv, $ngay_sinh, $sdt, $email, $que_quan, $cccd, $ma_cv, $khu_vuc_phu_trach, $mat_khau, $ngay_cap_nhat_thong_tin, $trangThaiTK);
    if ($result) {
        $successMessage = 'Thêm nhân viên thành công!';
    } else {
        $errorMessage = 'Thêm nhân viên thất bại!';
    }
}

// Handle Update Employee
if (isset($_POST['update'])) {
    $ma_nv = $_POST['ma_nv'];
    $ten_nv = $_POST['ten_nv'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $que_quan = $_POST['que_quan'];
    $cccd = $_POST['cccd'];
    $ma_cv = $_POST['ma_cv'];
    $khu_vuc_phu_trach = $_POST['khu_vuc_phu_trach'];
    $mat_khau = $_POST['mat_khau']; // Hash the password
    $ngay_cap_nhat_thong_tin = date('Y-m-d H:i:s'); // Use current timestamp
    $trang_thai_tai_khoan = $_POST['trangThaiTK'];
    // Call the update method from NhanVienModel
    $result = $nhanVienModel->updateNhanVien($ma_nv, $ten_nv, $ngay_sinh, $sdt, $email, $que_quan, $cccd, $ma_cv, $khu_vuc_phu_trach, $mat_khau, $ngay_cap_nhat_thong_tin, $trang_thai_tai_khoan);
    if ($result) {
        $successMessage = 'Cập nhật nhân viên thành công!';
    } else {
        $errorMessage = 'Cập nhật nhân viên thất bại!';
    }
}


// Xử lý yêu cầu GET để xóa người gửi
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['ma_nv'])) {
    $ma_nv = $_GET['ma_nv'];
    // Call the delete method from NhanVienModel
    $result = $nhanVienModel->deleteNhanVien($ma_nv);
    if ($result) {
        $successMessage = 'Khóa tài khoản nhân viên thành công!';
    } else {
        $errorMessage = 'Khóa tài khoản nhân viên thất bại!';
    }
}

// Fetch all employees for the table
$nhanViens = $nhanVienModel->getAllNhanVien();
$chucVuList = $chucVuModel->getAllChucVu();
?>

<?php
    include 'header.php';
    include 'sidebar.php';
?>

<div class="content">
    <h1 class="text-center">Quản lý Nhân viên</h1> <!-- Vietnamese Header -->

    <!-- Display Success or Error Message -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>

    <!-- Add Employee Button -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Thêm Nhân viên</button> <!-- Vietnamese Button -->

    <!-- Employee Table -->
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th> <!-- Vietnamese Column -->
                <th>Email</th>
                <th>Số điện thoại</th> <!-- Vietnamese Column -->
                <th>Chức vụ</th> <!-- Vietnamese Column -->
                <th>Hành động</th> <!-- Vietnamese Column -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nhanViens as $nv) { ?>
                <tr>
                    <td><?php echo $nv['maNV']; ?></td>
                    <td><?php echo $nv['tenNV']; ?></td>
                    <td><?php echo $nv['email']; ?></td>
                    <td><?php echo $nv['sdt']; ?></td>
                    <td><?php echo $nv['tenCV']; ?></td> <!-- Assuming `ten_cv` from a JOIN query -->
                    <td>
                        <!-- Edit Button -->
                        <button 
                            class="btn btn-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editEmployeeModal" 
                            data-id="<?php echo $nv['maNV']; ?>" 
                            data-name="<?php echo $nv['tenNV']; ?>" 
                            data-email="<?php echo $nv['email']; ?>" 
                            data-phone="<?php echo $nv['sdt']; ?>" 
                            data-position="<?php echo $nv['maCV']; ?>" 
                            data-dob="<?php echo $nv['ngaySinh']; ?>" 
                            data-hometown="<?php echo $nv['queQuan']; ?>" 
                            data-cccd="<?php echo $nv['CCCD']; ?>" 
                            data-area="<?php echo $nv['khuVucPhuTrach']; ?>"
                            data-area="<?php echo $nv['trangThaiTK']; ?>"
                        >Sửa</button> <!-- Vietnamese Button -->
                        
                        
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Thêm Nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="addTenNV" class="form-label">Tên nhân viên</label>
                        <input type="text" name="ten_nv" id="addTenNV" class="form-control" placeholder="Tên nhân viên" required>
                    </div>
                    <div class="mb-2">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="addEmail" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-2">
                        <label for="addSDT" class="form-label">Số điện thoại</label>
                        <input type="text" name="sdt" id="addSDT" class="form-control" placeholder="Số điện thoại" required>
                    </div>
                    <div class="mb-2">
                        <label for="addNgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" name="ngay_sinh" id="addNgaySinh" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="addQueQuan" class="form-label">Quê quán</label>
                        <input type="text" name="que_quan" id="addQueQuan" class="form-control" placeholder="Quê quán" required>
                    </div>
                    <div class="mb-2">
                        <label for="addCCCD" class="form-label">CCCD/ID</label>
                        <input type="text" name="cccd" id="addCCCD" class="form-control" placeholder="CCCD/ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="addMaCV" class="form-label">Chọn chức vụ</label>
                        <select class="form-select" id="addMaCV" name="ma_cv" required>
                            <option value="" disabled selected>Chọn chức vụ</option>
                            <?php foreach ($chucVuList as $position) { ?>
                                <option value="<?php echo $position['maCV']; ?>"><?php echo $position['tenCV']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="addKhuVuc" class="form-label">Khu vực phụ trách</label>
                        <input type="text" name="khu_vuc_phu_trach" id="addKhuVuc" class="form-control" placeholder="Khu vực phụ trách" required>
                    </div>
                    <div class="mb-2">
                        <label for="addMatKhau" class="form-label">Mật khẩu</label>
                        <input type="password" name="mat_khau" id="addMatKhau" class="form-control" placeholder="Mật khẩu" required>
                    </div>
                    <div class="mb-2">
                        <label for="addTrangThaiTK" class="form-label">Tùy chọn tài khoản</label>
                        
                        <select class="form-select" name="trangThaiTK" id="trangThaiTK">
                            <option value="1">Cấp phát tài khoản</option>
                            <option value="0">Khóa tài khoản</option>
                        </select>
                    </div>
                    <input type="submit" name="add" class="btn btn-primary" value="Thêm Nhân viên">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Sửa Nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ma_nv" id="editMaNV">
                    <div class="mb-2">
                        <label for="editTenNV" class="form-label">Tên nhân viên</label>
                        <input type="text" name="ten_nv" id="editTenNV" class="form-control" placeholder="Tên nhân viên" required>
                    </div>
                    <div class="mb-2">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-2">
                        <label for="editSDT" class="form-label">Số điện thoại</label>
                        <input type="text" name="sdt" id="editSDT" class="form-control" placeholder="Số điện thoại" required>
                    </div>
                    <div class="mb-2">
                        <label for="editNgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" name="ngay_sinh" id="editNgaySinh" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="editQueQuan" class="form-label">Quê quán</label>
                        <input type="text" name="que_quan" id="editQueQuan" class="form-control" placeholder="Quê quán" required>
                    </div>
                    <div class="mb-2">
                        <label for="editCCCD" class="form-label">CCCD/ID</label>
                        <input type="text" name="cccd" id="editCCCD" class="form-control" placeholder="CCCD/ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMaCV" class="form-label">Chọn chức vụ</label>
                        <select class="form-select" id="editMaCV" name="ma_cv" required>
                            <option value="" disabled selected>Chọn chức vụ</option>
                            <?php foreach ($chucVuList as $position) { ?>
                                <option value="<?php echo $position['maCV']; ?>"><?php echo $position['tenCV']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="editKhuVuc" class="form-label">Khu vực phụ trách</label>
                        <input type="text" name="khu_vuc_phu_trach" id="editKhuVuc" class="form-control" placeholder="Khu vực phụ trách" required>
                    </div>
                    <div class="mb-2">
                        <label for="editMatKhau" class="form-label">Mật khẩu</label>
                        <input type="password" name="mat_khau" id="editMatKhau" class="form-control" placeholder="Mật khẩu">
                    </div>
                    <div class="mb-2">
                        <label for="addTrangThaiTK" class="form-label">Tùy chọn tài khoản</label>                        
                        <select class="form-select" name="trangThaiTK" required>
                            <option value="1">Cấp phát tài khoản</option>
                            <option value="0">Khóa tài khoản</option>
                        </select>
                    </div>
                    <input type="submit" name="update" class="btn btn-warning" value="Cập nhật Nhân viên">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Employee Modal -->
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmployeeModalLabel">Khóa tài khoản</h5> <!-- Vietnamese Title -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khóa tài khoản của nhân viên này không? <!-- Vietnamese Confirmation Message -->
                <form method="GET" action="">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="ma_nv" id="deleteMaNV" value="">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button> <!-- Vietnamese Button -->
                        <button type="submit" class="btn btn-danger">Khóa</button> <!-- Vietnamese Button -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // Edit Button
const editButtons = document.querySelectorAll('[data-bs-target="#editEmployeeModal"]');
editButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Populate modal with employee data
        document.getElementById('editMaNV').value = this.getAttribute('data-id');
        document.getElementById('editTenNV').value = this.getAttribute('data-name');
        document.getElementById('editEmail').value = this.getAttribute('data-email');
        document.getElementById('editSDT').value = this.getAttribute('data-phone');
        document.getElementById('editNgaySinh').value = this.getAttribute('data-dob');
        document.getElementById('editQueQuan').value = this.getAttribute('data-hometown');
        document.getElementById('editCCCD').value = this.getAttribute('data-cccd');
        const positionSelect = document.getElementById('editMaCV');
        const selectedPosition = this.getAttribute('data-position');
        
        // Set the selected attribute for the correct option
        for (let option of positionSelect.options) {
            if (option.value === selectedPosition) {
                option.selected = true;
                break;
            }
        }
        document.getElementById('editKhuVuc').value = this.getAttribute('data-area');
    });
});


const deleteModal = document.getElementById('deleteEmployeeModal');
deleteModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const employeeId = button.getAttribute('data-id');
        document.getElementById('deleteMaNV').value = employeeId;
});
</script>