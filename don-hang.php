<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
require_once 'models/DonHangModel.php';
require_once 'models/NguoiGuiModel.php';
require_once 'models/NguoiNhanModel.php';
require_once 'models/NhanVienModel.php';
$conn = mysqli_connect ("localhost", "root", "", "ghntest") or die ("!!");
    mysqli_query($conn,"SET NAMES 'utf8'");
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

// Create instances of models
$donHangModel = new DonHangModel();
$nguoiGuiModel = new NguoiGuiModel();
$nguoiNhanModel = new NguoiNhanModel();
// Assuming you have a function to fetch employees
$employeeModel = new NhanVienModel();


// Initialize message variables
$message = '';
$messageType = ''; // 'success' or 'error'

// Add order (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Get form data
    $ma_nn = $_POST['ma_nn']; 
    $ma_ng = $_POST['ma_ng']; 
    $ngay_dat_hang = $_POST['ngay_dat_hang'];
    $ngay_giao_hang_du_kien = $_POST['ngay_giao_hang_du_kien'];
    $trang_thai = 0;
    $tong_tien = $_POST['tong_tien'];
    $ten_hang = $_POST['ten_hang'];
    $can_nang = $_POST['can_nang'];
    $tien_thu_ho = $_POST['tien_thu_ho'];
    $ghi_chu = $_POST['ghi_chu'];
    $loai_hang = $_POST['loai_hang'];
    $so_luong=$_POST['so_luong'];
    $ngay_dat_hang = formatToMySQLDateTime($ngay_dat_hang);
    $$ngay_giao_hang_du_kien = formatToMySQLDateTime($ngay_giao_hang_du_kien);
    
    $sql = "INSERT INTO donhang (MaNN, MaNG, ngayDatHang, ngayGiaoHangDuKien, trangThai, tongTien)
            VALUES ('$ma_nn', '$ma_ng', '$ngay_dat_hang', '$ngay_giao_hang_du_kien', '$trang_thai', '$tong_tien')";
     if ($conn->query($sql) === TRUE) {$maDH = $conn->insert_id; } else echo "Error: " . $conn->error;
    
    $sql1 = "INSERT INTO chitietdonhang (maDH, tenHang, loaiHang, soLuong, canNang, tienThuHo, ghiChu) 
                VALUES ('$maDH', '$ten_hang', '$loai_hang', '$so_luong', '$can_nang', '$tien_thu_ho', '$ghi_chu')";
        
   if ($conn->query($sql1) === true) {
    echo "<script>alert('Thêm thành công'); window.history.back();</script>";
               } else 
               if ($conn->query($sql1) === FALSE) {
                echo "Error2: " . $sql1 . "<br>";}
}

// Edit order (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $ma_dh = $_POST['MaDonHang']; 
    $trang_thai = $_POST['TrangThaiEdit'];  
    $ma_nv = $_POST['ma_nvEdit']; 
    if ($donHangModel->updateEmployeeAndStatus($ma_dh, $trang_thai, $ma_nv)) {
        $message = 'Trạng thái đơn hàng và nhân viên phụ trách đã được cập nhật!';
        $messageType = 'success';
    } else {
        $message = 'Cập nhật trạng thái đơn hàng và nhân viên phụ trách thất bại.';
        $messageType = 'error';
    }
}


// Delete order (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Get the order ID
    $ma_dh = $_POST['ma_dh']; // Order ID

    // Delete the order
    if ($donHangModel->deleteDonHang($ma_dh)) {
        $message = 'Đơn hàng đã được xóa!';
        $messageType = 'success';
    } else {
        $message = 'Xóa đơn hàng thất bại.';
        $messageType = 'error';
    }
}

// Autoload PhpSpreadsheet classes
require 'vendor/autoload.php';

