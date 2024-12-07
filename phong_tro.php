<?php
include 'db.php';

// Lấy danh sách phòng trọ
// $sql = "SELECT m.*, m.area 
//         FROM motel m";
// $sql = "SELECT m.*, d.name as district_name 
//         FROM motel m
//         LEFT JOIN districts d ON m.district_id = d.id";
// $result = $conn->query($sql);
$sql = "SELECT m.*, d.name as district_name, m.images 
        FROM motel m
        LEFT JOIN districts d ON m.district_id = d.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách phòng trọ</title>
</head>
<body>
    <h2>Danh sách phòng trọ</h2>
    <a href="them_phongtro.php">Thêm phòng trọ</a>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Diện tích phòng (m<sup>2</sup>)</th>
            <th>Địa chỉ</th>
            <th>Khu vực</th>
            <th>Ảnh phòng trọ</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['area'] ?> m<sup>2</sup></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['district_name'] ?></td>
                <td>
                    <?php if ($row['images']): ?>
                        <img src="uploads/<?= $row['images'] ?>" alt="Ảnh phòng trọ" width="100" height="100">
                    <?php else: ?>
                        <p>Chưa có ảnh</p>
                    <?php endif; ?>
                </td>
                <td><?= $row['approve'] == 1 ? 'Duyệt' : 'Chưa duyệt' ?></td>
                <td>
                    <a href="sua_phongtro.php?id=<?= $row['id'] ?>">Sửa</a> | 
                    <a href="xoa_phongtro.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a> | 
                    <a href="trang_thai_phongtro.php?id=<?= $row['id'] ?>">Đổi trạng thái</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="admin.php">Quay lại Trang Admin</a>
</body>
</html>

