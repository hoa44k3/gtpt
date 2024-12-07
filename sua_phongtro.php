<?php
include 'db.php';

// Lấy thông tin phòng trọ
$id = $_GET['id'];
$sql = "SELECT * FROM motel WHERE ID = $id";
$result = $conn->query($sql);
$motel = $result->fetch_assoc();

// Lấy danh sách khu vực
$sqlDistricts = "SELECT * FROM districts";
$resultDistricts = $conn->query($sqlDistricts);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $district_id = $_POST['district_id'];
    $images = $motel['images']; // Giữ ảnh cũ nếu không có ảnh mới

    // Kiểm tra nếu có ảnh mới
    if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";  // Thư mục lưu trữ ảnh
        $fileName = basename($_FILES["images"]["name"]);
        $targetFile = $targetDir . $fileName;

        // Kiểm tra loại tệp
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
                $images = $fileName; // Lưu tên ảnh mới vào biến
            } else {
                echo "Có lỗi khi tải ảnh lên.";
                exit;
            }
        } else {
            echo "Chỉ chấp nhận các định dạng ảnh JPG, JPEG, PNG, GIF.";
            exit;
        }
    }

    // Sửa câu lệnh SQL
    $stmt = $conn->prepare("UPDATE motel SET title = ?, description = ?, price = ?, address = ?, area = ?, district_id = ?, images = ? WHERE ID = ?");
    $stmt->bind_param("ssdssisi", $title, $description, $price, $address, $area, $district_id, $images, $id);

    if ($stmt->execute()) {
        header("Location: phong_tro.php");
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
    <title>Sửa phòng trọ</title>
</head>
<body>
    <h2>Sửa phòng trọ</h2>
    <form action="sua_phongtro.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <label>Tiêu đề:</label>
        <input type="text" name="title" value="<?= $motel['title'] ?>" required><br><br>

        <label>Mô tả:</label>
        <textarea name="description" required><?= $motel['description'] ?></textarea><br><br>

        <label>Giá:</label>
        <input type="number" name="price" value="<?= $motel['price'] ?>" required><br><br>

        <label>Địa chỉ:</label>
        <input type="text" name="address" value="<?= $motel['address'] ?>" required><br><br>

        <label>Diện tích:</label>
        <input type="number" name="area" value="<?= $motel['area'] ?>" required step="any"><br><br>

        <label>Khu vực:</label>
        <select name="district_id" required>
            <?php while ($row = $resultDistricts->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $motel['district_id'] == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <!-- Hiển thị ảnh hiện tại nếu có -->
        <label>Ảnh hiện tại:</label><br>
        <?php if ($motel['images']): ?>
            <img src="uploads/<?= $motel['images'] ?>" alt="Ảnh phòng trọ" width="100"><br>
        <?php else: ?>
            <p>Không có ảnh hiện tại.</p>
        <?php endif; ?><br><br>

        <!-- Trường tải lên ảnh mới -->
        <label>Chọn ảnh mới (nếu có):</label>
        <input type="file" name="images" accept="image/*"><br><br>

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>

