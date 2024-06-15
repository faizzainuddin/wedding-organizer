<?php
require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <title>Wedding Organizer - UNPwedding Landing Page</title>
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

  <div class="pre-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-sm-9">
          <div class="left-info">
            <ul>
              <li><a href="#"><i class="fa fa-phone"></i>+62 221032211</a></li>
              <li><a href="#"><i class="fa fa-envelope"></i>info@unpwedding.com</a></li>
              <li><a href="#"><i class="fa fa-map-marker"></i>Jl. Pakuan, RT.02/RW.06, Tegallega, Kota Bogor, Jawa Barat
                  16129</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-sm-3">
          <div class="social-icons">
            <ul>
              <li><a href="#"><i class="fab fa-facebook"></i></a></li>
              <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
              <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
            </ul>
          </div>
        </div>
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
              <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
              <li class="scroll-to-section"><a href="#package">Paket</a></li>
              <li class="scroll-to-section"><a href="#galeri">Galeri Photo</a></li>
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

  <div class="main-banner" id="top">
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="caption header-text">
            <h6>Wedding Organizer</h6>
            <div class="line-dec"></div>
            <h4>Rencanakan <em>Pernikahan Impian</em> Anda <span>Bersama Kami</span></h4>
            <p><strong>UNPwedding</strong> adalah adalah jawaban atas impian pernikahan Anda.
              Dari konsep hingga pelaksanaan, kami hadir untuk menyalakan percikan-pencikan magis
              yang membuat setiap momen berharga.
            <div class="main-button scroll-to-section"><a href="#package">Pilih Paket</a></div>
            <div class="second-button"><a href="login.php">Masuk</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="services section" id="services">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 offset-lg-6">
          <div class="row">
            <div class="col-lg-12">
              <div class="section-heading">
                <h2>Mengapa harus <em>memilih kami?</em>
                </h2>
                <div class="line-dec"></div>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="service-item">
                <div class="icon">
                  <i class="fa fa-solid fa-heart fa-2x"></i>
                </div>
                <h4>Pemilihan paket yang lengkap</h4>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="service-item">
                <div class="icon">
                  <i class="fa fa-solid fa-money-bill-wave fa-2x"></i>
                </div>
                <h4>Harga terjangkau</h4>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="service-item">
                <div class="icon">
                  <i class="fa fa-solid fa-wand-magic-sparkles fa-2x"></i>
                </div>
                <h4>Dekorasi yang menarik</h4>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="service-item">
                <div class="icon">
                  <i class="fa fa-solid fa-place-of-worship fa-2x"></i>
                </div>
                <h4>Pemilihan Venue Yang Lengkap</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="package section">
    <div class="container">
      <div class="main-content" id="package">
        <div class="row">
          <div class="section-heading">
            <h2>Paket <em>Wedding</em></h2>
            <div class="line-dec"></div>
            <div class="container mt-5">
              <div class="row">
                <?php
                require_once("config.php");

                function getPackages()
                {
                  global $conn;
                  $result = $conn->query("SELECT * FROM packages");
                  return $result->fetch_all(MYSQLI_ASSOC);
                }

                $package = getPackages();
                $no = 1;
                foreach ($package as $pkg) {
                  echo "<div class='col-md-4 mb-3'>";
                  echo "<div class='card'>";
                  echo "<img src='dashboard/" . $pkg['p_image'] . "' class='card-img-top' alt='Paket Wedding'>";
                  echo "<div class='card-body'>";
                  echo "<h5 class='card-title'>" . $pkg['p_name'] . "</h5>";
                  echo "<p class='card-text'>" . $pkg['p_description'] . "</p>";
                  echo "<a href='package_detail.php?id=" . $pkg['p_id'] . "' class='btn btn-primary'>Detail Paket</a>";
                  echo "</div>";
                  echo "</div>";
                  echo "</div>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="galeri section" id="galeri">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="section-heading">
            <h2>Galeri <em>Photo</em> </h2>
            <div class="line-dec"></div>
            <p>Berikut adalah galeri photo klien kami.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="owl-features owl-carousel">
            <div class="item">
              <img src="assets/images/galeri/1.png" alt="">
              <div class="down-content">
                <h4>Beloved Venue</h4>
                <a href="#"><i class="fa fa-link"></i></a>
              </div>
            </div>
            <div class="item">
              <img src="assets/images/galeri/2.png" alt="">
              <div class="down-content">
                <h4>A Dream Event Venue</h4>
                <a href="#"><i class="fa fa-link"></i></a>
              </div>
            </div>
            <div class="item">
              <img src="assets/images/galeri/3.png" alt="">
              <div class="down-content">
                <h4>Moonlit Bliss Events</h4>
                <a href="#"><i class="fa fa-link"></i></a>
              </div>
            </div>
            <div class="item">
              <img src="assets/images/galeri/4.png" alt="">
              <div class="down-content">
                <h4>A Wishing Heart</h4>
                <a href="#"><i class="fa fa-link"></i></a>
              </div>
            </div>
            <div class="item">
              <img src="assets/images/galeri/5.png" alt="">
              <div class="down-content">
                <h4>To The Happy Couple!</h4>
                <a href="#"><i class="fa fa-link"></i></a>
              </div>
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
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>


</body>

</html>