<?php
require_once("../config.php");
?>
<?php

function pageTitle()
{
  $level = $_SESSION['level'];

  if ($level === 'Admin') :
    echo "<title>Admin Dashboard - UNPWedding</title>";
  elseif ($level === 'User') :
    echo "<title>Dashboard - UNPWedding</title>";

  endif;
}

function navbarTitle()
{
  $level = $_SESSION['level'];

  if ($level === 'Admin') :
    echo "Admin Dashboard";
  elseif ($level === 'User') :
    echo "Dashboard Pelanggan";

  endif;
}

function navSidebar()
{
  $level = $_SESSION['level'];

  if ($level === 'Admin') :
    echo '
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="ml-2">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#package" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                  <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
                <span class="ml-2">Paket</span>
              </a>
              <ul class="collapse list-unstyled" id="package">
              <li class="nav-item">
                <a class="nav-link" href="add_package.php">
                  <span class="ml-4">Tambah Paket</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="packages.php">
                  <span class="ml-4">Lihat Paket</span>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#goods" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                  <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
                <span class="ml-2">Barang</span>
              </a>
              <ul class="collapse list-unstyled" id="goods">
              <li class="nav-item">
                <a class="nav-link" href="add_goods.php">
                  <span class="ml-4">Tambah Barang</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="goods.php">
                  <span class="ml-4">Lihat Barang</span>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#orders" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="ml-2">Pesanan</span>
              </a>
              <ul class="collapse list-unstyled" id="orders">
              <li class="nav-item">
                <a class="nav-link" href="add_order.php">
                  <span class="ml-4">Tambah Pesanan</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="orders.php">
                  <span class="ml-4">Lihat Pesanan</span>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#customers" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="ml-2">Pelanggan</span>
              </a>
              <ul class="collapse list-unstyled" id="customers">
              <li class="nav-item">
                <a class="nav-link" href="add_customer.php">
                  <span class="ml-4">Tambah Pelanggan</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="customers.php">
                  <span class="ml-4">Lihat Pelanggan</span>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#paymentMethod" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
                <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                </svg>
                <span class="ml-2">Metode Pembayaran</span>
              </a>
              <ul class="collapse list-unstyled" id="paymentMethod">
              <li class="nav-item">
                <a class="nav-link" href="add_paymentMethod.php">
                  <span class="ml-4">Tambah Metode Pembayaran</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="payment_method.php">
                  <span class="ml-4">Lihat Metode Pembayaran</span>
                </a>
              </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#reports" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2">
                  <line x1="18" y1="20" x2="18" y2="10"></line>
                  <line x1="12" y1="20" x2="12" y2="4"></line>
                  <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span class="ml-2">Laporan</span>
              </a>
              <ul class="collapse list-unstyled" id="reports">
              <li class="nav-item">
                <a class="nav-link" href="report_packages.php">
                  <span class="ml-4">Laporan Paket</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="report_goods.php">
                  <span class="ml-4">Laporan Barang</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="report_orders.php">
                  <span class="ml-4">Laporan Pesanan</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="report_customers.php">
                  <span class="ml-4">Laporan Pelanggan</span>
                </a>
              </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    ';
  elseif ($level === 'User') :
    echo '
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="ml-2">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="my_orders.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="ml-2">Pesanan Anda</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      ';
  endif;
}

