<?php
session_start();
require_once("function_orders.php");

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login
    $message = "Anda belum login! silahkan login terlebih dahulu.";
    $alert_type = "warning";
    header("Location: login.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit();
}

$username = $_SESSION['username'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
$address = $_SESSION['address'];
$city = $_SESSION['city'];
$customer_id = $_SESSION['user_id'];

// Ambil package_id dari parameter URL
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;
if ($package_id === 0) {
    die("ID package tidak ditemukan.");
}

// Ambil data paket
$package = getPackageById($package_id);
if (!$package) {
    die("Paket tidak ditemukan.");
}

// Ambil metode pembayaran
$payment_methods = getPaymentMethods();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses form order
    $event_date = $_POST['event_date'];
    $payment_method = $_POST['payment_method'];
    $status = 'Menunggu dikonfirmasi';

    if (createOrder($customer_id, $package_id, $status, $event_date, $payment_method)) {
        global $conn; // Pastikan $conn diambil dari file config.php atau di mana koneksi database didefinisikan
        $order_id = $conn->insert_id; // Dapatkan ID pesanan yang baru dibuat
        header("Location: payment.php?order_id=" . $order_id);
        exit();
    } else {
        $message = "Terjadi kesalahan: Tanggal sudah terpesan atau kesalahan lainnya.";
        $alert_type = "danger";
        header("Location: orders.php?package_id=" . $package_id . "&message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Order - Wedding Organizer</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
</head>
<body>
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
    <div class="page-order">
        <div class="card">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 order-2">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Detail Pesanan</span>
                            <span class="badge rounded-pill bg-secondary">1</span>
                        </h4>
                        <div class="card-detail">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0"><?php echo htmlspecialchars($package['p_name']); ?></h6>
                                    <small class="text-muted">Package ID: <?php echo $package_id; ?></small>
                                </div>
                                <span class="text-muted">Rp <?php echo number_format($package['p_price'], 2, ',', '.'); ?></span>
                            </li>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <span>Total (Rp)</span>
                            <strong>Rp <?php echo number_format($package['p_price'], 2, ',', '.'); ?></strong>
                        </div>
                        <form class="card p-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo code">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-secondary" disabled>Redeem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8 order-1">
                        <h4 class="mb-3">Informasi Pesanan</h4>
                        <!-- Tampilkan alert jika ada pesan -->
                        <?php if (isset($_GET['message'])) : ?>
                            <div class="alert alert-<?php echo htmlspecialchars($_GET['alert_type']); ?>" role="alert">
                                <?php echo htmlspecialchars($_GET['message']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" placeholder="<?php echo $first_name; ?>" value="<?php echo $first_name; ?>" disabled>
                                </div>
                                <div class="col mb-4">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" placeholder="<?php echo $last_name; ?>" value="<?php echo $last_name; ?>" disabled>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="email">Email (optional)</label>
                                <input type="text" class="form-control" id="email" placeholder="<?php echo $email; ?>" value="<?php echo $email; ?>" disabled>
                            </div>
                            <div class="mb-4">
                                <label for="address">Alamat</label>
                                <input type="text" class="form-control" id="address" placeholder="<?php echo $address; ?>" value="<?php echo $address; ?>" disabled>
                            </div>
                            <div class="mb-4">
                                <label for="city">Kota</label>
                                <input type="text" class="form-control" id="city" placeholder="<?php echo $city; ?>" value="<?php echo $city; ?>" disabled>
                            </div>
                            <div class="mb-4">
                                <label for="event_date">Tanggal Acara</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" required>
                            </div>
                            <div class="col-md-12 order-2">
                                <h4 class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Pilih metode pembayaran</span>
                                </h4>
                                <div class="mb-4">
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <?php foreach ($payment_methods as $method) : ?>
                                            <option value="<?php echo htmlspecialchars($method['pm_id']); ?>">
                                                <?php echo htmlspecialchars($method['pm_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Lanjutkan ke pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const datePicker = document.getElementById("event_date");
            const today = new Date().toISOString().split('T')[0];
            datePicker.setAttribute('min', today);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/owl.js"></script>
    <script src="assets/js/slick.js"></script>
    <script src="assets/js/accordions.js"></script>
</body>
</html>
