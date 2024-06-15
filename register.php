<?php
include 'function_register.php'; // Include file function_register.php

// Mengatur zona waktu menjadi WIB
date_default_timezone_set('Asia/Jakarta');

// Pesan kesalahan akan ditampilkan di sini
$error = '';

// Proses form pendaftaran jika ada pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan membersihkannya untuk mencegah SQL injection
    $username = preventSQLInjection($_POST['username']);
    $password = preventSQLInjection($_POST['password']);
    $email = preventSQLInjection($_POST['email']);
    $level = 'User'; // Level pengguna default
    $first_name = preventSQLInjection($_POST['first_name']);
    $last_name = preventSQLInjection($_POST['last_name']);
    $address = preventSQLInjection($_POST['address']);
    $city = preventSQLInjection($_POST['city']);
    $zipcode = preventSQLInjection($_POST['zipcode']);
    $telephone = preventSQLInjection($_POST['telephone']);

    // Panggil fungsi registerUser
    if (registerUser($username, $password, $email, $level, $first_name, $last_name, $address, $city, $zipcode, $telephone)) {
        // Redirect ke halaman login setelah pendaftaran berhasil
        $message = "Berhasil mendaftar pengguna!";
        $alert_type = "success";
    } else {
        $error = "Oops! Gagal mendaftar pengguna...";
        $alert_type = "warning";
    }
    header("Location: login.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Register - Wedding Organizer</title>
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

    <div class="page-heading mt-0">
        <div class="container">
            <div class="row">
                <div class="login-page bg-light">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="bg-white shadow rounded">
                                    <div class="row">
                                        <div class="col-md-7 pe-0">
                                            <div class="form-left h-100 py-5 px-5">
                                                <h3 class="mb-3">Register</h3>
                                                <h3 class="login-title">-</h3>
                                                <form action="" method="POST" id="register-form" class="row g-4">
                                                    <div class="col-12">
                                                        <label>Username</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-at"></i></div>
                                                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Nama Pengguna" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label>Email</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Alamat Email" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Password</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Nama Depan</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Masukkan Nama Depan" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Nama Belakang</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Masukkan Nama Belakang" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Alamat</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-map-marker"></i></div>
                                                            <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan Alamat Rumah" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Kota</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-city"></i></div>
                                                            <input type="text" class="form-control" id="city" name="city" placeholder="Masukkan Kota Asal" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Kodepos</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-building"></i></div>
                                                            <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Masukkan Kodepos" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Telepon</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                                            <input type="number" class="form-control" id="telephone" name="telephone" placeholder="Masukkan Nomor Telepon" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-8">
                                                        <p>Already have an account?</p><a href="login.php" class="float-">Login now</a>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="submit" value="Register" class="btn btn-primary px-4 float-end mt-4">Register</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-md-5 ps-0 d-none d-md-block rounded">
                                            <div class="form-right h-100 text-white text-center pt-5">
                                                <img class="" href="assets/images/logo.png">
                                                <i class="fas fa-user-plus fa-7x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script>
        // Reset formulir saat halaman dimuat ulang
        window.onload = function() {
            document.getElementById("register-form").reset();
        };
    </script>

</body>

</html>