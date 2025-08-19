<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Models/RolModel.php';
require_once __DIR__ . '/../../Models/PedidosModel.php';
require_once __DIR__ . '/../../Controllers/PedidosController.php';     // Cargar el modelo de roles
// Crear una instancia de la conexión
$db = new Database1();
$connection = $db->getConnection();
// Instanciar el controlador
if (!isset($_SESSION['user_id'])) {
    header("Location: /index");
    exit();
}
require_once __DIR__ . '/../../Config/db.php'; // Conectar con la base de datos


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Gestión Pedidos</title>
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="/../../assets/imagenesMilogar/logomilo.jpg">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <style>
        .pagination .page-link {
            color: black;
            /* Color del texto */
            background-color: white;
            /* Fondo blanco para los botones */
            border-color: #dee2e6;
            /* Borde gris claro */
        }

        .pagination .page-link:hover {
            background-color: #f0f0f0;
            /* Fondo ligeramente gris en hover */
            border-color: #dee2e6;
            /* Borde gris claro */
            color: black;
            /* Mantener el texto negro en hover */
        }

        .pagination .page-item.disabled .page-link {
            background-color: #e9ecef;
            /* Fondo gris claro para botones deshabilitados */
            color: #6c757d;
            /* Texto gris */
        }

        .pagination .page-item.active .page-link {
            background-color: black;
            /* Fondo negro para el botón activo */
            border-color: black;
            /* Borde negro para el botón activo */
            color: white;
            /* Texto blanco para el botón activo */
        }

   
    </style>
    <script>
    
    </script>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">


        <!-- Modal -->
        <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetallesLabel">Detalles del Pedido</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-pedido-body">
                        <!-- Los detalles del pedido se agregarán dinámicamente aquí -->
                    </div>
                 
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión pedidos(Tienda virtual)</h1>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#" class="btn btn-secondary" onclick="window.open('../../menu', '_self')">Regresar al menú</a>
            <br><br><h3>Realiza tu búsqueda aquí</h3>
                            <!-- PAGINACION -->
                        <form id="formBusqueda" class="form-inline mb-3">
    <div class="input-group mr-2">
        <input type="text" id="numeroPedido" class="form-control" placeholder="Número de pedido" aria-label="Número de pedido">
    </div>
    <div class="input-group mr-2">
        <input type="text" id="nombreUsuario" class="form-control" placeholder="Nombre de usuario" aria-label="Nombre de usuario">
    </div>
    <div class="input-group mr-2">
        <input type="date" id="fechaInicio" class="form-control" aria-label="Fecha inicio">
    </div>
    <div class="input-group mr-2">
        <input type="date" id="fechaFin" class="form-control" aria-label="Fecha fin">
    </div>
    <button type="button" id="btnBuscar" class="btn btn-primary">Buscar</button>
</form>

                        <div id="paginacion" class="text-center my-3"></div>
            <br><div class="content">
                <div class="container-fluid">
                    <div class="table-responsive">
          
                        <table id="tabla_Pedidos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Número de pedido</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Dirección</th>
                                    <th>Subtotal</th>
                                    <th>IVA</th>
                                    <th>Descuento aplicado</th>
                                    <th>Total a pagar</th>
                                    <th>Items</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody>

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
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="js/read.js"></script>
</body>

</html>