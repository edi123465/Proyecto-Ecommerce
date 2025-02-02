<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
//trae informacion del carrito de compras
echo $userId . "" . $userId;

// Si el carrito no está vacío
/*if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  // Recuperar los productos del carrito
  $cartItems = $_SESSION['cart'];

  // Calcular subtotal, IVA, descuento y total
  $subtotal = 0;
  foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
  }

  // IVA (por ejemplo, 19%)
  $iva = $subtotal * 0.19;

  // Descuento del 10%
  $descuento = $subtotal * 0.10;

  // Total a pagar
  $total = $subtotal + $iva - $descuento;
} else {
  echo "El carrito está vacío.";
  exit;
}*/
?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from freshcart.codescandy.com/pages/shop-checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:29 GMT -->

<head>

  <title>MILOGAR - Detalle de compra</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="FreshCart is a beautiful eCommerce HTML template specially designed for multipurpose shops & online stores selling products. Most Loved by Developers to build a store website easily.">
  <meta content="Codescandy" name="author" />


  <!-- Favicon icon-->
  <link rel="icon" href="../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap JS (necesario para Collapse) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Theme CSS -->
  <link rel="stylesheet" href="../assets/css/theme.min.css">
  <style>
    /* Estilos para el botón de edición */
    .btn-edit {
      background-color: #28a745;
      /* Fondo verde */
      border: none;
      /* Sin borde */
      border-radius: 15%;
      /* Hacer el botón redondeado */
      padding: 10px;
      /* Padding alrededor del icono */
      color: white;
      /* Color del icono (Tápiz) */
      font-size: 20px;
      /* Tamaño del icono */
      transition: background-color 0.3s ease;
      /* Transición suave para el cambio de color */
    }

    .btn-edit:hover {
      background-color: #218838;
      /* Fondo verde más oscuro al pasar el mouse */
      cursor: pointer;
      /* Cambio del cursor al pasar por encima */
    }

    .btn-edit i {
      font-size: 22px;
      /* Aumentar el tamaño del icono del lápiz */
    }

    .position-relative {
      position: relative;
    }
  </style>
</head>

