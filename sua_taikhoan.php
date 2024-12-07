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
    $avatar = $user['avatar']; // Giữ nguyên ảnh hiện tại nếu không cập nhật

    // Xử lý cập nhật ảnh đại diện
    if (!empty($_FILES['avatar']['name'])) {
        $targetDir = "storage/uploads/"; // Thư mục lưu trữ file
        $fileName = time() . '-' . basename($_FILES['avatar']['name']); // Tạo tên file duy nhất
        $targetFile = $targetDir . $fileName;
    
        // Kiểm tra định dạng file hợp lệ
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['avatar']['tmp_name']);
    
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                $avatar = $fileName; // Chỉ lưu tên file vào cơ sở dữ liệu
            } else {
                echo "Không thể tải ảnh lên thư mục.";
                exit;
            }
        } else {
            echo "Định dạng ảnh không được hỗ trợ. Chỉ chấp nhận JPEG, PNG, GIF.";
            exit;
        }
    }
    
    

    // Nếu có nhập mật khẩu mới, cập nhật mật khẩu
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, password = ?, role = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("ssssiis", $name, $username, $email, $password, $role, $avatar, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, role = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("sssisi", $name, $username, $email, $role, $avatar, $id);
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
    <h2>Sửa thông tin tài khoản</h2>
    <form action="sua_taikhoan.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
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

        <label>Ảnh đại diện:</label>
        <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(event)"><br><br>

        <div class="avatar-preview">
        <img id="avatar-preview" src="storage/uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar hiện tại">


        </div><br>

        <button type="submit">Lưu thay đổi</button>
    </form>

    <a href="tai_khoan.php">Quay lại</a>
</body>
</html>


