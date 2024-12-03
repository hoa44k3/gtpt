<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Hiển thị thông tin người dùng
echo "<h2>Danh sách phòng trọ</h2>";
echo "Chào, " . $_SESSION['username'] . "!<br>";
if ($_SESSION['avatar']) {
    echo "<img src='" . $_SESSION['avatar'] . "' alt='Avatar' style='width:100px; height:100px;'><br>";
}
?>
