<?php
session_start();
require_once "../Config/db.php";
require_once "../Models/TiendaModel.php";
require_once "../Controllers/TiendaController.php";
require_once "../Controllers/ProductoController.php";
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
// Obtener categorías con subcategorías
$categorias = $tiendaController->getCategoriasConSubcategorias(); // Este es el método que deberías usar
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
    <meta name="description" content="FreshCart is a beautiful eCommerce HTML template specially designed for multipurpose shops & online stores selling products. Most Loved by Developers to build a store website easily.">
    <meta content="Codescandy" name="author" />


    <!-- Favicon icon-->
    <link rel="shortcut icon" href="../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">
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
    </style>
</head>

<body>
    <?php
    require_once "../Views/Navigation/navigation.php";
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
                            <li class="breadcrumb-item active" aria-current="page">Snacks & Munchies</li>
                        </ol>
                    </nav>
                </div>
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
                        <!-- title -->
                        <h5 class="mb-3">Todas las categorías</h5>
                        <ul class="nav nav-category" id="categoryCollapseMenu">
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <li class="nav-item border-bottom w-100 collapsed category-item" aria-expanded="false">
                                        <a href="mostrarProductos.php?categoria_id=<?= $categoria['id']; ?>" class="nav-link"><?= $categoria['nombreCategoria']; ?> <i class="feather-icon icon-chevron-right"></i></a>
                                        <div class="subcategories-menu">
                                            <ul class="nav flex-column ms-3">
                                                <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                                                    <li class="nav-item">
                                                        <a href="mostrarProductos.php?subcategoria_id=<?= $subcategoria['id']; ?>" class="nav-link subcategoria-item" data-id="<?= $subcategoria['id']; ?>">
                                                            <?= $subcategoria['nombrSubcategoria']; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="nav-item">No hay categorías disponibles</li>
                            <?php endif; ?>
                        </ul>



                        <!-- Modal para mostrar los productos -->
                        <div id="productosModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span id="closeModal" style="cursor:pointer;">&times;</span>
                                <h5 id="subcategoriaTitulo"></h5>
                                <div id="listaProductos"></div>
                            </div>
                        </div>

                    </div>

                    <div class="py-4">

                        <h5 class="mb-3">Stores</h5>
                        <div class="my-4">
                            <!-- input -->
                            <input type="search" class="form-control" placeholder="Search by store">
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="eGrocery" checked>
                            <label class="form-check-label" for="eGrocery">
                                E-Grocery
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="DealShare">
                            <label class="form-check-label" for="DealShare">
                                DealShare
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="Dmart">
                            <label class="form-check-label" for="Dmart">
                                DMart
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="Blinkit">
                            <label class="form-check-label" for="Blinkit">
                                Blinkit
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="BigBasket">
                            <label class="form-check-label" for="BigBasket">
                                BigBasket
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="StoreFront">
                            <label class="form-check-label" for="StoreFront">
                                StoreFront
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="Spencers">
                            <label class="form-check-label" for="Spencers">
                                Spencers
                            </label>
                        </div>
                        <!-- form check -->
                        <div class="form-check mb-2">
                            <!-- input -->
                            <input class="form-check-input" type="checkbox" value="" id="onlineGrocery">
                            <label class="form-check-label" for="onlineGrocery">
                                Online Grocery
                            </label>
                        </div>

                    </div>
                    <div class="py-4">
                        <!-- price -->
                        <h5 class="mb-3">Price</h5>
                        <div>
                            <!-- range -->
                            <div id="priceRange" class="mb-3"></div>
                            <small class="text-muted">Price:</small> <span id="priceRange-value" class="small"></span>

                        </div>



                    </div>
                    <!-- rating -->
                    <div class="py-4">

                        <h5 class="mb-3">Rating</h5>
                        <div>
                            <!-- form check -->
                            <div class="form-check mb-2">
                                <!-- input -->
                                <input class="form-check-input" type="checkbox" value="" id="ratingFive">
                                <label class="form-check-label" for="ratingFive">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                </label>
                            </div>
                            <!-- form check -->
                            <div class="form-check mb-2">
                                <!-- input -->
                                <input class="form-check-input" type="checkbox" value="" id="ratingFour" checked>
                                <label class="form-check-label" for="ratingFour">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star text-warning"></i>
                                </label>
                            </div>
                            <!-- form check -->
                            <div class="form-check mb-2">
                                <!-- input -->
                                <input class="form-check-input" type="checkbox" value="" id="ratingThree">
                                <label class="form-check-label" for="ratingThree">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star-fill text-warning "></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </label>
                            </div>
                            <!-- form check -->
                            <div class="form-check mb-2">
                                <!-- input -->
                                <input class="form-check-input" type="checkbox" value="" id="ratingTwo">
                                <label class="form-check-label" for="ratingTwo">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </label>
                            </div>
                            <!-- form check -->
                            <div class="form-check mb-2">
                                <!-- input -->
                                <input class="form-check-input" type="checkbox" value="" id="ratingOne">
                                <label class="form-check-label" for="ratingOne">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </label>
                            </div>
                        </div>


                    </div>
                    <div class="py-4">
                        <!-- Banner Design -->
                        <!-- Banner Content -->
                        <div class="position-absolute p-5 py-8">
                            <h3 class="mb-0">Fresh Fruits </h3>
                            <p>Get Upto 25% Off</p>
                            <a href="#" class="btn btn-dark">Shop Now<i class="feather-icon icon-arrow-right ms-1"></i></a>
                        </div>
                        <!-- Banner Content -->
                        <!-- Banner Image -->
                        <!-- img --><img src="../assets/images/banner/assortment-citrus-fruits.png" alt=""
                            class="img-fluid rounded-3">
                        <!-- Banner Image -->
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
                    <!-- row -->
                    <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2" id="productos-populares-container">
                            

                        
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
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true" data-id="<?php echo $pop['id'];  ?>">
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
                                <a href="#!" class="mb-4 d-block">Bakery Biscuits</a>
                                <h2 class="product-name mb-1 h1" id="product-name"></h2>
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
                                    <span class="fw-bold text-dark">$32</span>
                                    <span class="text-decoration-line-through text-muted">$35</span>
                                    <span><small class="fs-6 ms-2 text-danger">26% Off</small></span>
                                </div>
                                <hr class="my-6">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary">250g</button>
                                    <button type="button" class="btn btn-outline-secondary">500g</button>
                                    <button type="button" class="btn btn-outline-secondary">1kg</button>
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
                                                <td>Descripcion:</td>
                                                <td class="product-description">product-price</td>
                                            </tr>
                                            <tr>
                                                <td>Product Code:</td>
                                                <td>FBB00255</td>
                                            </tr>
                                            <tr>
                                                <td>Availability:</td>
                                                <td>In Stock</td>
                                            </tr>
                                            <tr>
                                                <td>Type:</td>
                                                <td>Fruits</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Mantener el carrito de compras en todas las ventanas del navegador, agregar el script donde 
        esta toda la logica -->

    <script src="../assets/js/cart.js"></script>
    <script src="../assets/js/producto.js"></script><!--Este script abre el modal de los detalles del producto en esta vista-->
    <script src="../assets/js/login.js"></script>
    <script src="../assets/js/BusquedaDinamica.js"></script>

</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:10 GMT -->

</html>