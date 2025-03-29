<?php
session_start();
$form_disabled = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>Empieza a comprar</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Milogar - Tienda en línea con los mejores productos del hogar. Compra fácil, rápido y seguro. ¡Descubre nuestras ofertas!">
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
  <style>
    .whatsapp-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }

    .whatsapp-icon {
      position: relative;
      display: inline-block;
    }

    .whatsapp-icon img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      transition: transform 0.3s ease;
    }

    .whatsapp-icon:hover img {
      transform: scale(1.1);
    }

    .whatsapp-text {
      position: absolute;
      bottom: 70px;
      right: 0;
      background-color: #25D366;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      white-space: nowrap;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
      font-size: 14px;
      font-family: Arial, sans-serif;
    }

    .whatsapp-icon:hover .whatsapp-text {
      opacity: 1;
    }
  </style>
</head>

<body>

  <?php
  require_once "Views/Navigation/navigation.php";
  ?>


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
        <!-- col -->
        <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1">
          <div class="mb-lg-9 mb-5">
            <h1 class="mb-1 h2 fw-bold">Empiece a comprar</h1>
            <p>¡Bienvenido a MILOGAR! Ingrese sus datos para comenzar.</p>
          </div>
          <!-- form -->
          <form id="registerForm" method="POST">
            <div class="row g-3">
              <!-- col -->
              <div class="col-12">
                <!-- input --><input type="text" class="form-control" placeholder="Nombre de usuario" id="nombreUsuario" name="nombre_usuario" aria-label="First name" required <?php echo $form_disabled ? 'disabled' : ''; ?>
                  required>
              </div>
              <div class="col-12">
                <!-- input --><input type="email" class="form-control" placeholder="Correo electrónico" id="email" name="correo_electronico" aria-label="Last name" <?php echo $form_disabled ? 'disabled' : ''; ?>
                  required>
              </div>
              <div class="col-12">

                <!-- input --><input type="password" class="form-control" id="password" name="contrasenia" placeholder="Contraseña" <?php echo $form_disabled ? 'disabled' : ''; ?> required>
              </div>
              <div class="col-12">
                <!-- input --><input type="password" class="form-control" id="confirmPassword" name="confirmar_contrasenia" <?php echo $form_disabled ? 'disabled' : ''; ?> placeholder="Confirma tu contraseña"
                  required>
              </div>
               
              <?php if ($form_disabled): ?>
                <p>Cierra la sesión actual para poder crear una nueva cuenta.<a href="../Views/login/logout.php">Cerrar Sesión</a></p>
              <?php else: ?>
                <div class="col-12 d-grid"> <button type="submit" class="btn btn-primary">Registrar cuenta</button>
                <?php endif; ?>
                <!-- btn -->
                <div class="col-12 d-grid"> <a class="btn btn-light" href="../index.php">Visitar la tienda</a></button>

                </div>

                <!-- text -->
                <p><small>By continuing, you agree to our <a href="#!"> Terms of Service</a> & <a href="#!">Privacy
                      Policy</a></small></p>
                </div>
          </form>
        </div>
      </div>
    </div>


  </section>
  <div class="whatsapp-container">
    <a href="https://wa.me/593989082073" target="_blank" class="whatsapp-icon">
      <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
      <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
    </a>
  </div>
  <?php require_once "Views/Navigation/footer.php";  ?>


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
  <!-- choose one -->


  <script src="assets/js/registrarse.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/cart.js"></script>
  <script src="assets/js/BusquedaDinamica.js"> </script>

</body>


<!-- Mirrored from freshcart.codescandy.com/pages/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>