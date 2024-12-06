<?php
include 'db.php';

// Lấy danh sách khu vực
$sqlDistricts = "SELECT * FROM districts";
$resultDistricts = $conn->query($sqlDistricts);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khu vực</title>
</head>
<body>
    <h2>Danh sách khu vực</h2>
    <a href="them_khuvuc.php">Thêm khu vực mới</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên khu vực</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultDistricts->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td>
                        <!-- Chỉnh sửa khu vực -->
                        <a href="sua_khuvuc.php?id=<?= $row['id'] ?>">Sửa</a>
                        <!-- Xóa khu vực -->
                        <a href="xoa_khuvuc.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <a href="admin.php">Quay lại admin</a>
</body>
</html>
