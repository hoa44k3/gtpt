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
    
    // Xử lý tải ảnh lên
    $avatar = null;
    if (!empty($_FILES['avatar']['name'])) {
        $targetDir = "storage/uploads/";
        $fileName = time() . '-' . basename($_FILES['avatar']['name']); // Thêm thời gian để tránh trùng tên
        $targetFile = $targetDir . $fileName;

        // Kiểm tra và di chuyển file tải lên
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $avatar = $fileName; // Lưu tên file vào cơ sở dữ liệu
        } else {
            echo "Tải lên ảnh thất bại.";
            exit;
        }
    }

    // Chuẩn bị và thực thi câu lệnh SQL
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role, avatar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $username, $email, $password, $role, $avatar);

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
    <style>
        .avatar-preview {
            margin-top: 10px;
        }
        .avatar-preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    <script>
        function previewAvatar(event) {
            const preview = document.getElementById('avatar-preview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <h2>Thêm tài khoản</h2>
    <form action="them_taikhoan.php" method="POST" enctype="multipart/form-data">
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

        <label>Ảnh đại diện:</label>
        <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(event)"><br><br>

        <div class="avatar-preview">
            <img id="avatar-preview" src="storage/uploads/default-avatar.jpg" alt="Avatar Preview">
        </div><br>

        <button type="submit">Thêm</button>
    </form>

    <a href="tai_khoan.php">Quay lại</a>
</body>
</html>