<body>
  <?php
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
  <!-- section-->
  <div class="mt-4">
    <div class="container">
      <!-- row -->
      <div class="row ">
        <!-- col -->
        <div class="col-12">
          <!-- breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="#!">Home</a></li>
              <li class="breadcrumb-item"><a href="#!">Shop</a></li>
              <li class="breadcrumb-item active" aria-current="page">Shop Checkout</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- section -->
  <section class="mb-lg-14 mb-8 mt-8">
    <div class="container">
      <!-- row -->
      <div class="row">
        <!-- col -->
        <div class="col-12">
          <div>
            <div class="mb-8">
              <!-- text -->
              <h1 class="fw-bold mb-0">Verificar</h1>
              <p class="mb-0">
                ¿Ya tienes una cuenta? Haga clic aquí para <a href="#!">iniciar sesión.</a>.</p>
            </div>
          </div>
        </div>
      </div>
      <div>
        <!-- row -->
        <div class="row">
          <div class="col-lg-7 col-md-12">
            <!-- accordion -->
            <div class="accordion accordion-flush" id="accordionFlushExample">
              <!-- accordion item -->
              <div class="accordion-item py-4">

                <div class="d-flex justify-content-between align-items-center">
                  <!-- heading one -->
                  <a href="#" class="fs-5 text-inherit collapsed h4" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                    <i class="feather-icon icon-map-pin me-2 text-muted"></i>Direcciones disponibles
                  </a>
                  <!-- btn -->
                  <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#addAddressModal">agregar una nueva dirección
                  </a>
                  <!-- collapse -->
                </div>


                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                  data-bs-parent="#accordionFlushExample">
                  <div class="mt-5">
                    <div class="row">
                      <div class="col-12">
                        <div class="row direccionesContainer">

                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <!-- Enlace que activa el Collapse para el tipo de pago -->
              <br>
              <a href="#" class="text-inherit h5" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseTipoPago" aria-expanded="false" aria-controls="flush-collapseTipoPago">
                <i class="feather-icon icon-credit-card me-2 text-muted"></i>Opciones de entrega
              </a>

              <!-- Sección de Collapse para el tipo de pago -->
              <div id="flush-collapseTipoPago" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushExample">
                <div class="mt-5">
                  <div class="row">
                    <div class="col-12">
                      <!-- Opciones de entrega -->
                      <div>
                        <!-- Opción Recoger en tienda -->
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="deliveryOption" id="recogerEnTienda" value="recogerEnTienda">
                          <label class="form-check-label" for="recogerEnTienda">Recoger en tienda</label>
                        </div>
                        <!-- Opción Agregar dirección de envío -->
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="deliveryOption" id="agregarDireccionEnvio" value="agregarDireccionEnvio" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                          <label class="form-check-label" for="agregarDireccionEnvio">Agregar dirección de envío</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <!-- accordion item -->
              <div class="accordion-item py-4">

                <a href="#" class="text-inherit h5" data-bs-toggle="collapse"
                  data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                  <i class="feather-icon icon-credit-card me-2 text-muted"></i>Métodos de pago
                  <!-- collapse --> </a>
                <div id="flush-collapseFour" class="accordion-collapse collapse "
                  data-bs-parent="#accordionFlushExample">

                  <div class="mt-5">
                    <div>
                      <div class="card card-bordered shadow-none mb-2">
                        <!-- card body -->
                        <div class="card-body p-6">
                          <div class="d-flex">
                            <div class="form-check">
                              <!-- checkbox -->
                              <input class="form-check-input" type="radio" name="opcion" id="transferencia" value="transferencia_directa">
                              <label class="form-check-label ms-2" for="paypal">

                              </label>
                            </div>
                            <div>
                              <!-- title -->
                              <h5 class="mb-1 h6"> Transferencia directa</h5>
                              <p class="mb-0 small">Paga de manera rápida y segura.</p>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="card card-bordered shadow-none mb-2">
                        <!-- card body -->
                        <div class="card-body p-6">
                          <div class="d-flex">
                            <div class="form-check">
                              <!-- checkbox -->
                              <input class="form-check-input" type="radio" name="opcion" id="paypal" value="paypal">
                              <label class="form-check-label ms-2" for="paypal">

                              </label>
                            </div>
                            <div>
                              <!-- title -->
                              <h5 class="mb-1 h6"> Pago con Paypal</h5>
                              <p class="mb-0 small">Serás redirigido al sitio web de PayPal para completar tu compra de forma segura.</p>
                            </div>
                          </div>
                        </div>

                      </div>
                      <!-- card -->
                      <div class="card card-bordered shadow-none mb-2">
                        <!-- card body -->
                        <div class="card-body p-6">
                          <div class="d-flex mb-4">
                            <div class="form-check ">
                              <!-- input -->
                              <input class="form-check-input" type="radio" name="opcion" id="tarjetaDC" value="Tarjeta">
                              <label class="form-check-label ms-2" for="creditdebitcard">

                              </label>
                            </div>
                            <div>
                              <h5 class="mb-1 h6"> Tarjeta de crédito/débito</h5>
                              <p class="mb-0 small">Transferencia de dinero segura utilizando su cuenta bancaria. Admitimos Mastercard tercard, Visa, Discover y Stripe.</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <!-- input -->
                              <div class="mb-3">
                                <label class="form-label">Número de tarjeta
                                </label>
                                <input type="text" class="form-control" placeholder="1234 4567 6789 4321">
                              </div>
                            </div>
                            <div class="col-md-6 col-12">
                              <!-- input -->
                              <div class="mb-3 mb-lg-0">
                                <label class="form-label">Titular de la cuenta</label>
                                <input type="text" class="form-control" placeholder="Enter your first name">
                              </div>
                            </div>
                            <div class="col-md-3 col-12">
                              <!-- input -->
                              <div class="mb-3  mb-lg-0 position-relative">
                                <label class="form-label">Fecha de caducidad</label>
                                <input class="form-control flatpickr " type="text" placeholder="Select Date">
                                <div class="position-absolute bottom-0 end-0 p-3 lh-1">
                                  <i class="bi bi-calendar text-muted"></i>
                                </div>

                              </div>
                            </div>
                            <div class="col-md-3 col-12">
                              <!-- input -->
                              <div class="mb-3  mb-lg-0">
                                <label class="form-label">Código CVV</label>
                                <input type="text" class="form-control" placeholder="312">

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- card -->
                      <?php
                      require_once "../Controllers/ProductoController.php";
                      require_once "../Config/db.php";
                      // Función para obtener la conexión
                      function getDatabaseConnection()
                      {
                        $db = new Database1();
                        return $db->getConnection();
                      }
                      $connection = getDatabaseConnection();
                      $numeroPedido = new ProductoController($connection);
                      $numPedido = $numeroPedido->generarNumeroPedido();
                      $fechaActual = $numeroPedido->obtenerFechaActual();

                      ?>
                      <!-- card -->

                      <!-- Button -->
                      <div class="mt-5 d-flex justify-content-end">
                        <a href="shop-grid.php" class="btn btn-outline-gray-400 text-muted"
                          data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                          aria-controls="flush-collapseThree">Seguir comprando</a>
                        <a id="proceder" href="#" class="btn btn-primary ms-2"

                          data-user-id="<?php echo $_SESSION['user_id']; ?>"
                          data-numero-pedido="<?php echo isset($numPedido) ? $numPedido : ''; ?>"
                          data-fecha="<?php echo $fechaActual; ?>">
                          Proceder
                        </a>
                        <a id="generarPDF" href="shop-grid.php" class="btn btn-outline-gray-400 text-muted"
                          data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                          data-user-id="<?php echo $_SESSION['user_id']; ?>"
                          data-numero-pedido="<?php echo isset($numPedido) ? $numPedido : ''; ?>"
                          aria-controls="flush-collapseThree">GENERAR PDF</a>

                      </div>
                    </div>
                  </div>
                </div>

              </div>


            </div>

          </div>
          <div class="col-12 col-md-12 offset-lg-1 col-lg-4">
            <div class="mt-4 mt-lg-0">
              <div class="card shadow-sm">
                <h5 class="px-6 py-4 bg-transparent mb-0 text-center">Detalle de pedido
                  <h6>&nbsp;&nbsp;&nbsp;&nbsp;Número de pedido: <?php echo $numPedido; ?></h6>
                  <h6>&nbsp;&nbsp;&nbsp;&nbsp;Fecha actual: <?php echo $fechaActual; ?></h6>
                </h5>

                <!-- Opciones de factura o nota de venta -->
                <div class="mt-3">
                  <label class="form-label">Tipo de comprobante:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="comprobante" id="facturaElectronica" value="facturaElectronica" onclick="toggleFacturaForm()">
                    <label class="form-check-label" for="facturaElectronica">
                      Factura Electrónica
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="comprobante" id="notaVenta" value="notaVenta" onclick="toggleFacturaForm()">
                    <label class="form-check-label" for="notaVenta">
                      Nota de Venta
                    </label>
                  </div>
                </div>
                <!-- Collapse para formulario de Factura Electrónica -->
                <div class="collapse" id="facturaFormCollapse">
                  <div class="card card-body mt-3">
                    <h5>Datos de la Factura Electrónica</h5>
                    <form id="facturaForm">
                      <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email-address" placeholder="Ingresa tu correo electrónico" required>
                      </div>
                      <div class="mb-3">
                        <label for="cedula" class="form-label">Cédula o RUC</label>
                        <input type="text" class="form-control" id="cedula" placeholder="Ingresa tu cédula o RUC" required>
                      </div>
                      <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="celular" placeholder="Ingresa tu teléfono" required>
                      </div>
                      <!-- Agrega más campos según lo necesites -->
                    </form>
                  </div>
                </div>

                <!-- Detalles del pedido -->
                <ul class="list-group list-group-flush" id="order-details">
                  <!-- Los productos del carrito se generarán aquí con JavaScript -->
                </ul>

                <!-- Subtotal, IVA, descuento, Total -->
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div>Subtotal</div>
                      <div id="subtotal" class="fw-bold">$0.00</div>
                    </div>
                  </li>
                  <li class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div>IVA (15%)</div>
                      <div id="iva" class="fw-bold">$0.00</div>
                    </div>
                  </li>
                  <li class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div>Descuento (4%)</div>
                      <div id="descuento" class="fw-bold">-$0.00</div>
                    </div>
                  </li>
                  <li class="list-group-item px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between fw-bold">
                      <div>Total a pagar</div>
                      <div id="total" class="fw-bold">$0.00</div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h6>Are you sure you want to delete this address?</h6>
          <p class="mb-6">Jitu Chauhan<br>

            4450 North Avenue Oakland, <br>

            Nebraska, United States,<br>

            402-776-1106</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-gray-400" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal -->
  <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- modal body -->
        <div class="modal-body p-6">
          <div class="d-flex justify-content-between mb-5">
            <!-- heading -->
            <div>
              <h5 class="h6 mb-1" id="addAddressModalLabel">Nueva dirección de envío</h5>
              <p class="small mb-0">Agregue una nueva dirección de envío para la entrega de su pedido.</p>
            </div>
            <div>
              <!-- button -->
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>
          <!-- Formulario -->
          <form id="direccionForm" method="POST">
            <!-- row -->
            <div class="row g-3">
              <div class="col-12">
                <input type="hidden" class="form-control" name="estado" id="usuario_id" value="<?php echo htmlspecialchars($userId);  ?>">
              </div>
              <div class="col-12">
                <!-- País -->
                <select class="form-select" name="pais" id="pais">
                  <option selected="">País</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Colombia">Colombia</option>
                  <option value="México">México</option>
                  <option value="Estados Unidos">Estados Unidos</option>
                </select>
              </div>
              <div class="col-12">
                <!-- Estado o Provincia -->
                <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado o Provincia">
              </div>
              <div class="col-12">
                <!-- Dirección de envío -->
                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingresa tu dirección de envío">
              </div>
              <div class="col-12">
                <!-- Referencia (Opcional) -->
                <input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referencia (Opcional)">
              </div>
              <div class="col-12">
                <!-- Teléfono de contacto (Opcional) -->
                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono de contacto (Opcional)">
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="direccionPredeterminada" value="1" id="flexCheckDefault">
                  <!-- label -->
                  <label class="form-check-label" for="flexCheckDefault">
                    Marcar como dirección predeterminada
                  </label>
                </div>
              </div>
              <!-- botones -->
              <div class="col-12 text-end">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="saveAddressBtn" type="submit">Guardar Dirección</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>




  <!-- Footer -->
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
  <!-- choose one -->
  <script src="../assets/js/direcciones.js"></script>
  <script src="../assets/js/cart.js"></script>
  <script>
    function toggleFacturaForm() {
      const facturaRadio = document.getElementById('facturaElectronica');
      const notaVentaRadio = document.getElementById('notaVenta');
      const facturaForm = document.getElementById('facturaFormCollapse');

      // Si la opción "Factura Electrónica" está seleccionada, mostrar el formulario
      if (facturaRadio.checked) {
        facturaForm.classList.add('show');
      } else {
        facturaForm.classList.remove('show');
      }

      // Si la opción "Nota de Venta" está seleccionada, cerrar el formulario
      if (notaVentaRadio.checked) {
        facturaForm.classList.remove('show');
      }
    }
  </script>
</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:30 GMT -->

</html>