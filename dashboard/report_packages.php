<?php
// Mulai sesi
require_once("function_package.php");
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  // Jika belum login, redirect ke halaman login

  header("Location: ../login.php");
  exit;
}

// Ambil nama,level pengguna dari sesi login saat ini
$username = $_SESSION['username'];
$level = $_SESSION['level'];

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
?>
<?php
require_once("function_page.php")
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php pageTitle_package(); ?>
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/chartist.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">
  <style>
    @media print 
    {
        @page { margin: 0; }
        body  { margin: 1.6cm; }
        title, .btn-sm, tfoot { display: none; }
        img { height: 50px; width: 50px;}
    }

  </style>
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
          Hello, <?php echo $username; ?>
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
            <li class="breadcrumb-item active" aria-current="page">Laporan Paket Wedding</li>
          </ol>
        </nav>
        <form method="GET" action="">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" class="form-control" name="start_date" placeholder="Start Date" required>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="end_date" placeholder="End Date" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <div class="row">
          <div class="col-12 col-xl-12 mb-4 mb-lg-0">
            <div class="card">
              <h5 class="card-header">Laporan Paket Wedding</h5>
              <button class="btn btn-sm btn-outline-warning mt-3" type="submit" id="print" onclick="printAllPackages('printAll')" style="width: auto; max-width: 200px; padding: 10px 10px;"><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-printer-fill' viewBox='0 0 16 16'>
  <path d='M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1'/>
  <path d='M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1'/>
</svg> Cetak semua paket</button>
              <div class="card-body">
                <div class="table-responsive">
                <div id="printAll">
                  <table class="table table-striped" id="example" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">PackageID</th>
                        <th scope="col">Image</th>
                        <th scope="col">Package Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">CreatedAt</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                       $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
                       $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

                       $package = getPackages($start_date, $end_date);
                      $no = 1;
                      foreach ($package as $pkg) {
                
                      echo "<tr>";
                      echo "<th scope='row'>". $no++ ."</th>";
                      echo "<td>" .$pkg['p_id']. "</td>";
                      echo "<td><img src='" .$pkg['p_image']. "' height='200' width='200'></td>";
                      echo "<td>" .$pkg['p_name']. "</td>";
                      echo "<td>" .$pkg['p_description']. "</td>";
                      echo "<td>" .$pkg['p_price']. "</td>";
                      echo "<td>" .$pkg['p_created_at']. "</td>";
                      echo "<td><a href='cetak/c_paket.php?id=" . $pkg['p_id'] . "' class='btn btn-sm btn-outline-warning'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-printer-fill' viewBox='0 0 16 16'>
  <path d='M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1'/>
  <path d='M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1'/>
</svg> Cetak</a></td>";
                      }
                      ?>
                    </tbody>
                    <div id="printAll">
                    <tfoot>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">PackageID</th>
                        <th scope="col">Image</th>
                        <th scope="col">Package Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">CreatedAt</th>
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
  <Script src="../assets/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script>new DataTable('#example');</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include_once("action_alert.php"); ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Mengambil semua elemen navigasi
    const navLinks = document.querySelectorAll('.nav-link');

    // Iterasi melalui setiap elemen navigasi
    navLinks.forEach(link => {
      // Menambahkan event listener untuk setiap elemen navigasi
      link.addEventListener('click', () => {
        // Menghapus kelas aktif dari semua elemen navigasi 
        navLinks.forEach(navLink => {
          navLink.classList.remove('active');
        });
        // Menambahkan kelas aktif pada elemen navigasi yang diklik
        link.classList.add('active');
      });
    });
  });
</script>
<script>
		function printAllPackages(printAll){
			var printContents = document.getElementById(printAll).innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;

		}
	</script>

</html>