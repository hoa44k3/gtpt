<?php
include 'db.php';

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
    $approve = 0; // Mặc định chưa duyệt
    $images = '';

    // Kiểm tra xem giá trị area có hợp lệ không
    if (empty($area) || !is_numeric($area) || $area <= 0) {
        echo "Diện tích phòng trọ không hợp lệ.";
        exit;
    }

    // Kiểm tra xem có ảnh hay không và xử lý ảnh
    if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";  // Thư mục lưu trữ ảnh
        $fileName = basename($_FILES["images"]["name"]);
        $targetFile = $targetDir . $fileName;
        
        // Kiểm tra loại tệp
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFile)) {
                $images = $fileName; // Lưu tên ảnh vào biến
            } else {
                echo "Có lỗi khi tải ảnh lên.";
                exit;
            }
        } else {
            echo "Chỉ chấp nhận các định dạng ảnh JPG, JPEG, PNG, GIF.";
            exit;
        }
    }

    // Chuẩn bị câu lệnh SQL để chèn dữ liệu vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO motel (title, description, price, address, area, district_id, approve, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsdiss", $title, $description, $price, $address, $area, $district_id, $approve, $images);

    // Thực hiện câu lệnh
    if ($stmt->execute()) {
        header("Location: phong_tro.php");
        exit;
    } else {
        echo "Thêm phòng trọ thất bại: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm phòng trọ</title>
</head>
<body>
    <h2>Thêm phòng trọ</h2>
    <form action="them_phongtro.php" method="POST" enctype="multipart/form-data">
        <label>Tiêu đề:</label>
        <input type="text" name="title" required><br><br>

        <label>Mô tả:</label>
        <textarea name="description" required></textarea><br><br>

        <label>Giá:</label>
        <input type="number" name="price" required><br><br>

        <label>Địa chỉ:</label>
        <input type="text" name="address" required><br><br>

        <label>Diện tích phòng trọ:</label>
        <input type="number" name="area" required><br><br>

        <label>Khu vực:</label>
        <select name="district_id" required>
            <?php while ($row = $resultDistricts->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Ảnh phòng trọ:</label>
        <input type="file" name="images" accept="image/*"><br><br>

        <button type="submit">Thêm</button>
    </form>
</body>
</html>

