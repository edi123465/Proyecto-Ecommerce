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

  <?php require_once "../Views/Navigation/footer.php"; ?>
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