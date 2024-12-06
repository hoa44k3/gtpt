<?php
$host = 'localhost';
$db = 'gtpt';
$user = 'root';
$password = '';
$port = 7307;

// Kết nối
$conn = new mysqli($host, $user, $password, $db, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
