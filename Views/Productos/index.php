<?php
session_start(); // Iniciar la sesión para mostrar mensajes
// Mostrar mensaje si existe
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Eliminar el mensaje después de mostrarlo
}

require_once __DIR__ . '/../../Controllers/ProductoController.php'; // Cargar el controlador
require_once __DIR__ . '/../../Config/db.php'; // Conectar con la base de datos
// Crear una instancia de la conexión
$db = new Database1();
$connection = $db->getConnection();

// Instanciar el controlador
$controller = new ProductoController($connection);
//$productos = $controller->getAllProductos();

if (!isset($_SESSION['user_id'])) {
    header("Location: /Milogar/index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Dashboard</title>
    <link rel="icon" href="../../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../Recursos/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../Recursos/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../Recursos/dist/css/adminlte.min.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Aseguramos que el contenedor de la paginación ocupe el 100% del ancho */
        .pagination-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Aseguramos que los botones de la paginación no se desborden */
        .pagination {
            display: flex;
            justify-content: center;
        }

        /* Aseguramos que se ajuste bien en pantallas pequeñas */
        @media (max-width: 576px) {
            .pagination-container {
                justify-content: center;
                /* Asegura que se centre en pantallas pequeñas */
            }
        }
    </style>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión de Productos</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <!-- Botón para crear nuevo producto -->
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">Crear Nuevo Producto</a>
                            <!-- Botón para regresar al menú -->
                            <a href="../../menu" class="btn btn-secondary">Regresar al Menú</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createProductModalLabel">Agregar Nuevo Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createProductForm" method="POST" enctype="multipart/form-data">
                                <!-- Nombre del Producto -->
                                <div class="form-group">
                                    <label for="nombreProducto">Nombre del Producto</label>
                                    <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                                </div>

                                <!-- Descripción del Producto -->
                                <div class="form-group">
                                    <label for="descripcionProducto">Descripción</label>
                                    <textarea class="form-control" id="descripcionProducto" name="descripcionProducto" rows="3" required></textarea>
                                </div>

                                <!-- Categoría -->
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select id="categoria" name="categoria" class="form-control" required>
                                        <option value="" disabled selected>Selecciona una categoría</option>
                                        <!-- Aquí se llenarán las categorías desde la base de datos -->
                                    </select>
                                </div>

                                <!-- Subcategoría -->
                                <div class="form-group">
                                    <label for="subcategoria">Subcategoría</label>
                                    <select id="subcategoria" name="subcategoria" class="form-control" required>
                                        <option value="" disabled selected>Selecciona una subcategoría</option>
                                        <!-- Aquí se llenarán las subcategorías desde la base de datos -->
                                    </select>
                                </div>

                                <!-- Precio -->
                                <div class="form-group">
                                    <label for="precio">Precio de compra</label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                                </div>

                                <!-- Precio 1 -->
                                <div class="form-group">
                                    <label for="precio_1">Precio 1</label>
                                    <input type="number" class="form-control" id="precio_1" name="precio_1" step="0.01" required>
                                </div>

                                <!-- Precio 2 -->
                                <div class="form-group">
                                    <label for="precio_2">Precio 2</label>
                                    <input type="number" class="form-control" id="precio_2" name="precio_2" step="0.01" required>
                                </div>

                                <!-- Precio 3 -->
                                <div class="form-group">
                                    <label for="precio_3">Precio 3</label>
                                    <input type="number" class="form-control" id="precio_3" name="precio_3" step="0.01" required>
                                </div>

                                <!-- Precio 4 -->
                                <div class="form-group">
                                    <label for="precio_4">Precio 4</label>
                                    <input type="number" class="form-control" id="precio_4" name="precio_4" step="0.01" required>
                                </div>

                                <!-- Stock -->
                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>

                                <!-- Código de barras -->
                                <div class="form-group">
                                    <label for="codigo_barras">Código de barras</label>
                                    <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" required>
                                </div>

                                <!-- Imagen -->
                                <div class="form-group">
                                    <label for="imagen">Imagen del Producto</label>
                                    <input type="file" id="imagen" name="imagen" />
                                </div>

                                <!-- Estado -->
                                <div class="form-group">
                                    <label for="isActive">Estado</label>
                                    <select id="isActive" name="isActive" class="form-control" required>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>

                                <!-- Promoción -->
                                <div class="form-group">
                                    <label for="is_promocion">¿Está en Promoción?</label>
                                    <select id="is_promocion" name="is_promocion" class="form-control" required>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <!-- Descuento -->
                                <div class="form-group">
                                    <label for="descuento">Descuento (%)</label>
                                    <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" max="100">
                                </div>

                                <!-- Total Puntos Otorgados -->
                                <div class="form-group">
                                    <label for="total_puntos">Total Puntos Otorgados</label>
                                    <input type="number" class="form-control" id="total_puntos" name="total_puntos" required>
                                </div>

                                <!-- Cantidad Mínima -->
                                <div class="form-group">
                                    <label for="cantidad_minima">Cantidad Mínima</label>
                                    <input type="number" class="form-control" id="cantidad_minima" name="cantidad_minima" required>
                                </div>

                                <!-- Botón para enviar el formulario -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Edición de Producto -->
            <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
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
                                    <select id="estado" class="form-control" required>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                                <!-- Descuento -->
                                <div class="form-group">
                                    <label for="desc">Descuento (%)</label>
                                    <input type="number" class="form-control" id="desc" step="0.01" min="0" max="100">
                                </div>
                                
                                <!-- Cantidad Mínima para otorgar puntos -->
                                <div class="form-group">
                                    <label for="cantidadMinimaPuntos">Cantidad Mínima</label>
                                    <input type="number" class="form-control" id="cantidadMinimaPuntos" name="cantidad_minima_para_puntos" required>
                                </div>
                                
                                <!-- Total Puntos Otorgados -->
                                <div class="form-group">
                                    <label for="puntosOtorgados">Total Puntos Otorgados</label>
                                    <input type="number" class="form-control" id="puntosOtorgados" name="puntos_otorgados" required>
                                </div>

                                <!-- Otros campos que quieras editar -->
                                <input type="hidden" id="productoId"> <!-- Este es el ID del producto que se va a editar -->
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filtro de búsqueda y Paginación alineados -->
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <!-- Filtro de búsqueda -->
                <div class="form-group mb-0 mr-3" style="flex-grow: 1; margin-left: 30px; max-width: 100%; min-width: 200px;">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar productos..." style="max-width: 300px;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="searchButton" type="button">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <nav aria-label="Paginación de productos" class="pagination-container">
                    <ul class="pagination align-items-center" id="paginationProductos" style="margin-bottom: 0;">
                        <!-- Botones de paginación se insertan aquí con JS -->
                    </ul>
                </nav>
            </div>

            <br>
            <div id="productResults"></div>
            <!-- Contenedor para la tabla -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive"> <!-- Para evitar el desbordamiento -->
                                <table id="productosTable" class="table table-striped table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Precio de Compra</th>
                                            <th>Precio 1</th>
                                            <th>Precio 2</th>
                                            <th>Precio 3</th>
                                            <th>Precio 4</th>
                                            <th>Categoria</th>
                                            <th>Subcategoría</th>
                                            <th>Código de barras</th>
                                            <th>Imagen</th>
                                            <th>Estado</th>
                                            <th>Fecha de creación</th>
                                            <th>Promoción</th>
                                            <th>Descuento</th>
                                            <th>Stock</th>
                                            <th>Cantidad minima para puntos</th>
                                            <th>Puntos otorgados al comprar</th>
                                            <th>Puntos requeridos para canje</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se insertarán los datos dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>




        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../../menu.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../login/logout.php" class="nav-link">Cerrar Sesión</a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <?php
        require_once "../Navigation/navigationAdmin.php";
        ?>
        <!-- Content Wrapper. Contains page content -->


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="../../Recursos/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../Recursos/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../Recursos/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../Recursos/dist/js/adminlte.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="../../Recursos/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="../../Recursos/plugins/raphael/raphael.min.js"></script>
    <script src="../../Recursos/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="../../Recursos/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <!-- ChartJS -->
    <script src="../../Recursos/plugins/chart.js/Chart.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="../../Recursos/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../Recursos/dist/js/pages/dashboard2.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tu archivo de JavaScript -->
    <script src="../../assets/js/mensajesYalertas.js"></script>
    <script src="js/read.js"></script>
</body>

</html>