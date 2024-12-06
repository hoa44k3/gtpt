<?php
session_start();
session_unset(); // Xóa tất cả các biến session
session_destroy(); // Hủy session

// Chuyển hướng về trang phongtro.php sau khi đăng xuất
header("Location: index.php");
exit;
?>
