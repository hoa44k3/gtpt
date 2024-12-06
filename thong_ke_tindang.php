<?php
include 'db.php';

// Lấy thông tin thống kê tin đăng trong tháng hiện tại
$currentMonth = date('Y-m');
$sql = "SELECT COUNT(*) as total_posts FROM Motel WHERE DATE_FORMAT(created_at, '%Y-%m') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$statistics = $result->fetch_assoc();

// Xử lý tìm kiếm và sắp xếp tin đăng
$whereClauses = [];
$bindParams = [];
$orderBy = "ORDER BY created_at DESC"; // Mặc định sắp xếp theo thời gian đăng

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $whereClauses[] = "user_id = ?";
        $bindParams[] = $_GET['username'];  // Giả sử 'username' là id của người dùng
    }

    if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
        $whereClauses[] = "created_at >= ?";
        $bindParams[] = $_GET['start_date'];
    }

    if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
        $whereClauses[] = "created_at <= ?";
        $bindParams[] = $_GET['end_date'];
    }

    if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
        $whereClauses[] = "price >= ?";
        $bindParams[] = $_GET['min_price'];
    }

    if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $whereClauses[] = "price <= ?";
        $bindParams[] = $_GET['max_price'];
    }

    // Thêm điều kiện WHERE vào câu SQL
    $whereSql = implode(" AND ", $whereClauses);
    $sql = "SELECT * FROM Motel";
    if (!empty($whereSql)) {
        $sql .= " WHERE " . $whereSql;
    }
    $sql .= " " . $orderBy;

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare($sql);
    
    // Liên kết tham số động vào câu truy vấn
    if (!empty($bindParams)) {
        $types = str_repeat("s", count($bindParams)); // Giả sử tất cả tham số là string
        $stmt->bind_param($types, ...$bindParams);
    }

    // Thực thi truy vấn
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê tin đăng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .filter-form, .statistics {
            margin-bottom: 20px;
        }

        .filter-form input, .filter-form select {
            margin-right: 10px;
        }

        .statistics {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

    <h2>Thống kê tin đăng</h2>

    <!-- Thống kê số lượng tin đăng trong tháng -->
    <div class="statistics">
        <p>Số lượng tin đăng trong tháng <?= date('m/Y') ?>: <strong><?= $statistics['total_posts'] ?></strong></p>
    </div>

    <!-- Form tìm kiếm và sắp xếp -->
    <form class="filter-form" method="GET" action="">
        <input type="text" name="username" placeholder="Tìm theo tài khoản" value="<?= isset($_GET['username']) ? $_GET['username'] : '' ?>" />
        <input type="date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>" />
        <!-- <input type="date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>" /> -->
        <input type="number" name="min_price" placeholder="Giá tối thiểu" value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : '' ?>" />
        <!-- <input type="number" name="max_price" placeholder="Giá tối đa" value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : '' ?>" /> -->
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Hiển thị danh sách tin đăng -->
    <h3>Danh sách tin đăng:</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Giá</th>
                <th>Địa chỉ</th>
                <th>Ngày đăng</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= number_format($row['price'], 0, ',', '.') ?> VND</td>
                <td><?= $row['address'] ?></td>
                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin.php">Quay lại admin</a>

</body>
</html>
