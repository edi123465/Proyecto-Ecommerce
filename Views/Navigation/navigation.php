<?php
require_once __DIR__ . "/../../Config/config.php";
$sesion_activa = false;

if (isset($_SESSION['user_role']) && 
   ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')) {
    $sesion_activa = true;
}
?>

<!-- navigation -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    body {
        padding-top: 160px; /* Ajusta según el alto total de tus navbars combinadas */
    }
    .dropdown-categorias {
  position: relative;
}

.dropdown-menu-categorias {
  display: none;
  position: absolute;
  background-color: white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  list-style: none;
  margin: 0;
  padding: 0;
  min-width: 200px;
  z-index: 1000;
  border-radius: 6px;
}

.dropdown-menu-categorias li a {
  display: block;
  padding: 10px 16px;
  text-decoration: none;
  color: #333;
}

.dropdown-menu-categorias li a:hover {
  background-color: #f8f9fa;
}

.dropdown-categorias:hover .dropdown-menu-categorias {
  display: block;
}
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu-sub {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    min-width: 220px;
    z-index: 1000;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    max-height: 250px;
    overflow-y: auto;
}

.dropdown-submenu:hover .dropdown-menu-sub {
    display: block;
}


    </style>
</head>

<body>
<div class="border-bottom pb-5 fixed-top bg-white shadow-sm" style="z-index: 1040;">
    <nav class="navbar navbar-light py-lg-5 pt-3 px-0 pb-0">
        <div class="container">
            <div class="row w-100 align-items-center g-3">
                <div class="col-xxl-2 col-lg-3">
                    <a class="navbar-brand d-none d-lg-flex align-items-center" href="index.php" style="text-decoration: none;">
                        <img src="<?php echo BASE_URL; ?>assets/imagenesMilogar/logomilo.jpg" alt="eCommerce HTML Template" style="width: 50px; height: 50px; margin-right: 10px;">
                        <span style="font-size: 1.5rem; font-weight: bold; color: #333;">MILOGAR</span>
                    </a>

                    <div class="d-flex justify-content-between w-100 d-lg-none">
                        <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>index.php" style="text-decoration: none;">
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
                                    <a class="text-muted position-relative" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                        role="button" aria-controls="offcanvasRight">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-shopping-bag">
                                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                            <line x1="3" y1="6" x2="21" y2="6"></line>
                                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                                        </svg>
                                        <span id="contador-carrito" class="cart-counter position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                            0
                                        </span>
                                    </a>
                                </div>

                                
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <div class="list-inline-item">
                                            <!-- Icono de Cerrar Sesión -->
                                            <a href="#" class="logout-link text-muted" data-url="<?php echo BASE_URL; ?>Views/login/logout.php">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-log-out">
                                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                    <polyline points="16 17 21 12 16 7"></polyline>
                                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    <?php endif; ?>
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
                <div class="col-xxl-6 col-lg-5"> <!-- Se eliminó d-none d-lg-block -->
                    <form action="/Milogar/shop-grid.php" class="search-header" method="GET" id="searchForm">
                        <input type="hidden" name="action" value="search">
                        <div class="input-group">
                            <input type="text" class="form-control border-end-0" id="busqueda" name="q"
                                placeholder="¿Buscas algo en específico?." required
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
                      
                        <div>
                            <div class="py-3">
                                <ul id="cart-items" class="list-group list-group-flush">
                                    <!--Aqui se llenaran los productos del catalogo-->
                                </ul>
                            </div>
                            <div class="d-grid">

                                <button id="checkout-button" class="btn btn-primary btn-lg d-flex justify-content-between align-items-center" type="submit"> Continuar
                                     <span id="cart-total" class="fw-bold">$0.00</span>
                                </button>
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

                        <!-- icono de usuario -->
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
                        <!-- Icono del carrito de compras -->
                      <div class="list-inline-item">
                            <a class="text-muted position-relative" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                role="button" aria-controls="offcanvasRight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-shopping-bag">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <span class="cart-counter position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    0
                                </span>
                            </a>
                        </div>

                     <?php if (isset($_SESSION['user_role']) && 
                            ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')): ?>
                        <div class="list-inline-item">
                            <!-- Icono de Cerrar Sesión -->
                            <a href="#" class="logout-link text-muted" data-url="<?php echo BASE_URL; ?>Views/login/logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>


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
                        <li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php">
        Inicio
    </a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Categorías
    </a>
    <ul class="dropdown-menu" id="menu-categorias-desktop">
        <!-- JS insertará aquí los elementos -->
    </ul>
</li>


<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/shop-checkout.php">
        Ver Carrito
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/sobreNosotros.php">
        Acerca de nosotros
    </a>
</li>

<?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="feather-icon icon-user me-2"></i>Cuenta
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/account-orders.php">
                    <i class="feather-icon icon-package me-2"></i>Pedidos
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="<?php echo BASE_URL; ?>/account-settings.php">
                    <i class="feather-icon icon-settings me-2"></i>Configuración
                </a>
            </li>
            <li>
                <a class="dropdown-item logout-link text-danger" href="#" data-url="<?php echo BASE_URL; ?>Views/login/logout.php">
                    <i class="feather-icon icon-log-out me-2"></i>Cerrar Sesión
                </a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/signup.php">
        <i class="feather-icon icon-user-plus me-2"></i>Suscríbete
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/terminos_condiciones.php">
        <i class="feather-icon icon-file-text me-2"></i>Términos y condiciones
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="feather-icon icon-log-in me-2"></i>Iniciar sesión
    </a>
</li>

                    <?php
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Administrador') {
    echo "<li class='nav-item'>
        <a class='nav-link' href='" . BASE_URL . "/menu.php'>
            <i class='feather-icon icon-settings me-2'></i>Panel administrativo
        </a>
    </li>";
}
?>

