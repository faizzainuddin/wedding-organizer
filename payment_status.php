<?php
session_start();
require_once("config.php");

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login
    $message = "Anda belum login! silahkan login terlebih dahulu.";
    $alert_type = "warning";
    header("Location: login.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id === 0) {
    die("ID order tidak ditemukan.");
}

// Ambil data pembayaran
$query = "SELECT * FROM payments WHERE p_o_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

if (!$payment) {
    die("Pembayaran tidak ditemukan.");
}

$payment_status = $payment['payment_status'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Status Pembayaran - Wedding Organizer</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <style>
        .centered-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .centered-card {
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>

<body>
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo.png" alt="">
                        </a>

                        <ul class="nav">
                            <li class="scroll-to-section"><a href="index.php" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="index.php">Paket</a></li>
                            <li class="scroll-to-section"><a href="index.php">Galeri Photo</a></li>
                            <li class="scroll-to-section"><a href="faqs.php">FAQ</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="page-order centered-container">
        <div class="card centered-card">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success text-center" role="alert">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                            Pesanan anda berhasil dibuat! Silahkan menunggu konfirmasi manual oleh administrator.
                        </div>
                        <div class="mb-4 text-center">
                            <h5>Order ID: <?php echo $order_id; ?></h5>
                            <h5>Status Pembayaran: <?php echo $payment_status; ?></h5>
                            <p>Silahkan lakukan pengecekkan berkala status pemesanan dan status pembayaran di halaman <a href="login.php">dashboard</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="col-lg-12">
                <p>Copyright © 2024 <a href="#">UNPwedding</a>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

</html>
