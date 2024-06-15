<?php
require_once("../config.php");
// Mulai sesi
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

?>
<?php
require_once("function_page.php")
?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  pageTitle();
  ?>
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/chartist.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
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
      <?php mainContent(); ?>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
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

</html>