<?php
if (isset($_SESSION['user_role']) && 
    ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')) {

    echo '
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="feather-icon icon-gift me-2"></i>Oferta de puntos
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="' . BASE_URL . '/oferta_puntos.php">
                    <i class="feather-icon icon-star me-2"></i>Ganar puntos
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="' . BASE_URL . '/canjear.php">
                    <i class="feather-icon icon-refresh-ccw me-2"></i>Canjear puntos
                </a>
            </li>
        </ul>
    </li>';
}
?>


                </div>
                <div class="d-block d-lg-none">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index">
                            <i class="feather-icon icon-home me-2"></i>Inicio
                        </a>
                    </li>

                 <!-- Categorías con subcategorías -->
        <li class="nav-item dropdown position-relative">
<a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
  <i class="bi bi-folder me-1"></i> Categorías
</a>

          <ul class="dropdown-menu" id="menu-categorias-movil">
            <!-- Aquí se insertan dinámicamente -->
          </ul>
        </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>shop-checkout">
                            <i class="feather-icon icon-shopping-cart me-2"></i>Ver Carrito
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>sobreNosotros">
                            <i class="feather-icon icon-users me-2"></i>Acerca de nosotros
                        </a>
                    </li>

                <?php if (isset($_SESSION['user_role']) && 
                    ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="feather-icon icon-user me-2"></i>Cuenta
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>account-orders">
                                    <i class="feather-icon icon-list me-2"></i>Pedidos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>account-settings">
                                    <i class="feather-icon icon-settings me-2"></i>Configuración
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item logout-link text-danger" href="#" 
                                    data-url="<?php echo BASE_URL; ?>Views/login/logout.php">
                                    <i class="feather-icon icon-log-out me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>signup">
        <i class="feather-icon icon-user-plus me-2"></i>Suscríbete
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>terminos_condiciones.php">
        <i class="feather-icon icon-file-text me-2"></i>Términos y condiciones
    </a>
</li>

                        <li class="nav-item">
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="feather-icon icon-log-in me-2"></i>Iniciar sesión
    </a>
</li>

<?php
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Administrador') {
    echo "<li class='nav-item'>
        <a class='nav-link' href='" . BASE_URL . "menu.php'>
            <i class='feather-icon icon-bar-chart me-2'></i>Panel administrativo
        </a>
    </li>";
}
?>

