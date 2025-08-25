<?php
session_start();
// Instanciar el controlador
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MILOGAR | Facturación</title>
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/imagenesMilogar/logomilo.jpg">

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

        #sugerencias {
            background: #fff !important;
            /* Fondo blanco */
            border: 1px solid #ddd;
            /* Borde gris suave */
            max-height: 200px;
            /* Límite de altura */
            overflow-y: auto;
            /* Scroll si hay muchos resultados */
            border-radius: 5px;
        }

        #sugerencias .list-group-item {
            background: #fff;
            /* Fondo blanco en los items */
            color: #333;
            /* Texto oscuro */
            cursor: pointer;
        }

        #sugerencias .list-group-item:hover {
            background: #f1f1f1;
            /* Fondo gris claro al pasar el mouse */
        }
    </style>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                <a href="../menu.php" class="nav-link">Home</a>
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

    <?php require_once "../Navigation/navigationAdmin.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper p-4">
                <section class="content">
                    <div class="container-fluid">
                        <h1>Facturación</h1>

                        <div class="row">
                            <!-- Datos de Factura + Totales -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card card-primary mb-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Datos de Factura</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="formFactura">
                                            <!-- Tipo de cliente -->
                                            <div class="form-group">
                                                <label>Tipo de Cliente</label>
                                                <select id="tipoCliente" class="form-control" onchange="mostrarFormulario()">
                                                    <option value="consumidor">Consumidor Final</option>
                                                    <option value="datos">Con Datos</option>
                                                </select>
                                            </div>

                                            <!-- Campos básicos -->
                                            <div class="form-group">
                                                <label>Fecha</label>
                                                <input type="date" class="form-control" id="fecha" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Método de pago</label>
                                                <select id="metodoPago" class="form-control">
                                                    <option>Efectivo</option>
                                                    <option>Tarjeta</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Pago del Cliente</label>
                                                <input type="number" class="form-control form-control-lg font-weight-bold text-success" id="pagoCliente" placeholder="0.00" style="font-size:1.5rem; text-align:right;">
                                            </div>

                                            <!-- Formulario adicional -->
                                            <div id="formDatosCliente" style="display:none; margin-top:15px; border-top:1px solid #ddd; padding-top:15px;">
                                                <h5>Datos del Cliente</h5>
                                                <div class="form-group">
                                                    <label>Nombre o Razón Social</label>
                                                    <input type="text" class="form-control" id="nombreCliente">
                                                </div>
                                                <div class="form-group">
                                                    <label>Cédula / RUC</label>
                                                    <input type="text" class="form-control" id="cedulaRuc">
                                                </div>
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input type="text" class="form-control" id="direccionCliente">
                                                </div>
                                                <div class="form-group">
                                                    <label>Teléfono</label>
                                                    <input type="text" class="form-control" id="telefonoCliente">
                                                </div>
                                                <div class="form-group">
                                                    <label>Correo</label>
                                                    <input type="email" class="form-control" id="correoCliente">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Totales destacados -->
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="bg-light p-3 rounded shadow-sm text-center">
                                            <h5>Total</h5>
                                            <span id="total" class="d-block font-weight-bold" style="font-size:2rem;">0.00</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="bg-light p-3 rounded shadow-sm text-center">
                                            <h5>Cambio</h5>
                                            <span id="cambio" class="d-block font-weight-bold text-success" style="font-size:2rem;">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Productos y tabla -->
                            <div class="col-lg-8 col-md-6">
                                <div class="card card-success mb-3">
                                    <div class="card-body">
                                        <!-- Buscar producto -->
                                        <div class="form-inline mb-3 w-100 position-relative">
                                            <input type="text" class="form-control mr-2 w-50" id="producto" placeholder="Busca por código o nombre del Producto" autocomplete="off">
                                            <div id="sugerencias" class="list-group position-absolute" style="z-index: 1000; top: 38px; width: 50%; display:none;"></div>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="mb-3 d-flex justify-content-end">
                                            <button type="button" class="btn btn-danger mr-2" id="btnLimpiarTabla">
                                                <i class="fas fa-trash-alt"></i> Limpiar Tabla
                                            </button>
                                            <button type="button" class="btn btn-success" id="btnTerminarVenta">
                                                <i class="fas fa-check"></i> Terminar Venta
                                            </button>
                                        </div>

                                        <!-- Tabla de productos -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="tablaProductos">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Precio</th>
                                                        <th>Cantidad</th>
                                                        <th>Subtotal</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>


            </div>
        </div>

    <script>
        let total = 0;

        $("#btnAgregar").click(function() {
            let producto = $("#producto").val();
            let precio = parseFloat($("#precio").val());
            let cantidad = parseInt($("#cantidad").val());

            if (!producto || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
                alert("Complete correctamente los campos del producto.");
                return;
            }

            let subtotal = precio * cantidad;
            total += subtotal;

            let fila = `
      <tr>
        <td>${producto}</td>
        <td>$${precio.toFixed(2)}</td>
        <td>${cantidad}</td>
        <td>$${subtotal.toFixed(2)}</td>
        <td><button class="btn btn-danger btn-sm btnEliminar"><i class="fas fa-trash"></i></button></td>
      </tr>
    `;

            $("#tablaProductos tbody").append(fila);
            $("#total").text(total.toFixed(2));

            calcularCambio();

            $("#producto").val("");
            $("#precio").val("");
            $("#cantidad").val("");
        });

        // Eliminar producto
        $(document).on("click", ".btnEliminar", function() {
            let fila = $(this).closest("tr");
            let subtotal = parseFloat(fila.find("td:eq(3)").text().replace("$", ""));
            total -= subtotal;
            fila.remove();
            $("#total").text(total.toFixed(2));
            calcularCambio();
        });

        // Calcular cambio en tiempo real
        $("#pagoCliente").on("input", function() {
            calcularCambio();
        });

        function calcularCambio() {
            let pagoCliente = parseFloat($("#pagoCliente").val());
            if (!isNaN(pagoCliente)) {
                let cambio = pagoCliente - total;
                $("#cambio").text(cambio.toFixed(2));
            } else {
                $("#cambio").text("0.00");
            }
        }
    </script>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->


    </div>
    <!-- ./wrapper -->
    <!-- Modal para editar estado -->
    <div class="modal fade" id="modalEditarComentario" tabindex="-1" role="dialog" aria-labelledby="modalEditarComentarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditarComentario">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarComentarioLabel">Editar Estado del Comentario</h5>
                        <!-- Bootstrap 4 usa btn-close como "close" con span -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="comentario_id" name="comentario_id" />

                        <div class="form-group">
                            <label>ID Producto</label>
                            <input type="text" id="producto_id" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label>Nombre Producto</label>
                            <input type="text" id="nombreProducto" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" id="nombreUsuario" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label>Comentario</label>
                            <textarea id="comentarioTexto" class="form-control" rows="3" readonly></textarea>
                        </div>

                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="text" id="fechaComentario" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="1">Autorizado</option>
                                <option value="0">Rechazado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- AdminLTE for demo purposes -->
    <script src="../../Recursos/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="js/generarVenta.js"></script>
    <script>
        function mostrarFormulario() {
            let tipo = document.getElementById("tipoCliente").value;
            let formDatos = document.getElementById("formDatosCliente");

            if (tipo === "datos") {
                formDatos.style.display = "block"; // Muestra los campos adicionales
            } else {
                formDatos.style.display = "none"; // Oculta los campos
            }
        }
    </script>
</body>

</html>