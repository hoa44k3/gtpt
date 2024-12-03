<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'gtpt',7307);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login']; // Username hoặc Email
    $password = $_POST['password'];

    // Tìm tài khoản trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT id, name, username, password, role, avatar FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin đăng nhập
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['avatar'] = $user['avatar'];

            header("Location: phongtro.php"); // Điều hướng đến danh sách phòng trọ
            exit;
        } else {
            echo "Sai mật khẩu!";
        }
    } else {
        echo "Tài khoản không tồn tại!";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form action="dangnhap.php" method="POST">
        <label>Tài khoản hoặc Email:</label>
        <input type="text" name="login" required><br><br>

        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login_btn">Đăng nhập</button>
    </form>
</body>
</html>

