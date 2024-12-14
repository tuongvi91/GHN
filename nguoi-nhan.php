<?php
// Bao gồm mô hình cần thiết
require_once 'models/NguoiNhanModel.php';

// Khởi tạo mô hình
$nguoiNhanModel = new NguoiNhanModel();

$response = '';

// Xử lý yêu cầu POST để thêm hoặc cập nhật người gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Thêm người nhận mới
            $ten_nn = $_POST['ten_nn'];
            $sdt = $_POST['sdt'];
            $so_nha = $_POST['so_nha'];
            $ten_duong = $_POST['ten_duong'];
            $phuong_xa = $_POST['phuong_xa'];
            $quan_huyen = $_POST['quan_huyen'];
            $tinh_thanh_pho = $_POST['tinh_thanh_pho'];
            
        $response = $nguoiNhanModel->addNguoiNhan($ten_nn, $sdt, $so_nha, $ten_duong, $phuong_xa, $quan_huyen, $tinh_thanh_pho) ? 'Thêm người nhận thành công!' : 'Có lỗi xảy ra khi thêm người nhận!';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Chỉnh sửa người gửi hiện có
        $ma_nn = $_POST['ma_nn'];
        $ten_nn = $_POST['ten_nn'];
        $sdt = $_POST['sdt'];
        $so_nha = $_POST['so_nha'];
        $ten_duong = $_POST['ten_duong'];
        $phuong_xa = $_POST['phuong_xa'];
        $quan_huyen = $_POST['quan_huyen'];
        $tinh_thanh_pho = $_POST['tinh_thanh_pho'];
        $response = $nguoiNhanModel->updateNguoiNhan($ma_nn, $ten_nn, $sdt, $so_nha, $ten_duong, $phuong_xa, $quan_huyen, $tinh_thanh_pho) ? 'Cập nhật người gửi thành công!' : 'Có lỗi xảy ra khi cập nhật người gửi!';
    }
}

// Xử lý yêu cầu GET để xóa người gửi
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['ma_nn'])) {
    $ma_nn = $_GET['ma_nn'];
    $response = $nguoiNhanModel->deleteNguoiNhan($ma_nn) ? 'Xóa người gửi thành công!' : 'Có lỗi xảy ra khi xóa người gửi!';
}

// Lấy danh sách người gửi
$nguoiNhans = $nguoiNhanModel->getAllNguoiNhan();

include 'header.php';
include 'sidebar.php';
?>


<div class="content mt-4">
    <h1 class="text-center">Quản Lý Người Nhận</h1>

    <!-- Hiển thị thông báo phản hồi -->
    <?php if ($response != ''): ?>
    <div class="alert alert-info">
        <?php echo $response; ?>
    </div>
<?php endif; ?>

    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addEditModal" data-action="add">Thêm người nhận</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Người Nhận</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nguoiNhans as $nguoiNhan): ?>
                <tr>
                <td><?php echo $nguoiNhan['tenNN']; ?></td>
                    <td><?php echo $nguoiNhan['sdt']; ?></td>
                    <td><?php echo $nguoiNhan['soNha'] . ' ' . $nguoiNhan['tenDuong'] . ', ' . $nguoiNhan['phuongXa'] . ', ' . $nguoiNhan['quanHuyen'] . ', ' . $nguoiNhan['tinhThanhpho']; ?></td>
                    <td>
                        <button class="btn btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#addEditModal" 
                        data-bs-id="<?php echo $nguoiNhan['maNN']; ?>"
                        data-bs-ten="<?php echo $nguoiNhan['tenNN']; ?>"
                        data-bs-sdt="<?php echo $nguoiNhan['sdt']; ?>"
                        data-bs-so_nha="<?php echo $nguoiNhan['soNha']; ?>"
                        data-bs-ten_duong="<?php echo $nguoiNhan['tenDuong']; ?>"
                        data-bs-phuong_xa="<?php echo $nguoiNhan['phuongXa']; ?>"
                        data-bs-quan_huyen="<?php echo $nguoiNhan['quanHuyen']; ?>"
                        data-bs-tinh_thanh_pho="<?php echo $nguoiNhan['tinhThanhpho']; ?>">
                     Sửa</button>
                        
                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<!-- Modal Thêm/Sửa Người Gửi -->
