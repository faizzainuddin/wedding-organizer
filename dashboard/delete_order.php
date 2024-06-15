<?php
require_once("function_orders.php");

// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Periksa apakah parameter 'id' ada
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

// Ambil ID pesanan dari parameter GET
$order_id = intval($_GET['id']);

// Hapus pesanan
try {
    if (deleteOrder($order_id)) {
        $message = "Berhasil menghapus pesanan!";
        $alert_type = "success";
    } else {
        $message = "Oops! Gagal menghapus pesanan...";
        $alert_type = "warning";
    }
} catch (Exception $e) {
    $message = "Terjadi kesalahan: " . $e->getMessage();
    $alert_type = "danger";
}

// Redirect ke halaman pesanan dengan pesan
header("Location: orders.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
exit;
?>
