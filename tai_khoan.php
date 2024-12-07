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
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <h2>Danh sách tài khoản</h2>
    <a href="them_taikhoan.php">Thêm tài khoản</a>
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
                    <td>
                        <a href="sua_taikhoan.php?id=<?= $row['id'] ?>">Sửa</a> | 
                        <a href="tai_khoan.php?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin.php">Quay lại Trang Admin</a>
</body>
</html>

