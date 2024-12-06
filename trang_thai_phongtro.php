<?php
include 'db.php';

$id = $_GET['id'];

$sql = "UPDATE Motel SET approve = NOT approve WHERE ID = $id";

if ($conn->query($sql)) {
    header("Location: phong_tro.php");
    exit;
} else {
    echo "Thay đổi trạngthái thất bại: " . $conn->error; } 
?>



