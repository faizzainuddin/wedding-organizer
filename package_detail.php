<?php
require_once("config.php");
?>

<?php
if (isset($_GET['id'])) {
    $package_id = intval($_GET['id']);
} else {
    die("ID package tidak ditemukan.");
}

function formatRupiah($amount)
{
    return "Rp " . number_format($amount, 0, ',', '.');
}

// Fungsi untuk mengambil data package dari database
function getPackageData($package_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT p_name, p_description, p_price, p_image FROM packages WHERE p_id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];
    while ($row = $result->fetch_assoc()) {
        $row['p_price'] = formatRupiah($row['p_price']); // Format harga menjadi Rupiah
        $packages[] = $row;
    }

    return $packages;
}

// Ambil data package
$packages = getPackageData($package_id); // tambahkan parameter $package_id pada pemanggilan fungsi


// Fungsi untuk mendapatkan data barang berdasarkan package_id
function getGoodsByPackageId($package_id)
{
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("SELECT * FROM goods WHERE g_p_id = ? ORDER BY g_created_at DESC LIMIT 10");
    $stmt->bind_param("i", $package_id);

    // Jalankan statement SQL
    $stmt->execute();

    // Ambil hasil query
    $result = $stmt->get_result();

    // Ambil data barang sebagai array assosiatif
    $goods = [];
    while ($row = $result->fetch_assoc()) {
        $goods[] = $row;
    }

    return $goods;
}
$goods = getGoodsByPackageId($package_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Detail Paket - UNPwedding</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <style>
        h3 {
            font-size: 12pt;
            color: #A77B78;
            margin-top: -20px;
        }
        .booked-dates-list {
            list-style-type: none;
            padding: 0;
            display: flex;
            gap: 10px; /* Jarak antar elemen */
        }

        .booked-dates-list li {
            background-color: #ffdddd;
            border: 1px solid #ffaaaa;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 5px 0;
            color: #d8000c;
            font-weight: bold;
            text-align: center;
            max-width: 200px;
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
                            <li class="scroll-to-section"><a href="index.php#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="index.php#package">Paket</a></li>
                            <li class="scroll-to-section"><a href="index.php#galeri">Galeri Photo</a></li>
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

    <div class="page-detail mt-0">
        <div class="container">
            <div class="row">
                <?php
                foreach ($packages as $pkg) {
                    echo "<div class='col-lg-5 mt-5'>";
                    echo "<div id='iniCarousel' class='carousel slide' data-bs-ride='carousel'>";
                    echo "<div class='carousel-inner'>";
                    echo "<img src='dashboard/" . $pkg['p_image'] . "' class='d-block w-100'>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='col-lg-7 align-self-center mt-5'>";
                    echo "<h6>Package Detail</h6>";
                    echo "<div class='line-package mt-2'></div>";
                    echo "<h4>" . $pkg['p_name'] . "</h4>";
                    echo "<div class='price-area my-4'>";
                    echo "<p class='mb-1'><strong>" . $pkg['p_price'] . "</strong></p>";
                    echo "<div class='product-details my-4'>";
                    echo "<p class='details-title text-color mb-1'>Product Details</p>";
                    echo "<p class='description'>" . $pkg['p_description'] . "</p>";
                } ?>
                <p class='details-title text-color mb-1'><strong>Termasuk : </strong></p>
                <?php
                foreach ($goods as $g) {
                    echo "<p class='description'>" . $g['g_name'] . "</p>";
                } ?>
            </div>
        </div>
        <div class="buttons d-flex my-5">
            <a href="orders.php?package_id=<?php echo $package_id; ?>" class="shadow btn custom-btn">Pesan Sekarang</a>
            <input type="number" class="" id="cart_quantity" value="1" min="1" max="1" placeholder="Enter email" name="cart_quantity" disabled>
        </div>
        <h3>Tanggal yang sudah terbooking: </h3>
        <ul id="booked-dates" class="booked-dates-list"></ul>
    </div>
    </div>
    <div class="row questions bg-light p-3">
        <div class="col-md-1 icon">
            <i class="fa fa-brands fa-rocketchat questions-icon"></i>
        </div>
        <div class="col-md-11 text">
            Apabila ada pertanyaan seputar pemilihan paket, atau pemesanan kustom silahkan hubungi kami
            melalui e-mail admin@demo.km-dev.or.id
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <footer>
        <div class="container">
            <div class="col-lg-12 ">
                <p>Copyright © 2024 <a href="#">UNPwedding</a>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    <script>
        // Fungsi untuk mendapatkan parameter dari URL
        function getURLParameter(name) {
            return new URLSearchParams(window.location.search).get(name);
        }

        // Ambil parameter ID dari URL
        const packageId = getURLParameter('id');

        // URL dari data JSON dengan parameter ID
        const url = `http://localhost/Wedding%20Organizer/getExistingOrdersDate.php?id=${packageId}`;


        // Fungsi untuk mengambil data JSON dari server
        async function fetchBookedDates() {
            try {
                const response = await fetch(url);
                const dates = await response.json();
                displayBookedDates(dates);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Fungsi untuk mengubah format tanggal dari YYYY-MM-DD ke DD-MM-YYYY
        function formatDate(date) {
            const [year, month, day] = date.split('-');
            return `${day}-${month}-${year}`;
        }

        // Fungsi untuk menampilkan tanggal-tanggal yang sudah terbooking
        function displayBookedDates(dates) {
            const bookedDatesList = document.getElementById('booked-dates');
            dates.forEach(date => {
                const listItem = document.createElement('li');
                listItem.textContent = formatDate(date);
                bookedDatesList.appendChild(listItem);
            });
        }

        // Panggil fungsi untuk mengambil dan menampilkan data jika ada parameter ID
        if (packageId) {
            fetchBookedDates();
        } else {
            console.error('Package ID not found in URL');
        }
    </script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.14/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

</html>