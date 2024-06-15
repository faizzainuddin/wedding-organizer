<?php
require_once("config.php");

function encryptPassword($password) {
    return md5($password);
}

function registerUser($username, $password, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone) {
    global $conn;

    // Enkripsi password sebelum disimpan
    $hashedPassword = encryptPassword($password);

    // Mengatur zona waktu menjadi WIB
    date_default_timezone_set('Asia/Jakarta');

    // Ambil tanggal dan waktu saat ini
    $currentDateTime = date("Y-m-d H:i:s");

     // Siapkan statement SQL
     $stmt = $conn->prepare("INSERT INTO users (username, password, email, level, first_name, last_name, address, city, zipcode, telephone, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
     $stmt->bind_param("sssssssssss", $username, $hashedPassword, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone, $currentDateTime);
  
     // Jalankan statement SQL
    if ($stmt->execute()) {
        header("location: login.php");
        return true; // Registrasi berhasil
    } else {
        echo "gagal!";
        return false; // Registrasi gagal
    }
}

function preventSQLInjection($data) {
    // Fungsi untuk mencegah SQL injection dengan membersihkan input data
    global $conn;
    return mysqli_real_escape_string($conn, stripslashes($data));
}
?>
