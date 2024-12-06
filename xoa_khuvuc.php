<?php
include 'db.php';

// Lấy ID khu vực từ URL
$id = $_GET['id'];

// Xóa khu vực khỏi cơ sở dữ liệu
$stmt = $conn->prepare("DELETE FROM districts WHERE ID = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: danh_sach_khuvuc.php"); // Quay lại danh sách khu vực
    exit;
} else {
    echo "Xóa khu vực thất bại: " . $conn->error;
}
?>
