<?php
include 'db.php';

// Lấy ID khu vực từ URL
$id = $_GET['id'];

// Lấy thông tin khu vực từ cơ sở dữ liệu
$sql = "SELECT * FROM districts WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$district = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE districts SET Name = ? WHERE ID = ?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        header("Location: danh_sach_khuvuc.php"); // Quay lại danh sách khu vực
        exit;
    } else {
        echo "Sửa khu vực thất bại: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa khu vực</title>
</head>
<body>
    <h2>Sửa khu vực</h2>
    <form action="sua_khuvuc.php?id=<?= $id ?>" method="POST">
        <label>Tên khu vực:</label>
        <input type="text" name="name" value="<?= $district['name'] ?>" required><br><br>
        <button type="submit">Lưu </button>
    </form>
</body>
</html>
