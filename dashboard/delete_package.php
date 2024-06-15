<?php
require_once("function_package.php");

// Ambil package_id dari parameter URL
if (isset($_GET['id'])) {
    $package_id = intval($_GET['id']);
} else {
    die("ID package tidak ditemukan.");
}

// Panggil fungsi deletePackage
if (deletePackage($package_id)) {
    $message = "Berhasil menghapus paket!";
    $alert_type = "success";
} else {
    $message = "Oops! Gagal menghapus paket..";
    $alert_type = "danger";
}

header("Location: packages.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
exit();
?>
