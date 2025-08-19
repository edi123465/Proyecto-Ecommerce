<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Milogar/index.php");
    exit();
}

// Crear una instancia de la conexión
//$db = new Database1();
//$connection = $db->getConnection();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Categorías</title>
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/imagenesMilogar/logomilo.jpg">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- CSS de AdminLTE desde CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión Categorías</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <!-- Enlace para crear categorías -->
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">Crear Categoría</a>

                    <!-- Enlace para regresar a la consulta -->
                    <a href="../../menu" class="btn btn-secondary">Regresar al menú</a>

                    <!-- Modal para la creación de una categoría -->
                    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createCategoryModalLabel">Crear Nueva Categoría</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para crear categoría -->
                                    <form id="createCategoryForm" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="categoryName">Nombre de Categoría</label>
                                            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="categoryDescription">Descripción</label>
                                            <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="categoryImage">Imagen</label>
                                            <input type="file" class="form-control" id="imagen" name="imagen" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="categoryStatus">Estado</label>
                                            <select class="form-control" id="categoryStatus" name="categoryStatus" required>
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Crear Categoría</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal para editar categoría -->
                    <div id="modalEditarCategoria" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalEditarCategoriaLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarCategoriaLabel">Editar Categoría</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formEditarCategoria" data-id="">
                                        <div class="form-group">
                                            <label for="nombreCategoria">Nombre</label>
                                            <input type="text" class="form-control" id="nombreCategoria">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcionCategoria">Descripción</label>
                                            <input type="text" class="form-control" id="descripcionCategoria">
                                        </div>
                                        <div class="form-group">
                                            <label for="isActive">Estado</label>
                                            <select class="form-control" id="isActive">
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="imagenCategoria">Imagen</label>
                                            <input type="file" class="form-control" id="imagenCategoria">
                                        </div>
                                        <div class="form-group">
                                            <label>Vista previa de imagen</label><br>
                                            <img id="imagenCategoriaPreview" src="" style="width: 100px; height: 100px;">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="guardarCambios()">Guardar cambios</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                </div>
                            </div>
                        </div>
                    </div>

 <!-- Filtro de búsqueda y Paginación alineados -->
                    <br><br>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <!-- Filtro de búsqueda -->
                        <div class="form-group mb-0 mr-3" style="flex-grow: 1; margin-left: 30px; max-width: 100%; min-width: 200px;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar categoría..." style="max-width: 300px;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="searchButton" type="button">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="pagination" class="d-flex justify-content-center mt-3">
                        <!-- Los botones de paginación serán generados aquí -->
                    </div>
                    <div class="table-responsive">
                        <br>
                        <table class="table table-bordered table-striped" id="tablaCategorias">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de categoría</th>
                                    <th>Descripción</th>
                                    <th>Imagen</th>
                                    <th>Estado</th>
                                    <th>Fecha de creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCategoriasBody">

                            </tbody>
                        </table>
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
        <!-- IMPORTAMOS EL ARCHIVO QUE TIENE LA BARRA DE NAVEGACION -->

        <?php require_once "../Navigation/navigationAdmin.php" ?>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4.5.2 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery y Bootstrap CSS -->


    <script src="js/read.js"></script>
    <script src="js/create.js"></script>
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
</body>

</html>