<?php
if (isset($_SESSION['user_role']) &&
    ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')) {

    echo '
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="feather-icon icon-gift me-2"></i>Oferta de puntos
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="' . BASE_URL . 'oferta_puntos.php">
                    <i class="feather-icon icon-trending-up me-2"></i>Ganar puntos
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="' . BASE_URL . 'canjear.php">
                    <i class="feather-icon icon-shopping-bag me-2"></i>Canjear puntos
                </a>
            </li>
        </ul>
    </li>';
}
?>

                    
                    
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fs-3" id="userModalLabel">Inicia sesión con tu cuenta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="loginForm">
          <input type="hidden" name="form_type" value="login">

          <div class="mb-3">
            <label for="loginEmail" class="form-label">Nombre de usuario</label>
            <input type="text" name="txt_nombreUsuario" class="form-control" placeholder="Nombre de usuario" required autocomplete="current-password">
          </div>

          <div class="mb-2 position-relative">
            <label for="loginPassword" class="form-label">Contraseña</label>
            <input type="password" name="txt_password" id="loginPassword" class="form-control" placeholder="Ingrese su contraseña" required autocomplete="current-password">
            <!-- Icono de ojito -->
            <span class="toggle-password" style="position: absolute; top: 38px; right: 15px; cursor: pointer;">
              <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-eye">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </span>
          </div>

          <!-- Enlace de recuperación de contraseña -->
          <div class="text-end mb-3">
            <a href="<?php echo BASE_URL; ?>forgot-password" class="text-decoration-none small text-primary">¿Olvidaste tu contraseña?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3">Iniciar sesión</button>
              <div class="col-12 d-grid mb-4">
        <div id="g_id_onload"
             data-client_id="TU_CLIENT_ID_DE_GOOGLE"
             data-callback="handleCredentialResponse"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-size="large"
             data-theme="outline"
             data-text="sign_in_with"
             data-shape="rectangular"
             data-logo_alignment="left">
        </div>
    </div>
        </form>
      </div>

      <div class="modal-footer border-0 justify-content-center flex-column flex-sm-row">
        <span class="me-2">¿No tienes una cuenta?</span> 
        <a href="<?php echo BASE_URL; ?>signup" class="text-decoration-none text-primary">Regístrate gratis.</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const logoutLinks = document.querySelectorAll('.logout-link');

    logoutLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Cerrar sesión?',
                text: 'Se cerrará tu sesión actual. ¿Deseas continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eliminar carrito del localStorage si existe
                    localStorage.removeItem('carrito');

                    // Redireccionar a la URL de logout
                    const logoutUrl = link.dataset.url;
                    window.location.href = logoutUrl;
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('loginPassword');
    const eyeIcon = document.getElementById('eyeIcon');

    eyeIcon.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';

        // Cambia el ícono visualmente si lo deseas (opcional)
        eyeIcon.innerHTML = isPassword
            ? '<path d="M17.94 17.94A10.06 10.06 0 0 1 12 20C4 20 1 12 1 12a19.77 19.77 0 0 1 5.65-6.94M10.58 10.58A3 3 0 0 0 13.42 13.42M3 3l18 18"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    });
});

</script>
<script>
fetch('http://localhost:8080/Milogar/Controllers/TiendaController.php?action=getCategoriasConSubcategorias')
    .then(response => response.json())
    .then(data => {
        const menuDesktop = document.getElementById('menu-categorias-desktop');
        const menuMovil = document.getElementById('menu-categorias-movil');

        data.forEach(categoria => {
            // Construye el item HTML, por ejemplo usando una función
            const itemHTML = crearItemCategoria(categoria);

            menuDesktop.insertAdjacentHTML('beforeend', itemHTML);
            menuMovil.insertAdjacentHTML('beforeend', itemHTML);
        });
    })
    .catch(error => console.error('Error al cargar categorías:', error));

function crearItemCategoria(categoria) {
    let subcategoriasHTML = '';
    if (Array.isArray(categoria.subcategorias)) {
        categoria.subcategorias.forEach(sub => {
            subcategoriasHTML += `
                <li>
                    <a class="dropdown-item" href="/Milogar/shop-grid.php?subcategoria_id=${sub.id}">
                        ${sub.nombrSubcategoria}
                    </a>
                </li>
            `;
        });
    }

    return `
        <li class="dropdown-submenu position-relative">
            <a class="dropdown-item" href="/Milogar/shop-grid.php?categoria_id=${categoria.id}">
                ${categoria.nombreCategoria}
            </a>
            <ul class="dropdown-menu dropdown-menu-sub">
                ${subcategoriasHTML}
            </ul>
        </li>
    `;
}


</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if ($sesion_activa): ?>
            const form = document.getElementById('loginForm');
            const inputs = form.querySelectorAll('input');
            const button = form.querySelector('button[type="submit"]');

            inputs.forEach(input => input.disabled = true);
            button.disabled = true;

            // Opcional: muestra un mensaje al usuario
            const mensaje = document.createElement('div');
            mensaje.className = 'alert alert-warning mt-3';
            mensaje.textContent = 'Ya hay una sesión activa. Cierra sesión antes de iniciar con otra cuenta.';
            form.appendChild(mensaje);
        <?php endif; ?>
    });
</script>

</body>

</html>
