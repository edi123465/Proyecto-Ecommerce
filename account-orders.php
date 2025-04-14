<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from freshcart.codescandy.com/pages/account-orders.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>MILOGAR - Pedidos de usuario</title>
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

<?php
  //Se incluye la vista para la barra de navegacion
  require_once "Views/Navigation/navigation.php";

  ?>


  <!-- Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fs-3 fw-bold" id="userModalLabel">Sign Up</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="fullName" class="form-label">Name</label>
              <input type="text" class="form-control" id="fullName" placeholder="Enter Your Name" required="">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter Email address" required="">
            </div>

            <div class="mb-5">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Enter Password" required="">
              <small class="form-text">By Signup, you agree to our <a href="#!">Terms of Service</a> & <a
                  href="#!">Privacy Policy</a></small>
            </div>

            <button type="submit" class="btn btn-primary">Sign Up</button>
          </form>
        </div>
        <div class="modal-footer border-0 justify-content-center">

          Already have an account? <a href="#">Sign in</a>
        </div>
      </div>
    </div>
  </div>


  <script>
            const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
  </script>

  <!-- Modal para mostrar los detalles del pedido -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetallesLabel">Detalles del Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-pedido-body">
          <!-- Aquí se insertarán los detalles del pedido -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="actualizarPedido()">Actualizar</button>
          <button type="button" class="btn btn-success" onclick="confirmarPedido()">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Shop Cart -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
      <div class="text-start">
        <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Carrito de compras</h5>
        <small>Número de pedido: 0000001</small>
      </div>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="alert alert-danger" role="alert">
        Tienes envío GRATIS. ¡Empiece a pagar ahora!
      </div>
      <div>
        <div class="py-3">
          <ul id="cart-items" class="list-group list-group-flush">
            <!--Aqui se llenaran los productos del catalogo-->
          </ul>
        </div>
        <div class="d-grid">

          <button id="checkout-button" class="btn btn-primary btn-lg d-flex justify-content-between align-items-center" type="submit"> Go to
            Checkout <span id="cart-total" class="fw-bold">$0.00</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-body p-6">
          <div class="d-flex justify-content-between align-items-start ">
            <div>
              <h5 class="mb-1" id="locationModalLabel">Choose your Delivery Location</h5>
              <p class="mb-0 small">Enter your address and we will specify the offer you area. </p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="my-5">
            <input type="search" class="form-control" placeholder="Search your area">
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Select Location</h6>
            <a href="#" class="btn btn-outline-gray-400 text-muted btn-sm">Clear All</a>


          </div>
          <div>
            <div data-simplebar style="height:300px;">
              <div class="list-group list-group-flush">

                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action active">
                  <span>Alabama</span><span>Min:$20</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Alaska</span><span>Min:$30</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Arizona</span><span>Min:$50</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>California</span><span>Min:$29</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Colorado</span><span>Min:$80</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Florida</span><span>Min:$90</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Arizona</span><span>Min:$50</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>California</span><span>Min:$29</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Colorado</span><span>Min:$80</span></a>
                <a href="#"
                  class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                  <span>Florida</span><span>Min:$90</span></a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- section -->
  <section>
    <div class="container">
      <!-- row -->
      <div class="row">
        <!-- col -->
        <div class="col-12">
          <div class="p-6 d-flex justify-content-between align-items-center d-md-none">
            <!-- heading -->
            <h3 class="fs-5 mb-0">Account Setting</h3>
            <!-- button -->
            <button class="btn btn-outline-gray-400 text-muted d-md-none btn-icon " type="button"
              data-bs-toggle="offcanvas" data-bs-target="#offcanvasAccount" aria-controls="offcanvasAccount">
              <i class="feather-icon icon-menu fs-4"></i>
            </button>
          </div>
        </div>
        <!-- col -->
        <div class="col-lg-3 col-md-4 col-12 border-end  d-none d-md-block">
          <div class="pt-10 pe-lg-10">
            <!-- nav -->
            <ul class="nav flex-column nav-pills nav-pills-dark">
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="account-orders"><i
                    class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link" href="account-settings"><i
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
            <!-- heading -->
             
            <h2 class="mb-6">Tus pedidos</h2>
            <h3>Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

            <div class="table-responsive border-0">
              <!-- Table -->
              <table id="tabla-pedidos" class="table mb-0 text-nowrap">
                <!-- Table Head -->
                <thead class="table-light">
                  <tr>
                    <th>Nombre de usuario</th>
                    <th class="border-0"># Pedido</th>
                    <th class="border-0">Fecha</th>
                    <th class="border-0">Items</th>
                    <th class="border-0">Estado</th>
                    <th class="border-0">Subtotal</th>
                    <th class="border-0">IVA(15%)</th>
                    <th class="border-0">Total</th>
                    <th class="border-0">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table body -->


                </tbody>
              </table>
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
          <a class="nav-link active" aria-current="page" href="account-orders"><i
              class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
        </li>
        <!-- nav item -->
        <li class="nav-item">
          <a class="nav-link " href="account-settings"><i class="feather-icon icon-settings me-2"></i>Configuración</a>
        </li>

      </ul>
      <hr class="my-6">
      <div>
        <!-- nav  -->
        <ul class="nav flex-column nav-pills nav-pills-dark">
          <!-- nav item -->
          <li class="nav-item">
            <a class="nav-link " href="../index.html"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
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

  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <script src="assets/js/cart.js"></script>
  <script src="assets/js/mostrarpedidos.js"></script>
<script src="assets/js/login.js"></script>
<script src="assets/js/BusquedaDinamica.js"></script>
  <!-- choose one -->
</body>
<!-- Mirrored from freshcart.codescandy.com/pages/account-orders.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>