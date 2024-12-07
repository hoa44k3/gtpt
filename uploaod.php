<?php
// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    // Lấy thông tin file tải lên
    $avatar = $_FILES['avatar'];
    $avatarName = $avatar['name'];  // Tên file
    $avatarTmpName = $avatar['tmp_name'];  // Tên tạm thời trên server
    $avatarError = $avatar['error'];  // Kiểm tra lỗi
    $avatarSize = $avatar['size'];  // Kích thước file

    // Kiểm tra nếu không có lỗi và file hợp lệ
    if ($avatarError === 0) {
        // Kiểm tra kích thước ảnh (ví dụ dưới 5MB)
        if ($avatarSize <= 5 * 1024 * 1024) {
            // Lấy phần mở rộng của file
            $avatarExt = pathinfo($avatarName, PATHINFO_EXTENSION);
            $avatarExt = strtolower($avatarExt);

            // Kiểm tra nếu phần mở rộng hợp lệ (ví dụ jpg, png)
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($avatarExt, $allowed)) {
                // Tạo tên file mới để tránh trùng lặp
                $newAvatarName = uniqid('', true) . "." . $avatarExt;

                // Đường dẫn lưu file vào thư mục uploads
                $uploadDir = 'storage/uploads/';
                $uploadPath = $uploadDir . $newAvatarName;

                // Di chuyển file từ thư mục tạm thời vào thư mục uploads
                if (move_uploaded_file($avatarTmpName, $uploadPath)) {
                    echo "Tải lên thành công!";

                    // Lưu tên ảnh vào cơ sở dữ liệu hoặc làm gì đó với ảnh
                    // Ví dụ: Lưu tên file vào cơ sở dữ liệu
                    // $conn->query("UPDATE users SET avatar = '$newAvatarName' WHERE id = $userId");
                } else {
                    echo "Lỗi khi tải ảnh lên!";
                }
            } else {
                echo "Định dạng file không hợp lệ! (Chỉ hỗ trợ jpg, jpeg, png, gif)";
            }
        } else {
            echo "Kích thước ảnh quá lớn! (Tối đa 5MB)";
        }
    } else {
        echo "Lỗi khi tải ảnh lên!";
    }
}
?>

<!-- Form để tải ảnh lên -->
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label for="avatar">Chọn ảnh đại diện:</label>
    <input type="file" name="avatar" id="avatar" required>
    <button type="submit">Tải lên</button>
</form>
