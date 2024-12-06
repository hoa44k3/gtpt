<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM Motel WHERE ID = $id";

if ($conn->query($sql)) {
    header("Location: phong_tro.php");
    exit;
} else {
    echo "Xóa thất bại: " . $conn->error;
}
?>
