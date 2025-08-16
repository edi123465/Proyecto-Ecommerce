<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>MILOGAR - Olvidaste tu contraseña</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="FreshCart is a beautiful eCommerce HTML template specially designed for multipurpose shops & online stores selling products. Most Loved by Developers to build a store website easily.">
  <meta content="Codescandy" name="author" />


  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/x-icon" href="assets/imagenesMilogar/logomilo.jpg">



  <!-- Libs CSS -->
  <link href="assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet" />
  <link href="assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
  <link href="assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
  <link href="assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />
  <link href="assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
  <link href="assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
  <link href="assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
  <link href="assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">

  <!-- Theme CSS -->
  <link rel="stylesheet" href="assets/css/theme.min.css">
</head>

<body>

  <?php require_once "Views/Navigation/navigation.php"; ?>


  <!-- section -->
  <section class="my-lg-14 my-8">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-6 col-lg-4 order-lg-1 order-2">
          <!-- img -->
          <img src="assets/imagenesMilogar/logomilo.jpg" alt="" class="img-fluid">
        </div>
        <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1 d-flex align-items-center">
          <div>
            <div class="mb-lg-9 mb-5">
              <!-- heading -->
              <h1 class="mb-2 h2 fw-bold">
                Recupera tu contraseña</h1>
              <p>Ingrese la dirección de correo electrónico asociada a su cuenta y le enviaremos un enlace para restablecer su contraseña.
              </p>
            </div>
            <!-- form -->
            <form id="formRecuperar">
              <!-- row -->
              <div class="row g-3">
                <!-- col -->
                <div class="col-12">
                  <!-- input -->
                  <input type="email" class="form-control" id="email" name="email" placeholder="Direccion de correo electrónico" required>
                </div>

                <!-- btn -->
                <div class="col-12 d-grid gap-2"> <button type="submit" class="btn btn-primary">Restablecer contraseña</button>
                  <a href="index" class="btn btn-light">Regresar al inicio</a>
                </div>


              </div>
            </form>
            <p id="mensaje"></p>

          </div>
        </div>
      </div>
    </div>


  </section>
  <?php require_once "Views/Navigation/footer.php"; ?>


  </div>
  </div>
  <!-- Javascript-->
  <!-- Libs JS -->
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
  <script src="assets/libs/slick-carousel/slick/slick.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="assets/libs/nouislider/dist/nouislider.min.js"></script>
  <script src="assets/libs/wnumb/wNumb.min.js"></script>
  <script src="assets/libs/rater-js/index.js"></script>
  <script src="assets/libs/prismjs/prism.js"></script>
  <script src="assets/libs/prismjs/components/prism-scss.min.js"></script>
  <script src="assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
  <script src="assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
  <script src="assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
  <script src="assets/libs/dropzone/dist/min/dropzone.min.js"></script>
  <script src="assets/libs/flatpickr/dist/flatpickr.min.js"></script>
  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <script src="assets/js/cart.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/BusquedaDinamica.js"></script>
  <script src="assets/js/restablecerClavePorEmail.js"></script>
  <!-- choose one --> 
</body>
<!-- Mirrored from freshcart.codescandy.com/pages/forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>