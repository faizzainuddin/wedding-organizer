<?php
require_once("config.php");

function loginUser($username_email, $password) {
    global $conn;

    // Enkripsi password
    $hashedPassword = md5($password);

    // Siapkan statement SQL
    $stmt = $conn->prepare("SELECT user_id, username, level, first_name, last_name, email, address, city FROM users WHERE (username = ? OR email = ?) AND password = ?");
    $stmt->bind_param("sss", $username_email, $username_email, $hashedPassword);

    // Jalankan statement SQL
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        // Ambil data pengguna
        $user = $result->fetch_assoc();
        
        // Atur sesi login
        session_start();
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['city'] = $user['city'];

        return true; // Login berhasil
    } else {
        return false; // Login gagal
    }
}

function preventSQLInjection($data) {
    global $conn;
    return mysqli_real_escape_string($conn, stripslashes($data));
}

?>
