<?php
session_start();

// Kiểm tra quyền truy cập (chỉ admin mới được truy cập)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: dangnhap.php");
    exit;
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'gtpt', 7307);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý xóa tài khoản
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: tai_khoan.php");
    exit;
}

// Lấy danh sách tài khoản
$result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tài khoản</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #007bff;
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .action-buttons a:hover {
            background-color: #218838;
        }

        .action-buttons .delete {
            background-color: #dc3545;
        }

        .action-buttons .delete:hover {
            background-color: #c82333;
        }

        .action-buttons .edit {
            background-color: #ffc107;
        }

        .action-buttons .edit:hover {
            background-color: #e0a800;
        }

        .text-center {
            text-align: center;
        }

    </style>
</head>
<body>
    <header>
        Quản lý tài khoản - Admin
    </header>
    <div class="container">
        <h2>Danh sách tài khoản</h2>
        <div class="text-center">
            <a href="them_taikhoan.php" class="btn">Thêm tài khoản</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh đại diện</th>
                    <th>Tên</th>
                    <th>Tài khoản</th>
                    <th>Email</th>
                    <th>Mật khẩu</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if (!empty($row['avatar'])): ?>
                                <img src="storage/uploads/<?= $row['avatar'] ?>" class="avatar" alt="Avatar">
                            <?php else: ?>
                                <img src="storage/uploads/default-avatar.jpg" class="avatar" alt="Avatar">
                            <?php endif; ?>
                        </td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['password'] ?></td>
                        <td><?= $row['role'] == 1 ? 'Admin' : 'Người dùng' ?></td>
                        <td class="action-buttons">
                            <a href="sua_taikhoan.php?id=<?= $row['id'] ?>" class="edit">Sửa</a>
                            <a href="tai_khoan.php?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="delete">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-center" style="margin-top: 30px;">
            <a href="admin.php" class="btn">Quay lại Trang Admin</a>
        </div>
    </div>
</body>
</html>
