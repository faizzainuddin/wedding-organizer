<?php
require_once("../config.php");

function countUsers() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
    $row = $result->fetch_assoc();
    return $row['total_users'];
}

function encryptPassword($password) {
    return md5($password);
}

function addUser($username, $password, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone) {
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
        header("location: customers.php");
        return true; // Registrasi berhasil
    } else {
        header("location: customers.php");
        return false; // Registrasi gagal
    }
}

function editUser($user_id, $username, $password, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone) {
    global $conn;

    // Enkripsi password jika tidak kosong
    $hashedPassword = '';
    if (!empty($password)) {
        $hashedPassword = encryptPassword($password);
    } else {
        // Jika password kosong, gunakan password lama
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();
    }

    // Siapkan statement SQL
    $stmt = $conn->prepare("UPDATE users SET username=?, password=?, email=?, level=?, first_name=?, last_name=?, address=?, city=?, zipcode=?, telephone=? WHERE user_id=?");
    $stmt->bind_param("ssssssssssi", $username, $hashedPassword, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone, $user_id);

    // Jalankan statement SQL
    if ($stmt->execute()) {
        return true; // Update berhasil
    } else {
        return false; // Update gagal
    }
}

function deleteUser($user_id) {
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->bind_param("i", $user_id);

    // Jalankan statement SQL
    if ($stmt->execute()) {
        return true; // Delete berhasil
    } else {
        return false; // Delete gagal
    }
}

function getUsers() {
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("SELECT * FROM users WHERE level='User' ORDER BY created_at DESC");

    // Jalankan statement SQL
    $stmt->execute();

    // Ambil hasil query
    $result = $stmt->get_result();

    // Ambil data users sebagai array assosiatif
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

function preventSQLInjection($data) {
    // Fungsi untuk mencegah SQL injection dengan membersihkan input data
    global $conn;
    return mysqli_real_escape_string($conn, stripslashes($data));
}
?>
