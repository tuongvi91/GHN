<?php
// Bao gồm mô hình cần thiết
require_once 'models/NguoiGuiModel.php';

// Khởi tạo mô hình
$nguoiGuiModel = new NguoiGuiModel();

$response = '';
$nguoiGuis = [];

// Xử lý yêu cầu POST để thêm hoặc cập nhật người gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Thêm người gửi mới
        $ten_ng = $_POST['ten_ng'];
        $sdt = $_POST['sdt'];
        $cccd = $_POST['cccd'];
        $dia_chi = $_POST['dia_chi'];
        $response = $nguoiGuiModel->addNguoiGui($ten_ng, $sdt, $cccd, $dia_chi) ? 'Thêm người gửi thành công!' : 'Có lỗi xảy ra khi thêm người gửi!';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Chỉnh sửa người gửi hiện có
        $ma_ng = $_POST['ma_ng'];
        $ten_ng = $_POST['ten_ng'];
        $sdt = $_POST['sdt'];
        $cccd = $_POST['cccd'];
        $dia_chi = $_POST['dia_chi'];
        $response = $nguoiGuiModel->updateNguoiGui($ma_ng, $ten_ng, $sdt, $cccd, $dia_chi) ? 'Cập nhật người gửi thành công!' : 'Có lỗi xảy ra khi cập nhật người gửi!';
    }
}

// Xử lý yêu cầu GET để xóa người gửi
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['ma_ng'])) {
    $ma_ng = $_GET['ma_ng'];
    $response = $nguoiGuiModel->deleteNguoiGui($ma_ng) ? 'Xóa người gửi thành công!' : 'Có lỗi xảy ra khi xóa người gửi!';
}

// Lấy danh sách người gửi
$nguoiGuis = $nguoiGuiModel->getAllNguoiGui();

include 'header.php';
include 'sidebar.php';
?>


<div class="content mt-4">
    <h1 class="text-center">Quản Lý Người Gửi</h1>

    <!-- Hiển thị thông báo phản hồi -->
    <?php if ($response != ''): ?>
    <div class="alert alert-info">
        <?php echo $response; ?>
    </div>
<?php endif; ?>

    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addEditModal" data-action="add">Thêm Người Gửi</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Số Điện Thoại</th>
                <th>CCCD</th>
                <th>Địa Chỉ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nguoiGuis as $nguoiGui): ?>
                <tr>
                    <td><?= $nguoiGui['maNG'] ?></td>
                    <td><?= $nguoiGui['tenNG'] ?></td>
                    <td><?= $nguoiGui['sdt'] ?></td>
                    <td><?= $nguoiGui['CCCD'] ?></td>
                    <td><?= $nguoiGui['diaChi'] ?></td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEditModal" 
                            data-action="edit" data-ma-ng="<?= $nguoiGui['maNG'] ?>" 
                            data-ten-ng="<?= $nguoiGui['tenNG'] ?>" data-sdt="<?= $nguoiGui['sdt'] ?>" 
                            data-cccd="<?= $nguoiGui['CCCD'] ?>" data-dia-chi="<?= $nguoiGui['diaChi'] ?>">Sửa</button>
                        
                        
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
                <h5 class="modal-title" id="addEditModalLabel">Thêm/Sửa Người Gửi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditForm" method="POST">
                    <input type="hidden" id="ma_ng" name="ma_ng">
                    <input type="hidden" name="action" id="action">
                    <div class="mb-3">
                        <label for="ten_ng" class="form-label">Tên</label>
                        <input type="text" class="form-control" id="ten_ng" name="ten_ng" required>
                    </div>
                    <div class="mb-3">
                        <label for="sdt" class="form-label">Số Điện Thoại</label>
                        <input type="text" class="form-control" id="sdt" name="sdt" required>
                    </div>
                    <div class="mb-3">
                        <label for="cccd" class="form-label">CCCD</label>
                        <input type="text" class="form-control" id="cccd" name="cccd" required>
                    </div>
                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa Chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="dia_chi" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
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
                Bạn có chắc chắn muốn xóa người gửi này không?
            </div>
            <div class="modal-footer">
                <form action="" method="GET">
                    <input type="hidden" id="delete_ma_ng" name="ma_ng" value="">
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
        modalTitle.textContent = 'Chỉnh Sửa Người Gửi';
        const ma_ng = button.getAttribute('data-ma_ng');
        const ten_ng = button.getAttribute('data-ten_ng');
        const sdt = button.getAttribute('data-sdt');
        const cccd = button.getAttribute('data-cccd');
        const dia_chi = button.getAttribute('data-dia_chi');

        form.action = '';
        form.querySelector('#ma_ng').value = ma_ng;
        form.querySelector('#ten_ng').value = ten_ng;
        form.querySelector('#sdt').value = sdt;
        form.querySelector('#cccd').value = cccd;
        form.querySelector('#dia_chi').value = dia_chi;
        form.querySelector('#action').value = 'edit';
    } else {
        modalTitle.textContent = 'Thêm Người Gửi';
        form.querySelector('#ma_ng').value = '';
        form.querySelector('#ten_ng').value = '';
        form.querySelector('#sdt').value = '';
        form.querySelector('#cccd').value = '';
        form.querySelector('#dia_chi').value = '';
        form.querySelector('#action').value = 'add';
    }
});

// Thiết lập mã người gửi trong modal xóa
const deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const ma_ng = button.getAttribute('data-ma-ng');
    document.getElementById('delete_ma_ng').value = ma_ng;
});
</script>
