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

    <title>MILOGAR - Catalogo</title>
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

        /* Añadir un margen superior para que el contenido no se solape con el navbar fijo */
        body {
            margin-top: 0px;
            /* Ajusta según la altura del navbar fijo */
        }

        /* También puedes aplicar el z-index si es necesario */
        .navbar {
            z-index: 1050;
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

        /* Tamanio predeterminado de la imagen*/
        .card-product img {
            width: 100%;
            /* Ajusta el ancho al contenedor */
            height: 200px;
            /* Define una altura fija */
            object-fit: cover;
            /* Recorta la imagen sin deformarla */
            border-radius: 5px;
            /* Opcional: bordes redondeados */
        }
    </style>
    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    </script>
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

                </div>

            </div>
        </div>
    </div>
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

    <!-- section -->
    <section class=" mt-8 mb-lg-14 mb-8">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row gx-10">
              <!-- col -->
                <div class="col-lg-3 col-md-4 mb-6 mb-md-0">
                    <div class="py-4">
                        <div class="categorias-wrapper">
                            <!-- Encabezado visible solo en móviles -->
                            <div class="p-4 d-flex justify-content-between align-items-center d-md-none">
                                <h5 class="mb-0">Todas las categorías</h5>
                                <button class="btn btn-outline-gray-400 text-muted btn-icon" id="toggleCategorias">
                                    <i class="feather-icon icon-menu fs-4"></i>
                                </button>
                            </div>
                            <span id="puntosUsuario" style="display:none;">100</span>


                            <!-- Menú lateral de categorías (Escritorio) -->
                            <div class="border-end pt-4 pe-lg-4 d-none d-md-block">
                                <ul class="nav flex-column nav-pills nav-pills-dark" id="categoryMenuDesktop">
                                    <!-- Categorías para escritorio -->
                                </ul>
                            </div>

                            <!-- Menú desplegable en móviles -->
                            <div class="d-md-none px-3">
                                <ul class="nav flex-column nav-pills nav-pills-dark mt-2 d-none" id="categoryMenuMobile">
                                    <!-- Categorías para móvil -->
                                </ul>
                            </div>
                        </div>

                        <!-- Modal para mostrar los productos -->
                        <div id="productosModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span id="closeModal" style="cursor:pointer;">&times;</span>
                                <h5 id="subcategoriaTitulo"></h5>
                                <div id="listaProductos"></div>
                            </div>
                        </div>

                    </div>
                    <!-- Banner Design -->
                </div>
                <div class="col-lg-9 col-md-8">
                    <!-- card -->
                    <div class="card mb-4 bg-light border-0">
                        <!-- card body -->
                        <div class=" card-body p-9">
                            <h1 class="mb-0">Productos Populares</h1>
                        </div>
                    </div>


                    <!-- list icon -->
                    <div class="d-md-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-3 mb-md-0"> <span class="text-dark">24 </span> Products found </p>
                        </div>
                        <!-- icon -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="shop-list.html" class="text-muted me-3"><i class="bi bi-list-ul"></i></a>
                            <a href="shop-grid.html" class=" me-3 active"><i class="bi bi-grid"></i></a>
                            <a href="shop-grid-3-column.html" class="me-3 text-muted"><i class="bi bi-grid-3x3-gap"></i></a>
                            <div class="me-2">
                                <!-- select option -->
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Show: 50</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                            <div>
                                <!-- select option -->
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Sort by: Featured</option>
                                    <option value="Low to High">Price: Low to High</option>
                                    <option value="High to Low"> Price: High to Low</option>
                                    <option value="Release Date"> Release Date</option>
                                    <option value="Avg. Rating"> Avg. Rating</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor de productos populares -->
                    <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2" id="productos-populares-container">
                    </div>

                    <!-- Contenedor de productos generales -->
                    <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2" id="productos-container">
                    </div>

                    <!-- Contenedor de resultados de búsqueda -->
                    <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2" id="resultados-busqueda-container">
                    </div>


                    <div class="row mt-8">
                        <div class="col">
                            <!-- nav -->
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link  mx-1 rounded-3 " href="#" aria-label="Previous">
                                            <i class="feather-icon icon-chevron-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item "><a class="page-link  mx-1 rounded-3 active" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link mx-1 rounded-3 text-body" href="#">2</a></li>

                                    <li class="page-item"><a class="page-link mx-1 rounded-3 text-body" href="#">...</a></li>
                                    <li class="page-item"><a class="page-link mx-1 rounded-3 text-body" href="#">12</a></li>
                                    <li class="page-item">
                                        <a class="page-link mx-1 rounded-3 text-body" href="#" aria-label="Next">
                                            <i class="feather-icon icon-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
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
    <div class="whatsapp-container">
        <a href="https://wa.me/593989082073" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
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
    <script src="assets/js/mostrarProductosLista.js"></script>
    <script src="assets/js/BusquedaDinamica.js"></script>

    <script src="assets/js/theme.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get("q");

            if (query) {
                document.getElementById("busqueda").value = query; // Mostrar el término en el input
                realizarBusqueda(query); // Llamar la función de búsqueda automáticamente
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const categoriaId = urlParams.get('categoria_id');
            const subcategoriaId = urlParams.get('subcategoria_id');

            // Ocultar productos populares si hay filtros aplicados
            if (categoriaId || subcategoriaId) {
                const popularesContainer = document.getElementById('productos-populares-container');
                if (popularesContainer) {
                    popularesContainer.style.display = 'none';
                }
            }

            // Si no hay filtros, no hacemos fetch adicional
            if (!categoriaId && !subcategoriaId) {
                return;
            }

            const url = new URL('http://localhost:8080/Milogar/Controllers/TiendaController.php');
            url.searchParams.append('action', 'obtenerProductos');
            if (categoriaId) url.searchParams.append('categoria_id', categoriaId);
            if (subcategoriaId) url.searchParams.append('subcategoria_id', subcategoriaId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('productos-container');
                    container.innerHTML = ''; // limpiar

                    if (!data || data.length === 0) {
                        container.innerHTML = '<p class="text-center">No hay productos disponibles.</p>';
                        return;
                    }

                    data.forEach(producto => {
                        const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;
                        const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);
                        const userRole = document.getElementById('role').getAttribute('data-role');
                        const isAdmin = userRole === 'Administrador';
                        const productHTML = `
                <div class="col">
                    <div class="card card-product">
                        <div class="card-body">
                            <div class="text-center position-relative">
                                ${producto.descuento > 0 ? `
                                                                  <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-danger rounded-circle d-flex justify-content-center align-items-center text-white"
                                                style="width: 60px; height: 60px; font-size: 0.8rem;">
                                                ${producto.descuento}%<br>Desc.
                                            </span>
                                        </div>
                                    ` : ''}

                                ${isAdmin ? `
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <a href="#!" class="text-decoration-none text-primary" onclick="editarProducto(${producto.id})">
                                            <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                                        </a>
                                    </div>` : ''}

                                <a href="#!">
                                    <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                                </a>
                                <br><br><br>
                                <a href="#!" class="btn btn-success btn-sm w-100 position-absolute bottom-0 start-0 mb-3" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}" style="border-radius: 12px;">
                                    Ver Detalle
                                </a>
                            </div>

                            <div class="text-small mb-1">
                                <a href="#!" class="text-decoration-none text-muted">
                                    <small>${producto.nombreSubcategoria}</small>
                                </a>
                            </div>

                            <h2 class="fs-6">
                                <a href="#!" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
                            </h2>

                            ${(usuarioSesion && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0) ? `
                                <p class="text-success fw-bold">
                                    Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
                                </p>` : ''}

                            <div>
                                <small class="text-warning"> 
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </small> 
                                <span class="text-muted small">4.5(149)</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="text-dark">$${precioFinal.toFixed(2)}</span>
                                    <span class="text-decoration-line-through text-muted">$${producto.precio_1}</span>
                                </div>

                                ${producto.is_talla == 0 ? `
                                    <div>
                                        <a href="#!" class="btn btn-primary btn-sm add-to-cart" 
                                            data-id="${producto.id}" 
                                            data-nombre="${producto.nombreProducto}"
                                            data-precio="${producto.precio_1}"
                                            data-descuento="${producto.descuento}"
                                            data-imagen="${imagenUrl}"
                                            data-puntos="${producto.puntos_otorgados}"
                                            data-minimo="${producto.cantidad_minima_para_puntos}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            Add
                                        </a>
                                    </div>` : ''}
                            </div>

                            <div class="mt-3">
                                <h6>Deja tu comentario:</h6>
                                <textarea id="comentario-${producto.id}" class="form-control mb-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                <select id="calificacion-${producto.id}" class="form-select mb-2">
                                    <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                    <option value="4">⭐️⭐️⭐️⭐️</option>
                                    <option value="3">⭐️⭐️⭐️</option>
                                    <option value="2">⭐️⭐️</option>
                                    <option value="1">⭐️</option>
                                </select>
                                <button onclick="enviarComentario(${producto.id})" class="btn btn-outline-primary btn-sm">Enviar comentario</button>
                            </div>

                            <div class="mt-4">
                                <h6>Comentarios:</h6>
                                <div id="comentarios-lista-${producto.id}">
                                    <p class="text-muted">Cargando comentarios...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                        container.innerHTML += productHTML;
                        setTimeout(() => {
                            cargarComentariosActivos(producto.id);
                        }, 10);
                    });
                })



                .catch(error => {
                    console.error('Error al obtener productos:', error);
                    const container = document.getElementById('productos-container');
                    container.innerHTML = '<p>Error al cargar productos.</p>';
                });


            $('#quickViewModal').off('show.bs.modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var productoId = button.data('id');
                var modal = $(this);
                const imagenProducto = "imagenesMilogar/productos/" + $(this).data('imagen');

                // Hacer la solicitud fetch
                fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=verDetalle&id=' + productoId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        if (data.status === 'success') {
                            var producto = data.data;

                            // Actualizar la información del producto en el modal
                            modal.find('#product-name').text(producto.nombreProducto);
                            modal.find('#product-description').text(producto.descripcionProducto);

                            var precioOriginal = producto.precio_1;
                            var descuento = producto.descuento || 0;
                            var precioConDescuento = (precioOriginal - (precioOriginal * descuento / 100)).toFixed(2);

                            modal.find('#product-price').text(`$${precioConDescuento}`);
                            modal.find('#original-price').text(`$${precioOriginal}`);
                            modal.find('#discount-percent').text(`${descuento}% Off`);
                            modal.find('#product-code').text(producto.codigo_barras);
                            modal.find('#availability').text(producto.stock > 0 ? 'In Stock' : 'Out of Stock');
                            modal.find('#Category').text(producto.categoria_nombre || 'Unknown Category');
                            modal.find('#Subcategory').text(producto.subcategoria_nombre || 'Unknown Subcategory');
                            modal.find('#product-image').attr('src', 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);

                            // Buscar si el producto ya está en el carrito
                            const productoEnCarrito = carrito.find(item => item.id === producto.id);
                            if (productoEnCarrito) {
                                modal.find('.quantity-field').val(productoEnCarrito.cantidad);
                            } else {
                                modal.find('.quantity-field').val(1);
                            }

                            if (producto.is_talla == 1) {
                                fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerTallasPorProducto&producto_id=' + productoId, {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(tallasData => {
                                        if (tallasData.status === 'success' && tallasData.data.length > 0) {
                                            let tallasHTML = '<label>Seleccione talla</label><br><div class="btn-group btn-group-toggle" data-toggle="buttons">';
                                            tallasData.data.forEach((talla, index) => {
                                                tallasHTML += `
                    <label class="btn btn-outline-secondary ${index === 0 ? 'active' : ''}" style="cursor:pointer; user-select:none;">
                        <input type="radio" name="select-talla" id="talla-${index}" value="${talla.talla}" autocomplete="off" ${index === 0 ? 'checked' : ''} style="display:none;"> ${talla.talla}
                    </label>
                `;
                                            });
                                            tallasHTML += '</div>';

                                            modal.find('#product-sizes').html(tallasHTML);

                                            // Opcional: Añadir pequeño script para que al seleccionar un label, cambie el estilo
                                            modal.find('#product-sizes .btn').click(function() {
                                                modal.find('#product-sizes .btn').removeClass('active');
                                                $(this).addClass('active');
                                                $(this).find('input[type=radio]').prop('checked', true);
                                            });
                                        } else {
                                            modal.find('#product-sizes').html('<p>No hay tallas disponibles.</p>');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error al obtener tallas:', error);
                                        modal.find('#product-sizes').html('<p>Error al cargar tallas.</p>');
                                    });
                            } else {
                                modal.find('#product-sizes').html('');
                            }


                            modal.find('#add-to-cart2').off('click').on('click', function() {
                                const cantidadSeleccionada = parseInt(modal.find('.quantity-field').val()) || 1;
                                const productoId = parseInt(producto.id);
                                const nombreProductoBase = producto.nombreProducto;
                                const precioProducto = parseFloat(producto.precio_1);
                                const descuento = parseFloat(producto.descuento) || 0;
                                const imagenProducto = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;
                                const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                                // Obtener talla seleccionada (si aplica)
                                const tallaSeleccionada = producto.is_talla == 1 ?
                                    modal.find('input[name="select-talla"]:checked').val() :
                                    null;

                                const nombreProducto = tallaSeleccionada ?
                                    `${nombreProductoBase} - Talla ${tallaSeleccionada}` :
                                    nombreProductoBase;

                                // Buscar si el producto ya está en el carrito
                                const productoExistente = carrito.find(p => {
                                    return parseInt(p.id) === productoId &&
                                        (p.talla ?? null) === (tallaSeleccionada ?? null);
                                });

                                if (productoExistente) {
                                    productoExistente.cantidad += cantidadSeleccionada;
                                } else {
                                    carrito.push({
                                        id: productoId,
                                        nombre: nombreProducto,
                                        precio: precioConDescuento,
                                        cantidad: cantidadSeleccionada,
                                        precioOriginal: precioProducto,
                                        imagen: imagenProducto,
                                        descuento: descuento,
                                        talla: tallaSeleccionada ?? null
                                    });
                                }

                                // Guardar en localStorage y actualizar
                                guardarCarrito();
                                actualizarCarrito();
                                actualizarContadorCarrito();

                                // Resetear cantidad en el modal a 1
                                modal.find('.quantity-field').val(1);

                                // Mostrar alerta
                                Swal.fire({
                                    position: 'bottom-left',
                                    icon: 'success',
                                    title: '¡Agregado correctamente!',
                                    showConfirmButton: false,
                                    timer: 4000,
                                    toast: true,
                                    timerProgressBar: true,
                                });
                            });



                        }
                    })
                    .catch(error => console.error("Error al obtener datos:", error));
            });

            document.addEventListener('click', function(event) {
                if (event.target.closest('.add-to-cart')) {
                    event.preventDefault();

                    const btn = event.target.closest('.add-to-cart');

                    const productoId = btn.dataset.id;
                    const nombreProducto = btn.dataset.nombre;
                    const precioProducto = parseFloat(btn.dataset.precio);
                    const descuento = parseFloat(btn.dataset.descuento) || 0;
                    const imagenProducto = btn.dataset.imagen;
                    const puntosOtorgados = parseInt(btn.dataset.puntos) || 0;
                    const cantidadMinimaParaPuntos = parseInt(btn.dataset.minimo) || 0;

                    if (!productoId || !nombreProducto || isNaN(precioProducto) || !imagenProducto) {
                        console.error("Datos del producto incompletos o inválidos");
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo agregar el producto. Intenta nuevamente.',
                            toast: true,
                            position: 'bottom-left',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        return;
                    }

                    const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                    const productoExistente = carrito.find(producto => producto.id == productoId);

                    if (productoExistente) {
                        productoExistente.cantidad++;
                    } else {
                        carrito.push({
                            id: productoId,
                            nombre: nombreProducto,
                            precio: precioConDescuento,
                            cantidad: 1,
                            precioOriginal: precioProducto,
                            imagen: imagenProducto,
                            descuento: descuento,
                            puntos_otorgados: puntosOtorgados,
                            cantidad_minima_para_puntos: cantidadMinimaParaPuntos
                        });
                    }

                    guardarCarrito();
                    actualizarCarrito();
                    actualizarContadorCarrito();

                    Swal.fire({
                        position: 'bottom-left',
                        icon: 'success',
                        title: `¡${nombreProducto} agregado al carrito!`,
                        html: `<a href="#" id="ver-carrito" class="btn btn-sm btn-outline-dark mt-2">Ver carrito de compras</a>`,
                        showConfirmButton: false,
                        timer: 4000,
                        toast: true,
                        timerProgressBar: true,
                        didOpen: () => {
                            document.getElementById('ver-carrito').addEventListener('click', function(e) {
                                e.preventDefault();
                                const carrito = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
                                carrito.show();
                            });
                        }
                    });
                }
            });


        });

        function enviarComentario(productoId) {
            const userId = usuarioSesion;
            const comentario = document.getElementById(`comentario-${productoId}`).value.trim();
            const calificacion = document.getElementById(`calificacion-${productoId}`).value;

            if (!userId) {
                Swal.fire('Error', 'Debes iniciar sesión para comentar.', 'warning');
                return;
            }

            if (!comentario) {
                Swal.fire('Error', 'Por favor escribe un comentario antes de enviar.', 'warning');
                return;
            }

            // Verificar si ya existe comentario para ese usuario y producto
            fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=checkComentario&user_id=${userId}&producto_id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Atención!',
                            text: 'Solo puedes hacer un comentario por producto.',
                            confirmButtonText: 'Aceptar'
                        });
                        return null; // detener la cadena
                    } else {
                        // Enviar comentario
                        return fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=create', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                producto_id: productoId,
                                comentario: comentario,
                                calificacion: parseInt(calificacion)
                            })
                        });
                    }
                })
                .then(response => {
                    if (!response) return null;
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Comentario enviado',
                            text: 'Tu comentario se ha publicado correctamente.',
                            confirmButtonText: 'Aceptar'
                        });

                        // Limpiar campos
                        document.getElementById(`comentario-${productoId}`).value = '';
                        document.getElementById(`calificacion-${productoId}`).value = 5;

                        // Recargar comentarios y mostrar botón si hay comentarios
                        cargarComentariosActivos(productoId);
                    } else {
                        Swal.fire('Error', 'No se pudo enviar el comentario: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Ocurrió un error al enviar el comentario.', 'error');
                });
        }



        function cargarComentariosActivos(productoId) {
            console.log('[🔍 DEBUG] Llamando a cargarComentariosActivos con productoId:', productoId);

            fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=getAllComentariosActive')
                .then(response => response.json())
                .then(data => {
                    console.log('[📥 API] Comentarios recibidos:', data);

                    if (data.success) {
                        const comentarios = data.data;

                        const contenedor = document.getElementById(`comentarios-lista-${productoId}`);
                        if (!contenedor) {
                            console.warn(`❌ No se encontró el contenedor con ID comentarios-lista-${productoId}`);
                            return;
                        }

                        console.log('[✅ DOM] Contenedor encontrado para productoId', productoId, ':', contenedor);

                        contenedor.innerHTML = ''; // Limpiar antes de renderizar

                        // Filtrar comentarios por producto
                        const comentariosProducto = comentarios
                            .filter(c => c.producto_id == productoId)
                            .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

                        console.log(`[📝 Comentarios] ${comentariosProducto.length} comentario(s) para productoId ${productoId}`);

                        if (comentariosProducto.length === 0) {
                            contenedor.innerHTML = '<p class="text-muted">Aún no hay comentarios.</p>';
                        } else {
                            const c = comentariosProducto[0];

                            contenedor.innerHTML = `
                        <div class="border rounded p-2 mb-2 bg-light">
                            <strong>${c.nombreUsuario}</strong> 
                            <span class="text-warning">${'⭐'.repeat(c.calificacion)}</span><br>
                            <small class="text-muted">${new Date(c.fecha).toLocaleString()}</small>
                            <p>${c.comentario}</p>
                        </div>
                    `;

                            contenedor.innerHTML += `
                        <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1"
                                onclick="mostrarModalTodosComentarios(${productoId})">
                            Ver todos los comentarios
                        </button>
                    `;
                        }
                    } else {
                        console.error("⚠️ Error en respuesta de la API:", data.message);
                    }
                })
                .catch(error => {
                    console.error("❌ Error en la petición de comentarios:", error);
                });
        }

        function abrirModalEditarComentario(idComentario, comentario, calificacion, productoId) {
            document.getElementById('edit-id-comentario').value = idComentario;
            document.getElementById('edit-id-producto').value = productoId;
            document.getElementById('edit-comentario').value = comentario;
            document.getElementById('edit-calificacion').value = calificacion;

            const modal = new bootstrap.Modal(document.getElementById('modalEditarComentario'));
            modal.show();
        }


        function guardarEdicionComentario(event) {
            event.preventDefault();

            const idComentario = document.getElementById('edit-id-comentario').value;
            const productoId = document.getElementById('edit-id-producto').value;
            const nuevoComentario = document.getElementById('edit-comentario').value.trim();
            const nuevaCalificacion = document.getElementById('edit-calificacion').value;

            if (!nuevoComentario) {
                Swal.fire('Error', 'El comentario no puede estar vacío.', 'warning');
                return;
            }

            fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: idComentario,
                        comentario: nuevoComentario,
                        calificacion: parseInt(nuevaCalificacion)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Comentario actualizado',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Oculta ambos modales (por si acaso)
                            const modalEdicion = bootstrap.Modal.getInstance(document.getElementById('modalEditarComentario'));
                            const modalComentarios = bootstrap.Modal.getInstance(document.getElementById('modalTodosComentarios'));
                            if (modalEdicion) modalEdicion.hide();
                            if (modalComentarios) modalComentarios.hide();
                            // Recargar contenido y volver a mostrar uno solo
                            // Solo recarga los comentarios en la tarjeta
                            cargarComentariosActivos(productoId);
                        });


                        // Cerrar el modal de edición (el pequeño)
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarComentario'));
                        modal.hide();
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo actualizar el comentario.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar comentario:', error);
                    Swal.fire('Error', 'Ocurrió un error al intentar actualizar el comentario.', 'error');
                });
        }

        function mostrarModalTodosComentarios(productoId) {
            const contenedor = document.getElementById("contenedor-todos-comentarios");
            contenedor.innerHTML = '<p class="text-muted">Cargando comentarios...</p>';

            fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=getComentariosPorProducto&producto_id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Comentarios:", data.data);
                        const comentarios = data.data;
                        if (comentarios.length === 0) {
                            contenedor.innerHTML = '<p class="text-muted">Aún no hay comentarios.</p>';
                            return;
                        }

                        const imagenDefecto = 'https://cdn-icons-png.flaticon.com/512/847/847969.png';

                        contenedor.innerHTML = comentarios.map(c => {
                            // Ahora la propiedad es c.usuario_id
                            const esUsuario = usuarioSesion && parseInt(c.usuario_id) === parseInt(usuarioSesion);

                            return `
                        <div class="border rounded p-2 mb-2 bg-light d-flex">
                            <img src="${imagenDefecto}" alt="Usuario" class="rounded-circle me-2" width="50" height="50">
                            <div style="flex: 1">
                                <strong>${c.nombreUsuario}</strong>
                                <span class="text-warning">${'⭐'.repeat(c.calificacion)}</span><br>
                                <small class="text-muted">${new Date(c.fecha).toLocaleString()}</small>
                                <p class="mb-1">${c.comentario}</p>

                                ${esUsuario ? `
                                    <button class="btn btn-sm btn-warning me-2"
                                        onclick="abrirModalEditarComentario(${c.id}, '${c.comentario.replace(/'/g, "\\'")}', ${c.calificacion}, ${productoId})">Editar</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="eliminarComentario(${c.id}, ${productoId})">Eliminar</button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                        }).join('');
                    } else {
                        contenedor.innerHTML = `<p class="text-danger">${data.message}</p>`;
                    }
                })
                .catch(err => {
                    console.error("Error al cargar comentarios:", err);
                    contenedor.innerHTML = '<p class="text-danger">Error al cargar los comentarios.</p>';
                });

            const modal = new bootstrap.Modal(document.getElementById("modalTodosComentarios"));
            modal.show();
        }

        function eliminarComentario(idComentario, productoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará tu comentario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=delete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: idComentario
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado', 'Tu comentario ha sido eliminado.', 'success').then(() => {
                                    // 🔁 Recargar la tarjeta
                                    if (typeof cargarComentariosActivos === 'function') {
                                        cargarComentariosActivos(productoId);
                                    }

                                    // ✅ Cerrar el modal si está abierto
                                    const modalElement = document.getElementById('modalTodosComentarios');
                                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }
                                });
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo eliminar el comentario.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar comentario:', error);
                            Swal.fire('Error', 'Ocurrió un error al intentar eliminar el comentario.', 'error');
                        });
                }
            });
        }

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
<!-- Mirrored from freshcart.codescandy.com/pages/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:10 GMT -->

</html>