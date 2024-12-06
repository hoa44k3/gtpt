
<?php
include 'db.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Câu lệnh truy vấn tìm kiếm và sắp xếp
$sql = "SELECT m.*, d.Name as district_name 
        FROM Motel m
        LEFT JOIN Districts d ON m.district_id = d.ID
        WHERE m.title LIKE ? OR m.address LIKE ?
        ORDER BY $sortBy $order";

$stmt = $conn->prepare($sql);
$searchTerm = "%$keyword%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm phòng trọ</title>
</head>
<body>
    <h2>Tìm kiếm và sắp xếp tin đăng</h2>
    <form action="tim_kiem_phongtro.php" method="GET">
        <input type="text" name="keyword" placeholder="Nhập từ khóa" value="<?= htmlspecialchars($keyword) ?>">
        <select name="sort_by">
            <option value="created_at" <?= $sortBy == 'created_at' ? 'selected' : '' ?>>Ngày tạo</option>
            <option value="price" <?= $sortBy == 'price' ? 'selected' : '' ?>>Giá</option>
        </select>
        <select name="order">
            <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
            <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
        </select>
        <button type="submit">Tìm kiếm</button>
    </form>

    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Khu vực</th>
            <th>Địa chỉ</th>
            <th>Trạng thái</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['district_name'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['approve'] == 1 ? 'Duyệt' : 'Chưa duyệt' ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
