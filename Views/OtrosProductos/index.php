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
    <title>MILOGAR | Otros Productos</title>
    <link rel="icon" href="../../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        #sugerenciasProducto {
    background-color: white; /* Cambiar el fondo a blanco */
    color: black; /* Asegurar que el texto sea oscuro y visible */
    max-height: 200px; /* Ajusta la altura máxima si es necesario */
    overflow-y: auto; /* Para que las sugerencias largas no se desborden */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Un pequeño efecto de sombra */
    z-index: 9999; /* Asegura que las sugerencias aparezcan por encima de otros elementos */
}

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
                            <h1 class="m-0">Otros productos</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <!-- Botón para crear nuevo producto -->
                            <button onclick="abrirModalCrearProducto()" class="btn btn-primary">Nuevo Producto</button>
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
                            <h5 class="modal-title" id="createProductModalLabel"> Nuevo Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario para asignar tallas -->
                            <form id="formAsignarTalla">
                                <!-- Buscar producto por nombre -->
                                <div class="form-group">
                                    <label for="buscarProducto">Buscar Producto</label>
                                    <input type="text" class="form-control" id="buscarProducto" placeholder="Escribe el nombre del producto...">
                                    <div id="sugerenciasProducto" class="list-group"></div>
                                </div>

                                <!-- ID del producto (oculto) -->
                                <input type="hidden" id="producto_id" name="producto_id">

                                <!-- Nombre del producto -->
                                <div class="form-group">
                                    <label for="nombreProducto">Producto Seleccionado</label>
                                    <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" readonly>
                                </div>

                                <!-- Descripción del producto -->
                                <div class="form-group">
                                    <label for="descripcionProducto">Descripción</label>
                                    <textarea class="form-control" id="descripcionProducto" name="descripcionProducto" rows="3" readonly></textarea>
                                </div>

                                <!-- Imagen del producto -->
                                <div class="form-group">
                                    <label>Imagen del Producto</label><br>
                                    <img id="imagenProducto" src="" alt="Imagen del producto" style="max-width: 200px; max-height: 200px; display: none;" class="img-thumbnail">
                                </div>

                                <!-- Selección de Talla -->
                                <div class="form-group">
                                    <label for="talla_id">Talla</label>
                                    <select id="talla_id" name="talla_id" class="form-control" required>
                                        <option value="">Seleccione una talla</option>
                                    </select>
                                </div>

                                <!-- Stock -->
                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>

                                <button type="submit" class="btn btn-success">Asignar Talla</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
<!-- Modal de Edición -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar el producto -->
                <form id="formEditProduct">
                    <!-- ID del producto (oculto) -->
                    <input type="hidden" id="edit_producto_id" name="producto_id">

                    <!-- Nombre del producto -->
                    <div class="form-group">
                        <label for="edit_nombreProducto">Producto</label>
                        <input type="text" class="form-control" id="edit_nombreProducto" name="nombreProducto" readonly>
                    </div>

                    <!-- Descripción del producto -->
                    <div class="form-group">
                        <label for="edit_descripcionProducto">Descripción</label>
                        <textarea class="form-control" id="edit_descripcionProducto" name="descripcionProducto" rows="3" readonly></textarea>
                    </div>

                    <!-- Imagen del producto -->
                    <div class="form-group">
                        <label>Imagen del Producto</label><br>
                        <img id="edit_imagenProducto" src="" alt="Imagen del producto" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                    </div>

                    <!-- Selección de Talla -->
                    <div class="form-group">
                        <label for="edit_talla_id">Talla</label>
                        <select id="edit_talla_id" name="talla_id" class="form-control" required>
                            <option value="">Seleccione una talla</option>
                        </select>
                    </div>

                    <!-- Stock -->
                    <div class="form-group">
                        <label for="edit_stock">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="stock" required>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

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
                    <ul class="pagination align-items-center" id="pagination" style="margin-bottom: 0;">
                        <!-- Botones de paginación se insertan aquí con JS -->
                    </ul>
                </nav>
            </div>

            <br>
            <div id="productosTallasResults"></div>
            <!-- Contenedor para la tabla -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <!-- Agrega esto dentro del contenedor AdminLTE (card, box, etc.) -->
                            <div class="table-responsive"> <!-- Para evitar el desbordamiento -->
                                <table id="productosTallasTable" class="table table-striped table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre del producto</th>
                                            <th>Descripción</th>
                                            <th>Talla</th>
                                            <th>Stock</th>
                                            <th>Imagen</th>
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

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Fullscreen -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- Logout Icon -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="logoutBtn" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i>
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
     <!-- JS de AdminLTE y dependencias desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById("logoutBtn").addEventListener("click", function(e) {
    e.preventDefault(); // Evita que el enlace se ejecute inmediatamente

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar sesión?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
            // Redirige al logout
            window.location.href = "../../Views/login/logout.php";
        }
    });
});
</script>

    <!-- Tu archivo de JavaScript -->
    <script src="../../assets/js/mensajesYalertas.js"></script>
    <script src="js/read.js"></script>
</body>

</html>