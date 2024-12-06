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

// Kiểm tra xem ID có được truyền vào hay không
if (!isset($_GET['id'])) {
    header("Location: tai_khoan.php");
    exit;
}

$id = $_GET['id'];

// Lấy thông tin tài khoản từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Tài khoản không tồn tại!";
    exit;
}

$user = $result->fetch_assoc();

// Xử lý cập nhật thông tin tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Nếu có nhập mật khẩu mới, cập nhật mật khẩu
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $name, $username, $email, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssii", $name, $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: tai_khoan.php");
        exit;
    } else {
        echo "Cập nhật thất bại: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa tài khoản</title>
</head>
<body>
    <h2>Sửa thông tin tài khoản</h2>
    <form action="sua_taikhoan.php?id=<?= $id ?>" method="POST">
        <label>Tên:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>

        <label>Tài khoản:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label>Mật khẩu (để trống nếu không đổi):</label>
        <input type="password" name="password"><br><br>

        <label>Vai trò:</label>
        <select name="role" required>
            <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
            <option value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>Người dùng</option>
        </select><br><br>

        <button type="submit">Lưu thay đổi</button>
    </form>

    <a href="tai_khoan.php">Quay lại</a>
</body>
</html>
