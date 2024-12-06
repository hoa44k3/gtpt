<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO districts (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        header("Location: danh_sach_khuvuc.php"); // Quay lại danh sách khu vực
        exit;
    } else {
        echo "Thêm khu vực thất bại: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khu vực</title>
</head>
<body>
    <h2>Thêm khu vực mới</h2>
    <form action="them_khuvuc.php" method="POST">
        <label>Tên khu vực:</label>
        <input type="text" name="name" required><br><br>
        <button type="submit">Thêm</button>
    </form>
</body>
</html>
