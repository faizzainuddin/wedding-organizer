<?php
require_once("function_page.php");
require_once("function_customers.php");
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

// Mengatur zona waktu menjadi WIB
date_default_timezone_set('Asia/Jakarta');

// Ambil user_id dari parameter URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
} else {
    die("ID pengguna tidak ditemukan.");
}

// Fungsi untuk mengambil data pengguna dari database
function getUserData($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT username, email, first_name, last_name, address, city, zipcode, telephone FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Ambil data pengguna
$userData = getUserData($user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $level = 'User'; // Anggap level tetap 'user'
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $telephone = $_POST['telephone'];

    // Panggil fungsi editUser
    if (editUser($user_id, $username, $password, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone)) {
        $message = "Pengguna berhasil diupdate!";
        $alert_type = "success";

        $userData = getUserData($user_id);
    } else {
        $message = "Gagal mengupdate pengguna!";
        $alert_type = "danger";
    }
    header("Location: customers.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php pageTitle_customerEdit(); ?>
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/chartist.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">
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
            <main class="col-md-9 col-lg-10 offset-md-2 px-md-5 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Pelanggan</li>
                    </ol>
                </nav>
                <h1 class="h2">Edit Pelanggan</h1>
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="container">
                                <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $user_id; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <small class="form-text text-muted">**Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="first_name" class="form-label">Nama Depan</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="last_name" class="form-label">Nama Belakang</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($userData['address']); ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="city" class="form-label">Kota</label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($userData['city']); ?>" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="zipcode" class="form-label">Kodepos</label>
                                            <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo htmlspecialchars($userData['zipcode']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Telepon</label>
                                        <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($userData['telephone']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Edit Pelanggan</button>
                                </form>
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