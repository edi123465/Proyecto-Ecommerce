<?php
ob_start(); // Inicia el buffer de salida
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "./Config/db.php";
require_once "./Controllers/UsuarioController.php";
require_once "./Controllers/LoginController.php";
require_once "./Controllers/ProductoController.php";

// Función para obtener la conexión
function getDatabaseConnection()
{
    $db = new Database1();
    return $db->getConnection();
}
$connection = getDatabaseConnection();
// Instanciar el controlador
$usuarioController = new UsuarioController($connection);
$loginController = new LoginController($connection);
$productoController = new ProductoController($connection);
//$productos = $productoController->getAllProductos();

$numPedido = $productoController->generarNumeroPedido();



ob_end_clean(); // Limpia el buffer al final del archivo si no se necesita
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/index-3.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:14 GMT -->

<head>

    <title>MILOGAR - HOME</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/theme.min.css">

    <style>
        /* Estilos para el carrusel */
        .product-carousel {
            width: 70%;
            height: 100%;
            margin: 0 auto;
            /* Centra el carrusel */
        }

        /* Estilos para las tarjetas de productos */
        .product-card {
            padding: 15px;
            text-align: center;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        /* Estilo para las imágenes dentro de las tarjetas */
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Estilo para los nombres y precios de los productos */
        .product-card h3 {
            font-size: 18px;
            margin-top: 10px;
        }

        .product-card p {
            font-size: 16px;
            color: #333;
        }

        /* Contenedor del carrusel y el título */
        .carousel-container {
            position: relative;
            /* Hacer el contenedor relativo para que el título se posicione dentro de él */
            width: 100%;
            margin: 0 auto;
        }

        /* Estilo para el título de las categorías */
        .carousel-title {
            position: absolute;
            top: -30px;
            /* Posicionar el título hacia arriba, según necesites */
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            /* Cambia el color si es necesario */
            background-color: rgba(255, 255, 255, 0.7);
            /* Fondo semitransparente para que se vea bien */
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        .card-product {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card-product:hover {
            transform: translateY(-5px);
        }

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






        /* Contenedor relativo para los botones */
        .swiper {
            position: relative;
        }

        /* Ajuste de imágenes: mismo alto y centrado */
        .mySwiper .swiper-slide img {
            width: 100%;
            height: 700px;
            /* ajusta al alto de tu banner */
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.5s ease;
        }

        .product-carousel .swiper-slide img.categoria-img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            /* para que se vea completa la imagen */
            border-radius: 12px;
            background-color: #f8f9fa;
            transition: transform 0.5s ease;
        }


        .swiper-slide:hover img {
            transform: scale(1.03);
            /* efecto leve de zoom al pasar el mouse */
        }

        /* Overlay oscuro y elegante */
        .overlay-text {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.35);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            border-radius: 12px;
            text-shadow: 2px 2px 8px rgba(242, 238, 238, 0.7);
            transition: background 0.3s ease;
        }



        .overlay-text h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .overlay-text p {
            font-size: 1.2rem;
            max-width: 600px;
            line-height: 1.5;
        }

        /* Botones de navegación al lado de la imagen */
        .swiper-button-prev,
        .swiper-button-next {
            color: #fff;
            background: rgba(0, 0, 0, 0.6);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            transition: background 0.3s ease;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Posición lateral de botones */
        .swiper-button-prev {
            left: 15px;
        }

        .swiper-button-next {
            right: 15px;
        }

        /* Ajuste responsive en móviles */
        @media (max-width: 768px) {
            .swiper-slide img {
                height: 400px;
                /* alto reducido */
            }

            .overlay-text h2 {
                font-size: 1.6rem;
            }

            .overlay-text p {
                font-size: 1rem;
            }

            .swiper-button-prev {
                left: 5px;
            }

            .swiper-button-next {
                right: 5px;
            }
        }
    </style>
    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    </script>

<body>
    <?php
    require_once "Views/Navigation/navigation.php";
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
                <?php require_once "Controllers/ProductoController.php";

                $numeroPedido = $productoController->generarNumeroPedido();
                ?>

                <small>Número de pedido: <?php echo $numeroPedido; ?> </small>
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

                    <button id="checkout-button" class="btn btn-primary btn-lg d-flex justify-content-between align-items-center" type="submit">
                        Continuar Compra <span id="cart-total" class="fw-bold">$0.00</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editarModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Editar Categoria</h2>
            <form id="editarForm">
                <div class="form-group">
                    <label for="CategoriaNombre">Nombre Categoría</label>
                    <input type="text" id="categoriaNombre" name="categoriaNombre" required>
                </div>
                <div class="form-group">
                    <label for="categoriaDescripcion">Descripción</label>
                    <textarea id="categoriaDescripcion" name="categoriaDescripcion" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagenCategoria">Imagen</label>
                    <input type="file" class="form-control" id="imagenCategoria">
                </div>
                <div class="form-group">
                    <label>Vista previa de imagen</label><br>
                    <img id="imagenCategoriaPreview" src="" style="width: 100px; height: 100px;">
                </div>
                <div class="form-group">
                    <label for="isActive">Estado</label>
                    <select class="form-control" id="isActive">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <section class="mt-8">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light d-lg-flex justify-content-between align-items-center py-4 px-4 rounded-3 text-center text-lg-start"
                        data-aos="fade-up">

                        <!-- Imagen + saludo -->
                        <div class="d-lg-flex align-items-center mb-4 mb-lg-0" data-aos="fade-right">
                            <img src="assets/imagenesMilogar/logomilo.jpg" alt="" width="200" height="200"
                                style="border-radius: 20px;" class="img-fluid">
                            <div class="ms-lg-4 mt-3 mt-lg-0">
                                <h1 class="fs-2 mb-1">
                                    <?php
                                    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['user_name']) && $_SESSION['user_name'] !== 'Invitado') {
                                        echo 'Hola, ' . htmlspecialchars($_SESSION['user_name']);
                                    } else {
                                        echo 'Bienvenidos a MILOGAR';
                                    }
                                    ?>
                                </h1>
                                <span>Todo lo que necesitas en un solo lugar a los mejores precios</span>
                            </div>
                        </div>

                        <!-- Botón y texto debajo -->
                        <div class="d-flex flex-column align-items-center align-items-lg-start ms-lg-n3" data-aos="fade-left">
                            <a href="signup" class="btn btn-dark mb-3 px-4 py-2 fs-6">
                                Suscríbete para acceder a promociones y más.
                            </a>
                            <div class="text-muted small" style="max-width: 220px;">
                                Para una atención personalizada comunícate al número:<br>
                                <a href="tel:+593967342065" class="text-decoration-none fw-semibold fs-5">096 734 2065</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container my-5">
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide position-relative text-center">
                    <img src="assets/imagenesMilogar/plasticosHogar.webp" class="img-fluid rounded" alt="Plásticos para el Hogar">
                    <div class="overlay-text">
                        <h2 style="color: white; font-size: 60px;">¡Transforma tu Hogar!</h2>
                        <p style="font-size: 25px;">Plásticos duraderos y prácticos para hacer tu vida más fácil. Aprovecha nuestras ofertas exclusivas.</p>
                        <!-- btn -->
                        <a href="/Milogar/busquedaClientes?search=Hogar" class="btn btn-primary text-light">Explora ofertas
                            <i class="feather-icon icon-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide position-relative text-center">
                    <img src="assets/imagenesMilogar/canasta_basica.png" class="img-fluid rounded" alt="Canasta Básica">
                    <div class="overlay-text">
                        <h2 style="color: white; font-size: 60px;">Lo Esencial al Mejor Precio</h2>
                        <p style="font-size: 25px;">Canasta básica completa para tu familia con descuentos que no puedes dejar pasar.</p>
                        <a href="/Milogar/busquedaClientes?search=Primera" class="btn btn-primary text-light">Explorar Ofertas <i class="feather-icon icon-arrow-right ms-1"></i></a>

                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide position-relative text-center">
                    <img src="assets/imagenesMilogar/cristaleria.jpg" class="img-fluid rounded" alt="Cristalería Elegante">
                    <div class="overlay-text">
                        <h2 style="color: white; font-size: 60px;">Elegancia en Cada Mesa</h2>
                        <p style="font-size: 25px;">Cristalería fina para tus momentos especiales. Dale estilo a tus reuniones y cenas familiares.</p>
                        <a href="/Milogar/busquedaClientes?search=cristaleria" class="btn btn-primary text-white">Explorar ofertas <i class="feather-icon icon-arrow-right ms-1"></i></a>

                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide position-relative text-center">
                    <img src="assets/imagenesMilogar/mueblesHogar.jpg" class="img-fluid rounded" alt="Muebles para el Hogar">
                    <div class="overlay-text">
                        <h2 style="color: white; font-size: 60px;">Confort y Estilo</h2>
                        <p style="font-size: 25px;">Muebles funcionales y elegantes para que tu hogar refleje tu personalidad.</p>
                        <a href="/Milogar/busquedaClientes?search=muebles" class="btn btn-primary text-white">Explorar ofertas <i class="feather-icon icon-arrow-right ms-1"></i></a>

                    </div>
                </div>

                <!-- Slide 5 -->
                <div class="swiper-slide position-relative text-center">
                    <img src="assets/imagenesMilogar/desechables.jpg" class="img-fluid rounded" alt="Desechables">
                    <div class="overlay-text">
                        <h2 style="color: white; font-size: 60px;">Practicidad al Instante</h2>
                        <p style="font-size: 25px;">Desechables de alta calidad para tu día a día. Comodidad y ahorro en un solo lugar.</p>
                        <a href="/Milogar/busquedaClientes?search=desechables" class="btn btn-primary">Explorar ofertas <i class="feather-icon icon-arrow-right ms-1"></i></a>

                    </div>
                </div>
            </div>

            <!-- Botones de navegación -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- Paginación (dots) -->
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- section -->
    <section class="mt-8">
        <div class="container">
            <div class="row g-3"> <!-- g-3 agrega espacio entre columnas -->

                <!-- Primer Banner -->
                <div class="col-12 col-md-4">
                    <div class="banner position-relative rounded-3 overflow-hidden" style="height: 260px;">
                        <img src="assets/imagenesMilogar/licores.jpg" alt="" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <!-- Overlay oscuro -->
                        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:1;"></div>
                        <div class="banner-text position-absolute top-50 start-0 translate-middle-y ms-3 text-white" style="z-index:2;">
                            <h3 class="mb-2 fw-bold" style="font-size: 1.3rem; text-shadow: 2px 2px 6px rgba(0,0,0,0.7); color:#f8f9fa">Licores y bebidas</h3>
                            <p class="mb-2" style="font-size:0.9rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.7);">Lo mejor en bebidas alcohólicas para eventos.</p>
                            <!-- Primer Banner -->
                            <a href="/Milogar/busquedaClientes?search=bebidas"
                                class="btn btn-primary rounded-pill"
                                style="padding: 12px 25px; font-size: 1rem;">Explorar ofertas</a>
                        </div>
                    </div>
                </div>

                <!-- Segundo Banner -->
                <div class="col-12 col-md-4">
                    <div class="banner position-relative rounded-3 overflow-hidden" style="height: 260px;">
                        <img src="assets/imagenesMilogar/aseoLimpieza.jpg" alt="" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <!-- Overlay oscuro -->
                        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:1;"></div>
                        <div class="banner-text position-absolute top-50 start-50 translate-middle text-center text-white" style="z-index:2; padding: 10px; border-radius: 0.5rem;">
                            <h3 class="fs-5 fw-bold mb-1" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7); color: #f8f8f8;">¡Limpieza y aseo!</h3>
                            <p class="mb-1" style="font-size:0.85rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.7);">Productos de limpieza y aseo personal que te brindan frescura y confianza.</p>
                            <!-- Segundo Banner -->
                            <a href="/Milogar/busquedaClientes?search=aseo"
                                class="btn btn-primary rounded-pill mt-1"
                                style="padding: 12px 25px; font-size: 1rem;">Explora Ofertas</a>
                        </div>
                    </div>
                </div>

                <!-- Tercer Banner -->
                <div class="col-12 col-md-4">
                    <div class="banner position-relative rounded-3 overflow-hidden" style="height: 260px;">
                        <img src="assets/imagenesMilogar/mueblesHogar.jpg" alt="" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <!-- Overlay oscuro -->
                        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:1;"></div>
                        <div class="banner-text position-absolute top-50 start-0 translate-middle-y ms-3 text-white" style="z-index:2;">
                            <h3 class="mb-2 fw-bold" style="font-size: 1.3rem; color: white; text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">Muebles para el Hogar</h3>
                            <p class="mb-2" style="font-size:0.9rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.7);">Confort y estilo para tu hogar, funcionales y elegantes.</p>
                            <a href="/Milogar/busquedaClientes?search=muebles"
                                class="btn btn-primary rounded-pill"
                                style="padding: 12px 25px; font-size: 1rem;">Explorar ofertas</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <br>
    <h2 style="text-align: center;">Todas nuestras categorias</h2><br>
    <div class="product-carousel">
        <!-- Las categorías se agregarán dinámicamente aquí -->
    </div>
    <h3 style="display: none;">Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

    <!-- Modal de Edición de Producto -->
    <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarProducto" method="POST" enctype="multipart/form-data">
                        <!-- Nombre del Producto -->
                        <div class="form-group">
                            <label for="nombreProducto">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre_Producto" required>
                        </div>

                        <!-- Descripción del Producto -->
                        <div class="form-group">
                            <label for="descripcionProducto">Descripción</label>
                            <textarea class="form-control" id="descripcion_Producto" rows="3" required></textarea>
                        </div>

                        <!-- Categoría -->
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select id="categoriaa" class="form-control" required>
                                <option value="" disabled selected>Selecciona una categoría</option>
                                <!-- Aquí se llenarán las categorías desde la base de datos -->
                            </select>
                        </div>

                        <!-- Subcategoría -->
                        <div class="form-group">
                            <label for="subcategoria">Subcategoría</label>
                            <select id="subcategoriaa" class="form-control" required>
                                <option value="" disabled selected>Selecciona una subcategoría</option>
                                <!-- Aquí se llenarán las subcategorías desde la base de datos -->
                            </select>
                        </div>

                        <!-- Precio -->
                        <div class="form-group">
                            <label for="precio">Precio de compra</label>
                            <input type="number" class="form-control" id="precioCompra" step="0.01" required>
                        </div>

                        <!-- Precio 1 -->
                        <div class="form-group">
                            <label for="precio_1">Precio 1</label>
                            <input type="number" class="form-control" id="precio1" step="0.01" required>
                        </div>

                        <!-- Precio 2 -->
                        <div class="form-group">
                            <label for="precio_2">Precio 2</label>
                            <input type="number" class="form-control" id="precio2" step="0.01" required>
                        </div>

                        <!-- Precio 3 -->
                        <div class="form-group">
                            <label for="precio_3">Precio 3</label>
                            <input type="number" class="form-control" id="precio3" step="0.01" required>
                        </div>

                        <!-- Precio 4 -->
                        <div class="form-group">
                            <label for="precio_4">Precio 4</label>
                            <input type="number" class="form-control" id="precio4" step="0.01" required>
                        </div>

                        <!-- Stock -->
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stocks" required>
                        </div>

                        <!-- Código de barras -->
                        <div class="form-group">
                            <label for="codigo_barras">Código de barras</label>
                            <input type="text" class="form-control" id="codigoBarras" required>
                        </div>

                        <!-- Imagen -->
                        <div class="form-group">
                            <label for="imagen">Imagen del Producto</label>
                            <input type="file" id="imagen_Producto" name="imagen" />
                            <br>
                            <img id="imagenPreview" src="http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/default.png" alt="Imagen del Producto" width="100" />
                        </div>

                        <!-- Promoción -->
                        <div class="form-group">
                            <label for="is_promocion">¿Está en Promoción?</label>
                            <select id="isPromocion" name="isPromocion" class="form-control" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div class="form-group">
                            <label for="isActive">Estado</label>
                            <select id="estado" name="isActive" class="form-control" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>


                        <!-- Descuento -->
                        <div class="form-group">
                            <label for="desc">Descuento (%)</label>
                            <input type="number" class="form-control" id="desc" step="0.01" min="0" max="100">
                        </div>
                        <!-- Otros campos que quieras editar -->
                        <input type="hidden" id="productoId"> <!-- Este es el ID del producto que se va a editar -->
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                                <div id="product-sizes" class="mt-3"></div>

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
    <!-- section -->
    <!-- Popular Products Start-->
    <section id="productos-promocion" class="my-lg-14 my-8">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-6">

                    <h3 class="mb-0">Productos con promoción</h3>

                </div>
            </div>

            <div class="row g-4 row-cols-lg-5 row-cols-2 row-cols-md-3" id="productos-promocion-container">

            </div>
            <div class="text-center mt-4">
                <button id="btn-mostrar-mas" class="btn btn-outline-primary" onclick="mostrarProductosSiguientes()" style="display:none;">
                    Mostrar más
                </button>
            </div>

        </div>
    </section>
    <div id="paginacion-productos" class="mt-4 d-flex justify-content-center"></div>
    <!-- Chatbot -->
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

    <!-- WhatsApp Bubble (ya existente) -->
    <div class="whatsapp-container">
        <a href="https://wa.me/593967342065" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
    </div>

    <div class="whatsapp-container">
        <a href="https://wa.me/593989082073" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
    </div>


    <!-- featured section -->
    <section class="my-lg-14 my-8">
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="mb-8 mb-lg-0">
                        <!-- img -->
                        <div class="mb-6"><img src="assets/icons/clock.svg" alt=""></div>
                        <!-- title -->
                        <h3 class="h5 mb-3">
                            Compra desde cualquier lugar
                        </h3>
                        <!-- text -->
                        <p class="mb-0">Recibe tu pedido en la puerta de tu casa o recógelo en tu tienda Milogar ¡Rápido, fácil y seguro! 🚀🏡</p>
                    </div>
                </div>

                <!-- col -->
                <div class="col-md-6  col-lg-3">
                    <div class="mb-8 mb-lg-0">
                        <!-- img -->
                        <div class="mb-6"><img src="assets/icons/gift.svg" alt=""></div>
                        <!-- title -->
                        <h3 class="h5 mb-3">Ahorra mas en cada compra</h3>
                        <!-- text -->
                        <p class="mb-0">En Milogar, cada compra te acerca a los mejores descuentos y ofertas exclusivas. ¡Aprovecha hoy mismo!
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="mb-8 mb-lg-0">
                        <!-- img -->
                        <div class="mb-6"><img src="assets/icons/package.svg" alt=""></div>
                        <!-- title -->
                        <h3 class="h5 mb-3">Variedad para tu hogar</h3>
                        <!-- text -->
                        <p class="mb-0">Haz tus compras de manera fácil y rápida con nuestra gran variedad de productos esenciales para el hogar.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="mb-8 mb-lg-0">
                        <!-- img -->
                        <div class="mb-6"><img src="assets/icons/refresh-cw.svg" alt=""></div>
                        <!-- title -->
                        <h3 class="h5 mb-3"> Compra sin preocupaciones</h3>
                        <!-- text -->
                        <p class="mb-0">¿No estás satisfecho con tu compra? Devuélvela sin problemas y obtén tu reembolso de forma rápida y sencilla.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--modal para traer todos los comentarios por producto-->
    <div class="modal fade" id="modalTodosComentarios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Todos los comentarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenedor-todos-comentarios">
                    <p class="text-muted">Cargando comentarios...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form onsubmit="guardarEdicionComentario(event)">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Comentario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id-comentario">
                        <input type="hidden" id="edit-id-producto">
                        <div class="mb-3">
                            <label for="edit-comentario" class="form-label">Comentario</label>
                            <textarea id="edit-comentario" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-calificacion" class="form-label">Calificación</label>
                            <select id="edit-calificacion" class="form-select">
                                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                <option value="4">⭐️⭐️⭐️⭐️</option>
                                <option value="3">⭐️⭐️⭐️</option>
                                <option value="2">⭐️⭐️</option>
                                <option value="1">⭐️</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="puntosUsuario" style="display:none;">100</span>


    <?php
    require_once "Views/Navigation/footer.php";
    ?>
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
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/theme.min.js"></script>
    <!-- Incluye SweetAlert desde el CDN -->
    <script src="assets/js/alertas.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"> </script>
    <script src="assets/js/index.js"></script>
    <script src="assets/js/mostrarCategorias.js"></script>
    <!-- choose one -->
    <script>
        $(document).ready(function() {
            $('.slick-carousel').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev btn btn-light rounded-circle shadow"><i class="bi bi-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next btn btn-light rounded-circle shadow"><i class="bi bi-chevron-right"></i></button>'
            });
        });
        // Inicialización específica para este carrusel
        var swiperBanner = new Swiper(".mySwiper", {
            slidesPerView: 1, // un slide visible a la vez
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            breakpoints: {
                576: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 1
                },
                992: {
                    slidesPerView: 1
                }
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


</body>

</html>