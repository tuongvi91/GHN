<?php
    session_start();
    session_destroy();
    echo "<script> 
        alert('Đăng xuất thành công');
        window.location='login.php'	;	
         </script>";	
?>