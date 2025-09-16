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
    <title>MILOGAR | Productos</title>
    <link rel="icon" href="../../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createProductModalLabel">Agregar Nuevo Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createProductForm" method="POST" enctype="multipart/form-data">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Nombre y Descripción -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombreProducto">Nombre del Producto</label>
                                                <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descripcionProducto">Descripción</label>
                                                <textarea class="form-control" id="descripcionProducto" name="descripcionProducto" rows="1" required></textarea>
                                            </div>
                                        </div>

                                        <!-- Categoría y Subcategoría -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="categoria">Categoría</label>
                                                <select id="categoria" name="categoria" class="form-control" required>
                                                    <option value="" disabled selected>Selecciona una categoría</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subcategoria">Subcategoría</label>
                                                <select id="subcategoria" name="subcategoria" class="form-control" required>
                                                    <option value="" disabled selected>Selecciona una subcategoría</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Precios y Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio">Precio Compra</label>
                                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio_1">Precio 1</label>
                                                <input type="number" class="form-control" id="precio_1" name="precio_1" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio_2">Precio 2</label>
                                                <input type="number" class="form-control" id="precio_2" name="precio_2" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio_3">Precio 3</label>
                                                <input type="number" class="form-control" id="precio_3" name="precio_3" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio_4">Precio 4</label>
                                                <input type="number" class="form-control" id="precio_4" name="precio_4" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="stock">Stock</label>
                                                <input type="number" class="form-control" id="stock" name="stock" required>
                                            </div>
                                        </div>

                                        <!-- Código de barras e Imagen -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="codigo_barras">Código de barras</label>
                                                <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="imagen">Imagen del Producto</label>
                                                <input type="file" id="imagen" name="imagen" />
                                            </div>
                                        </div>

                                        <!-- Estado, Promoción y Descuento -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isActive">Estado</label>
                                                <select id="isActive" name="isActive" class="form-control" required>
                                                    <option value="1">Activo</option>
                                                    <option value="0">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="is_promocion">Promoción</label>
                                                <select id="is_promocion" name="is_promocion" class="form-control" required>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="descuento">Descuento (%)</label>
                                                <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" min="0" max="100">
                                            </div>
                                        </div>

                                        <!-- Puntos y Talla -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="total_puntos">Puntos otorgados</label>
                                                <input type="number" class="form-control" id="total_puntos" name="total_puntos" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cantidad_minima">Cantidad mínima</label>
                                                <input type="number" class="form-control" id="cantidad_minima" name="cantidad_minima" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="is_talla">¿Tiene tallas?</label>
                                                <select id="is_talla" name="is_talla" class="form-control" required>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal de Edición de Producto -->
            <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel">
                <div class="modal-dialog modal-lg modal-dialog-scrollable"> <!-- modal-lg + scroll interno -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form id="formEditarProducto" method="POST" enctype="multipart/form-data">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Nombre y Descripción -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombreProducto">Nombre del Producto</label>
                                                <input type="text" class="form-control" id="nombre_Producto" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descripcionProducto">Descripción</label>
                                                <textarea class="form-control" id="descripcion_Producto" rows="1" required></textarea>
                                            </div>
                                        </div>

                                        <!-- Categoría y Subcategoría -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="categoria">Categoría</label>
                                                <select id="categoriaa" class="form-control" required>
                                                    <option value="" disabled selected>Selecciona una categoría</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subcategoria">Subcategoría</label>
                                                <select id="subcategoriaa" class="form-control" required>
                                                    <option value="" disabled selected>Selecciona una subcategoría</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Precio y Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precioCompra">Precio Compra</label>
                                                <input type="number" class="form-control" id="precioCompra" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio1">Precio 1</label>
                                                <input type="number" class="form-control" id="precio1" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio2">Precio 2</label>
                                                <input type="number" class="form-control" id="precio2" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio3">Precio 3</label>
                                                <input type="number" class="form-control" id="precio3" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio4">Precio 4</label>
                                                <input type="number" class="form-control" id="precio4" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="stocks">Stock</label>
                                                <input type="number" class="form-control" id="stocks" required>
                                            </div>
                                        </div>

                                        <!-- Código de barras e Imagen -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="codigoBarras">Código de barras</label>
                                                <input type="text" class="form-control" id="codigoBarras" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="imagen_Producto">Imagen</label>
                                                <input type="file" id="imagen_Producto" name="imagen" />
                                                <br>
                                                <img id="imagenPreview" src="https://milogar.wuaze.com/assets/imagenesMilogar/productos/default.png" alt="Imagen" width="100" />
                                            </div>
                                        </div>

                                        <!-- Promoción, Estado y Descuento -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isPromocion">Promoción</label>
                                                <select id="isPromocion" name="isPromocion" class="form-control" required>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <select id="estado" name="isActive" class="form-control" required>
                                                    <option value="1">Activo</option>
                                                    <option value="0">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="desc">Descuento (%)</label>
                                                <input type="number" class="form-control" id="desc" step="0.01" min="0" max="100">
                                            </div>
                                        </div>

                                        <!-- Puntos y Talla -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cantidadMinimaPuntos">Cantidad mínima para puntos</label>
                                                <input type="number" class="form-control" id="cantidadMinimaPuntos" name="cantidad_minima_para_puntos" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="puntosOtorgados">Puntos otorgados</label>
                                                <input type="number" class="form-control" id="puntosOtorgados" name="puntos_otorgados" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="talla">¿Tiene Talla?</label>
                                                <select class="form-control" id="talla" name="talla" required>
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> <!-- row -->
                                </div> <!-- container-fluid -->

                                <input type="hidden" id="productoId"> <!-- ID del producto -->
                                <div class="mt-3 d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtro de búsqueda y Paginación alineados -->
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <!-- Filtro de búsqueda -->
                <div class="form-group mb-0 mr-3" style="flex-grow: 1; margin-left: 30px; max-width: 100%; min-width: 200px;">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar productos..." style="max-width: 100%; width: 200px;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="searchButton" type="button">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <nav aria-label="Paginación de productos" class="pagination-container w-100">
                    <ul class="pagination justify-content-center" id="paginationProductos" style="margin-bottom: 0; margin-top: 10px;"> <!-- margin-top añadido aquí -->
                        <!-- Botones de paginación se insertan aquí con JS -->
                    </ul>
                </nav>
            </div>
            <br>
            <div id="productResults"></div>


            <div class="content">
                <div class="container-fluid">
                    <div class="content">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <nav aria-label="Page navigation">

                                        </nav>
<div class="mt-3 d-flex justify-content-start gap-2">
    <button type="submit" class="btn btn-danger">Importar desde excel</button>&nbsp;&nbsp;&nbsp;
    <button type="submit" class="btn btn-success">Exportar a excel</button>
</div><br>
                                        <!-- Para evitar el desbordamiento -->
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
                                                    <th>Cantidad Minima para ganar puntos</th>
                                                    <th>puntos otorgados al comprar</th>
                                                    <th>Talla</th>

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

    <!-- JS de AdminLTE y dependencias desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- SweetAlert JS -->
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
    </script> <!-- Tu archivo de JavaScript -->
    <script src="../../assets/js/mensajesYalertas.js"></script>
    <script src="js/read.js"></script>

</body>

</html>