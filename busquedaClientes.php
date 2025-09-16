<?php
header("Access-Control-Allow-Origin: http://localhost:8080");

session_start();
// busquedaClientes.php
require_once "Config/config.php"; // ajusta ruta según tu proyecto
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Productos</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- Libs CSS -->
    <link href="assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet" />
    <link href="assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
    <link href="assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
    <link href="assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />
    <link href="assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
    <link href="assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
    <link href="assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
    <link href="assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/theme.min.css">
    <script>
        const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    </script>
    <style>
        /* Estilos para las tarjetas de productos */
        .card-product img {
            width: 200%;
            height: 300px;
            /* o ajusta según quieras */
            object-fit: contain;
            /* ahora se ve completa sin recorte */
            border-radius: 5px;
            background-color: #f8f9fa;
            /* opcional, para el espacio vacío */
        }

        .d-grid.mt-3 a {
            display: block;
            width: 100%;
            font-size: 1rem;
            padding: 0.6rem 0;
            border-radius: 50px;
            /* pill effect */
        }

        .d-grid.mt-3 a+a {
            margin-top: 8px;
            /* pequeño salto de línea entre los botones */
        }
    </style>
</head>

<body>
    <?php
    require_once "Views/Navigation/navigation.php";
    // Verificar si la variable 'user_role' está definida en la sesión
    if (!isset($_SESSION['user_role'])) {
        // Si no está definida, asignar 'Invitado' como rol predeterminado
        $_SESSION['user_role'] = 'Invitado';
    }
    // Verificar si el usuario tiene rol de administrador y, en caso afirmativo, asignarlo
    if ($_SESSION['user_role'] == 'Administrador') {
        // El usuario tiene rol de Administrador
        $userRole = 'Administrador';
    } else {
        // El usuario no tiene rol de Administrador (puede ser Invitado, Cliente, etc.)
        $userRole = 'Invitado';
    }
    // Verificar si el usuario ha iniciado sesión (puedes usar tu propia lógica de autenticación)
    $isUserLoggedIn = isset($_SESSION['user_id']); // Suponiendo que 'userId' esté en la sesión
    // Pasar el estado de la sesión a JavaScript
    echo "<script>var isUserLoggedIn = " . ($isUserLoggedIn ? 'true' : 'false') . ";</script>";
    echo '<div id="role" data-role="' . $userRole . '"></div>';

    ?>
    <h3 style="display: none;">Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

    <div class="container mt-4">
        <h2 class="mb-4">
            Resultados de búsqueda para:
            <span id="search-text" class="text-primary"><?php echo htmlspecialchars($search); ?></span>
        </h2>
        <!-- Contenedor dinámico de resultados -->
        <div id="resultados-busqueda-container" class="row g-3"></div>
        <h1>Nuestros clientes tambien buscaron:</h1><br><br><br>
        <div class="product-slider" id="slider-mas-vendidos">
        </div>

        <!-- Los productos se insertarán aquí dinámicamente -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-8">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Imagen principal sin efecto de zoom -->
                            <div class="product productModal" id="productModal">
                                <div>
                                    <img id="product-image" class="product-image" alt="Product Image">
                                </div>
                            </div>

                            <!-- Miniatura -->
                            <div class="product-tools">
                                <div class="thumbnails row g-3" id="productModalThumbnails">
                                    <div class="col-3">
                                        <div class="thumbnails-img">
                                            <img src="../assets/images/products/product-single-img-1.jpg" alt="Thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ps-md-8">
                                <a href="#!" style="font-size: 30px;" class="mb-4 d-block" id="product-category">Detalles del producto</a>
                                <h2 class="product-name mb-1 h1" id="product-name">Product Name</h2>
                                <div class="mb-4">
                                    <small class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </small>
                                    <a href="#" class="ms-2">(30 reviews)</a>
                                </div>
                                <div class="product-price fs-4">
                                    <span class="fw-bold text-dark" id="product-price"></span> <!-- Precio con descuento -->
                                    <span class="text-decoration-line-through text-muted" id="original-price"></span> <!-- Precio original -->
                                    <span><small class="fs-6 ms-2 text-danger" id="discount-percent"></small></span> <!-- Descuento en porcentaje -->
                                </div>
                                <hr class="my-6">
                                <div id="product-sizes" class="mt-3"></div>

                                <div class="mt-5 d-flex justify-content-start">
                                    <div class="col-2">
                                        <div class="input-group flex-nowrap justify-content-center">
                                            <input type="button" value="-" class="button-minus form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0" data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity" class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0">
                                            <input type="button" value="+" class="button-plus form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0" data-field="quantity">
                                        </div>
                                    </div>
                                    <div class="ms-2 col-4 d-grid">
                                        <button type="button" id="add-to-cart2" class="btn btn-primary"><i class="feather-icon icon-shopping-bag me-2"></i>Add to cart</button>
                                    </div>

                                </div>
                                <hr class="my-6">
                                <div>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>Descripción:</td>
                                                <td id="product-description"></td>
                                            </tr>
                                            <tr>
                                                <td>Código:</td>
                                                <td id="product-code">FBB00255</td>
                                            </tr>
                                            <tr>
                                                <td>Disponibilidad:</td>
                                                <td id="availability">In Stock</td>
                                            </tr>
                                            <tr>
                                                <td>Categoría</td>
                                                <td id="Category">Fruits</td>
                                            </tr>
                                            <tr>
                                                <td>Subcategoría</td>
                                                <td id="Subcategory">Fruits</td>
                                            </tr>

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
    <!--modal para traer todos los comentarios por producto-->
    <div class="modal fade" id="modalTodosComentarios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Todos los comentarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenedor-todos-comentarios">
                    <p class="text-muted">Cargando comentarios...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form onsubmit="guardarEdicionComentario(event)">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Comentario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id-comentario">
                        <input type="hidden" id="edit-id-producto">
                        <div class="mb-3">
                            <label for="edit-comentario" class="form-label">Comentario</label>
                            <textarea id="edit-comentario" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-calificacion" class="form-label">Calificación</label>
                            <select id="edit-calificacion" class="form-select">
                                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                <option value="4">⭐️⭐️⭐️⭐️</option>
                                <option value="3">⭐️⭐️⭐️</option>
                                <option value="2">⭐️⭐️</option>
                                <option value="1">⭐️</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once "Views/Navigation/footer.php"; ?>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="assets/libs/slick-carousel/slick/slick.min.js"></script>
    <script src="assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="assets/libs/nouislider/dist/nouislider.min.js"></script>
    <script src="assets/libs/wnumb/wNumb.min.js"></script>
    <script src="assets/libs/rater-js/index.js"></script>
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/libs/prismjs/components/prism-scss.min.js"></script>
    <script src="assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
    <script src="assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
    <script src="assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
    <script src="assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="assets/libs/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/theme.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const formularioBusqueda = document.getElementById("searchForm");
            const campoBusqueda = document.getElementById("busqueda");
            const resultadosDiv = document.getElementById("resultados-busqueda-container");
            const BASE_URL = window.location.origin + "/Milogar";

            const itemsPerPage = 12; // productos por página
            let currentPage = 1;
            let isAdmin = false; // ajustar según rol real
            let currentQuery = '';

            // Función para renderizar productos
            const renderProductos = (productos) => {
                if (!productos || productos.length === 0) {
                    resultadosDiv.innerHTML = "<h3 class='text-danger'>No se encontraron productos.</h3>";
                    return;
                }

                let productosHTML = '';
                productos.forEach(producto => {
                    const rutaImagen = `/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;
                    productosHTML += `
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card card-product h-100">
                    <div class="card-body">
                        <div class="text-center position-relative">
                            ${producto.descuento > 0 ? `
                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-danger rounded-circle d-flex justify-content-center align-items-center text-white"
                                        style="width: 50px; height: 50px; font-size: 0.8rem;">
                                        ${producto.descuento}%<br>Desc.
                                    </span>
                                </div>` : ''}
                            ${isAdmin ? `
                                <div class="position-absolute top-0 end-0 p-2">
                                    <a href="#!" class="text-decoration-none text-primary" onclick="editarProducto(${producto.id})">
                                        <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                                    </a>
                                </div>` : ''}
                            <a href="#!">
                                <img src="${rutaImagen}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                            </a>
                        </div>

                        <div class="text-small mb-1">
                            <a href="#!" class="text-decoration-none text-muted">
                                <small>${producto.nombrSubcategoria}</small>
                            </a>
                        </div>

                        <h2 class="fs-6">
                            <a href="#!" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
                        </h2>

${usuarioSesion !== null && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0 ? `
    <p class="text-success fw-bold">
        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
    </p>` : ''}

                        <div class="d-flex align-items-baseline gap-2 mt-2">
                            <span class="fw-bold text-success fs-5">
                                $${(producto.precio_1 - (producto.precio_1 * producto.descuento / 100)).toFixed(2)}
                            </span>
                            <span class="text-muted text-decoration-line-through fs-6">
                                $${producto.precio_1}
                            </span>
                        </div>
                    ${producto.is_talla == 0 
                        ? `
                            <div class="mt-2 d-flex flex-column gap-2">
                                <a href="#!" class="btn btn-danger btn-sm w-100 text-center fs-5 add-to-cart" 
                                    data-id="${producto.id}" 
                                    data-nombre="${producto.nombreProducto}"
                                    data-precio="${producto.precio_1}"
                                    data-descuento="${producto.descuento}"
                                    data-imagen="${rutaImagen}"
                                    data-puntos="${producto.puntos_otorgados}"
                                    data-minimo="${producto.cantidad_minima_para_puntos}">
                                    Agregar al carrito
                                </a>
                                <a href="#!" class="btn btn-success btn-sm w-100 text-center fs-5" 
                                    data-bs-toggle="modal" data-bs-target="#quickViewModal"
                                    data-id="${producto.id}" style="border-radius: 12px;">
                                    Ver más detalles
                                </a>
                            </div>
                        `
                        : `
                            <div class="mt-2">
                                <a href="#!" class="btn btn-success btn-sm w-100 text-center fs-5" 
                                    data-bs-toggle="modal" data-bs-target="#quickViewModal"
                                    data-id="${producto.id}" style="border-radius: 12px;">
                                    Ver más detalles
                                </a>
                            </div>
                            
                        `
                    }

                                <div class="mt-3">
                                    <h6>Deja tu comentario:</h6>
                                    <textarea id="comentario-${producto.id}" class="form-control mb-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                    <select id="calificacion-${producto.id}" class="form-select mb-2">
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                        <option value="4">⭐️⭐️⭐️⭐️</option>
                                        <option value="3">⭐️⭐️⭐️</option>
                                        <option value="2">⭐️⭐️</option>
                                        <option value="1">⭐️</option>
                                    </select>
                                    <button onclick="enviarComentario(${producto.id})" class="btn btn-outline-primary btn-sm">Enviar comentario</button>
                                </div>

                                <div class="mt-4">
                                    <h6>Comentarios:</h6>
                                    <div id="comentarios-lista-${producto.id}">
                                        <p class="text-muted">Cargando comentarios...</p>
                                    </div>
                                </div>
                    </div>
                    
                </div>
            </div>`;
                    // 👇 Aquí llamas a cargar los comentarios para este producto
                    setTimeout(() => {
                        cargarComentariosActivos(producto.id);
                    }, 0);
                });

                resultadosDiv.innerHTML = `<div class="row g-3">${productosHTML}</div>`;
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.addEventListener('click', (event) => {
                        event.preventDefault(); // ✅ Evita que el <a href="#"> recargue la página

                        // Obtén los datos del producto desde los atributos data-* del botón
                        const productoId = parseInt(event.target.dataset.id);
                        const nombreProducto = event.target.dataset.nombre;
                        const precioProducto = parseFloat(event.target.dataset.precio);
                        const descuento = parseFloat(event.target.getAttribute('data-descuento')) || 0; // Aseguramos que el descuento sea un número
                        const imagenProducto = event.target.dataset.imagen;
                        const puntosOtorgados = parseInt(event.target.dataset.puntos) || 0;
                        const cantidadMinimaParaPuntos = parseInt(event.target.dataset.minimo) || 0;

                        // Validación: evita insertar si falta algún dato importante
                        if (!productoId || !nombreProducto || isNaN(precioProducto) || !imagenProducto) {
                            console.error("Datos del producto incompletos o inválidos");
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo agregar el producto. Intenta nuevamente.',
                                toast: true,
                                position: 'bottom-left',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            return; // Detenemos la ejecución
                        }
                        // Calcular el precio con descuento
                        const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                        // Verifica si el producto ya está en el carrito
                        const productoExistente = carrito.find(producto =>
                            producto.id === productoId && (producto.talla ?? null) === null
                        );

                        // Si el producto ya está en el carrito, incrementa la cantidad
                        if (productoExistente) {
                            productoExistente.cantidad++;
                        } else {
                            // Si no está, lo añade al carrito con cantidad 1
                            carrito.push({
                                id: productoId,
                                nombre: nombreProducto,
                                precio: precioConDescuento, // Guardamos el precio con descuento
                                cantidad: 1,
                                precioOriginal: precioProducto, // Añadir el precio original
                                imagen: imagenProducto,
                                descuento: descuento,
                                puntos_otorgados: puntosOtorgados,
                                cantidad_minima_para_puntos: cantidadMinimaParaPuntos,
                                talla: null // ✅ AÑADIR ESTO

                            });
                        }

                        // Guarda el carrito en localStorage
                        guardarCarrito();

                        // Actualiza el carrito en el modal (si es necesario)
                        actualizarCarrito();

                        // Mostrar la alerta con SweetAlert2
                        Swal.fire({
                            position: 'bottom-left',
                            icon: 'success',
                            title: `¡${nombreProducto} agregado al carrito!`,
                            html: `<a href="#" id="ver-carrito" class="btn btn-sm btn-outline-dark mt-2">Ver carrito de compras</a>`,
                            showConfirmButton: false,
                            timer: 4000,
                            toast: true,
                            timerProgressBar: true,
                            didOpen: () => {
                                document.getElementById('ver-carrito').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const carrito = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
                                    carrito.show();
                                });
                            }
                        });

                        // Actualiza el contador en el icono
                        actualizarContadorCarrito();
                    });
                });

                // Escucha el evento cuando el modal se abre
                $('#quickViewModal').off('show.bs.modal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var productoId = button.data('id');
                    var modal = $(this);
                    const imagenProducto = "imagenesMilogar/productos/" + $(this).data('imagen');

                    // Hacer la solicitud fetch
                    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=verDetalle&id=' + productoId, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Datos recibidos:', data);
                            if (data.status === 'success') {
                                var producto = data.data;

                                // Actualizar la información del producto en el modal
                                modal.find('#product-name').text(producto.nombreProducto);
                                modal.find('#product-description').text(producto.descripcionProducto);

                                var precioOriginal = producto.precio_1;
                                var descuento = producto.descuento || 0;
                                var precioConDescuento = (precioOriginal - (precioOriginal * descuento / 100)).toFixed(2);

                                modal.find('#product-price').text(`$${precioConDescuento}`);
                                modal.find('#original-price').text(`$${precioOriginal}`);
                                modal.find('#discount-percent').text(`${descuento}% Off`);
                                modal.find('#product-code').text(producto.codigo_barras);
                                modal.find('#availability').text(producto.stock > 0 ? 'In Stock' : 'Out of Stock');
                                modal.find('#Category').text(producto.categoria_nombre || 'Unknown Category');
                                modal.find('#Subcategory').text(producto.subcategoria_nombre || 'Unknown Subcategory');
                                modal.find('#product-image').attr('src', 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);

                                // Buscar si el producto ya está en el carrito
                                const productoEnCarrito = carrito.find(item => item.id === producto.id);
                                if (productoEnCarrito) {
                                    modal.find('.quantity-field').val(productoEnCarrito.cantidad);
                                } else {
                                    modal.find('.quantity-field').val(1);
                                }

                                if (producto.is_talla == 1) {
                                    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerTallasPorProducto&producto_id=' + productoId, {
                                            method: 'GET',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(tallasData => {
                                            if (tallasData.status === 'success' && tallasData.data.length > 0) {
                                                let tallasHTML = '<label>Seleccione talla</label><br><div class="btn-group btn-group-toggle" data-toggle="buttons">';
                                                tallasData.data.forEach((talla, index) => {
                                                    tallasHTML += `
                    <label class="btn btn-outline-secondary ${index === 0 ? 'active' : ''}" style="cursor:pointer; user-select:none;">
                        <input type="radio" name="select-talla" id="talla-${index}" value="${talla.talla}" autocomplete="off" ${index === 0 ? 'checked' : ''} style="display:none;"> ${talla.talla}
                    </label>
                `;
                                                });
                                                tallasHTML += '</div>';

                                                modal.find('#product-sizes').html(tallasHTML);

                                                // Opcional: Añadir pequeño script para que al seleccionar un label, cambie el estilo
                                                modal.find('#product-sizes .btn').click(function() {
                                                    modal.find('#product-sizes .btn').removeClass('active');
                                                    $(this).addClass('active');
                                                    $(this).find('input[type=radio]').prop('checked', true);
                                                });
                                            } else {
                                                modal.find('#product-sizes').html('<p>No hay tallas disponibles.</p>');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error al obtener tallas:', error);
                                            modal.find('#product-sizes').html('<p>Error al cargar tallas.</p>');
                                        });
                                } else {
                                    modal.find('#product-sizes').html('');
                                }


                                modal.find('#add-to-cart2').off('click').on('click', function() {
                                    const cantidadSeleccionada = parseInt(modal.find('.quantity-field').val()) || 1;
                                    const productoId = parseInt(producto.id);
                                    const nombreProductoBase = producto.nombreProducto;
                                    const precioProducto = parseFloat(producto.precio_1);
                                    const descuento = parseFloat(producto.descuento) || 0;
                                    const imagenProducto = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;
                                    const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                                    // Obtener talla seleccionada (si aplica)
                                    const tallaSeleccionada = producto.is_talla == 1 ?
                                        modal.find('input[name="select-talla"]:checked').val() :
                                        null;

                                    const nombreProducto = tallaSeleccionada ?
                                        `${nombreProductoBase} - Talla ${tallaSeleccionada}` :
                                        nombreProductoBase;

                                    // Buscar si el producto ya está en el carrito
                                    const productoExistente = carrito.find(p => {
                                        return parseInt(p.id) === productoId &&
                                            (p.talla ?? null) === (tallaSeleccionada ?? null);
                                    });

                                    if (productoExistente) {
                                        productoExistente.cantidad += cantidadSeleccionada;
                                    } else {
                                        carrito.push({
                                            id: productoId,
                                            nombre: nombreProducto,
                                            precio: precioConDescuento,
                                            cantidad: cantidadSeleccionada,
                                            precioOriginal: precioProducto,
                                            imagen: imagenProducto,
                                            descuento: descuento,
                                            talla: tallaSeleccionada ?? null
                                        });
                                    }

                                    // Guardar en localStorage y actualizar
                                    guardarCarrito();
                                    actualizarCarrito();
                                    actualizarContadorCarrito();

                                    // Resetear cantidad en el modal a 1
                                    modal.find('.quantity-field').val(1);

                                    // Mostrar alerta
                                    Swal.fire({
                                        position: 'bottom-left',
                                        icon: 'success',
                                        title: '¡Agregado correctamente!',
                                        showConfirmButton: false,
                                        timer: 4000,
                                        toast: true,
                                        timerProgressBar: true,
                                    });
                                });
                            }
                        })
                        .catch(error => console.error("Error al obtener datos:", error));
                });


            }



            // Función para renderizar paginación
            const renderPagination = (totalItems, currentPage, totalPages) => {
                if (totalPages <= 1) return; // no mostrar paginación si solo hay 1 página

                let paginationHTML = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center mt-3">';
                paginationHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a>
    </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `<li class="page-item ${currentPage === i ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
                }

                paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a>
    </li>`;
                paginationHTML += '</ul></nav>';

                resultadosDiv.insertAdjacentHTML('beforeend', paginationHTML);

                document.querySelectorAll('.page-link').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        const page = parseInt(btn.dataset.page);
                        if (!isNaN(page) && page > 0 && page <= totalPages) {
                            realizarBusqueda(currentQuery, page);
                        }
                    });
                });
            };


            const realizarBusqueda = (query, page = 1) => {
                if (!query) {
                    resultadosDiv.innerHTML = "<h3 class='text-muted'>Escribe algo para buscar.</h3>";
                    return;
                }

                currentQuery = query;
                currentPage = page;

                fetch(`${BASE_URL}/Controllers/ProductoController.php?action=search&q=${encodeURIComponent(query)}&page=${page}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log("Respuesta completa del backend:", data); // 👈 aquí vemos todo
                        if (data.error) {
                            resultadosDiv.innerHTML = `<h3 class="text-danger">${data.error}</h3>`;
                        } else {
                            console.log("Productos recibidos:", data.productos); // 👈 productos
                            console.log("Total de productos:", data.total); // 👈 total
                            console.log("Página actual:", data.currentPage); // 👈 página actual
                            console.log("Total páginas:", data.totalPages); // 👈 total páginas

                            renderProductos(data.productos);
                            renderPagination(data.total, data.currentPage, data.totalPages);
                        }
                    })
                    .catch(err => console.error("Error en búsqueda:", err));
            }

            // Submit del formulario
            formularioBusqueda.addEventListener("submit", e => {
                e.preventDefault();
                const query = campoBusqueda.value.trim();
                realizarBusqueda(query);
            });

            // Buscar si hay query en URL
            const params = new URLSearchParams(window.location.search);
            if (params.has("search")) {
                const query = params.get("search");
                campoBusqueda.value = query;
                realizarBusqueda(query);
            }
        });

        function cargarComentariosActivos(productoId) {
            const contenedor = document.getElementById(`comentarios-lista-${productoId}`);
            if (!contenedor) return;

            contenedor.innerHTML = '<p class="text-muted">Cargando comentarios...</p>';

            fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=getComentariosPorProducto&producto_id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const comentariosProducto = data.data;

                        if (comentariosProducto.length === 0) {
                            contenedor.innerHTML = '<p class="text-muted">Aún no hay comentarios.</p>';
                        } else {
                            contenedor.innerHTML = '';

                            const primerComentario = comentariosProducto[0];
                            if (primerComentario) {

                                contenedor.innerHTML += `
                            <div class="border rounded p-2 mb-2 bg-light" id="comentario-${primerComentario.id}">
                                <strong>${primerComentario.nombreUsuario}</strong> 
                                <span class="text-warning">${'⭐'.repeat(primerComentario.calificacion)}</span><br>
                                <small class="text-muted">${new Date(primerComentario.fecha).toLocaleString()}</small>
                                <p>${primerComentario.comentario}</p>

                 
                            </div>
                        `;
                            }

                            contenedor.innerHTML += `
                        <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1"
                                onclick="mostrarModalTodosComentarios(${productoId})">
                            <i class="bi bi-chat-dots"></i> Ver comentarios
                        </button>

                    `;
                        }
                    } else {
                        contenedor.innerHTML = `<p class="text-danger">${data.message}</p>`;
                    }
                })
                .catch(error => {
                    contenedor.innerHTML = '<p class="text-danger">Error al cargar comentarios.</p>';
                    console.error("Error:", error);
                });
        }

        function mostrarModalTodosComentarios(productoId) {
            const contenedor = document.getElementById("contenedor-todos-comentarios");
            contenedor.innerHTML = '<p class="text-muted">Cargando comentarios...</p>';

            fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=getComentariosPorProducto&producto_id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Comentarios:", data.data);
                        const comentarios = data.data;
                        if (comentarios.length === 0) {
                            contenedor.innerHTML = '<p class="text-muted">Aún no hay comentarios.</p>';
                            return;
                        }

                        const imagenDefecto = 'https://cdn-icons-png.flaticon.com/512/847/847969.png';

                        contenedor.innerHTML = comentarios.map(c => {
                            // Ahora la propiedad es c.usuario_id
                            const esUsuario = usuarioSesion && parseInt(c.usuario_id) === parseInt(usuarioSesion);

                            return `
                        <div class="border rounded p-2 mb-2 bg-light d-flex">
                            <img src="${imagenDefecto}" alt="Usuario" class="rounded-circle me-2" width="50" height="50">
                            <div style="flex: 1">
                                <strong>${c.nombreUsuario}</strong>
                                <span class="text-warning">${'⭐'.repeat(c.calificacion)}</span><br>
                                <small class="text-muted">${new Date(c.fecha).toLocaleString()}</small>
                                <p class="mb-1">${c.comentario}</p>

                                ${esUsuario ? `
                                    <button class="btn btn-sm btn-warning me-2"
                                        onclick="abrirModalEditarComentario(${c.id}, '${c.comentario.replace(/'/g, "\\'")}', ${c.calificacion}, ${productoId})">Editar</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="eliminarComentario(${c.id}, ${productoId})">Eliminar</button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                        }).join('');
                    } else {
                        contenedor.innerHTML = `<p class="text-danger">${data.message}</p>`;
                    }
                })
                .catch(err => {
                    console.error("Error al cargar comentarios:", err);
                    contenedor.innerHTML = '<p class="text-danger">Error al cargar los comentarios.</p>';
                });

            const modal = new bootstrap.Modal(document.getElementById("modalTodosComentarios"));
            modal.show();
        }

        function abrirModalEditarComentario(idComentario, comentario, calificacion, productoId) {
            document.getElementById('edit-id-comentario').value = idComentario;
            document.getElementById('edit-id-producto').value = productoId;
            document.getElementById('edit-comentario').value = comentario;
            document.getElementById('edit-calificacion').value = calificacion;

            const modal = new bootstrap.Modal(document.getElementById('modalEditarComentario'));
            modal.show();
        }

        function guardarEdicionComentario(event) {
            event.preventDefault();

            const idComentario = document.getElementById('edit-id-comentario').value;
            const productoId = document.getElementById('edit-id-producto').value;
            const nuevoComentario = document.getElementById('edit-comentario').value.trim();
            const nuevaCalificacion = document.getElementById('edit-calificacion').value;

            if (!nuevoComentario) {
                Swal.fire('Error', 'El comentario no puede estar vacío.', 'warning');
                return;
            }

            fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: idComentario,
                        comentario: nuevoComentario,
                        calificacion: parseInt(nuevaCalificacion)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Comentario actualizado',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            cargarComentariosActivos(productoId); // ✅ Actualiza la lista

                            // ✅ Cerrar ambos modales
                            const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarComentario'));
                            if (modalEditar) modalEditar.hide();

                            const modalMostrar = bootstrap.Modal.getInstance(document.getElementById('modalMostrarComentarios'));
                            if (modalMostrar) modalMostrar.hide();

                            // ✅ Opcional: recargar contenido de comentarios si lo necesitas
                            cargarComentariosActivos(productoId);
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo actualizar el comentario.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar comentario:', error);
                    Swal.fire('Error', 'Ocurrió un error al intentar actualizar el comentario.', 'error');
                });
        }

        function eliminarComentario(idComentario, productoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará tu comentario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=delete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: idComentario
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado', 'Tu comentario ha sido eliminado.', 'success').then(() => {

                                    // 🟢 Cierra el modal si está abierto
                                    const modalElement = document.getElementById('modalTodosComentarios');
                                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }

                                    // Recargar comentarios
                                    if (typeof cargarComentariosActivos === 'function') {
                                        cargarComentariosActivos(productoId);
                                    }
                                    if (typeof mostrarModalTodosComentarios === 'function') {
                                        mostrarModalTodosComentarios(productoId);
                                    }
                                });
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo eliminar el comentario.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar comentario:', error);
                            Swal.fire('Error', 'Ocurrió un error al intentar eliminar el comentario.', 'error');
                        });
                }
            });
        }


        function enviarComentario(productoId) {
            const userId = usuarioSesion;
            const comentario = document.getElementById(`comentario-${productoId}`).value.trim();
            const calificacion = document.getElementById(`calificacion-${productoId}`).value;

            if (!userId) {
                alert('Debes iniciar sesión para comentar.');
                return;
            }

            if (!comentario || comentario.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Campo vacío!',
                    text: 'El comentario no puede estar en blanco.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }


            // Primero verificar si ya existe comentario para ese usuario y producto
            fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=checkComentario&user_id=${userId}&producto_id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Atención!',
                            text: 'Solo puedes hacer un comentario por producto.',
                            confirmButtonText: 'Aceptar'
                        });
                        return;


                    } else {
                        // Si no existe, enviamos el comentario
                        return fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=create', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    user_id: userId,
                                    producto_id: productoId,
                                    comentario: comentario,
                                    calificacion: parseInt(calificacion)
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Comentario enviado correctamente',
                                        text: '¡Gracias por compartir tu opinión!',
                                        confirmButtonText: 'OK'
                                    });

                                    // Limpiar campos después de enviar
                                    document.getElementById(`comentario-${productoId}`).value = '';
                                    document.getElementById(`calificacion-${productoId}`).value = '5';

                                    // Recargar los comentarios
                                    cargarComentariosActivos(productoId);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Ups...',
                                        text: data.message || 'No se pudo guardar el comentario.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error al guardar comentario:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error al enviar tu comentario.',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }


                })
                .then(response => {
                    if (!response) return; // ya mostró alerta, no continuar
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        alert('Comentario enviado con éxito');
                        // Opcional: limpiar campos, actualizar lista, etc.
                    } else {
                        alert('Error al enviar comentario: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar el comentario.');
                });
        }
    </script>


    <script src="assets/js/cart.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/losMasVendidos.js"></script>
</body>

</html>