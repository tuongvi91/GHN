<!-- header.php -->
<?php
// Start the session at the beginning of the file
session_start();
if (!isset($_SESSION['ma_nv']) || !isset($_SESSION['ten_nv'])) {
    header("Location: dang-nhap.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
    <title>Quản lý nhân viên</title>
</head>
<body>