// Namespace imports
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'import') {
    // Check if a valid file is uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];

        try {
            // Load the uploaded Excel file
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

           

            // Iterate over rows, skipping the header row
            foreach ($sheetData as $index => $row) {
                if ($index === 0) continue; // Skip header row

                
                $ten_ng = $row[0] ?? null; 
                $sdt_ng = $row[1] ?? null; 
                $cccd_ng = $row[2] ?? null; 
                $diaChi_ng = $row[3] ?? null; 
                $ten_nn = $row[4] ?? null; 
                $sdt_nn = $row[5] ?? null; 
                $soNha_nn = $row[6] ?? null; 
                $tenDuong_nn = $row[7] ?? null; 
                $phuongXa_nn = $row[8] ?? null; 
                $quanHuyen_nn = $row[9] ?? null; 
                $tinhThanhpho_nn = $row[10] ?? null; 
                $ngayDH = $row[11] ?? null; 
                $ngayGH = $row[12] ?? null; 
                $tongTien = $row[13] ?? null; 
                $tenHang = $row[14] ?? null; 
                $soLuong = $row[15] ?? null; 
                $canNang = $row[16] ?? null; 
                $tienThuHo = $row[17] ?? null; 
                $ghiChu = $row[18] ?? null;
                $loaiHang = $row[19] ?? null;
                

                // Format dates to MySQL compatible format (YYYY-MM-DD HH:MM:SS)
                $ngayDH = formatToMySQLDateTime($ngayDH);
                $ngayGH = formatToMySQLDateTime($ngayGH);
                
                $insertNguoiNhan = "INSERT INTO nguoinhan (tenNN, sdt, soNha, tenDuong, phuongXa, quanHuyen, tinhThanhpho) 
                VALUES ('$ten_nn', '$sdt_nn', '$soNha_nn', '$tenDuong_nn', '$phuongXa_nn', '$quanHuyen_nn', '$tinhThanhpho_nn')";
                if ($conn->query($insertNguoiNhan) === TRUE) { $maNN = $conn->insert_id; }

                $insertNguoiGui = "INSERT INTO nguoigui (tenNG, sdt, CCCD, diaChi) 
                VALUES ('$ten_ng', '$sdt_ng', '$cccd_ng', '$diaChi_ng')";
                if ($conn->query($insertNguoiGui) === TRUE) { $maNG = $conn->insert_id; }

                
                $insertDonHang = "INSERT INTO donhang (MaNN, MaNG, ngayDatHang, ngayGiaoHangDuKien, trangThai, tongTien) 
                VALUES ('$maNN', '$maNG', '$ngayDH', '$ngayGH', '0', '$tongTien')";
                if ($conn->query($insertDonHang) === TRUE) { $maDH = $conn->insert_id; }

                $insertChiTietDonHang = "INSERT INTO chitietdonhang (maDH, tenHang, loaiHang, soLuong, canNang, tienThuHo, ghiChu) 
                VALUES ('$maDH', '$tenHang', '$loaiHang', '$soLuong', '$canNang', '$tienThuHo', '$ghiChu')";
                if ($conn->query($insertChiTietDonHang) === TRUE) { $maCTDH = $conn->insert_id; }

                
                
            }

            $message = 'Thêm file thành công!';
            $messageType = 'success';
        } catch (PhpSpreadsheetException $e) {
            // Catch and handle PhpSpreadsheet-specific exceptions
            $message = 'Spreadsheet error: ' . $e->getMessage();
            $messageType = 'error';
        } catch (Exception $e) {
            // General error handling
            $message = 'Error importing file: ' . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        // Handle file upload errors
        $message = 'đã xảy ra lỗi.';
        $messageType = 'error';
    }
}

/**
 * Convert Excel date format to MySQL datetime format (YYYY-MM-DD HH:MM:SS).
 */
function formatToMySQLDateTime($excelDate) {
    $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
    $datetime = new DateTime($excelDate, $timezone);
    return $datetime->format('Y-m-d H:i:s');
}


// Fetch all senders and receivers
$senders = $nguoiGuiModel->getAllNguoiGui();
$receivers = $nguoiNhanModel->getAllNguoiNhan();
$employees = $employeeModel->getAllNhanVien();
// Search functionality
$searchQuery = isset($_GET['search']) ? $_GET['search'] : ''; // Get search query from GET
if ($searchQuery) {
    $donHangs = $donHangModel->searchDonHang($searchQuery); // Perform search
} else {
    $donHangs = $donHangModel->getAllDonHang(); // Fetch all orders if no search query
}

?>

