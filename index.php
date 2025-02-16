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

    <style>
        /* Estilos para el carrusel */
        .product-carousel {
            width: 90%;
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
    </style>

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
    <section class="mt-8">
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-xxl-8 col-xl-7 ">
                    <!-- hero slider -->
                    <div class="hero-slider">
                        <div
                            style="background: url(assets/imagenesMilogar/desechables.jpg) no-repeat center center; 
           background-size: cover; 
           border-radius: .5rem; 
           height: 550px; 
           position: relative;">
                            <div class="ps-lg-12 py-lg-16 col-md-7 py-14 px-8 text-xs-center"
                                style="background-color: rgba(0, 0, 0, 0.6); 
                border-radius: .5rem; 
                position: absolute; 
                top: 0; 
                left: 0; 
                width: 100%; 
                height: 100%; 
                color: #fff; 
                display: flex; 
                flex-direction: column; 
                justify-content: center; 
                align-items: center; 
                padding: 20px;">
                                <!-- badge -->
                                <div class="d-flex align-items-center mb-4">
                                    <span style="color: #fff;">Oferta Especial</span>
                                    <span class="badge bg-danger ms-2">Descuentos de hasta el 15%</span>
                                </div>
                                <!-- title -->
                                <h2 class="text-white display-5 fw-bold mb-3">Plásticos Desechables para Alimentos</h2>
                                <p class="fs-5 text-white">¡Equipa tu negocio o evento! Encuentra precios increíbles en productos de calidad.</p>
                                <!-- btn -->
                                <a href="#!" class="btn btn-primary">Explorar Descuentos <i class="feather-icon icon-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                        <div class=" "
                            style="background: url(assets/imagenesMilogar/plasticosHogar.webp) no-repeat center center; 
           background-size: cover; 
           border-radius: .5rem; 
           height: 550px; 
           position: relative;">
                            <div class="ps-lg-12 py-lg-10 col-md-7 py-10 ps-8 text-xs-center"
                                style="background-color: rgba(255, 255, 255, 0.1); 
               border-radius: .5rem; 
               position: absolute; 
               top: 0; 
               left: 0; 
               width: 100%; 
               height: 100%; 
               color: #212529; 
               display: flex; 
               flex-direction: column; 
               justify-content: center; 
               align-items: center; 
               padding: 20px;">
                                <!-- badge -->
                                <div class="d-flex align-items-center mb-4">
                                    <span style="color: #212529;">Oferta Especial</span>
                                    <span class="badge bg-warning ms-2 text-dark">20% OFF</span>
                                </div>
                                <!-- title -->
                                <h2 class="text-white display-5 fw-bold mb-3">¡Transforma tu Hogar con Plásticos Prácticos!</h2>
                                <!-- para -->
                                <p class="fs-5 text-white">Organiza tu espacio con productos duraderos y de alta calidad.</p>

                                <!-- btn -->
                                <a href="#!" class="btn btn-primary text-light">Explora la Colección
                                    <i class="feather-icon icon-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- img -->
                        <div class=""
                            style="background: url(assets/imagenesMilogar/canasta_basica.png) no-repeat center center; 
           background-size: cover; 
           border-radius: .5rem; 
           position: relative; 
           height: 550px;">
                            <div class="ps-lg-12 py-lg-16 col-md-7 py-14 ps-8 text-xs-center"
                                style="background-color: rgba(0, 0, 0, 0.5); 
               border-radius: .5rem; 
               position: absolute; 
               top: 0; 
               left: 0; 
               width: 100%; 
               height: 100%; 
               color: #fff; 
               display: flex; 
               flex-direction: column; 
               justify-content: center; 
               align-items: center; 
               padding: 20px;">
                                <!-- badge -->
                                <div class="d-flex align-items-center mb-4">
                                    <span style="color: #fff;">Oferta Exclusiva</span>
                                    <span class="badge bg-success ms-2">20% de Descuento</span>
                                </div>
                                <!-- title -->
                                <h2 class="text-white display-5 fw-bold mb-3">Productos de Primera Necesidad</h2>
                                <!-- para -->
                                <p class="fs-5 text-light">Equipa tu hogar con productos esenciales que garantizan bienestar y satisfacción para toda la familia.</p>
                                <!-- btn -->
                                <a href="#!" class="btn btn-primary text-dark">Explorar Ofertas <i class="feather-icon icon-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                        <div class=""
                            style="background: url(assets/imagenesMilogar/cristaleria.jpg) no-repeat center center;
                        background-size: cover;
                        border-radius: .5rem;
                        position: relative;
                        height: 550px;">
                            <div class="ps-lg-12 py-lg-16 col-md-7 py-14 ps-8 text-xs-center"
                                style="background-color: rgba(0, 0, 0, 0.6); 
               border-radius: .5rem; 
               position: absolute; 
               top: 0; 
               left: 0; 
               width: 100%; 
               height: 100%; 
               color: #000; 
               display: flex; 
               flex-direction: column; 
               justify-content: center; 
               align-items: center; 
               padding: 20px;">
                                <!-- badge -->
                                <div class="d-flex align-items-center mb-4">
                                    <span style="color: #000;">Oferta Exclusiva</span>
                                    <span class="badge bg-primary ms-2 text-light">30% de Descuento</span>
                                </div>
                                <!-- title -->
                                <h2 class="text-white display-5 fw-bold mb-3">Elegancia y Estilo en Cada Detalle</h2>
                                <!-- para -->
                                <p class="fs-5 text-white">Descubre nuestra colección de cristal y porcelana, perfecta para realzar la belleza de tu mesa.</p>
                                <!-- btn -->
                                <a href="#!" class="btn btn-primary text-white">Explorar Colección <i class="feather-icon icon-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                        <div class=""
                            style="background: url(assets/imagenesMilogar/mueblesHogar.jpg) no-repeat center center; 
           background-size: cover; 
           border-radius: .5rem; 
           position: relative; 
           height: 550px;">
                            <div class="ps-lg-12 py-lg-16 col-md-7 py-14 ps-8 text-xs-center"
                                style="background-color: rgba(0, 0, 0, 0.6); 
               border-radius: .5rem; 
               position: absolute; 
               top: 0; 
               left: 0; 
               width: 100%; 
               height: 100%; 
               color: #fff; 
               display: flex; 
               flex-direction: column; 
               justify-content: center; 
               align-items: center; 
               padding: 20px;">
                                <!-- badge -->
                                <div class="d-flex align-items-center mb-4">
                                    <span style="color: #fff;">Oferta Exclusiva</span>
                                    <span class="badge bg-success ms-2 text-light">Hasta 40% OFF</span>
                                </div>
                                <!-- title -->
                                <h2 class="text-white display-5 fw-bold mb-3">Muebles que Transforman Tu Espacio</h2>
                                <!-- para -->
                                <p class="fs-5 text-white">Encuentra el equilibrio perfecto entre diseño, comodidad y funcionalidad para tu hogar.</p>
                                <!-- btn -->
                                <a href="#!" class="btn btn-light text-dark">Explorar Muebles <i class="feather-icon icon-arrow-right ms-1"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xxl-4 col-xl-5 col-12 d-md-flex d-xl-block">

                    <div class="banner mb-6 w-100" style="height: 260px; position: relative;">
                        <!-- Banner Design -->
                        <div class="banner-img" style="height: 100%; overflow: hidden; position: relative;">
                            <!-- Superposición oscura -->
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
                            <!-- Imagen -->
                            <img src="assets/imagenesMilogar/licores.jpg" alt="" class="img-fluid rounded-3 w-100" style="height: 100%; object-fit: cover; position: relative; z-index: 0;">
                            <!-- Texto -->
                            <div class="banner-text" style="position: absolute; top: 50%; right: 10%; text-align: left; color: white; z-index: 2;">
                                <h3 class="mb-2" style="font-size: 1.8rem; font-weight: bold; color: white;">Licores y bebidas</h3>
                                <div class="my-2" style="font-size: 1rem;">
                                    <span style="color: white; font-weight: 600;">Lo mejor en bebidas alcoholicas para eventos.</span>
                                </div>
                                <a href="#" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.9rem; border-radius: 25px;">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="banner w-100">
                        <!-- Banner Design -->
                        <div class="banner-img">
                            <img src="assets/imagenesMilogar/aseoLimpieza.jpg" alt="Productos de limpieza y aseo personal" class="img-fluid rounded-3 w-100">
                            <div class="banner-text"
                                style="position: absolute; top: 0; left: 0; transform: translate(0, 0); 
                                color: #fff; text-align: center; background-color: rgba(0, 0, 0, 0.4); 
                                padding: 20px; border-radius: .5rem; width: 100%; height: 100%;">
                                <h3 class="fs-4 fw-bold lh-1 mb-2">¡Limieza y aseo personal!</h3>
                                <p class="fs-6">Descubre productos de limpieza y aseo personal que te brindan frescura y confianza todos los días.</p>
                                <a href="#" class="btn btn-primary mt-2">Explora las Ofertas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <h2 style="text-align: center;">Todas nuestras categorias</h2>
    <div class="product-carousel">
        <!-- Las categorías se agregarán dinámicamente aquí -->
    </div>
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
                                <div>
                                    <button type="button" class="btn btn-outline-secondary" id="size-250g">250g</button>
                                    <button type="button" class="btn btn-outline-secondary" id="size-500g">500g</button>
                                    <button type="button" class="btn btn-outline-secondary" id="size-1kg">1kg</button>
                                </div>
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
                                    <div class="ms-2 col-4">
                                        <a class="btn btn-light" href="#" data-bs-toggle="tooltip" data-bs-html="true" title="Compare"><i class="bi bi-arrow-left-right"></i></a>
                                        <a class="btn btn-light" href="shop-wishlist.html" data-bs-toggle="tooltip" data-bs-html="true" title="Wishlist"><i class="feather-icon icon-heart"></i></a>
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
                                            <tr>
                                                <td>Shipping:</td>
                                                <td><small>01 day shipping.<span class="text-muted">(Free pickup today)</span></small></td>
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
    <section class="my-lg-14 my-8">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-6">

                    <h3 class="mb-0">Productos con promoción</h3>

                </div>
            </div>

            <div class="row g-4 row-cols-lg-5 row-cols-2 row-cols-md-3" id="productos-promocion-container">

            </div>
        </div>
    </section>


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
</body>

</html>