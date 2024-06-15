<?php
session_start();
require_once("config.php");
require_once("function_payment.php");

if (!isset($_SESSION['user_id'])) {
    $message = "Anda belum login! silahkan login terlebih dahulu.";
    $alert_type = "warning";
    header("Location: login.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id === 0) {
    die("ID order tidak ditemukan.");
}

$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$customer_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE o_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order tidak ditemukan.");
}

$package = getPackageById($order['o_p_id']);
if (!$package) {
    die("Paket tidak ditemukan.");
}

$query = "SELECT * FROM payment_method WHERE pm_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order['o_pm_id']);
$stmt->execute();
$payment_method = $stmt->get_result()->fetch_assoc();

if (!$payment_method) {
    die("Metode pembayaran tidak ditemukan.");
}

$total_payment = $package['p_price'];
$dp_payment = $total_payment / 2;

$date = new DateTime();
$unique_code = (int)($date->format('d')) . (int)substr($order['o_user_id'], -2);
$total_payment_with_code = $total_payment + $unique_code;
$dp_payment_with_code = $dp_payment + $unique_code;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = $_POST['sender'];
    $target_dir = "bukti-pembayaran/";
    $target_file = $target_dir . basename($_FILES["payment_proof"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["payment_proof"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["payment_proof"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Maaf, hanya file JPG, JPEG, PNG yang diperbolehkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            $payment_status = 'Unpaid';
            $payment_id = createPayment($order_id, $customer_id, $order['o_pm_id'], $sender, $total_payment_with_code, $payment_status, $target_file);
            if ($payment_id) {
                header("Location: payment_status.php?order_id=" . $order_id);
                exit();
            } else {
                echo "Gagal memuat pembayaran!";
            }
        } else {
            echo "Gagal mengupload bukti transfer!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Pembayaran - Wedding Organizer</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
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
    <div class="page-order">
        <div class="card">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 order-2">
                        <div class="row">
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="name">Nama Pengirim</label>
                                    <input type="text" class="form-control" value="<?php echo $first_name; ?> <?php echo $last_name; ?>" name="name" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="name">Nomor/Rekening Pengirim</label>
                                    <input type="text" class="form-control" placeholder="No: +62xxxxx / Rekening : 5012xxxx" name="sender" required>
                                </div>
                                <div class="mb-4">
                                    <label for="bank">Nama Bank</label>
                                    <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($payment_method['pm_id']); ?>" disabled>
                                    <input type="text" class="form-control" placeholder="<?php echo htmlspecialchars($payment_method['pm_name']); ?>" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="name">Nomor/Rekening Penerima</label>
                                    <input type="disabled" class="form-control" placeholder="<?php echo htmlspecialchars($payment_method['pm_detail']); ?>" name="rcv" disabled>
                                </div>
                                    <div class="mb-4">
                                    <label for="total_payment"> Total pembayaran (yang harus dilunaskan) </label>
                                    <input type="text" class="form-control" value="Rp <?php echo number_format($total_payment_with_code, 2, ',', '.'); ?>" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="dp_payment">Pembayaran (DP 50%)</label>
                                    <input type="text" class="form-control" value="Rp <?php echo number_format($dp_payment_with_code, 2, ',', '.'); ?>" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="unique_code">Kode Unik</label>
                                    <input type="text" class="form-control" value="Rp <?php echo number_format($unique_code, 0, ',', '.'); ?>" disabled>
                                    <small class="form-text text-muted">Silahkan transfer sesuai dengan kode unik.</small>
                                </div>
                                <div class="mb-4">
                                    <label for="payment_proof">Bukti Transfer</label>
                                    <input type="file" class="form-control" name="payment_proof" accept=".jpg, .jpeg, .png" required>
                                </div>
                                <hr class="mb-4">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        Konfirmasi pembayaran
                                    </button>
                                </div>
                            </form>
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