<?php
include 'header.php';
include 'sidebar.php';
?>
    <div class="content mt-4">
        <h1 class="text-center">Quản Lý Đơn Hàng</h1>
    <!-- Search Form -->
    <form action="don-hang.php" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm đơn hàng..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button class="btn btn-primary" type="submit">Tìm Kiếm</button>
        </div>
    </form>
        <!-- Display message -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType; ?>" role="alert">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between">
                   <!-- Button to open the modal for adding a new order -->
            <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addOrderModal">Thêm Đơn Hàng Mới</button>
            <form action="phatDonHang.php" method="post"> 
                <button type="submit" class="btn btn-primary" name="phatDonHang">Phát đơn hàng cho nhân viên</button> 
            </form>
            <!-- Import Excel Form -->
            <form action="don-hang.php" method="POST" enctype="multipart/form-data" class="mb-3">
                <div class="input-group">
                    <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls" required>
                    <button class="btn btn-primary" type="submit" name="action" value="import">Import Excel</button>
                </div>
            </form>
        </div>

        <!-- Modal for adding new order -->
        <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">Thêm Đơn Hàng Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="don-hang.php" method="POST">
                            <input type="hidden" name="action" value="add">
                            <div class="mb-3">
                                <label for="ma_nn" class="form-label">Người Gửi:</label>
                                <select name="ma_nn" class="form-control" required>
                                    <?php foreach ($senders as $sender): ?>
                                        <option value="<?= $sender['maNG']; ?>"><?= $sender['tenNG']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ma_ng" class="form-label">Người Nhận:</label>
                                <select name="ma_ng" class="form-control" required>
                                    <?php foreach ($receivers as $receiver): ?>
                                        <option value="<?= $receiver['maNN']; ?>"><?= $receiver['tenNN']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ngay_dat_hang" class="form-label">Ngày Đặt Hàng:</label>
                                <input type="date" name="ngay_dat_hang" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="ngay_giao_hang_du_kien" class="form-label">Ngày Giao Hàng Dự Kiến:</label>
                                <input type="date" name="ngay_giao_hang_du_kien" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tong_tien" class="form-label">Tổng Tiền:</label>
                                <input type="text" name="tong_tien" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="ten_hang" class="form-label">Tên hàng:</label>
                                <input type="text" name="ten_hang" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="can_nang" class="form-label">Cân nặng (kg):</label>
                                <input type="text" name="can_nang" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="tien_thu_ho" class="form-label">Tiền thu hộ:</label>
                                <input type="text" name="tien_thu_ho" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="so_luong" class="form-label">Số lượng:</label>
                                <input type="text" name="so_luong" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="ghi_chu" class="form-label">Ghi chú:</label>
                                <input type="text" name="ghi_chu" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="loai_hang" class="form-label">Loại hàng:</label>
                                <select name="loai_hang" class="form-control" required>
                                    <option value="nặng">Nặng</option>
                                    <option value="nhẹ">Nhẹ</option>
                                    <option value="cồng kềnh">Cồng kềnh</option>
                                    <option value="dễ vỡ">Dễ vỡ</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm Đơn Hàng</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Tên người nhận</th>
                    <th>Tên người gửi</th>
                    <th>Nhân viên phụ trách</th>
                    <th>Ngày Đặt Hàng</th>
                    <th>Ngày giao hàng dự kiến</th>
                    <th>Trạng Thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donHangs as $donHang): ?>
                    <tr>
                        <td><?= $donHang['maDH']; ?></td> 
                        <td><?= $donHang['tenNN']; ?></td> 
                        <td><?= $donHang['tenNG']; ?></td> 
                        <td><?= $donHang['maNVPhuTrach']; ?></td> 
                        <td><?= $donHang['ngayDatHang']; ?></td> 
                        <td><?= $donHang['ngayGiaoHangDuKien']; ?></td>
                        <td>
                            <?= $donHang['trangThai'] == 0 ? 'Đang xử lý' : 'Hoàn thành'; ?>
                        </td>

                        <td>
                            <!-- Button to open the modal for updating order -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal<?= $donHang['maDH']; ?>">Sửa</button>

                            <!-- Edit Order Modal -->
                            <div class="modal fade" id="editOrderModal<?= $donHang['maDH']; ?>" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editOrderModalLabel">Chỉnh Sửa Đơn Hàng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="don-hang.php" method="POST">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="MaDonHang" value="<?= $donHang['maDH']; ?>"> 
                                                <div class="mb-3">
                                                    <label for="TrangThaiEdit" class="form-label">Trạng Thái:</label> 
                                                    <select name="TrangThaiEdit" id="TrangThai" class="form-control" required>
                                                        <option value="0" <?= $donHang['trangThai'] == 0 ? 'selected' : ''; ?>>Đang xử lý</option>
                                                        <option value="1" <?= $donHang['trangThai'] == 1 ? 'selected' : ''; ?>>Hoàn thành</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ma_nvEdit" class="form-label">Nhân Viên Phụ Trách:</label>
                                                    <select name="ma_nvEdit" class="form-control" required>
                                                        <?php foreach ($employees as $employee): ?>
                                                            <option value="<?= $employee['maNV']; ?>" 
                                                                <?= $employee['maNV'] == $donHang['maNVPhuTrach'] ? 'selected' : ''; ?>>
                                                                <?= $employee['tenNV']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            <!-- Delete Order Modal -->
                            <div class="modal fade" id="deleteOrderModal<?= $donHang['maDH']; ?>" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteOrderModalLabel">Xóa Đơn Hàng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn muốn xóa đơn hàng này?</p>
                                            <form action="don-hang.php" method="POST">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="ma_dh" value="<?= $donHang['maDH']; ?>">
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<?php
include 'footer.php';
?>
