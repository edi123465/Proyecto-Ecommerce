<?php
require_once __DIR__ . "/../../Config/config.php";

?>

<!-- navigation -->
<div class="border-bottom pb-5">
    <nav class="navbar navbar-light py-lg-5 pt-3 px-0 pb-0">
        <div class="container">
            <div class="row w-100 align-items-center g-3">
                <div class="col-xxl-2 col-lg-3">
                    <a class="navbar-brand d-none d-lg-flex align-items-center" href="index.php" style="text-decoration: none;">
                        <img src="<?php  echo BASE_URL; ?>assets/imagenesMilogar/logomilo.jpg" alt="eCommerce HTML Template" style="width: 50px; height: 50px; margin-right: 10px;">
                        <span style="font-size: 1.5rem; font-weight: bold; color: #333;">MILOGAR</span>
                    </a>

                    <div class="d-flex justify-content-between w-100 d-lg-none">
                        <a class="navbar-brand d-flex align-items-center" href="#" style="text-decoration: none;">
                        <img src="<?php echo BASE_URL; ?>assets/imagenesMilogar/logomilo.jpg" alt="eCommerce HTML Template" style="width: 35px; height: 35px; margin-right: 8px;">
                        <span style="font-size: 1.25rem; font-weight: bold; color: #333;">MILOGAR</span>
                        </a>

                        <div class="d-flex align-items-center lh-1">

                            <div class="list-inline me-2">
                                <div class="list-inline-item">

                                    <a href="#!" class="text-muted" data-bs-toggle="modal" data-bs-target="#userModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-user">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a class="text-muted" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                        href="#offcanvasExample" role="button" aria-controls="offcanvasRight">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-shopping-bag">
                                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                            <line x1="3" y1="6" x2="21" y2="6"></line>
                                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                            <!-- Button -->
                            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="icon-bar top-bar mt-0"></span>
                                <span class="icon-bar middle-bar"></span>
                                <span class="icon-bar bottom-bar"></span>
                            </button>

                        </div>
                    </div>

                </div>
                <div class="col-xxl-6 col-lg-5 d-none d-lg-block">
                    <form class="search-header" method="GET" id="searchForm">
                        <input type="hidden" name="action" value="search">
                        <div class="input-group">
                            <input type="text" class="form-control border-end-0" id="busqueda" name="q"
                                placeholder="Busca lo que necesitas" required
                                aria-label="Search for products.." aria-describedby="basic-addon2">
                            <button type="submit" class="input-group-text bg-transparent" id="searchButton">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
                <!-- Modal de Inicio de Sesión -->
                <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-4">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fs-3" id="loginModalLabel">Iniciar sesión</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="loginForm">
                                    <input type="hidden" name="form_type" value="login">

                                    <div class="mb-3">
                                        <label for="loginEmail" class="form-label">Nombre de usuario</label>
                                        <input type="text" name="txt_nombreUsuario" class="form-control" placeholder="Nombre de usuario" required="">
                                    </div>
                                    <div class="mb-5">
                                        <label for="loginPassword" class="form-label">Contraseña</label>
                                        <input type="password" name="txt_password" class="form-control" placeholder="Ingrese su contraseña" required="">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-3">Iniciar sesión</button>
                                </form>

                                <!-- Enlace para continuar como invitado -->
                                <div class="text-center mt-3">
                                    <a href="Continuar_invitado.php" class="btn btn-outline-secondary w-100">Continuar como invitado</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Espacio entre el input de buscar y los iconos de user y shop -->
                <div class="col-md-2 col-xxl-3 d-none d-lg-block">
                </div>
                <div class="col-md-2 col-xxl-1 text-end d-none d-lg-block">
                    <!-- CONTENEDOR PARA LOS ICONOS DE CORAZON, USER Y SHOP -->
                    <div class="list-inline">
                        <div class="list-inline-item">
                            <a href="pages/shop-wishlist.html" class="text-muted position-relative">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-heart">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    5
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                        </div>
                        <!-- icono de usuario -->
                        <div class="list-inline-item">

                            <a href="#!" class="text-muted" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </a>
                        </div>
                        <!-- icono del carrito de compras -->
                        <div class="list-inline-item">
                            <a class="text-muted position-relative " data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                href="#offcanvasExample" role="button" aria-controls="offcanvasRight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-shopping-bag">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <span id="cart_counter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    0
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light navbar-default pt-0 pb-0">
        <div class="container px-0 px-md-3">
            <div class="offcanvas offcanvas-start p-4 p-lg-0" id="navbar-default">

                <div class="d-flex justify-content-between align-items-center mb-2 d-block d-lg-none">
                    <div class="d-flex align-items-center">
                    <img src="<?php echo BASE_URL; ?>assets/imagenesMilogar/logomilo.jpg" alt="eCommerce HTML Template" style="width: 35px; height: 35px; margin-right: 8px;">
                    <span style="font-size: 1.2rem; font-weight: bold; color: #333;">MILOGAR</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="d-none d-lg-block">
                    <ul class="navbar-nav ">
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="<?php echo BASE_URL; ?>index.php" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Inicio
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php">Home 1</a></li>
                                <li><a class="dropdown-item" href="pages/index-2.html">Home 2</a></li>
                                <li><a class="dropdown-item" href="pages/index-3.html">Home 3</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">
                                Inicio
                            </a>

                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Views/shop-grid.php">
                                Tienda Virtual
                            </a>

                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Views/shop-checkout.php">
                                Verificar compra
                            </a>

                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="pages/signin.html">Sign in</a></li>
                                <li><a class="dropdown-item" href="pages/signup.html">Signup</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>Views/forgot-password.php">Forgot Password</a></li>
                                <li class="dropdown-submenu dropend">
                                    <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                        My Account
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>Views/account-orders.php">Orders</a></li>
                                        <li><a class="dropdown-item" href="pages/account-settings.html">Settings</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>Views/account-address.php">Address</a></li>
                                        <li><a class="dropdown-item" href="pages/account-payment-method.html">Payment Method</a>
                                        </li>
                                        <li><a class="dropdown-item" href="pages/account-notification.html">Notification</a></li>


                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="docs/index.html">
                                Docs
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Views/signup.php">
                                Suscribete
                            </a>

                        </li>
                        <?php
                        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Administrador') {
                            echo "<li class='nav-item'>
                            <a class='nav-link' href='" . BASE_URL . "Views/menu.php'>
                            Panel administrativo
                            </a>
                            </li>";
                        }

                        ?>
                    </ul>
                </div>
                <div class="d-block d-lg-none">
                    <ul class="navbar-nav ">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Inicio
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php BASE_URL; ?>index.php">Home 1</a></li>
                                <li><a class="dropdown-item" href="pages/index-2.html">Home 2</a></li>
                                <li><a class="dropdown-item" href="pages/index-3.html">Home 3</a></li>

                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Views/shop-grid.php">
                                Tienda Virtual
                            </a>

                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Views/shop-checkout.php">
                                Verificar compra
                            </a>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="pages/signin.html">Sign in</a></li>
                                <li><a class="dropdown-item" href="pages/signup.html">Signup</a></li>
                                <li><a class="dropdown-item" href="pages/forgot-password.html">Forgot Password</a></li>
                                <li class="dropdown-submenu dropend">
                                    <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                        My Account
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="pages/account-orders.html">Orders</a></li>
                                        <li><a class="dropdown-item" href="pages/account-settings.html">Settings</a></li>
                                        <li><a class="dropdown-item" href="pages/account-address.html">Address</a></li>
                                        <li><a class="dropdown-item" href="pages/account-payment-method.html">Payment Method</a>
                                        </li>
                                        <li><a class="dropdown-item" href="pages/account-notification.html">Notification</a></li>


                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="docs/index.html">
                                Docs
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
<?php
// Verificar si el usuario está logueado y si es un usuario registrado o invitado
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    // Si está logueado como invitado, mostrar "Invitado"
    if ($_SESSION['user_name'] === 'Invitado') {
        echo "<p>Bienvenido, Invitado!</p>";
    } else {
        // Si está logueado como usuario registrado, mostrar su nombre
        echo "<p>Bienvenido, " . htmlspecialchars($_SESSION['user_name']) . "!</p>";
    }
} else {
    // Si no está logueado, mostrar una opción para iniciar sesión
    echo "<p>Bienvenido, Invitado!</p>";
}

?>