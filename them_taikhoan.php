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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $username, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: tai_khoan.php");
        exit;
    } else {
        echo "Thêm tài khoản thất bại: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm tài khoản</title>
</head>
<body>
    <h2>Thêm tài khoản</h2>
    <form action="qly_themtaikhoan.php" method="POST">
        <label>Tên:</label>
        <input type="text" name="name" required><br><br>

        <label>Tài khoản:</label>
        <input type="text" name="username" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <label>Vai trò:</label>
        <select name="role" required>
            <option value="1">Admin</option>
            <option value="2">Người dùng</option>
        </select><br><br>

        <button type="submit">Thêm</button>
    </form>

    <a href="tai_khoan.php">Quay lại</a>
</body>
</html>
