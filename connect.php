<?php
    $conn = mysqli_connect ("localhost", "root", "", "ghntest") or die ("!!");
    mysqli_query($conn,"SET NAMES 'utf8'");
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>