<?php
require_once("function_login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = preventSQLInjection($_POST['username_email']);
    $password = preventSQLInjection($_POST['password']);

    if (loginUser($username_email, $password)) {
        // Jika login berhasil, redirect ke halaman dashboard
        header("Location: dashboard/index.php");
        exit;
    } else {
        $message = "Login gagal. Periksa kembali username/email dan password Anda.";
        $alert_type = "error";
        header("Location: login.php?message=" . urlencode($message) . "&alert_type=" . urlencode($alert_type));
        // Jika login gagal, tampilkan pesan error
    }
}

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
                  title: '".($alert_type === 'success' ? 'Success!' : 'Warning!')."',
                  text: '$message',
                  confirmButtonText: 'OK'
              });
          });
    </script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Login - Wedding Organizer</title>
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
                                                <h3 class="mb-3">Login</h3>
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="login-form" class="row g-4">
                                                    <div class="col-12">
                                                        <label>Username or Email<span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                            <input type="text" class="form-control" id="username_email" name="username_email" placeholder="Masukkan Username atau Email" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label>Password<span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="inlineFormCheck">
                                                            <label class="form-check-label" for="inlineFormCheck">Remember me</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <a href="#" class="float-end text-danger">Forgot Password?</a>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <p>Don't have an account?</p><a href="register.php" class="float-">Register Now</a>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="submit" value="Login" class="btn btn-primary px-4 float-end mt-4">login</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-md-5 ps-0 d-none d-md-block rounded">
                                            <div class="form-right h-100 text-white text-center pt-5">
                                                <img class="" href="assets/images/logo.png">
                                                <i class="fas fa-user-lock fa-7x"></i>
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
            document.getElementById("login-form").reset();
        };
    </script>

</body>

</html>