<?php
session_start(); // Iniciar la sesión para acceder a la información del usuario
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Dashboard</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
<!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="css/styles.css">

</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <?php
    require_once "../../Config/config.php";
    require_once "../Navigation/navigationAdmin.php";
    ?>
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

    <div class="wrapper">
        <div class="content-wrapper">

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión de Usuarios</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <!-- Botón para crear nuevo producto -->
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">Crear un nuevo usuario</a>
                            <!-- Botón para regresar al menú -->
                            <a href="../../menu" class="btn btn-secondary">Regresar al Menú</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal para crear usuario -->
            <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserLabel">Crear Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="crearUsuarioForm">
                                <input type="hidden" id="crearUsuarioID">
                                <div class="mb-3">
                                    <label for="crearNombreUsuario" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="crearNombreUsuario" required>
                                </div>
                                <div class="mb-3">
                                    <label for="crearEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="crearEmail" required>
                                </div>
                                <div class="form-group">
                                    <label for="crearRol">Rol</label>
                                    <select class="form-control select2bs4" id="crearRol" style="width: 100%;">
                                        <!-- Los roles se llenarán dinámicamente -->
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="crearIsActive">Estado</label>
                                    <select class="form-control select2bs4" id="crearIsActive" style="width: 100%;">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="guardarNuevoUsuario(event)">Crear Usuario</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar usuario -->
            <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarUsuarioLabel">Editar Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editarUsuarioForm">
                                <input type="hidden" id="editarUsuarioID">
                                <div class="mb-3">
                                    <label for="editarNombreUsuario" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="editarNombreUsuario" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="editarEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editarEmail" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="editarRol">Rol</label>
                                    <select class="form-control select2bs4" id="editarRol" style="width: 100%;">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editarIsActive">Estado</label>
                                    <select class="form-control select2bs4" id="editarIsActive" style="width: 100%;">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="actualizarUsuario()">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
            </div>

<!-- Contenedor del campo de búsqueda -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Buscar</h3>
    <div class="card-tools">
      <input type="text" id="busqueda" class="form-control form-control-sm" placeholder="Buscar en la tabla...">
    </div>
  </div>
</div>
            <div id="paginacion" class="mt-3"></div>
            <div class="content">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Puntos disponibles</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usuarioTableBody">
                                <!-- Los usuarios serán cargados aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="jsGrid1"></div>
            </div>
        </div>
    </div>


    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/read.js"></script>
    <script src="js/create.js"></script>
    <script src="js/update.js"></script>
    <script>
        document.getElementById("regresarBtn").addEventListener("click", function() {
            // Redirigir directamente a la página del menú sin confirmación
            window.location.href = "../../menu"; // Redirige en la misma pestaña
        });

        
    </script>
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

</html><?php
session_start(); // Iniciar la sesión para acceder a la información del usuario
// Definimos un array con las opciones del menú
if (!isset($_SESSION['user_id'])) {
    header("Location: /Milogar/index.php");
    exit();
}
?>

