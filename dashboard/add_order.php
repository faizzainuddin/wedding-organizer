<?php
require_once("function_page.php");
include_once "function_orders.php";

// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil nama dan level pengguna dari sesi login saat ini
$username = $_SESSION['username'];
$level = $_SESSION['level'];

// Proses form jika metode request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['customer_id']) && isset($_POST['package_id']) && isset($_POST['status']) && isset($_POST['event_date']) && isset($_POST['payment_method'])) {
        $customer_id = $_POST['customer_id'];
        $package_id = $_POST['package_id'];
        $status = $_POST['status'];
        $event_date = $_POST['event_date'];
        $payment_method = $_POST['payment_method'];

        if (createOrder($customer_id, $package_id, $status, $event_date, $payment_method)) {
            $message = "Pesanan berhasil ditambahkan.";
            $alert_type = "success";
        } else {
            $alert_type = "danger";
            $message = "Terjadi kesalahan saat menambahkan pesanan.";
        }
        header("Location: orders.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
        exit();
    } else {
        $alert_type = "danger";
        $message = "Semua field harus diisi.";
        header("Location: orders.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
        exit();
    }
}

$packages = getPackages();
$payment_methods = getPaymentMethods();
$users = getUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php pageTitle_ordersAdd(); ?>
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/chartist.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="index.php">
                <?php navbarTitle() ?>
            </a>
            <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <input class="form-control form-control-dark" type="text" placeholder="Search" aria-label="Search">
        </div>
        <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                    Hello, <?php echo htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Messages</a></li>
                    <li><a class="dropdown-item logout-link" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <?php navSidebar(); ?>
            <main class="col-md-9 col-lg-10 offset-md-2 px-md-5 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Pesanan</li>
                    </ol>
                </nav>
                <h1 class="h2">Tambah Pesanan</h1>
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="container">
                                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label">Pilih Customer</label>
                                        <select class="form-control" id="customer_id" name="customer_id" required>
                                            <?php foreach ($users as $user) { ?>
                                                <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="package_id" class="form-label">Pilih Paket</label>
                                        <select class="form-control" id="package_id" name="package_id" required>
                                            <?php foreach ($packages as $pkg) { ?>
                                                <option value="<?php echo $pkg['p_id']; ?>"><?php echo htmlspecialchars($pkg['p_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="Menunggu dikonfirmasi">Menunggu dikonfirmasi</option>
                                            <option value="Dikonfirmasi">Dikonfirmasi</option>
                                            <option value="Selesai">Selesai</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="event_date" class="form-label">Tanggal Acara</label>
                                        <input type="date" class="form-control" id="event_date" name="event_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                        <select class="form-control" id="payment_method" name="payment_method" required>
                                            <?php foreach ($payment_methods as $method) { ?>
                                                <option value="<?php echo $method['pm_id']; ?>"><?php echo htmlspecialchars($method['pm_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah Pesanan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2024 <a href="#">UNPWedding</a></span>
                        <ul class="nav m-0">
                            <li class="nav-item">
                                <a class="nav-link text-secondary" aria-current="page" href="#">Privacy Policy</a></li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="#">Terms and conditions</a></li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="#">Contact</a></li>
                        </ul>
                    </footer>
            </main>
        </div>
    </div>
    <script src="../assets/js/jquery-3.7.1.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dataTables.js"></script>
    <Script src="../assets/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        new DataTable('#example');
    </script>
    <script>
        new Chartist.Line('#traffic-chart', {
            labels: ['January', 'Februrary', 'March', 'April', 'May', 'June'],
            series: [
                [23000, 25000, 19000, 34000, 56000, 64000]
            ]
        }, {
            low: 0,
            showArea: true
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include_once("action_alert.php"); ?>
</html>
