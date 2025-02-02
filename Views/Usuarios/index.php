<?php
session_start(); // Iniciar la sesión para acceder a la información del usuario
// Definimos un array con las opciones del menú
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
    <link rel="stylesheet" href="../../Recursos/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../Recursos/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../Recursos/dist/css/adminlte.min.css">
    <!--Uso de DataTables-->
    <link rel="stylesheet" href="../../Recursos/plugins/">

    <link rel="stylesheet" href="../../Recursos/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../Recursos/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../Recursos/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../Recursos/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <?php
    require_once "../../Config/config.php";
    require_once "../Navigation/navigationAdmin.php";
    ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gestión de Usuarios</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Botones -->
        <!-- Botón para abrir el modal -->
        <button id="openModalBtn">Crear un nuevo usuario</button>
        <button id="regresarBtn">Regresar al menú</button>

        <!-- Modal para crear un usuario nuevo -->
        <div id="createUserModal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close">&times;</span>
                <h2>Nuevo Usuario</h2>

                <form id="createUserForm" method="POST" enctype="multipart/form-data">
                    <!-- Campo para el nombre de usuario -->
                    <label for="NombreUsuario">Nombre de Usuario:</label>
                    <input type="text" id="NombreUsuario" name="NombreUsuario" required>

                    <!-- Campo para el correo electrónico -->
                    <label for="Email">Correo Electrónico:</label>
                    <input type="email" id="Email" name="Email" required>
                    <!-- Campo para el rol del usuario -->
                    <label for="RolID">Rol:</label>
                    <select id="RolID" name="RolID" required>
                        <option value="1">Administrador</option>
                        <option value="20">Cliente</option>
                    </select>

                    <!-- Campo para activar o desactivar al usuario -->
                    <label for="IsActive">Activo:</label>
                    <input type="checkbox" id="IsActive" name="IsActive" checked>

                    <!-- Campo para subir una imagen -->
                    <label for="Imagen">Imagen de Perfil:</label>
                    <input type="file" id="Imagen" name="Imagen" accept="image/*">

                    <!-- Enviar el formulario -->
                    <button type="submit">Crear Usuario</button>
                </form>
            </div>
        </div>
        <!-- Modal para editar usuario -->
        <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarUsuarioLabel">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarUsuarioForm">
                            <input type="hidden" id="editarUsuarioID">
                            <div class="mb-3">
                                <label for="editarNombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="editarNombreUsuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="editarEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editarEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="editarRol" class="form-label">Rol</label>
                                <select class="form-select" id="editarRol">

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editarIsActive" class="form-label">Activo</label>
                                <select class="form-select" id="editarIsActive">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarCambiosUsuario()">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>

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
                                <th>Activo</th>
                                <th>Fecha Creación</th>
                                <th>Imagen</th>
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
    <script src="js/read.js"></script>
    <script src="js/create.js"></script>
    <script src="js/update.js"></script>


</body>

</html>