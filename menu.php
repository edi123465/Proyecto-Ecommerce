<?php
session_start(); // Iniciar la sesión para acceder a la información del usuario
// Definimos un array con las opciones del menú
$menuItems = [
    [
        'name' => 'Usuarios',
        'link' => 'usuarios/index.php', // Enlace al CRUD de Usuarios
    ],
    [
        'name' => 'Roles',
        'link' => 'RolesUsuarios/index.php', // Enlace al CRUD de Roles
    ],
    [
        'name' => 'Productos',
        'link' => 'productos/index.php', // Enlace al CRUD de Productos
    ],
    [
        'name' => 'Tienda',
        'link' => 'Tienda/index.php', // Enlace al CRUD de Productos
    ],
    [
        'name' => 'Reportes',
        'link' => 'reportes/index.php', // Enlace a Reportes
    ],
];

// Verificar si la sesión está activa y si el usuario tiene el rol de 'Administrador'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    // Si no está logueado o no es administrador, redirigir al login
    header('Location: index.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Dashboard</title>
    <link rel="icon" href="assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <!-- CSS de AdminLTE desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- CSS de FontAwesome desde CDN (opcional, si lo necesitas) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

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

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="menu" class="brand-link">
                <img src="assets/imagenesMilogar/logomilo.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">MILOGAR</span>
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
                            <a href="Views/RolesUsuarios/index" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>
                                    Roles
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="Views/Usuarios/index" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Usuarios
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/Categorias/index" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>
                                    Categorías
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/Subcategorias/index" class="nav-link">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    Subcategorías
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/Productos/index" class="nav-link">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Productos
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Tienda
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/PedidosTienda/index" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Pedidos tienda Virtual
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="Views/Canjeables/index" class="nav-link">
                                <i class="nav-icon fas fa-gift"></i>
                                <p>
                                    Productos Canjeables
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/Tallas/index" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Tallas Productos
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/OtrosProductos/index" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Otros Productos
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/ComentariosProductos/index" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Comentarios por Producto
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/ComentariosGeneral/index" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Comentarios Tienda
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Views/Facturacion/index" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Facturación
                                </p>
                            </a>
                        </li>


                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">MILOGAR - Panel Administración</h1>
                        </div><!-- /.col -->

                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v2</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">CPU Traffic</span>
                                    <span class="info-box-number">
                                        10
                                        <small>%</small>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Likes</span>
                                    <span class="info-box-number">41,410</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Ventas</span>
                                    <span id="totalVentas" class="info-box-number">760 Dólares</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Todos los usuarios</span>
                                    <span class="info-box-number" id="cantidadUsuarios">2,000</span>

                                    <!-- Botón para administrar usuarios -->
                                    <a href="Views/Usuarios/index.php"
                                        class="btn btn-sm btn-primary mt-2"
                                        role="button"
                                        aria-label="Administrar usuarios">
                                        Administrar Usuarios
                                    </a>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h2>Reporte de Ventas</h2>

                                    <div class="card-tools d-flex">
                                        <select id="periodoSelect" class="form-control form-control-sm me-2">
                                            <option value="anio">Anual</option>
                                            <option value="mes">Mensual</option>
                                            <option value="semana">Semanal</option>
                                            <option value="dia">Diario</option>
                                        </select>

                                        <input type="month" id="mesFiltro" class="form-control form-control-sm" />
                                    </div>
                                </div>

                                <div class="card-body">
                                    <p class="text-center">
                                        <strong id="tituloPeriodo"></strong>
                                    </p>
                                    <div class="chart">
                                        <canvas id="salesChart" height="180"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <h2>Productos vendidos</h2>
                    <canvas id="productosMasVendidosChart" width="400" height="200"></canvas>


                    <!-- /.row -->
                </div><!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

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
    <!-- JS de AdminLTE y dependencias desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="Views/graficos/js/read.js"></script>
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
                    window.location.href = "Views/login/logout.php";
                }
            });
        });

        // Función para actualizar la cantidad de usuarios en la UI
        function actualizarCantidadUsuarios() {
            fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=contarUsuarios')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar el contenido del span con el número de usuarios
                        document.getElementById('cantidadUsuarios').textContent = data.total.toLocaleString();
                    } else {
                        console.error('Error al obtener la cantidad de usuarios:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                });
        }

        // Llamamos a la función al cargar la página
        document.addEventListener('DOMContentLoaded', actualizarCantidadUsuarios);
    </script>

</body>

</html>