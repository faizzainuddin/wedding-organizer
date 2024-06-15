<?php
require_once("function_page.php");
require_once("function_package.php");

// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: ../login.php");
    exit;
}

// Ambil nama dan level pengguna dari sesi login saat ini
$username = $_SESSION['username'];
$level = $_SESSION['level'];

// Mengatur zona waktu menjadi WIB
date_default_timezone_set('Asia/Jakarta');

// Ambil package_id dari parameter URL
if (isset($_GET['id'])) {
    $package_id = intval($_GET['id']);
} else {
    die("ID package tidak ditemukan.");
}

// Fungsi untuk mengambil data package dari database
function getPackageData($package_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT p_name, p_description, p_price, p_image FROM packages WHERE p_id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Ambil data package
$packageData = getPackageData($package_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Logika untuk menangani file gambar
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        $uploadOk = 1;
        
        // Check if image file is an actual image or fake image
        if ($check === false) {
            $message = "File bukan gambar.";
            $uploadOk = 0;
        }

        // Check file size (max 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            $message = "File terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
            $message = "Hanya JPG, JPEG, & PNG yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Maaf, file Anda tidak terupload.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Hapus gambar lama
                if ($packageData['image'] && file_exists($packageData['image'])) {
                    unlink($packageData['image']);
                }
                $image = $targetFile;
            } else {
                $message = "Terjadi kesalahan saat mengupload file Anda.";
            }
        }
    } else {
        // Jika gambar kosong, gunakan gambar lama
        $image = $packageData['image'];
    }

    // Panggil fungsi editPackage
    if (editPackage($package_id, $name, $description, $price, $image)) {
        $message = "Berhasil memperbarui paket!";
        $alert_type = "success";

        $packageData = getPackageData($package_id);
    } else {
        $message = "Oops! Gagal memperbarui paket...";
        $alert_type = "warning";
    }

    header("Location: packages.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit();
}
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
                        <li class="breadcrumb-item active" aria-current="page">Paket</li>
                    </ol>
                </nav>
                <h1 class="h2">Package Manager</h1>
                <p>Easily Manage Wedding Package</p>
                <a href="#" class="btn btn-sm btn-primary">Add Package</a>
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Package Data</h5>
                            <div class="card-body">
                                <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $package_id; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Package Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($packageData['p_name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($packageData['p_description']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($packageData['p_price']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <small>*! Kosongkan jika tidak ingin mengganti gambar.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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