function mainContent()
{
  global $conn;

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  ################ ADMIN  ####################
  // Query untuk menghitung jumlah user dengan level 'User'
  $totalPelangganQuery = "SELECT COUNT(*) as totalPelanggan FROM users WHERE level = 'User'";
  // Eksekusi query
  $resultPelanggan = mysqli_query($conn, $totalPelangganQuery);
  // Ambil hasil query
  $rowPelanggan = mysqli_fetch_assoc($resultPelanggan);
  $totalPelanggan = $rowPelanggan['totalPelanggan'];

  // Query untuk menghitung jumlah paket
  $totalPaketQuery = "SELECT COUNT(*) as totalPaket FROM packages";
  // Eksekusi query
  $resultPaket = mysqli_query($conn, $totalPaketQuery);
  // Ambil hasil query
  $rowPaket = mysqli_fetch_assoc($resultPaket);
  $totalPaket = $rowPaket['totalPaket'];

  // Query untuk menghitung jumlah barang
  $totalBarangQuery = "SELECT COUNT(*) as totalBarang FROM goods";
  // Eksekusi query
  $resultBarang = mysqli_query($conn, $totalBarangQuery);
  // Ambil hasil query
  $rowBarang = mysqli_fetch_assoc($resultBarang);
  $totalBarang = $rowBarang['totalBarang'];

  // Query untuk menghitung jumlah pesanan
  $totalPesananQuery = "SELECT COUNT(*) as totalPesanan FROM orders";
  // Eksekusi query
  $resultPesanan = mysqli_query($conn, $totalPesananQuery);
  // Ambil hasil query
  $rowPesanan = mysqli_fetch_assoc($resultPesanan);
  $totalPesanan = $rowPesanan['totalPesanan'];

  // Query untuk menghitung jumlah penghasilan keseluruhan berdasarkan payment status 'Fully Paid', 'Paid DP', dan 'Unpaid'
  $totalIncomeQuery = "
      SELECT SUM(
          CASE
              WHEN p.payment_status = 'Fully Paid' THEN p.p_amount
              WHEN p.payment_status = 'Paid DP' THEN p.p_amount * 0.5
              WHEN p.payment_status = 'Unpaid' THEN 0
              ELSE 0
          END
      ) AS totalIncome 
      FROM orders o
      JOIN payments p ON o.o_id = p.p_o_id
      WHERE p.payment_status IN ('Fully Paid', 'Paid DP', 'Unpaid')";

  // Eksekusi query
  $resultIncome = $conn->query($totalIncomeQuery);

  // Ambil hasil query
  if ($resultIncome->num_rows > 0) {
    $rowIncome = $resultIncome->fetch_assoc();
    $totalIncome = $rowIncome['totalIncome'];
  } else {
    $totalIncome = 0;
  }
  require_once("function_goods.php");
  $totalOutcome = goodsOutcome();

  $user_id = $_SESSION['user_id'];
  ################ USER  ####################
  // Query untuk menghitung jumlah pesanan 'menunggu dikonfirmasi'
  $qryMenungguDikonfirmasi = "SELECT COUNT(*) as totalMD FROM orders WHERE o_status='Menunggu dikonfirmasi' AND o_user_id=$user_id";
  // Eksekusi query
  $resultMD = mysqli_query($conn, $qryMenungguDikonfirmasi);
  // Ambil hasil query
  $rowMD = mysqli_fetch_assoc($resultMD);
  $totalMD = $rowMD['totalMD'];

  // Query untuk menghitung jumlah pesanan 'Dikonfirmasi'
  $qryDikonfirmasi = "SELECT COUNT(*) as totalD FROM orders WHERE o_status='Dikonfirmasi' AND o_user_id=$user_id";
  // Eksekusi query
  $resultD = mysqli_query($conn, $qryDikonfirmasi);
  // Ambil hasil query
  $rowD = mysqli_fetch_assoc($resultD);
  $totalD = $rowD['totalD'];

  // Query untuk menghitung jumlah pesanan 'Dikonfirmasi'
  $qrySelesai = "SELECT COUNT(*) as totalS FROM orders WHERE o_status='Selesai' AND o_user_id=$user_id";
  // Eksekusi query
  $resultS = mysqli_query($conn, $qrySelesai);
  // Ambil hasil query
  $rowS = mysqli_fetch_assoc($resultS);
  $totalS = $rowS['totalS'];

  $level = $_SESSION['level'];
  $username = $_SESSION['username'];
  // INI MAIN CONTENT ADMIN
  if ($level === 'Admin') :
    echo '
    <main class="col-md-9 col-lg-10 offset-md-2 px-md-5 py-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
        <h1 class="h2">Dashboard</h1>
          <p>Selamat datang <strong>' . $username . '</strong> semoga harimu menyenangkan!</p>
        <div class="row my-4">
          <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
            <div class="card">
              <h5 class="card-header">Total Pelanggan</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalPelanggan . '</h5>
                <p class="card-text">Total pelanggan UNPWedding saat ini.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Total Paket</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalPaket . '</h5>
                <p class="card-text">Jumlah paket wedding organizer saat ini</p>
                <p class="card-text text-success"></p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Total Barang</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalBarang . '</h5>
                <p class="card-text">Jumlah barang wedding organizer saat ini</p>
                <p class="card-text text-success"></p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Total Pesanan</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalPesanan . '</h5>
                <p class="card-text">Total keseluruhan pesanan yang masuk</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Penghasilan</h5>
              <div class="card-body">
                <h5 class="card-title">Rp ' . number_format($totalIncome, 2, ',', '.') . '</h5>
                <p class="card-text">Jumlah penghasilan wedding organizer</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Pengeluaran</h5>
              <div class="card-body">
                <h5 class="card-title">Rp ' . number_format($totalOutcome, 2, ',', '.') . '</h5>
                <p class="card-text">Jumlah pengeluaran wedding organizer</p>
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
    ';
  // INI MAIN CONTENT USER

  elseif ($level === 'User') :
    $username = $_SESSION['username'];
    echo '
      <main class="col-md-9 col-lg-10 offset-md-2 px-md-5 py-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
        <h1 class="h2">Dashboard</h1>
          <p>Selamat datang <strong>' . $username . '</strong> semoga harimu menyenangkan!</p>
        <div class="row my-4">
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Menunggu Konfirmasi</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalMD . '</h5>
                <p class="card-text">Jumlah pesanan yang memiliki status menunggu konfirmasi</p>
                <p class="card-text text-success"></p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Dikonfirmasi</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalD . '</h5>
                <p class="card-text">Jumlah pesanan yang telah dikonfirmasi / sedang diproses.</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
            <div class="card">
              <h5 class="card-header">Pesanan Terselesaikan</h5>
              <div class="card-body">
                <h5 class="card-title">' . $totalS . '</h5>
                <p class="card-text">Jumlah pesanan yang berhasil.</p>
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
      ';
  endif;
}

