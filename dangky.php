<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'gtpt', 7307);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu khớp
    if ($password !== $confirm_password) {
        echo "Mật khẩu không khớp!";
        exit;
    }

    // Kiểm tra trùng lặp username hoặc email
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        echo "Username hoặc Email đã tồn tại!";
        exit;
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Xử lý avatar
    $avatar = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['avatar']['type'], $allowed_types)) {
            if ($_FILES['avatar']['size'] <= 2 * 1024 * 1024) { // Giới hạn 2MB
                $avatar = 'uploads/' . uniqid() . '_' . basename($_FILES['avatar']['name']);
                move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
            } else {
                echo "Ảnh đại diện quá lớn, tối đa 2MB!";
                exit;
            }
        } else {
            echo "Định dạng ảnh không hợp lệ!";
            exit;
        }
    }

    // Thêm tài khoản vào cơ sở dữ liệu (role mặc định: 2 - người dùng)
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, phone, avatar, role) VALUES (?, ?, ?, ?, ?, ?, 2)");
    $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $phone, $avatar);

    if ($stmt->execute()) {
        echo "Đăng ký thành công!";
        header("Location: dangnhap.php");
        exit;
    } else {
        echo "Đăng ký thất bại: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>
    <form action="dangky.php" method="POST" enctype="multipart/form-data">
        <label>Họ và tên:</label>
        <input type="text" name="name" required><br><br>

        <label>Tài khoản:</label>
        <input type="text" name="username" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Số điện thoại:</label>
        <input type="text" name="phone" required><br><br>

        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <label>Nhập lại mật khẩu:</label>
        <input type="password" name="confirm_password" required><br><br>

        <label>Avatar:</label>
        <input type="file" name="avatar" accept="image/*"><br><br>

        <button type="submit" name="register">Đăng ký</button>
    </form>
</body>
</html>
