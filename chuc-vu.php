<?php
// Include the ChucVuModel class
require_once 'models/ChucVuModel.php';

// Create an instance of the ChucVuModel class
$chucVuModel = new ChucVuModel();

// Handle form submissions (add, update, delete)
// Add or update a position
if (isset($_POST['submit_chuc_vu'])) {
    $ma_cv = $_POST['ma_cv'] ?? null;
    $ten_cv = $_POST['ten_cv'];

    // Check if the position name already exists
    if ($chucVuModel->checkTenCvExists($ten_cv, $ma_cv)) {
        $message = "Chức vụ này đã tồn tại!";
    } else {
        if ($ma_cv) {
            // Update existing position
            if ($chucVuModel->updateChucVu($ma_cv, $ten_cv)) {
                $message = "Chức vụ đã được cập nhật thành công!";
            } else {
                $message = "Có lỗi khi cập nhật chức vụ.";
            }
        } else {
            // Add new position
            if ($chucVuModel->addChucVu($ten_cv)) {
                $message = "Chức vụ đã được thêm thành công!";
            } else {
                $message = "Có lỗi khi thêm chức vụ.";
            }
        }
    }
}

// Delete a position
if (isset($_POST['delete_chuc_vu'])) {
    $ma_cv = $_POST['ma_cv'];
    if ($chucVuModel->deleteChucVu($ma_cv)) {
        $message = "Chức vụ đã được xóa thành công!";
    } else {
        $message = "Có lỗi khi xóa chức vụ.";
    }
}

// Fetch all positions
$chucVuList = $chucVuModel->getAllChucVu();
?>

<?php
include 'header.php';
include 'sidebar.php';
?>
<div class="content">
    <h1 class="text-center">Quản lý Chức Vụ</h1>

    <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>

    <!-- Add/Edit Chuc Vu Form -->
    <button class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEditModal">Thêm Chức Vụ</button>

    <table class="table table-bordered table-sm mt-2">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Chức Vụ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chucVuList as $index => $chucVu): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($chucVu['tenCV']); ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEditModal" data-id="<?php echo $chucVu['maCV']; ?>" data-name="<?php echo htmlspecialchars($chucVu['tenCV']); ?>">Sửa</button>

                        <!-- Delete Button -->
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal<?php echo $chucVu['maCV']; ?>">Xóa</button>
                    </td>
                </tr>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirmDeleteModal<?php echo $chucVu['maCV']; ?>" tabindex="-1" aria-labelledby="confirmDeleteModalLabel<?php echo $chucVu['maCV']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDeleteModalLabel<?php echo $chucVu['maCV']; ?>">Xác Nhận Xóa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắc chắn muốn xóa chức vụ này không?</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="">
                                    <input type="hidden" name="ma_cv" value="<?php echo $chucVu['maCV']; ?>">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" name="delete_chuc_vu" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addEditModal" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalLabel">Thêm Chức Vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="ma_cv" id="ma_cv">
                    <div class="mb-3">
                        <label for="ten_cv" class="form-label">Tên Chức Vụ</label>
                        <input type="text" class="form-control form-control-sm" id="ten_cv" name="ten_cv" required>
                    </div>
                    <button type="submit" name="submit_chuc_vu" class="btn btn-sm btn-success">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
    // JavaScript to populate the form fields with values for editing
    const editButtons = document.querySelectorAll('button[data-bs-target="#addEditModal"]');
    const modal = document.getElementById('addEditModal');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const maCv = this.getAttribute('data-id');
            const tenCv = this.getAttribute('data-name');
            document.getElementById('ma_cv').value = maCv;
            document.getElementById('ten_cv').value = tenCv;
            document.getElementById('addEditModalLabel').textContent = 'Sửa Chức Vụ'; // Change modal title to 'Sửa Chức Vụ'
            document.querySelector('button[type="submit"]').textContent = 'Cập Nhật'; // Change button text to 'Cập Nhật'
        });
    });

    // When the modal is opened for adding a new position, reset to 'Thêm Chức Vụ'
    const addButton = document.querySelector('button[data-bs-target="#addEditModal"]');
    addButton.addEventListener('click', function() {
        document.getElementById('addEditModalLabel').textContent = 'Thêm Chức Vụ'; // Set modal title to 'Thêm Chức Vụ'
        document.querySelector('button[type="submit"]').textContent = 'Lưu'; // Set button text to 'Lưu'
    });
</script>