// INI FUNCTION PAGE 'PAKET WEDDING' ATAU PACKAGE PAGE

function pageTitle_package()
{
  echo "<title>Package Manager - UNPWedding</title>";
}

// INI FUNCTION PAGE 'CUSTOMERS' 

function pageTitle_customers()
{
  echo "<title>Kelola Pelanggan - UNPWedding</title>";
}
function pageTitle_customerAdd()
{
  echo "<title>Tambah Pelanggan - UNPWedding</title>";
}
function pageTitle_customerEdit()
{
  echo "<title>Edit Pelanggan - UNPWedding</title>";
}
function pageTitle_goods()
{
  echo "<title>Kelola Barang - UNPWedding</title>";
}
function pageTitle_goodsAdd()
{
  echo "<title>Tambah Barang - UNPWedding</title>";
}
function pageTitle_goodsEdit()
{
  echo "<title>Edit Barang - UNPWedding</title>";
}
function pageTitle_myOrders()
{
  echo "<title>Pesanan Saya - UNPWedding</title>";
}
function pageTitle_orders()
{
  echo "<title>Kelola Pesanan - UNPWedding</title>";
}
function pageTitle_ordersAdd()
{
  echo "<title>Tambah Pesanan - UNPWedding</title>";
}
function pageTitle_ordersEdit()
{
  echo "<title>Edit Pesanan - UNPWedding</title>";
}
?>