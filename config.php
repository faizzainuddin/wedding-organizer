<?php
// Koneksi ke database
$servername = "127.0.0.1:3307;";
$username = "root";
$password = "";
$database = "db_weddingorg";

$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
