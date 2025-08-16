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

  <!-- Modal de Bootstrap para editar los datos -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Actualizar Información de Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Nombre de usuario (editable en el modal) -->
          <div class="mb-3">
            <label class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="modal_user_name" require>
          </div>

          <!-- Email (editable en el modal) -->
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="modal_email" require>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="saveChangesButton">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- section -->
  <section>
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row">
        <!-- col -->
        <div class="col-12">
          <div class="p-6 d-flex justify-content-between align-items-center d-md-none">
            <!-- heading -->
            <h3 class="fs-5 mb-0">Account Setting</h3>
            <!-- btn -->
            <button class="btn btn-outline-gray-400 text-muted d-md-none" type="button" data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasAccount" aria-controls="offcanvasAccount">
              <i class="feather-icon icon-menu"></i>
            </button>
          </div>
        </div>
        <!-- col -->
        <div class="col-lg-3 col-md-4 col-12 border-end  d-none d-md-block">
          <div class="pt-10 pe-lg-10">
            <!-- nav item -->
            <ul class="nav flex-column nav-pills nav-pills-dark">
              <li class="nav-item">
                <a class="nav-link " aria-current="page" href="account-orders"><i
                    class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link active" href="account-settings"><i
                    class="feather-icon icon-settings me-2"></i>Configuración</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <hr>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link " href="Views/login/logout.php"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-9 col-md-8 col-12">
          <div class="p-6 p-lg-10">
            <div class="mb-6">
              <!-- heading -->
              <h2 class="mb-0">Configuración de la cuenta</h2>
            </div>
            <div>
              <!-- heading -->
              <h5 class="mb-4">Detalles</h5>
              <div class="row">
                <div class="col-lg-5">
                  <!-- form -->
                  <form id="userForm">
                    <!-- Nombre de usuario (inicialmente bloqueado) -->
                    <input type="hidden" id="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">

                    <div class="mb-3">
                      <label class="form-label">Nombre de usuario</label>
                      <input type="text" class="form-control" id="user_name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>" readonly>
                    </div>

                    <!-- Email (inicialmente bloqueado) -->
                    <div class="mb-3">
                      <label class="form-label">Dirección de correo</label>
                      <input type="email" class="form-control" id="email" value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') : ''; ?>" readonly>
                    </div><br>

                    <!-- Botón para abrir el modal -->
                    <div class="mb-3">
                      <button type="button" class="btn btn-primary" id="openModalButton">Actualizar Información</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <hr class="my-10">
            <div class="pe-lg-14">
              <!-- heading -->
              <h5 class="mb-4">Actualizar Contraseña</h5>
              <form id="formCambiarContrasenia" onsubmit="cambiarContrasenia(event)">
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Contraseña Actual</label>
                  <input type="password" class="form-control" placeholder="" id="contrasenia_actual" required>
                </div>
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Nueva Contraseña</label>
                  <input type="password" class="form-control" placeholder="" id="nueva_contrasenia" required>
                </div>
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Confirmar nueva Contraseña</label>
                  <input type="password" class="form-control" placeholder="" id="confirmar_contrasenia" required>
                </div>
                <!-- input -->
                <div class="col-12">
                  <p class="mb-4">¿No recuerdas tu contraseña actual?<a href="forgot-password"> Restablece tu contraseña.</a></p>
                  <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                </div>
              </form>
              <div id="mensaje"></div> <!-- Aquí mostraremos la respuesta -->
            </div>
            <hr class="my-10">
            <div class="mb-6">
              <!-- heading -->
              <h1 class="mb-0">Puntos de canje</h1><br>
              <h3>Tienes <span id="puntosUsuario"">0</span> puntos acumulados 🎁</h3>

              <a href=" canje.html">Ir a Canjear Puntos</a><br>
                  <a href="historial.html">Ver Historial de Canjes</a>
            </div>
            <br><br>
            <div>
              <!-- heading -->
              <h5 class="mb-4">Eliminar Cuenta</h5>
              <p class="mb-2">¿Quieres eliminar tu cuenta?</p>
              <p class="mb-5">Esta cuenta puede contener pedidos. Al eliminarla, se eliminarán todos los detalles de los pedidos asociados.</p>
              <!-- btn -->
              <button id="eliminarCuenta" class="btn btn-danger">Eliminar Cuenta</button>
              <div id="mensaje"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- modal -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasAccount" aria-labelledby="offcanvasAccountLabel">
    <!-- offcanvas header -->
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasAccountLabel">Offcanvas</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- offcanvas body -->
    <div class="offcanvas-body">
      <ul class="nav flex-column nav-pills nav-pills-dark">
        <!-- nav item -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="account-orders.html"><i
              class="feather-icon icon-shopping-bag me-2"></i>Your Orders</a>
        </li>
        <!-- nav item -->

        <li class="nav-item">
          <a class="nav-link active" href="account-settings.html"><i
              class="feather-icon icon-settings me-2"></i>Settings</a>
        </li>
        <!-- nav item -->

        <li class="nav-item">
          <a class="nav-link" href="account-address.html"><i class="feather-icon icon-map-pin me-2"></i>Address</a>
        </li>
        <!-- nav item -->

        <li class="nav-item">
          <a class="nav-link" href="account-payment-method.html"><i
              class="feather-icon icon-credit-card me-2"></i>Payment Method</a>
        </li>
        <!-- nav item -->

        <li class="nav-item">
          <a class="nav-link" href="account-notification.html"><i
              class="feather-icon icon-bell me-2"></i>Notification</a>
        </li>
      </ul>
      <hr class="my-6">
      <div>
        <!-- navs -->
        <ul class="nav flex-column nav-pills nav-pills-dark">
          <!-- nav item -->
          <li class="nav-item">
            <a class="nav-link " href="Views/login/logout.php"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?php require_once "Views/Navigation/footer.php"; ?>


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
  <script>
                    const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

    document.addEventListener("DOMContentLoaded", function() {
      const usuarioId = document.getElementById("user_id").value;
      fetch(`http://localhost:8088/Milogar/Controllers/UsuarioController.php?action=obtenerPuntos&usuario_id=${usuarioId}`)
        .then((response) => response.json())
        .then((data) => {
          document.getElementById("puntosUsuario").textContent = data.puntos;
        })
        .catch((error) => {
          console.error("Error al obtener puntos:", error);
          document.getElementById("puntosUsuario").textContent = "Error";
        });
    });
  </script>
</body>


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>