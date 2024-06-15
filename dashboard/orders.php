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

// Ambil data pesanan
$orders = getOrders();

if (isset($_GET['message']) && isset($_GET['alert_type'])) {
    $message = $_GET['message'];
    $alert_type = $_GET['alert_type'];
    echo "
    <style>
        .swal2-icon {
            border: 1px solid black;
            margin: 0 auto;
            margin-top: 30px;
        }
        .swal2-title {
            margin-bottom: 15px;
        }
        .swal2-content {
            margin-bottom: 30px;
        }
        .swal2-actions {
            margin-top: 30px;
        }
    </style>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
              Swal.fire({
                  icon: '$alert_type',
                  title: '" . ($alert_type === 'success' ? 'Success!' : 'Warning!') . "',
                  text: '$message',
                  confirmButtonText: 'OK'
              });
          });
    </script>";
}
if (isset($_GET['buktiTrf'])) {

    $order_id = isset($_GET['buktiTrf']) ? intval($_GET['buktiTrf']) : 0;
    if ($order_id === 0) {
        die("ID order tidak ditemukan.");
    }
    
    // Ambil bukti transfer berdasarkan order_id
    $transferProof = getTransferProofByOrderId($order_id);
    
    if ($transferProof !== null) {
        echo "
        <style>
            .swal2-icon {
                border: 1px solid black;
                margin: 0 auto;
                margin-top: 30px;
            }
            .swal2-title {
                margin-bottom: 15px;
            }
            .swal2-content {
                margin-bottom: 30px;
            }
            .swal2-actions {
                margin-top: 30px;
            }
        </style>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Bukti Pembayaran',
                    html:  '<img src=\"../".htmlspecialchars($transferProof['payment_proof'])."\" style=\"max-width: 50%; height: 200px;\">',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    } 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php pageTitle_orders(); ?>
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
                        <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                    </ol>
                </nav>
                <h1 class="h2">Kelola Pesanan</h1>
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Data Pesanan</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="example" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">OrderID</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">Paket</th>
                                                <th scope="col">Metode Pembayaran</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Status Pembayaran</th>
                                                <th scope="col">Tanggal Acara</th>
                                                <th scope="col">Tanggal Dipesan</th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($orders as $order) {
                                                $payment_status = '';
                                                if (isset($order['payment_status'])) {
                                                    if ($order['payment_status'] == 'Paid DP') {
                                                        $payment_status = "<a href='orders.php?buktiTrf=" . urlencode($order['o_id']) . "'>Paid DP</a>";
                                                    } elseif ($order['payment_status'] == 'Fully Paid') {
                                                        $payment_status = "<a href='orders.php?buktiTrf=" . urlencode($order['o_id']) . "'>Fully Paid</a>";
                                                    } else {
                                                        $payment_status = htmlspecialchars($order['payment_status'] ?? '');
                                                    }
                                                } else {
                                                    $payment_status = htmlspecialchars($order['payment_status'] ?? '');
                                                }
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($order['o_id'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($order['username'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($order['p_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($order['pm_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($order['o_status'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . $payment_status . "</td>";
                                                echo "<td>" . htmlspecialchars($order['o_event_date'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($order['o_created_at'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td><a href='edit_order.php?id=" . urlencode($order['o_id']) . "' class='btn btn-sm btn-outline-warning'>Edit</a></td>";
                                                echo "<td><a href='delete_order.php?id=" . urlencode($order['o_id']) . "' class='btn btn-sm btn-danger'>Delete</a></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">OrderID</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">Paket</th>
                                                <th scope="col">Metode Pembayaran</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Status Pembayaran</th>
                                                <th scope="col">Tanggal Acara</th>
                                                <th scope="col">Tanggal Dipesan</th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="pt-5 d-flex justify-content-between">
                    <span>Copyright © 2024 <a href="#">UNPWedding</a></span>
                    <ul class="nav m-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" aria-current="page" href="#">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Terms and conditions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Contact</a>
                        </li>
                    </ul>
                </footer>
            </main>
        </div>
    </div>  
    <script src="../assets/js/jquery-3.7.1.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dataTables.js"></script>
    <script src="../assets/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        new DataTable('#example');
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include_once("action_alert.php"); ?>

</html>