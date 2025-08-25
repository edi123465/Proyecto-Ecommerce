<?php
session_start();
// Instanciar el controlador
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
    <title>MILOGAR | Canjes</title>
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

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestión de comentarios - Por producto</h1>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-secondary" onclick="window.open('../../menu', '_self')">Regresar al menú</a>
            <!-- Botón para abrir el modal -->
            <br><br>

            <!-- El Modal -->
            <div class="modal fade" id="modalCanjeable" tabindex="-1" role="dialog" aria-labelledby="modalCanjeableLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCanjeableLabel">Formulario para Crear Canjeable</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario dentro del modal -->
                            <form action="" method="POST" enctype="multipart/form-data">
                                <!-- Campo para producto_id -->
                                <div class="form-group">
                                    <label for="producto_id">Producto:</label>
                                    <select id="producto_id" name="producto_id" class="form-control" required>
                                        <option value="">Seleccione un producto</option>
                                        <!-- Aquí se llenarán los productos dinámicamente -->
                                    </select>
                                </div>
                                <!-- Campo para nombre -->
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="100" placeholder="Nombre del canjeable">
                                </div>

                                <!-- Campo para descripcion -->
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea id="descripcion" name="descripcion" class="form-control" required placeholder="Descripción del canjeable"></textarea>
                                </div>

                                <!-- Campo para tipo (producto o cupon) -->
                                <div class="form-group">
                                    <label for="tipo">Tipo:</label>
                                    <select id="tipo" name="tipo" class="form-control" required>
                                        <option value="producto">Producto</option>
                                        <option value="cupon">Cupón</option>
                                    </select>
                                </div>

                                <!-- Campo para valor_descuento -->
                                <div class="form-group">
                                    <label for="valor_descuento">Valor de Descuento:</label>
                                    <input type="number" id="valor_descuento" name="valor_descuento" class="form-control" step="0.01" placeholder="Valor del descuento" required>
                                </div>

                                <!-- Campo para puntos_necesarios -->
                                <div class="form-group">
                                    <label for="puntos_necesarios">Puntos Necesarios:</label>
                                    <input type="number" id="puntos_necesarios" name="puntos_necesarios" class="form-control" required placeholder="Puntos necesarios">
                                </div>

                                <!-- Campo para estado (activo o inactivo) -->
                                <div class="form-group">
                                    <label for="estado">Estado:</label>
                                    <select id="estado" name="estado" class="form-control" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>

                                <!-- Campo para imagen (vista previa) -->
                                <div class="form-group">
                                    <label for="imagenPreview">Imagen del Producto:</label><br>
                                    <img id="imagenPreview" src="" alt="Vista previa de la imagen" style="max-width: 200px; max-height: 200px;">
                                </div>

                                <input type="file" id="imagen" name="imagen">



                                <!-- Botón para enviar el formulario -->
                                <button type="submit" class="btn btn-primary">Crear Canjeable</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal de Edición de Canjeables -->
            <div class="modal fade" id="modalEditarCanjeable" tabindex="-1" role="dialog" aria-labelledby="modalEditarCanjeableLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarCanjeableLabel">Editar Canjeable</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarCanjeable">
                                <input type="hidden" id="canjeable_id">
                                <div class="form-group">
                                    <label for="producto_idEditar">Producto</label>
                                    <select class="form-control" id="producto_idEditar" required>
                                        <!-- Las opciones de productos se cargarán dinámicamente aquí -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nombreEditar">Nombre</label>
                                    <input type="text" class="form-control" id="nombreEditar" required>
                                </div>
                                <div class="form-group">
                                    <label for="descripcionEditar">Descripción</label>
                                    <textarea class="form-control" id="descripcionEditar" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="tipoEditar">Tipo</label>
                                    <select class="form-control" id="tipoEditar" required>
                                        <option value="cupon">Cupón</option>
                                        <option value="producto">Producto</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="valor_descuentoEditar">Valor Descuento</label>
                                    <input type="number" class="form-control" id="valor_descuentoEditar" required>
                                </div>
                                <div class="form-group">
                                    <label for="puntos_necesariosEditar">Puntos Necesarios</label>
                                    <input type="number" class="form-control" id="puntos_necesariosEditar" required>
                                </div>

                                <div class="form-group">
                                    <label for="estadoEditar">Estado</label>
                                    <select class="form-control" id="estadoEditar" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="imagenEditar">Imagen</label>
                                    <input type="file" class="form-control-file" id="imagenEditar">
                                </div>
                                <div class="form-group">
                                    <label for="imagenPreviewEditar">Vista previa de imagen</label>
                                    <img id="imagenPreviewEditar" src="" alt="Vista previa" style="width: 100px;">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table id="tabla_canjes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Comentario</th>
                                    <th>ID Producto</th>
                                    <th>Nombre Producto</th>
                                    <th>Imagen Producto</th>
                                    <th>Usuario</th>
                                    <th>Comentario</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody id="comentarios-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!<!-- PAGINACION -->
                    <div class="d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">&raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
            </div>
        </div>
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="../../Recursos/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
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


  <!-- JS de AdminLTE y dependencias desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="../../Recursos/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="js/read.js"></script>
</body>

</html>