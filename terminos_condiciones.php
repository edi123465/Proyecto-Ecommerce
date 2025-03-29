<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
//trae informacion del carrito de compras
echo $userId . "   " . $userId;
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>MILOGAR - Account Settings</title>
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
</head>

<body>

  <?php
  require_once "Views/Navigation/navigation.php";
  require_once "Models/UsuarioModel.php";
  // Verificar si el usuario está logueado y si es un usuario registrado o invitado
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $ocultarDireccion = true;
    if ($_SESSION['user_name'] === 'Invitado') {
      // Si está logueado como invitado, mostrar mensaje específico
      $esInvitado = true;
    } else {
      // Si está logueado como usuario registrado, mostrar su nombre
      $esInvitado = false;
    }
  } else {
    $ocultarDireccion = false;
    // Si no está logueado, mostrar un mensaje o algo diferente
    $esInvitado = true;  // Consideramos a los no logueados como invitados
  }





  require_once "Controllers/ProductoController.php";
  require_once "Config/db.php";

  function getDatabaseConnection()
  {
    $db = new Database1();
    return $db->getConnection();
  }

  $connection = getDatabaseConnection();
  $numeroPedido = new ProductoController($connection);
  $numPedido = $numeroPedido->generarNumeroPedido();
  $fechaActual = $numeroPedido->obtenerFechaActual();

  $usuarioModel = new UsuarioModel($connection);
  $email = null;

  if ($userId) {
    $email = $usuarioModel->getEmailByUserId($userId);
  }

  ?>


  <h1>Términos y condiciones</h1>
  <div class="container">
        <div class="terms-container">
            <h2 class="text-center">Términos y Condiciones</h2>
            <p class="text-muted text-center">Última actualización: <strong>Marzo 2025</strong></p>

            <h5>1. Gestión de Pedidos</h5>
            <p>Los pedidos se procesan en un plazo de 24 a 48 horas. Se enviará una confirmación por correo electrónico con los detalles del pedido y el estado del envío.</p>

            <h5>2. Métodos de Pago</h5>
            <p>Aceptamos pagos seguros a través de <strong>PayPhone</strong>, garantizando la protección de sus datos mediante encriptación avanzada y <strong>certificado SSL</strong>.</p>

            <h5>3. Seguridad y Protección de Datos</h5>
            <p>Implementamos medidas de seguridad de nivel bancario para proteger su información personal. No compartimos datos con terceros sin consentimiento.</p>

            <h5>4. Devoluciones y Reembolsos</h5>
            <p>Las solicitudes de reembolso deben realizarse en un plazo de 7 días tras la compra. Se evaluarán caso por caso para garantizar la satisfacción del cliente.</p>

            <h5>5. Contacto</h5>
            <p>Para cualquier consulta, contáctenos a través de <a href="mailto:soporte@milogar.com">soporte@milogar.com</a> o mediante nuestro chat en vivo.</p>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary">Aceptar y Continuar</a>
            </div>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <!-- choose one -->

  <script src="assets/js/cart.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/BusquedaDinamica.js"></script>
  <script src="assets/js/actualizarInformacion.js"></script>
</body>


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>