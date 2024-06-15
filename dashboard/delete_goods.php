<?php
require_once("function_goods.php");

// Ambil package_id dari parameter URL
if (isset($_GET['id'])) {
    $goods_id = intval($_GET['id']);
} else {
    die("ID package tidak ditemukan.");
}

// Panggil fungsi deletePackage
if (deleteGoods($goods_id)) {
    $message = "Berhasil menghapus barang!";
    $alert_type = "success";
} else {
    $message = "Oops! Gagal menghapus barang...";
    $alert_type = "danger";
}

header("Location: goods.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
exit();
?>
