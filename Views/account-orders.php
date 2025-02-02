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
  <link rel="shortcut icon" type="image/x-icon" href="../assets/imagenesMilogar/logomilo.jpg">



  <!-- Libs CSS -->
  <link href="../assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="../assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet" />
  <link href="../assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
  <link href="../assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
  <link href="../assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />
  <link href="../assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
  <link href="../assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
  <link href="../assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
  <link href="../assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">

  <!-- Theme CSS -->
  <link rel="stylesheet" href="../assets/css/theme.min.css">
</head>

<body>

<?php
  //Se incluye la vista para la barra de navegacion
  require_once "../Views/Navigation/navigation.php";
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
                <a class="nav-link active" aria-current="page" href="account-orders.html"><i
                    class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link" href="account-settings.html"><i
                    class="feather-icon icon-settings me-2"></i>Settings</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link" href="account-address.html"><i
                    class="feather-icon icon-map-pin me-2"></i>Address</a>
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
              <!-- nav item -->
              <li class="nav-item">
                <hr>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link " href="login/logout.php"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-9 col-md-8 col-12">
          <div class="p-6 p-lg-10">
            <!-- heading -->
            <h2 class="mb-6">Tus pedidos</h2>

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
          <a class="nav-link active" aria-current="page" href="account-orders.html"><i
              class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
        </li>
        <!-- nav item -->
        <li class="nav-item">
          <a class="nav-link " href="account-settings.html"><i class="feather-icon icon-settings me-2"></i>Settings</a>
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

  <!-- Footer -->
  <!-- footer -->
  <div class="footer">
    <div class="container">
      <footer class="row g-4 py-4">
        <div class="col-12 col-md-12 col-lg-4">
          <h6 class="mb-4">Categories</h6>
          <div class="row">
            <div class="col-6">
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Vegetables & Fruits</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Breakfast & instant food</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Bakery & Biscuits</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Atta, rice & dal</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Sauces & spreads</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Organic & gourmet</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Baby care</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Cleaning essentials</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Personal care</a></li>
              </ul>
            </div>
            <div class="col-6">
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Dairy, bread & eggs</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Cold drinks & juices</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Tea, coffee & drinks</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Masala, oil & more</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Chicken, meat & fish</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Paan corner</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Pharma & wellness</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Home & office</a></li>
                <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Pet care</a></li>
              </ul>
            </div>
          </div>

        </div>
        <div class="col-12 col-md-12 col-lg-8">
          <div class="row g-4">
            <div class="col-6 col-sm-6 col-md-3">
              <h6 class="mb-4">Get to know us</h6>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="about.html" class="nav-link">Company</a></li>
                <li class="nav-item mb-2"><a href="about.html" class="nav-link">About</a></li>
                <li class="nav-item mb-2"><a href="blog.html" class="nav-link">Blog</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Help Center</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Our Value</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <h6 class="mb-4">For Consumers</h6>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Payments</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Shipping</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Product Returns</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">FAQ</a></li>
                <li class="nav-item mb-2"><a href="shop-shop-checkout.html" class="nav-link">Shop Checkout</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <h6 class="mb-4">Become a Shopper</h6>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Shopper Opportunities</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Become a Shopper</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Earnings</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Ideas & Guides</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">New Retailers</a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <h6 class="mb-4">Freshcart programs</h6>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Freshcart programs</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Gift Cards</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Promos & Coupons</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Freshcart Ads</a></li>
                <li class="nav-item mb-2"><a href="#!" class="nav-link">Careers</a></li>
              </ul>
            </div>
          </div>
        </div>





      </footer>
      <div class="border-top py-4">
        <div class="row align-items-center">
          <div class="col-lg-5 text-lg-start text-center mb-2 mb-lg-0">
            <ul class="list-inline mb-0">
              <li class="list-inline-item text-dark">Payment Partners</li>
              <li class="list-inline-item">
                <a href="#!"><img src="../assets/images/payment/amazonpay.svg" alt=""></a>
              </li>
              <li class="list-inline-item">
                <a href="#!"><img src="../assets/images/payment/american-express.svg" alt=""></a>
              </li>
              <li class="list-inline-item">
                <a href="#!"><img src="../assets/images/payment/mastercard.svg" alt=""></a>
              </li>
              <li class="list-inline-item">
                <a href="#!"><img src="../assets/images/payment/paypal.svg" alt=""></a>
              </li>
              <li class="list-inline-item">
                <a href="#!"><img src="../assets/images/payment/visa.svg" alt=""></a>
              </li>
            </ul>
          </div>
          <div class="col-lg-7 mt-4 mt-md-0">
            <ul class="list-inline mb-0 text-lg-end text-center">
              <li class="list-inline-item mb-2 mb-md-0 text-dark">Get deliveries with FreshCart</li>
              <li class="list-inline-item ms-4">
                <a href="#!"> <img src="../assets/images/appbutton/appstore-btn.svg" alt=""
                    style="width: 140px;"></a>
              </li>
              <li class="list-inline-item">
                <a href="#!"> <img src="../assets/images/appbutton/googleplay-btn.svg" alt=""
                    style="width: 140px;"></a>
              </li>
            </ul>
          </div>
        </div>

      </div>
      <div class="border-top py-4">
        <div class="row align-items-center">
          <div class="col-md-6"><span class="small text-muted">Copyright 2023 © FreshCart eCommerce HTML Template. All rights reserved. Powered by Codescandy.</span></div>
          <div class="col-md-6">
            <ul class="list-inline text-md-end mb-0 small mt-3 mt-md-0">
              <li class="list-inline-item text-muted">Follow us on</li>
              <li class="list-inline-item me-1">
                <a href="#!" class="icon-shape icon-sm social-links"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-facebook" viewBox="0 0 16 16">
                    <path
                      d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                  </svg></a>
              </li>
              <li class="list-inline-item me-1">
                <a href="#!" class="icon-shape icon-sm social-links"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-twitter" viewBox="0 0 16 16">
                    <path
                      d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                  </svg></a>
              </li>
              <li class="list-inline-item">
                <a href="#!" class="icon-shape icon-sm social-links"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-instagram" viewBox="0 0 16 16">
                    <path
                      d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                  </svg></a>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- Javascript-->
  <!-- Libs JS -->
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
  <script src="../assets/libs/slick-carousel/slick/slick.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="../assets/libs/nouislider/dist/nouislider.min.js"></script>
  <script src="../assets/libs/wnumb/wNumb.min.js"></script>
  <script src="../assets/libs/rater-js/index.js"></script>
  <script src="../assets/libs/prismjs/prism.js"></script>
  <script src="../assets/libs/prismjs/components/prism-scss.min.js"></script>
  <script src="../assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
  <script src="../assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
  <script src="../assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
  <script src="../assets/libs/dropzone/dist/min/dropzone.min.js"></script>
  <script src="../assets/libs/flatpickr/dist/flatpickr.min.js"></script>

  <!-- Theme JS -->
  <script src="../assets/js/theme.min.js"></script>
  <script src="../assets/js/cart.js"></script>
  <script src="../assets/js/mostrarpedidos.js"></script>

  <!-- choose one -->
</body>
<!-- Mirrored from freshcart.codescandy.com/pages/account-orders.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>