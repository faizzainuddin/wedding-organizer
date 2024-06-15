<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_weddingorg";

$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
