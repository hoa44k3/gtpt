<?php
session_start();

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$avatar = $_SESSION['avatar'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile img {
            border-radius: 50%;
            margin-right: 15px;
            width: 100px;
            height: 100px;
        }

        .profile h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .profile p {
            color: #777;
            font-size: 16px;
        }

        .room-list {
            margin-bottom: 20px;
        }

        .room-list ul {
            list-style-type: none;
            padding: 0;
        }

        .room-list li {
            background-color: #f9f9f9;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .room-list li:hover {
            background-color: #f1f1f1;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .logout-button {
            background-color: #f44336;
        }

        .logout-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <header>
        <h2>Trang chủ</h2>
    </header>

    <div class="container">
        <div class="profile">
            <?php if ($avatar): ?>
                <img src="<?= htmlspecialchars($avatar); ?>" alt="Avatar">
            <?php endif; ?>
            <div>
                <h3>Chào, <?= htmlspecialchars($username); ?>!</h3>
                <p>Chúc bạn có một ngày làm việc vui vẻ!</p>
            </div>
        </div>

        <div class="room-list">
            <h3>Hiện thị danh sách các phòng trọ:</h3>
            <ul>
                <li>Phòng trọ A</li>
                <li>Phòng trọ B</li>
                <li>Phòng trọ C</li>
            </ul>
        </div>

        <?php if ($role != 1): ?>
            <form action="admin.php" method="POST">
                <button type="submit" class="button">Vào trang Admin</button>
            </form>
        <?php endif; ?>

        <!-- Nút đăng xuất -->
        <form action="dangxuat.php" method="POST">
            <button type="submit" class="button logout-button">Đăng xuất</button>
        </form>
    </div>
</body>
</html>
