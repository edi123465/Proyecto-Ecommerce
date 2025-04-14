<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
//trae informacion del carrito de compras
echo $userId . "" . $userId;


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
    <link rel="shortcut icon" type="image/x-icon" href="assets/imagenesMilogar/logomilo.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


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
    <!-- Bootstrap CSS -->

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/theme.min.css">

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

        /* Media query for mobile viewport */
        @media screen and (max-width: 400px) {
            #paypal-button-container {
                width: 100%;
            }
        }

        /* Media query for desktop viewport */
        @media screen and (min-width: 400px) {
            #paypal-button-container {
                width: 250px;
            }
        }

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

        .btn-payphone {
            background-color: #1A1A1A;
            /* Fondo oscuro similar a PayPhone */
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-payphone:hover {
            background-color: #333;
            /* Cambia el fondo al pasar el mouse */
            transform: scale(1.05);
            /* Efecto de aumento al pasar el mouse */
        }

        .btn-payphone:focus {
            outline: none;
        }

        .btn-payphone img {
            max-height: 20px;
            /* Tamaño adecuado para el logo */
            margin-right: 10px;
        }

        #order-details-container {
            max-height: 700px;
            /* Ajusta la altura según tu necesidad */
            overflow-y: auto;
            /* Activa el scroll vertical */
            border: 1px solid #ddd;
            /* Opcional: Borde para mejor visibilidad */
            padding: 10px;
            /* Espaciado interno */
        }
    </style>
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




    <div class="whatsapp-container">
        <a href="https://wa.me/593989082073" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
    </div>

    <!-- section -->
    <section class="mb-lg-14 mb-8 mt-8">
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-12">
                    <!-- card -->

                    <div class="card py-1 border-0 mb-8">
                        <div>
                            <h1 class="fw-bold">Tu carrito de compras</h1>
                            <p class="mb-0">Número de pedido: <?php echo $numPedido; ?></p>

                        </div>

                    </div>
                </div>
                <div id="puntosUsuario">
                </div>
            </div>

            <!-- row -->
            <div class="row">
                <div class="col-lg-8 col-md-7">

                    <div class="py-3">
                        <!-- alert -->


                        <div id="order-details-container">
                            <ul id="order-details" class="list-group"></ul>
                        </div>

                        <div class="total">
                            <span>Total de puntos acumulados en este pedido: <span id="total-puntos">0.00</span></span>
                        </div>
                        <div class="total mt-2">
                            <span>Puntos a restar del total por este pedido: <span id="puntos-descontar">0</span></span>
                        </div>
                        <!-- btn -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="shop-grid" class="btn btn-primary">Continue Shopping</a>
                            <a href="#!" class="btn btn-dark">Update Cart</a>
                        </div>

                    </div>
                </div>

                <!-- sidebar -->
                <div class="col-12 col-lg-4 col-md-5">
                    <!-- card -->
                    <div class="mb-5 card mt-6">
                        <div class="card-body p-6">
                            <!-- heading -->
                            <h2 class="h5 mb-4">Información de envio</h2>

                            <form id="facturaForm">
                                <!-- Opciones de entrega SIEMPRE visibles -->
                                <div>
                                    <!-- Opción Recoger en tienda -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deliveryOption" id="recogerEnTienda" value="recogerEnTienda">
                                        <label class="form-check-label" for="recogerEnTienda">Recoger en tienda</label>
                                    </div>
                                    <!-- Opción Agregar dirección de envío -->
                                    <!-- Opción Agregar dirección de envío (ocultar si el usuario es invitado) -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deliveryOption" id="agregarDireccionEnvio" value="agregarDireccionEnvio">
                                        <label class="form-check-label" for="agregarDireccionEnvio">Agregar dirección de envío</label>
                                    </div>
                                </div>
                                <input type="hidden" id="user_name" value="<?php echo isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : ''; ?>">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email-address" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ingresa tu correo electrónico" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="celular" placeholder="Ingresa tu teléfono" required>
                                </div>
                                <div class="mb-3" id="direccionContainer">
                                    <label for="email" class="form-label">Dirección de envío</label>
                                    <input type="text" class="form-control" id="direccion" placeholder="Ingresa tu dirección de envio" required>
                                </div>
                                <!-- Agrega más campos según lo necesites -->
                            </form>
                            <h5 class="text-inherit">
                                <i class="feather-icon icon-credit-card me-2 text-muted"></i>Métodos de pago
                            </h5>

                            <!-- Opción: Transferencia directa -->
                            <div class="d-flex mb-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="opcion" id="transferencia" value="transferencia_directa">
                                    <label class="form-check-label ms-2" for="transferencia"></label>
                                </div>
                                <div>
                                    <h5 class="mb-1 h6">Transferencia directa</h5>
                                    <p class="mb-0 small">Aceptamos pagos por transferencia bancaria. Los detalles de la cuenta se enviarán después de finalizar tu pedido.</p>
                                </div>
                            </div>

                            <!-- Opción: Pago con Payphone -->
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="opcion" id="payphone" value="payphone">
                                    <label class="form-check-label ms-2" for="payphone"></label>
                                </div>
                                <div>
                                    <h5 class="mb-1 h6">Pago con Payphone</h5>
                                    <p class="mb-0 small">Realiza tu pago de forma segura usando Payphone desde tu celular o tarjeta.</p>
                                </div>
                            </div>

                            <div class="card mb-2">
                                <!-- list group -->
                                <ul class="list-group list-group-flush">
                                    <!-- list group item -->
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div>Subtotal</div>

                                        </div>
                                        <div id="subtotal" class="fw-bold">$0.00</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div>IVA(15%) </div>

                                        </div>
                                        <div id="iva" class="fw-bold">$0.00</div>
                                    </li>
                                    <!-- list group item -->
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div>Descuento</div>

                                        </div>
                                        <div id="descuento">-$0.00</div>
                                    </li>
                                    <!-- list group item -->
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div class="fw-bold">Total</div>

                                        </div>
                                        <span class="fw-bold" id="total">$67.00</span>
                                    </li>
                                </ul>

                            </div>


                            <!-- heading -->
                            <div class="mt-8">
                                <h2 class="h5 mb-3">Tienes cupon de descuento?</h2>
                                <form>
                                    <div class="mb-2">
                                        <!-- input -->
                                        <label for="giftcard" class="form-label sr-only">Email address</label>
                                        <input type="text" class="form-control" id="giftcard" placeholder="Codigo de descuento" required>

                                    </div>
                                    <!-- btn -->
                                    <div class="d-grid"><button type="submit" class="btn btn-danger mb-1">Canjear Cupón</button></div>

                                    <p><small>
                                            Al realizar su pedido, acepta estar sujeto a los
                                            <a href="terminos_condiciones.php" target="_blank">Términos de servicio</a> y la
                                            <a href="terminos_condiciones.php" target="_blank">Política de privacidad de Milogar.</a>
                                        </small></p>
                                    </b>
                                    <div class="d-grid">

                                        <a id="proceder" href="#" class="btn btn-primary ms-2"
                                            data-user-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"
                                            data-numero-pedido="<?php echo isset($numPedido) ? $numPedido : ''; ?>"
                                            data-fecha="<?php echo isset($fechaActual) ? $fechaActual : ''; ?>">
                                            Proceder
                                        </a>
                                    </div>
                                    <!-- Botón de PayPhone -->
                                    <div class="d-grid mt-3">
                                        <a id="pagoPayPhone" href="javascript:void(0);" class="btn btn-payphone ms-2"
                                            data-amount="100"
                                            data-reference="12345"
                                            data-user-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                                            <img src="../assets/imagenesMilogar/LogoPayphone.jpg" alt="PayPhone" style="width: 30px; margin-right: 10px;">
                                            Pagar con PayPhone
                                        </a>
                                    </div>

                                </form>
                            </div>

                            <!-- text -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




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
    <!-- choose one -->
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"></script>
    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

        document.getElementById('pagoPayPhone').addEventListener('click', function(e) {
            e.preventDefault(); // Evitar el comportamiento por defecto del enlace

            const amount = this.getAttribute('data-amount');
            const reference = this.getAttribute('data-reference');
            const userId = this.getAttribute('data-user-id');

            // Enviar la solicitud al backend
            fetch('http://localhost:8088/Milogar/Controllers/PayPhoneController.php', { // URL de tu backend
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: amount,
                        reference: reference,
                        userId: userId
                    })
                })
                .then(response => {
                    // Verificar si la respuesta es exitosa
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json(); // Parsear la respuesta JSON
                })
                .then(data => {
                    console.log("Respuesta recibida:", data); // Mostrar la respuesta para depuración

                    // Verificar si la respuesta contiene la URL de pago
                    if (data.success && data.paymentUrl) {
                        window.location.href = data.paymentUrl; // Redirigir a la URL de pago
                    } else {
                        console.error('Error: No se encontró la URL de pago en la respuesta.');
                        alert("Error al obtener la URL de pago.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Hubo un error al realizar la solicitud de pago.");
                });
        });
        document.addEventListener("DOMContentLoaded", function () {
    const direccionContainer = document.getElementById("direccionContainer");
    const recogerOption = document.getElementById("recogerEnTienda");
    const enviarOption = document.getElementById("agregarDireccionEnvio");

    // Función para mostrar u ocultar el campo de dirección según la opción seleccionada
    function toggleDireccion() {
        if (enviarOption.checked) {
            direccionContainer.style.display = "block";
        } else {
            direccionContainer.style.display = "none";
            document.getElementById("direccion").value = ""; // Limpiar si se oculta
        }
    }

    // Detectar cambio en los radio buttons
    recogerOption.addEventListener("change", toggleDireccion);
    enviarOption.addEventListener("change", toggleDireccion);

    // Llamar a la función al cargar la página por si ya hay una opción seleccionada
    toggleDireccion();
});
    </script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/theme.min.js"></script>

</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:30 GMT -->

</html>