<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;

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

        .chatbot-container {
            position: fixed;
            bottom: 100px;
            right: 20px;
            z-index: 1001;
        }

        .chatbot-icon {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .chatbot-icon img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .chatbot-icon:hover img {
            transform: scale(1.1);
        }

        .chatbot-text {
            position: absolute;
            bottom: 70px;
            right: 0;
            background-color: #343a40;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .chatbot-icon:hover .chatbot-text {
            opacity: 1;
        }

        .chatbot-box {
            display: none;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        .chatbot-header {
            background-color: #343a40;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            position: relative;
        }

        .chatbot-close {
            position: absolute;
            right: 10px;
            top: 5px;
            cursor: pointer;
        }

        .chatbot-log {
            height: 250px;
            overflow-y: auto;
            padding: 10px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            background: #f9f9f9;
        }

        .chatbot-input {
            display: flex;
            border-top: 1px solid #ccc;
        }

        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
        }

        .chatbot-input button {
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
    </style>
    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    </script>
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




    <!-- Shop Cart -->
    <div id="offcanvasRight" class="offcanvas offcanvas-end" tabindex="-1" aria-labelledby="offcanvasRightLabel">
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


    <!-- Chatbot Bubble Container -->
    <div class="chatbot-container">
        <div class="chatbot-icon" onclick="toggleChatbot()">
            <img src="https://cdn-icons-png.flaticon.com/512/4712/4712104.png" alt="Chatbot">
            <span class="chatbot-text">¿Necesitas ayuda?</span>
        </div>

        <div id="chatbot-box" class="chatbot-box">
            <div class="chatbot-header">
                Chat Milogar
                <span class="chatbot-close" onclick="toggleChatbot()">✖</span>
            </div>
            <div id="chatlog" class="chatbot-log"></div>
            <div class="chatbot-input">
                <input id="userInput" type="text" placeholder="Escribe tu mensaje...">
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </div>


    <div class="whatsapp-container">
        <a href="https://wa.me/593967342065" target="_blank" class="whatsapp-icon">
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
                            <h3>Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Carrito de compras centrado -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body p-4">

                            <h4 class="mb-4 fw-semibold">Resumen del Carrito</h4>

                            <!-- Lista de productos -->
                            <div id="order-details-container">
                                <ul id="order-details" class="list-group list-group-flush"></ul>
                            </div>
                            <br>
                            <!-- Puntos acumulados y usados -->
                            <div class="row text-center mb-4">
                                <div class="col-md-6 mb-2">
                                    <div class="bg-light p-3 rounded border">
                                        <strong>Puntos acumulados:</strong><br>
                                        <span id="total-puntos" class="text-success fw-bold">0.00</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="bg-light p-3 rounded border">
                                        <strong>Puntos utilizados:</strong><br>
                                        <span id="puntos-descontar" class="text-danger fw-bold">0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="shop-grid" class="btn btn-outline-primary">Seguir Comprando</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <h2 class="h5 mb-4 text-primary fw-bold">Información del pedido</h2>
                <div class="row">
                    <!-- Columna: Datos del cliente -->
                    <div class="col-md-6 mb-4">
                        <form id="facturaForm">
                            <h3 class="fw-semibold mb-3">Datos del cliente</h3>

                            <!-- Opciones de entrega -->
                            <div class="mb-3">
                                <label class="form-label d-block fw-semibold">Método de entrega</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deliveryOption" id="recogerEnTienda" value="recogerEnTienda">
                                    <label class="form-check-label" for="recogerEnTienda">Recoger en tienda</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deliveryOption" id="agregarDireccionEnvio" value="agregarDireccionEnvio">
                                    <label class="form-check-label" for="agregarDireccionEnvio">Agregar dirección de envío</label>
                                </div>
                            </div>

                            <input type="hidden" id="user_name" value="<?= $_SESSION["user_name"] ?? '' ?>">

                            <div class="mb-3">
                                <label for="email-address" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email-address" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ingresa tu correo electrónico" required>
                            </div>

                            <div class="mb-3">
                                <label for="celular" class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control" id="celular" placeholder="Ingresa tu teléfono" required>
                            </div>

                            <div class="mb-3" id="direccionContainer">
                                <label for="provincia" class="form-label fw-semibold">Tipo de envio</label>
                                <select class="form-select" id="provincia" name="provincia" required>
                                    <option value="">Seleccionar tipo de envio</option>
                                    <option value="sectorEnvio">Envio por sector(El Quinche, Iguiñaro, Yaruqí, Checa, Ascazubí, Cusubamba, Guayabamba, Cayambe)</option>
                                    <option value="otra">Otra dirección</option>
                                </select>
                            </div>
                            <div id="camposDireccionAdicionales" style="display: none; margin-top: 15px;">
                                <div class="mb-3" id="direccionContainer">
                                    <label for="empresaEnvio" class="form-label fw-semibold">Empresa de envío</label>
                                    <select class="form-select" id="empresaEnvio" name="empresaEnvio" required>
                                        <option value="">Seleccionar empresa de envío</option>
                                        <option value="servientrega">Servientrega</option>
                                        <option value="favorcito">Favorcito Logistics</option>
                                    </select>
                                </div>
                                <!-- Provincia con select -->
                                <div class="mb-3">
                                    <label for="provinciaEnvio" class="form-label fw-semibold">Provincia</label>
                                    <select class="form-select" id="provinciaEnvio" name="provinciaEnvio" required>
                                        <option value="">Seleccionar provincia</option>
                                        <option value="Pichincha">Pichincha</option>
                                        <option value="Imbabura">Imbabura</option>
                                        <option value="Guayas">Guayas</option>
                                        <option value="Manabí">Manabí</option>
                                        <option value="Esmeraldas">Esmeraldas</option>
                                        <option value="Loja">Loja</option>
                                        <option value="Azuay">Azuay</option>
                                        <option value="Tungurahua">Tungurahua</option>
                                        <option value="Cotopaxi">Cotopaxi</option>
                                        <option value="Chimborazo">Chimborazo</option>
                                        <option value="El Oro">El Oro</option>
                                        <option value="Bolívar">Bolívar</option>
                                        <option value="Carchi">Carchi</option>
                                        <option value="Cañar">Cañar</option>
                                        <option value="Galápagos">Galápagos</option>
                                        <option value="Los Ríos">Los Ríos</option>
                                        <option value="Morona Santiago">Morona Santiago</option>
                                        <option value="Napo">Napo</option>
                                        <option value="Orellana">Orellana</option>
                                        <option value="Pastaza">Pastaza</option>
                                        <option value="Santa Elena">Santa Elena</option>
                                        <option value="Santo Domingo de los Tsáchilas">Santo Domingo de los Tsáchilas</option>
                                        <option value="Sucumbíos">Sucumbíos</option>
                                        <option value="Zamora Chinchipe">Zamora Chinchipe</option>
                                    </select>
                                </div>

                                <!-- Ciudad -->
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label fw-semibold">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ej: Quito" required>
                                </div>

                                <!-- Calle y número -->
                                <div class="mb-3">
                                    <label for="calle" class="form-label fw-semibold">Calle y número</label>
                                    <input type="text" class="form-control" id="calle" name="calle" placeholder="Ej: Av. Amazonas N34-55" required>
                                </div>

                                <!-- Referencias -->
                                <div class="mb-3">
                                    <label for="referencias" class="form-label fw-semibold">Referencias (opcional)</label>
                                    <textarea class="form-control" id="referencias" name="referencias" rows="2" placeholder="Ej: Cerca del parque central"></textarea>
                                </div>
                            </div>


                        </form>
                    </div>

                    <!-- Columna: Métodos de pago y totales -->
                    <div class="col-md-6">
                        <h3 class="fw-semibold mb-3">Método de pago</h3>

                        <div class="d-flex align-items-start border rounded p-3 mb-3">
                            <input class="form-check-input mt-1 me-3" type="radio" name="opcion" id="transferencia" value="transferencia_directa">
                            <div>
                                <label class="h6 fw-semibold" for="transferencia">Transferencia directa</label>
                                <p class="small mb-0 text-muted">Te enviaremos los detalles bancarios tras completar el pedido.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start border rounded p-3 mb-4">
                            <input class="form-check-input mt-1 me-3" type="radio" name="opcion" id="payphone" value="payphone">
                            <div>
                                <label class="h6 fw-semibold" for="payphone">PayPhone</label>
                                <p class="small mb-0 text-muted">Pago seguro desde tu celular. Aceptamos tarjetas de débito.</p>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="card shadow-sm border-0 mb-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span> <span id="subtotal" class="fw-bold text-muted">$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>IVA (15%)</span> <span id="iva" class="fw-bold text-muted">$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Costo de envio</span> <span id="costoEnvio" class="fw-bold text-muted">$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Descuento</span> <span id="descuento" class="text-danger">-$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Total</span> <span id="total" class="fw-bold">$0.00</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 text-center">
                            <!-- Botón Proceder -->
                            <a id="proceder" href="#" class="btn btn-primary btn-lg disabled"
                                style="pointer-events: none; opacity: 0.6;"
                                data-user-id="<?= $_SESSION['user_id'] ?? '' ?>"
                                data-numero-pedido="<?= $numPedido ?? '' ?>"
                                data-fecha="<?= $fechaActual ?? '' ?>">
                                Proceder
                            </a>

                            <!-- Texto debajo si no hay sesión -->
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <p class="mt-2 text-muted small">
                                    🔒 Para continuar debes <a href="signup" class="text-decoration-none fw-semibold text-primary">suscribirte</a> o
                                    <a href="#" class="text-decoration-none fw-semibold text-primary"
                                        data-bs-toggle="modal" data-bs-target="#userModal">
                                        iniciar sesión
                                    </a>
                                </p>
                            <?php endif; ?>

                            <!-- Botón PayPhone -->
                            <a id="pagoPayPhone" class="btn btn-outline-dark btn-lg disabled"
                                style="pointer-events: none; opacity: 0.5;"
                                onclick="generarPago();"
                                data-amount="100"
                                data-reference="12345"
                                data-user-id="<?= $_SESSION['user_id'] ?? '' ?>">
                                <img src="assets/imagenesMilogar/LogoPayphone.jpg" alt="PayPhone" style="width: 30px; margin-right: 10px;">
                                Pagar con PayPhone
                            </a>
                        </div>


                    </div>
                </div>

                <!-- Términos -->
                <div class="mt-4 text-center">
                    <p class="text-muted fs-5">
                        Al realizar tu pedido, aceptas los
                        <a href="terminos_condiciones.php" class="fw-semibold text-primary">Términos de servicio</a> y la
                        <a href="terminos_condiciones.php" class="fw-semibold text-primary">Política de privacidad</a> de Milogar.
                    </p>
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Theme JS -->
    <!-- choose one -->
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"></script>

    <script src="assets/js/cart.js"></script>
    <script src="assets/js/theme.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const direccionContainer = document.getElementById("direccionContainer");
            const recogerOption = document.getElementById("recogerEnTienda");
            const enviarOption = document.getElementById("agregarDireccionEnvio");
            const provinciaSelect = document.getElementById("provincia");
            const camposAdicionales = document.getElementById("camposDireccionAdicionales");
            const provinciaEnvioSelect = document.getElementById("provinciaEnvio");
            const empresaEnvioSelect = document.getElementById("empresaEnvio");
            const ciudadInput = document.getElementById("ciudad");
            const calleInput = document.getElementById("calle");
            const referenciasTextarea = document.getElementById("referencias");

            // Muestra u oculta el bloque de selección de provincia
            function toggleDireccion() {
                if (enviarOption.checked) {
                    direccionContainer.style.display = "block";
                } else {
                    direccionContainer.style.display = "none";
                    provinciaSelect.value = "";
                    camposAdicionales.style.display = "none";
                    empresaEnvioSelect.disabled = false;
                    provinciaEnvioSelect.disabled = false;
                }
            }

            // Muestra u oculta los campos adicionales y establece valores por defecto
            function toggleCamposAdicionales() {
                const seleccion = provinciaSelect.value;
                if (seleccion === "otra") {
                    camposAdicionales.style.display = "block";
                    empresaEnvioSelect.value = "servientrega";
                    empresaEnvioSelect.disabled = true;

                    provinciaEnvioSelect.disabled = false;
                    provinciaEnvioSelect.value = "";
                    ciudadInput.value = "";
                    calleInput.value = "";
                    referenciasTextarea.value = "";

                } else if (seleccion === "sectorEnvio") {
                    camposAdicionales.style.display = "block";
                    empresaEnvioSelect.value = "favorcito";
                    provinciaEnvioSelect.value = "Pichincha";
                    ciudadInput.value = "El Quinche";

                    // Bloquear empresa y provincia
                    empresaEnvioSelect.disabled = true;
                    provinciaEnvioSelect.disabled = true;

                } else {
                    camposAdicionales.style.display = "none";
                    empresaEnvioSelect.disabled = false;
                    provinciaEnvioSelect.disabled = false;
                }
            }

            // Eventos
            recogerOption.addEventListener("change", toggleDireccion);
            enviarOption.addEventListener("change", toggleDireccion);
            provinciaSelect.addEventListener("change", toggleCamposAdicionales);

            // Inicializar al cargar
            toggleDireccion();
            toggleCamposAdicionales();
        });

        function generarPago() {
            // Obtener el valor total desde la tienda virtual
            let totalElement = document.getElementById("total").innerText;

            // Convertirlo a número y asegurarse de que sea válido
            let total = parseFloat(totalElement.replace(/[^0-9.]/g, '')) * 100;

            if (isNaN(total) || total <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Total no válido',
                    text: 'El total del pedido no es válido. Verifica el monto antes de continuar.',
                    confirmButtonText: 'Aceptar',
                    timer: 4000,
                    timerProgressBar: true
                });
                return;
            }


            const redirectClientTraxID = Date.now();

            // Preparar cabecera para la solicitud
            const headers = {
                "Content-Type": "application/json",
                "Authorization": "Bearer bYrfxfYmTOu8sFy3hLe6w85G0VXkB97WgrBxCcVmRDh2Z6yrNo88wSvg12Q8l1UlYdXd2iCFB4q-DGllmKJBNTMGCvEF9sjKFAvdP6-qML-kRTlbfKyMTo1xmOWi3AJ0G1XwjRLOBfClgLDfGMd2wdrLA2lReu7iGV3DSjsoco1VbPIhBFVfbTBwZJMj01vjB3aLOce7aKf9VHcSRDqFqDqkrggqYHhwsKoUxaEes27SYLLZVI6_FokDKYDaiDoFFTJHmBHb8nPp-ZUXEmBkIdU36tSs0XQT9WasJyNRut4KnPxVFDmDwDuDNC7nDMmJ32LWWzM02K6NbLOzFg9JO6MKYhA", // Tu API Key
            };

            // Preparamos el objeto JSON para la solicitud
            const bodyJSON = {
                "amount": total, // Monto total
                "amountWithoutTax": total, // Monto sin impuestos
                "amountWithTax": 0, // Monto con impuestos
                "tax": 0, // Impuesto aplicado
                "reference": "Prueba Boton fetch", // Referencia opcional
                "currency": "USD", // Moneda
                "clientTransactionId": redirectClientTraxID, // ID único de transacción
                "storeId": "757a105c-7d02-4b21-b919-06667a9f4991", //  Store ID
                "ResponseUrl": "https://milogar.wuaze.com/respuesta.php" // URL de respuesta
            };

            // URL de la API de PayPhone
            const url = "https://pay.payphonetodoesposible.com/api/button/Prepare";

            // Realizar la solicitud POST
            fetch(url, {
                    method: "POST",
                    headers: headers,
                    body: JSON.stringify(bodyJSON)
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Respuesta de PayPhone:", data);

                    if (data.payWithPayPhone || data.payWithCard) {
                        // Redirigir al usuario a la URL de pago
                        if (data.payWithPayPhone) {
                            window.location.href = data.payWithPayPhone;
                        } else if (data.payWithCard) {
                            window.location.href = data.payWithCard;
                        }
                    } else {
                        alert("Error al procesar el pago. Inténtalo de nuevo.");
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                    alert("Hubo un problema con el pago. Inténtalo más tarde.");
                });
        }



        // Llamar a la función al hacer clic en el botón
        document.getElementById("pagoPayPhone").addEventListener("click", generarPago);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const transferenciaRadio = document.getElementById('transferencia');
            const payphoneRadio = document.getElementById('payphone');
            const botonPayphone = document.getElementById('pagoPayPhone');
            const botonProceder = document.getElementById('proceder');

            function actualizarEstadoBotones() {
                if (transferenciaRadio.checked) {
                    botonPayphone.classList.add('disabled');
                    botonPayphone.style.pointerEvents = 'none';
                    botonPayphone.style.opacity = '0.5';

                    botonProceder.classList.remove('disabled');
                    botonProceder.style.pointerEvents = 'auto';
                    botonProceder.style.opacity = '1';
                } else if (payphoneRadio.checked) {
                    botonProceder.classList.add('disabled');
                    botonProceder.style.pointerEvents = 'none';
                    botonProceder.style.opacity = '0.5';

                    botonPayphone.classList.remove('disabled');
                    botonPayphone.style.pointerEvents = 'auto';
                    botonPayphone.style.opacity = '1';
                } else {
                    // Si ninguno está seleccionado, ambos activos
                    botonPayphone.classList.remove('disabled');
                    botonPayphone.style.pointerEvents = 'auto';
                    botonPayphone.style.opacity = '1';

                    botonProceder.classList.remove('disabled');
                    botonProceder.style.pointerEvents = 'auto';
                    botonProceder.style.opacity = '1';
                }
            }

            transferenciaRadio.addEventListener('change', actualizarEstadoBotones);
            payphoneRadio.addEventListener('change', actualizarEstadoBotones);
        });
    </script>
    <script>
        const respuestas = {
            hola: "¡Hola! ¿En qué puedo ayudarte hoy?",
            horario: "Atendemos de lunes a domingo de 07:15 a 21:00.",
            envio: "Realizamos envíos a Quito (24–48h) y a provincias (2–5 días hábiles).",
            precios: "Puedes ver los precios actualizados directamente en cada producto.",
            ayuda: "Estoy aquí para ayudarte. ¿Sobre qué necesitas información?",

            puntos: "Puedes ganar puntos con cada compra registrada en tu cuenta. Estos puntos pueden canjearse por premios o descuentos especiales.",
            canje: "Para canjear tus puntos, ve a tu perfil y entra en la sección 'Mis puntos'. Ahí verás las opciones disponibles de canje.",
            ganar: "Ganas puntos por cada compra registrada. También puedes ganar puntos adicionales en promociones o campañas especiales.",
            contraseña: "Para recuperar tu contraseña, haz clic en '¿Olvidaste tu contraseña?' en la página de inicio de sesión y sigue las instrucciones que se envían a tu correo.",
            mayor: "Para compras al por mayor, realiza tu pedido seleccionando pago por transferencia. Se generará un PDF con el resumen que debes validar.",
            pdf: "El PDF del pedido se genera automáticamente y será revisado por nuestro equipo. Recibirás el precio final validado por WhatsApp en breve.",

            pago: "Aceptamos pagos con tarjeta, transferencia bancaria y depósitos. Elige tu método preferido en el proceso de compra.",
            devolucion: "Aceptamos devoluciones por productos dañados o errores en el envío. Contáctanos dentro de las 48 horas de haber recibido tu pedido.",

            default: "Lo siento, aún no entiendo esa pregunta. ¿Puedes intentar con otra más específica?"
        };

        function toggleChatbot() {
            const box = document.getElementById('chatbot-box');
            box.style.display = box.style.display === 'block' ? 'none' : 'block';
        }

        function sendMessage() {
            const input = document.getElementById("userInput");
            const userMessage = input.value.trim();
            if (!userMessage) return;

            appendMessage("Tú", userMessage);
            input.value = "";

            const lowerMsg = userMessage.toLowerCase();
            let respuesta = respuestas.default;

            for (const key in respuestas) {
                if (lowerMsg.includes(key)) {
                    respuesta = respuestas[key];
                    break;
                }
            }

            setTimeout(() => {
                appendMessage("MILOGAR", respuesta);
            }, 600);
        }

        function appendMessage(sender, text) {
            const chatlog = document.getElementById("chatlog");
            const newMsg = document.createElement("div");
            newMsg.innerHTML = `<strong>${sender}:</strong> ${text}`;
            newMsg.style.marginBottom = "10px";
            chatlog.appendChild(newMsg);
            chatlog.scrollTop = chatlog.scrollHeight;
        }
    </script>


</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:30 GMT -->

</html>