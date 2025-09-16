<?php
session_start();
require_once "Config/db.php";
require_once "Models/TiendaModel.php";
require_once "Controllers/TiendaController.php";
require_once "Controllers/ProductoController.php";
// Función para obtener la conexión
function getDatabaseConnection()
{
    $db = new Database1();
    return $db->getConnection();
}
$connection = getDatabaseConnection();

// Instanciar el controlador de categorías
$tiendaController = new TiendaController($connection);
$productoController = new ProductoController($connection);
// Manejar la solicitud y llamada al controlador
if (isset($_GET['action']) && $_GET['action'] == 'mostrarProductosPorSubcategoria' && isset($_GET['subcategoria_id'])) {
    $subcategoria_id = $_GET['subcategoria_id'];
    $controller = new TiendaController($connection);
    $controller->mostrarProductosPorSubcategoria($subcategoria_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>MILOGAR - Oferta de puntos</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Milogar - Tienda en línea con los mejores productos del hogar. Compra fácil, rápido y seguro. ¡Descubre nuestras ofertas!">
    <meta content="Codescandy" name="author" />


    <!-- Favicon icon-->
    <link rel="shortcut icon" href="assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">
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
        .card-product img {
            width: 200%;
            height: 300px;
            /* o ajusta según quieras */
            object-fit: contain;
            /* ahora se ve completa sin recorte */
            border-radius: 5px;
            background-color: #f8f9fa;
            /* opcional, para el espacio vacío */
        }

        .promo-banner {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 3rem;
            flex-wrap: nowrap;
            flex-shrink: 0;
            overflow-x: visible;
            /* Cambio: quitar scroll horizontal en PC */
            -webkit-overflow-scrolling: touch;
        }

        .promo-banner .promo-img {
            flex: 0 0 auto;
            max-width: 350px;
            /* Aumentamos ancho máximo para PC */
            width: 100%;
            /* Quitamos max-height para que la imagen no se corte */
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: visible;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
        }

        .promo-banner .promo-img img {
            max-width: 100%;
            height: auto;
            /* mantener proporción */
            width: auto;
            object-fit: contain;
            display: block;
        }

        .promo-banner .promo-text {
            flex: 1 1 auto;
            min-width: 220px;
            color: #222;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .promo-banner .promo-text h1 {
            margin-bottom: 0.75rem;
            font-weight: 700;
            font-size: 2.2rem;
        }

        .promo-banner .promo-text p {
            color: #555;
            font-size: 1.125rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .promo-banner .promo-text a.btn {
            background-color: #ffdd57;
            color: #333;
            font-weight: 600;
            padding: 0.75rem 1.8rem;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 5px 12px rgba(255, 221, 87, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .promo-banner .promo-text a.btn:hover,
        .promo-banner .promo-text a.btn:focus {
            background-color: #ffc107;
            box-shadow: 0 7px 16px rgba(255, 193, 7, 0.8);
            color: #000;
            outline: none;
        }

        @media (max-width: 768px) {
            .promo-banner {
                flex-direction: column;
                /* Imagen arriba, texto abajo */
                align-items: center;
                text-align: center;
                gap: 1.5rem;
                margin-bottom: 2rem;
                overflow-x: visible;
                /* quitamos scroll horizontal */
                padding: 0 1rem;
            }

            .promo-banner .promo-img {
                max-width: 80%;
                height: auto;
            }

            .promo-banner .promo-img img {
                width: 100%;
                height: auto;
            }

            .promo-banner .promo-text h1 {
                font-size: 1.6rem;
            }

            .promo-banner .promo-text p {
                font-size: 1rem;
            }

            .promo-banner .promo-text ul {
                padding-left: 1.2rem;
                text-align: left;
                /* las listas lucen mejor alineadas a la izquierda */
            }

            .promo-banner .promo-text a.btn {
                padding: 0.6rem 1.4rem;
                font-size: 0.95rem;
                margin-top: 0.5rem;
            }
        }


        /* Ocultar subcategorías por defecto y agregar transición */
        .subcategories-menu {
            display: none;
            padding-left: 20px;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: opacity 1s ease, max-height 1s ease;
        }

        /* Mostrar subcategorías al hacer hover en la categoría con transición suave */
        .category-item:hover .subcategories-menu {
            display: block;
            opacity: 1;
            max-height: 500px;
            /* Ajusta el tamaño máximo según la altura esperada de la lista */
        }

        /* Opcional: Efectos adicionales en hover */
        .category-item:hover>.nav-link {
            color: #007bff;
            /* Cambia el color en hover */
        }

        .subcategories-menu .nav-link {
            padding: 5px 10px;
            color: #333;
        }

        .subcategories-menu .nav-link:hover {
            color: #007bff;
            /* Cambia el color de la subcategoría en hover */
            text-decoration: underline;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Para cubrir todo el contenedor, recortando si es necesario */

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
</head>

<body>
    <?php
    require_once __DIR__ . '/Views/Navigation/navigation.php';

    // Verificar si la variable 'user_role' está definida en la sesión
    if (!isset($_SESSION['user_role'])) {
        // Si no está definida, asignar 'Invitado' como rol predeterminado
        $_SESSION['user_role'] = 'Invitado';
    }
    // Verificar si el usuario tiene rol de administrador y, en caso afirmativo, asignarlo
    if ($_SESSION['user_role'] == 'Administrador') {
        // El usuario tiene rol de Administrador
        $userRole = 'Administrador';
    } else {
        // El usuario no tiene rol de Administrador (puede ser Invitado, Cliente, etc.)
        $userRole = 'Invitado';
    }
    // Verificar si el usuario ha iniciado sesión (puedes usar tu propia lógica de autenticación)
    $isUserLoggedIn = isset($_SESSION['user_id']); // Suponiendo que 'userId' esté en la sesión
    // Pasar el estado de la sesión a JavaScript
    echo "<script>var isUserLoggedIn = " . ($isUserLoggedIn ? 'true' : 'false') . ";</script>";
    echo '<div id="role" data-role="' . $userRole . '"></div>';
    ?>

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
                <div class="d-grid">


                    <button id="checkout-button" class="btn btn-primary btn-lg d-flex justify-content-between align-items-center" type="submit">
                        Continuar Compra <span id="cart-total" class="fw-bold">$0.00</span>
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

    <section class="mt-8 mb-lg-14 mb-8">
        <div class="container">
            <!-- Promo banner -->
            <div class="promo-banner" role="region" aria-label="Promoción sistema de puntos Milogar">
                <div class="promo-img">
                    <img src="https://milogar.wuaze.com/assets/imagenesMilogar/imagenesPuntos.jpg" alt="Gana puntos con Milogar" />
                </div>
                <div class="promo-text">
                    <h1>En Milogar premiamos tus compras!</h1>
                    <p>
                        Con cada compra, acumulas puntos que podrás canjear por descuentos exclusivos, regalos y promociones especiales diseñadas solo para ti.
                    </p>
                    <p>
                        ¡Es rápido, sencillo y cada compra te acerca a mayores beneficios!
                    </p>
                    <p style="font-weight: 600; color: #444; margin-top: 1rem;">
                        ¿Por qué comprar en Milogar?
                    </p>
                    <ul style="list-style: none; padding-left: 0; color: #666; line-height: 1.5;">
                        <li>✨ Gana puntos con cada compra que realices.</li>
                        <li>🎁 Canjea tus puntos por descuentos y regalos exclusivos.</li>
                        <li>🔥 Accede a ofertas y promociones especiales solo para miembros.</li>
                        <li>⚡️ Disfruta de un proceso fácil y rápido para empezar a ganar.</li>
                    </ul>
                    <a href="terminos_condiciones.php" class="btn" aria-label="Más información sobre cómo ganar puntos">Saber más sobre los puntos</a>
                </div>
            </div>

            <!-- Productos con puntos -->
            <div>
                <h3 class="mb-4">Productos con Puntos</h3>
                <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-2" id="productos-con-puntos">
                    <!-- Aquí se insertan los productos dinámicamente -->
                </div>
                <div id="paginacion-productos" class="mt-3 d-flex justify-content-center"></div>

            </div>


            <!-- Resultados búsqueda (opcional) -->
            <div class="mt-5" style="display:none;" id="resultados-busqueda-wrapper">
                <h3 class="mb-4">Resultados de búsqueda</h3>
                <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-2" id="resultados-busqueda-container">
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-8">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Imagen principal sin efecto de zoom -->
                            <div class="product productModal" id="productModal">
                                <div>
                                    <img id="product-image" class="product-image" alt="Product Image">
                                </div>
                            </div>

                            <!-- Miniatura -->
                            <div class="product-tools">
                                <div class="thumbnails row g-3" id="productModalThumbnails">
                                    <div class="col-3">
                                        <div class="thumbnails-img">
                                            <img src="../assets/images/products/product-single-img-1.jpg" alt="Thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ps-md-8">
                                <a href="#!" style="font-size: 30px;" class="mb-4 d-block" id="product-category">Detalles del producto</a>
                                <h2 class="product-name mb-1 h1" id="product-name">Product Name</h2>
                                <div class="mb-4">
                                    <small class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </small>
                                    <a href="#" class="ms-2">(30 reviews)</a>
                                </div>
                                <div class="product-price fs-4">
                                    <span class="fw-bold text-dark" id="product-price"></span> <!-- Precio con descuento -->
                                    <span class="text-decoration-line-through text-muted" id="original-price"></span> <!-- Precio original -->
                                    <span><small class="fs-6 ms-2 text-danger" id="discount-percent"></small></span> <!-- Descuento en porcentaje -->
                                </div>
                                <hr class="my-6">

                                <div class="mt-5 d-flex justify-content-start">
                                    <div class="col-2">
                                        <div class="input-group flex-nowrap justify-content-center">
                                            <input type="button" value="-" class="button-minus form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0" data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity" class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0">
                                            <input type="button" value="+" class="button-plus form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0" data-field="quantity">
                                        </div>
                                    </div>
                                    <div class="ms-2 col-4 d-grid">
                                        <button type="button" id="add-to-cart2" class="btn btn-primary"><i class="feather-icon icon-shopping-bag me-2"></i>Add to cart</button>
                                    </div>

                                </div>
                                <hr class="my-6">
                                <div>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>Description:</td>
                                                <td id="product-description"></td>
                                            </tr>
                                            <tr>
                                                <td>Product Code:</td>
                                                <td id="product-code">FBB00255</td>
                                            </tr>
                                            <tr>
                                                <td>Availability:</td>
                                                <td id="availability">In Stock</td>
                                            </tr>
                                            <tr>
                                                <td>Categoría</td>
                                                <td id="Category">Fruits</td>
                                            </tr>
                                            <tr>
                                                <td>Subcategoría</td>
                                                <td id="Subcategory">Fruits</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3>Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>
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

    <?php require_once "Views/Navigation/footer.php"; ?>
    <!-- Javascript-->
    <!-- Libs JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <!-- choose one -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Mantener el carrito de compras en todas las ventanas del navegador, agregar el script donde 
        esta toda la logica -->

    <script src="assets/js/cart.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"></script>
    <script src="assets/js/theme.min.js"></script>

    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get("q");

            if (query) {
                document.getElementById("busqueda").value = query; // Mostrar el término en el input
                realizarBusqueda(query); // Llamar la función de búsqueda automáticamente
            }
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

    <script src="assets/js/ofertasPuntos.js"></Script>


</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:10 GMT -->

</html>