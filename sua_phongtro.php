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

    // Sửa câu lệnh SQL ở đây
    $stmt = $conn->prepare("UPDATE motel SET title = ?, description = ?, price = ?, address = ?, area = ?, district_id = ? WHERE ID = ?");
    $stmt->bind_param("ssdssii", $title, $description, $price, $address, $area, $district_id, $id);

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
    <form action="sua_phongtro.php?id=<?= $id ?>" method="POST">
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

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>
