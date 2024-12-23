<?php
session_start();

// Kiểm tra quyền truy cập (chỉ admin mới được truy cập)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) { // 1 là admin
    header("Location: dangnhap.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        h2 {
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .admin-info img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
        .menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .menu a {
            display: block;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .logout {
            margin-top: 30px;
            text-align: center;
        }
        .logout a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h2>Trang Quản Trị Admin</h2>
    </header>
    <div class="container">
        <!-- Hiển thị ảnh và tên admin -->
        <div class="admin-info">
            <img src="storage/uploads/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Ảnh đại diện admin">
            <p>Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
        </div>
        
        <!-- Menu các liên kết quản lý -->
        <div class="menu">
            <a href="tai_khoan.php">Quản lý tài khoản</a>
            <a href="phong_tro.php">Quản lý phòng trọ</a>
            <a href="danh_sach_khuvuc.php">Quản lý khu vực</a>
            <a href="thong_ke_tindang.php">Thống kê báo cáo</a>
        </div>
        
        <!-- Đăng xuất -->
        <div class="logout">
            <a href="dangxuat.php">Đăng xuất</a>
        </div>
    </div>
</body>
</html>
