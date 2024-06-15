<?php
// Sertakan file koneksi dan file fungsi
include '../config.php'; // Pastikan file koneksi database Anda
include 'function_customers.php'; // Pastikan file yang berisi fungsi deleteUser

// Periksa apakah id telah diberikan
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Validasi apakah user ID ada di database
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Panggil fungsi deleteUser jika user ID ada
        if (deleteUser($user_id)) {
            $message = "User dengan ID $user_id telah berhasil dihapus.";
            $alert_type = "success";
        } else {
            $message = "Gagal menghapus user dengan ID $user_id.";
            $alert_type = "danger";
        }
    } else {
        $message = "User ID tidak ditemukan.";
        $alert_type = "warning";
    }
} else {
    // Set pesan error jika id tidak ditemukan
    $message = "User ID tidak ditemukan.";
    $alert_type = "warning";
}

// Redirect ke halaman tujuan dengan pesan
header("Location: customers.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
exit();
?>