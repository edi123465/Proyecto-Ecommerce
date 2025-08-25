<?php
session_start(); // Iniciar sesión para acceder al mensaje
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Controllers/SubcategoriaController.php'; // Cargar el controlador de subcategorías
require_once __DIR__ . '/../../Models/SubcategoriaModel.php';       // Cargar el modelo de subcategorías
// Crear una instancia de la conexión
$db = new Database1();
$connection = $db->getConnection();
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
    <title>MILOGAR | Gestión Subcategorías</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión Subcategorías de Productos</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Crear Subcategoría -->
            <div class="modal fade" id="createSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createSubCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createSubCategoryModalLabel">Crear Nueva Subcategoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario para agregar subcategoría -->
                            <form id="createSubCategoryForm">
                                <div class="form-group">
                                    <label for="subCategoryName">Nombre de la Subcategoría</label>
                                    <input type="text" class="form-control" id="subCategoryName" name="subCategoryName" required>
                                </div>
                                <div class="form-group">
                                    <label for="subCategoryDescription">Descripción</label>
                                    <textarea class="form-control" id="subCategoryDescription" name="subCategoryDescription" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="categoryId">Categoría</label>
                                    <select class="form-control" id="categoryId" name="categoryId" required>
                                        <!-- Las categorías se llenarán desde la base de datos -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="isActive">Estado</label>
                                    <select class="form-control" id="isActive" name="isActive">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="submitCreateSubCategory">Crear Subcategoría</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal para Editar Subcategoría -->
            <div class="modal fade" id="editSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editSubCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSubCategoryModalLabel">Editar Subcategoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario para editar subcategoría -->
                            <form id="editSubCategoryForm">
                                <input type="hidden" id="editSubCategoryId" name="subCategoryId"> <!-- ID oculto de la subcategoría -->

                                <div class="form-group">
                                    <label for="editSubCategoryName">Nombre de la Subcategoría</label>
                                    <input type="text" class="form-control" id="editSubCategoryName" name="subCategoryName" required>
                                </div>
                                <div class="form-group">
                                    <label for="editSubCategoryDescription">Descripción</label>
                                    <textarea class="form-control" id="editSubCategoryDescription" name="subCategoryDescription" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="editCategoryId">Categoría</label>
                                    <select class="form-control" id="editCategoryId" name="categoryId" required>
                                        <!-- Las categorías se llenarán desde la base de datos -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editIsActive">Estado</label>
                                    <select class="form-control" id="editIsActive" name="isActive">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="submitEditSubCategory">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="content">
                <div class="container-fluid">
                    <!-- Enlace para crear una subcategorías -->
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createSubCategoryModal">Crear Subcategoría</a>

                    <!-- Enlace para regresar a la consulta -->
                    <a href="../../menu" class="btn btn-secondary">Regresar al menú</a>
                    <br><br>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Input de búsqueda alineado a la izquierda -->
                        <input type="text" id="searchInput" placeholder="Buscar subcategoría..." class="form-control w-25">
                    </div>

                    <!-- Contenedor de los botones de paginación centrados -->
                    <div class="d-flex justify-content-center">
                        <div id="paginacion" class="btn-group"></div>
                    </div>
                    <br><div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Categoría ID</th>
                                    <th>Estado</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th> <!-- Nueva columna para las acciones -->
                                </tr>

                            </thead>
                            <tbody>

                            </tbody>


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
        <!-- /.navbar -->
        <?php require_once "../Navigation/navigationAdmin.php"; ?>

        <!-- Content Wrapper. Contains page content -->


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
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

    <script src="js/read.js"></script>
</body>

</html>