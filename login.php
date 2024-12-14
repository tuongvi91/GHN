<?php
session_start();
include 'connect.php';
?>
<html>
<head>
    <title> </title>
    <!-- <link rel="stylesheet" href="css/loginn.css"/> -->
</head>
<body>
<?php
if (isset($_POST['dangNhap'])) {
    $name = $_POST['taiKhoan'];
    $password = $_POST['matKhau'];

    $sql = "SELECT * FROM nhanvien WHERE (email='$name' or sdt='$name') AND matKhau='$password' and trangThaiTK=1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $maCV = $row['maCV'];

        if ($maCV == 1) {
            $_SESSION['tennguoidung'] = $row['tenNV'];
            $_SESSION['tdn'] = $row['maNV'];
            header("Location: donHangNV.php");
            exit();
        } elseif ($maCV == 2) {
            $_SESSION['ma_nv'] = $row['maNV']; 
            $_SESSION['ten_nv'] = $row['tenNV']; 
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid user role'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng'); window.history.back();</script>";
    }
    mysqli_close($conn);
}
?>

<div id="container">
        <!-- Phần bên trái -->
        <div id="left">
            <div id="text-overlay">
                <img id="logo" src="Hinhanh/logo1.png" alt="Giao Hàng Nhanh">
                <h2>Thiết kế cho giải pháp giao nhận hàng</h2>
                <p>Tốt nhất từ trước đến nay</p>
                <p><em>Nhanh hơn, rẻ hơn và thông minh hơn</em></p>
            </div>
        </div>

        <div id="right">
            <div id="login-form">
                <h1>Đăng nhập</h1>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group">
                        <label for="username">Tài khoản</label>
                        <input type="text" id="ipTaiKhoan" placeholder="Nhập số điện thoại/email" name="taiKhoan">
        
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="ipMatKhau" placeholder="Nhập mật khẩu" name="matKhau">
                    </div>
                    <input type="submit" id="ipSubmit" value="Đăng nhập" name="dangNhap" class="btn-submit">
                </form>
            </div>
        </div>   
    </div>
</body>
</html>

<style>
        /* Reset cơ bản */
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body, html {
        font-family: Arial, sans-serif;
        height: 100%;
    }
    
    /* Container chính */
    #container {
        display: flex;
        width: 100%;
        height: 100vh; /* Chiều cao toàn màn hình */
    }
    
    /* Phần bên trái */
    #left {
        width: 50%;
        background-image: 
            linear-gradient(to bottom, rgba(0, 0, 0, 0) 50%, rgba(0, 51, 102, 0.8) 100%), 
            url('Hinhanh/PXB_7050.png'); /* Thay 'your-image.jpg' bằng đường dẫn hình ảnh */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative; /* Để định vị nội dung chữ */
    }
    
    
    /* Nội dung chữ trên hình */
    #text-overlay {
        position: absolute;
        bottom: 10%;
        left: 10%;
        color: white;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Hiệu ứng bóng chữ */
    }
    
    #text-overlay h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }
    
    #text-overlay p {
        font-size: 16px;
        margin-bottom: 5px;
    }
    
    /* Logo GHN */
    #logo {
        width: 400px;
        height: 150px;
        margin-bottom: 20px;
        margin-left: 20px;
    }
    
    /* Phần bên phải */
    /* Phần bên phải */
    #right {
        width: 50%; /* Chiếm 50% màn hình */
        display: flex; /* Dùng flexbox để căn giữa */
        align-items: center; /* Căn giữa theo chiều dọc */
        justify-content: center; /* Căn giữa theo chiều ngang */
        background-color: #ffffff; /* Màu nền trắng */
        padding: 40px; /* Tạo khoảng cách xung quanh */
    }
    
    /* Khối form đăng nhập */
    #login-form {
        width: 100%; /* Chiếm toàn bộ chiều rộng */
        max-width: 400px; /* Giới hạn kích thước tối đa */
        text-align: center; /* Căn giữa nội dung */
        color: #333; /* Màu chữ mặc định */
    }
    
    /* Tiêu đề */
    #login-form h1 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #000; /* Màu chữ đậm */
    }
    
    /* Phụ đề */
    #login-form .subtitle {
        font-size: 16px;
        color: #f26822; /* Màu cam */
        margin-bottom: 30px;
    }
    
    /* Nhóm form */
    .form-group {
        position: relative;
        text-align: left;
        margin-bottom: 20px;
    }
    
    /* Nhãn */
    .form-group label {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
    }
    
    /* Input */
    .form-group input {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box; /* Đảm bảo padding không làm tăng chiều rộng */
    }
    
    
    /* Nút đăng nhập */
    .btn-submit {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        color: white;
        background-color: #f26822; /* Màu cam */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s;
    }
    
    /* Hiệu ứng hover nút đăng nhập */
    .btn-submit:hover {
        background-color: #d85616; /* Màu cam đậm hơn */
    }
</style>