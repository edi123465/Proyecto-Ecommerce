<?php
require_once __DIR__ . "/../../Config/config.php";
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../menu.php" class="brand-link">
        <img src="../../assets/imagenesMilogar/logomilo.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">MILOGAR </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
            </div>
        </div>


        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">Administración</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/Usuarios/index" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/RolesUsuarios/index" class="nav-link">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Roles
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/Categorias/index" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Categorías
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/Subcategorias/index" class="nav-link">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>
                            Subcategorías
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/Productos/index" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Productos
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>index" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>
                            Tienda
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/PedidosTienda/index" class="nav-link">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>
                            Pedidos tienda Virtual
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>Views/Canjeables/index" class="nav-link">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>
                            Productos Canjeables
                        </p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


</div>