<div class="modal fade" id="addEditModal" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalLabel">Thêm/Sửa Người Nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="" method="POST" id="addEditForm">
                <input type="hidden" name="ma_nn" id="ma_nn">
                <input type="hidden" name="action" id="action" value="create">
                <div class="mb-3">
                    <label for="ten_nn" class="form-label">Tên Người Nhận</label>
                    <input type="text" class="form-control" name="ten_nn" id="ten_nn"  required>
                </div>
                <div class="mb-3">
                    <label for="sdt" class="form-label">Số Điện Thoại</label>
                    <input type="text" class="form-control" name="sdt" id="sdt" required>
                </div>
                <div class="mb-3">
                    <label for="so_nha" class="form-label">Số Nhà</label>
                    <input type="text" class="form-control" name="so_nha" id="so_nha" required>
                </div>
                <div class="mb-3">
                    <label for="ten_duong" class="form-label">Tên Đường</label>
                    <input type="text" class="form-control" name="ten_duong" id="ten_duong" required>
                </div>
                <div class="mb-3">
                    <label for="phuong_xa" class="form-label">Phường/Xã</label>
                    <input type="text" class="form-control" name="phuong_xa" id="phuong_xa" required>
                </div>
                <div class="mb-3">
                    <label for="quan_huyen" class="form-label">Quận/Huyện</label>
                    <input type="text" class="form-control" name="quan_huyen" id="quan_huyen" required>
                </div>
                <div class="mb-3">
                    <label for="tinh_thanh_pho" class="form-label">Tỉnh/Thành Phố</label>
                    <input type="text" class="form-control" name="tinh_thanh_pho" id="tinh_thanh_pho" required>
                </div>
                <button type="submit" class="btn btn-success">Lưu</button>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác Nhận Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa người nhận này không?
            </div>
            <div class="modal-footer">
                <form action="" method="GET">
                    <input type="hidden" id="delete_ma_nn" name="ma_nn" value="">
                    <input type="hidden" name="action" value="delete">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// Thiết lập dữ liệu người gửi cho thêm/sửa
const addEditModal = document.getElementById('addEditModal');
addEditModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const action = button.getAttribute('data-action');
    const modalTitle = addEditModal.querySelector('.modal-title');
    const form = document.getElementById('addEditForm');

    if (action == 'edit') {
        modalTitle.textContent = 'Chỉnh Sửa Người Nhận';
        const ma_nn = button.getAttribute('data-bs-id');
        const ten_nn = button.getAttribute('data-bs-ten');
        const sdt = button.getAttribute('data-bs-sdt');
        const so_nha = button.getAttribute('data-bs-so_nha');
        const ten_duong = button.getAttribute('data-bs-');
        const phuong_xa = button.getAttribute('data-bs-phuong_xa');
        const quan_huyen = button.getAttribute('data-bs-quan_huyen');
        const tinh_thanh_pho = button.getAttribute('data-bs-tinh_thanh_pho');

        form.action = '';
        form.querySelector('#ma_nn').value = ma_ng;
        form.querySelector('#ten_nn').value = ten_ng;
        form.querySelector('#sdt').value = sdt;
        form.querySelector('#so_nha').value = cccd;
        form.querySelector('#ten_duong').value = ten_duong;
        form.querySelector('#phuong_xa').value = phuong_xa;
        form.querySelector('#quan_huyen').value = quan_huyen;
        form.querySelector('#tinh_thanh_pho').value = tinh_thanh_pho;
        form.querySelector('#action').value = 'edit';
    } else {
        modalTitle.textContent = 'Thêm Người Nhận';
        form.querySelector('#ma_nn').value = '';
        form.querySelector('#ten_nn').value = '';
        form.querySelector('#sdt').value = '';
        form.querySelector('#so_nha').value = '';
        form.querySelector('#ten_duong').value ='';
        form.querySelector('#phuong_xa').value = '';
        form.querySelector('#quan_huyen').value = '';
        form.querySelector('#tinh_thanh_pho').value = '';
        form.querySelector('#action').value = 'add';
    }
});

// Thiết lập mã người gửi trong modal xóa
const deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const ma_ng = button.getAttribute('data-ma-nn');
    document.getElementById('delete_ma_nn').value = ma_nn;
});
</script>
