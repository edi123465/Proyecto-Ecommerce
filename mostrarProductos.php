<?php
session_start();
require_once "../Config/db.php";
require_once "../Models/TiendaModel.php";
require_once("../Models/ProductoModel.php"); // Asegúrate de que el modelo esté incluido
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
//$productosPopulares = $productoController->ProductosPopulares();
// Obtener categorías con subcategorías
$productoModel = new ProductoModel($connection);
// Comprobar si hay un ID de categoría o subcategoría en la URL
$categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;
$subcategoria_id = isset($_GET['subcategoria_id']) ? $_GET['subcategoria_id'] : null;

// Inicializar el array de productos
$productos = [];
// Preparar la consulta de productos dependiendo de lo que se haya recibido en la URL
if ($categoria_id) {
    // Obtener productos por categoría
    $productos = $productoModel->getProductsByCategory($categoria_id);
} elseif ($subcategoria_id) {
    // Obtener productos por subcategoría
    $productos = $productoModel->getProductsBySubcategory($subcategoria_id);
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
                            <!-- Aquí se insertarán dinámicamente las categorías -->
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
                    <div class="row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2">


                        
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
                                    <img class="product-image" src="../assets/images/products/product-single-img-1.jpg" alt="">
                                </div>
                            </div>

                            <!-- Miniatura -->
                            <div class="prodxuct-tools">
                                <div class="thumbnails row g-3" id="productModalThumbnails">
                                    <div class="col-3">
                                        <div class="thumbnails-img">
                                            <img src="../assets/images/products/product-single-img-1.jpg" alt="">
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
                                        <button type="button" class="btn btn-primary" data-id="<?php echo $pop['id']; ?>" data-nombre="<?php echo $pop['nombreProducto']; ?>" data-precio="<?php echo number_format($pop['precio_1'], 2); ?>" data-imagen="<?php echo htmlspecialchars($rutaImagen); ?>"><i class="feather-icon icon-shopping-bag me-2 add-to-cart"></i>Add to cart</button>
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
                                    </svg>
                                </a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mantener el carrito de compras en todas las ventanas del navegador, agregar el script donde 
        esta toda la logica -->

    <script src="../assets/js/cart.js"></script>
    <script src="../assets/js/mostrarProductosLista.js"></script>
</body>
<!-- Mirrored from freshcart.codescandy.com/pages/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:46:10 GMT -->

</html>