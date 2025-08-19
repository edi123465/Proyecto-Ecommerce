
let paginaActual = 1; // Página actual inicial

function cargarProductos(pagina = 1) {
    const limit = 12;
    const offset = (pagina - 1) * limit; // Calcula el offset según la página actual

    fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductosPopulares&limit=${limit}&offset=${offset}`)
        .then(response => response.json())
        .then(data => {
            const userRole = document.getElementById('role').getAttribute('data-role');
            const isAdmin = userRole === 'Administrador'; 
            const productosPromocionContainer = document.getElementById('productos-populares-container');
            console.log('Datos recibidos de la API:', data);

            if (data.status === 'success' && data.data.length > 0) {
                // Limpia el contenedor antes de agregar nuevos productos
                productosPromocionContainer.innerHTML = '';

                // Mostrar los productos
                data.data.forEach(producto => {
                    // Verifica si la imagen tiene una URL completa
                    const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;

                    // Crear el HTML dinámicamente para cada producto
const productHTML = `
<div class="col">
    <div class="card card-product">
        <div class="card-body">
            <div class="text-center position-relative">
                <!-- Badge de descuento dentro de la tarjeta -->
                ${producto.descuento > 0 ? `
                    <div class="position-absolute top-0 start-0 p-2">
                        <span class="badge bg-warning"> ${producto.descuento}% Desc.</span>
                    </div>
                ` : ''}

                ${isAdmin ? `
                    <div class="position-absolute top-0 end-0 p-2">
                        <a href="#!" class="text-decoration-none text-primary" onclick="editarProducto(${producto.id})">
                            <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                        </a>
                    </div>
                ` : ''}

                <a href="#!">
                    <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                </a>

                <div class="card-product-action">
                    <a href="#!" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}">
                        Ver Detalle
                    </a>
                </div>
            </div>

            <div class="text-small mb-1">
                <a href="#!" class="text-decoration-none text-muted">
                    <small>${producto.nombreSubcategoria}</small>
                </a>
            </div>

            <h2 class="fs-6">
                <a href="#!" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
            </h2>

                  <!-- Mostrar texto de puntos solo si aplica -->
                                        ${(usuarioSesion && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0) ? `
                                    <p class="text-success fw-bold">
                                        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
                                    </p>
                                ` : ''}
            <div>
                <small class="text-warning"> 
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </small> 
                <span class="text-muted small">4.5(149)</span>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span class="text-dark">$${producto.precio_1 - (producto.precio_1 * producto.descuento / 100)}</span>
                    <span class="text-decoration-line-through text-muted">$${producto.precio_1}</span>
                </div>
                
                <div>
                                        <a href="#!" class="btn btn-primary btn-sm add-to-cart" 
                                        data-id="${producto.id}" 
                                        data-nombre="${producto.nombreProducto}"
                                        data-precio="${producto.precio_1}"
                                        data-descuento="${producto.descuento}"
                                        data-imagen="${imagenUrl}"
                                        data-puntos="${producto.puntos_otorgados}"
                                        data-minimo="${producto.cantidad_minima_para_puntos}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            Agregar al carrito
                                        </a>
                </div>
            </div>
        </div>
    </div>
</div>
`;
productosPromocionContainer.innerHTML += productHTML;

                });
                // Escucha el evento cuando el modal se abre
                $('#quickViewModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var productoId = button.data('id');
                    var modal = $(this);
                    const imagenProducto = "imagenesMilogar/productos/" + $(this).data('imagen');

                    console.log("Ruta de imagen:", imagenProducto);
                    console.log("ID del producto " + productoId);

                    // Hacer la solicitud fetch
                    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=verDetalle&id=' + productoId, {
                        method: 'GET',
                        headers: { 'Content-Type': 'application/json' }
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
                                modal.find('.product-image').attr('src', 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);

                                // Generar miniaturas
                                var thumbnailsHTML = '';
                                if (producto.imagen) {
                                    var imagenRuta = 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
                                    thumbnailsHTML += `
<div class="col-3">
    <div class="thumbnails-img">
        <img src="${imagenRuta}" alt="${producto.nombreProducto}">
    </div>
</div>
`;
                                }
                                modal.find('#productModalThumbnails').html(thumbnailsHTML);

                                modal.find('#add-to-cart2').off('click').on('click', function () {
                                    const cantidadSeleccionada = parseInt(modal.find('.quantity-field').val()) || 1;
                                    const productoId = producto.id; // Se obtiene del objeto 'producto'
                                    const nombreProducto = producto.nombreProducto;
                                    const precioProducto = parseFloat(producto.precio_1);
                                    const descuento = parseFloat(producto.descuento) || 0;

                                    // Verificar el objeto producto completo
                                    console.log("Objeto producto:", producto);

                                    // Acceder a la ruta de la imagen
                                    const imagenProducto = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

                                    // Imprimir la ruta de la imagen
                                    console.log("Ruta de la imagen:", imagenProducto);

                                    // Calcular el precio con descuento
                                    const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                                    // Imprimir en consola los datos antes de agregar al carrito
                                    console.log("ID del producto:", productoId);
                                    console.log("Nombre del producto:", nombreProducto);
                                    console.log("Precio del producto:", precioProducto);
                                    console.log("Descuento:", descuento);
                                    console.log("Precio con descuento:", precioConDescuento);

                                    // Verificar si el producto ya está en el carrito
                                    const productoExistente = carrito.find(producto => producto.id === productoId);

                                    if (productoExistente) {
                                        productoExistente.cantidad++;
                                    } else {
                                        carrito.push({
                                            id: productoId,
                                            nombre: nombreProducto,
                                            precio: precioConDescuento,
                                            cantidad: cantidadSeleccionada,
                                            precioOriginal: precioProducto,
                                            imagen: imagenProducto, // Usar la ruta completa
                                            descuento: descuento,
                                        });
                                    }

                                    // Guardar el carrito en localStorage
                                    guardarCarrito();
                                    actualizarCarrito();

                                    // Mostrar alerta con SweetAlert2
                                    Swal.fire({
                                        position: 'bottom-left',
                                        icon: 'success',
                                        title: '¡Agregado correctamente!',
                                        showConfirmButton: false,
                                        timer: 4000,
                                        toast: true,
                                        timerProgressBar: true,
                                    });

                                    // Actualizar el contador del carrito
                                    actualizarContadorCarrito();
                                });


                            }
                        })
                        .catch(error => console.error("Error al obtener datos:", error));
                });

                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.addEventListener('click', (event) => {
                        const productoId = event.target.dataset.id;
                        const nombreProducto = event.target.dataset.nombre;
                        const precioProducto = parseFloat(event.target.dataset.precio);
                        const descuento = parseFloat(event.target.getAttribute('data-descuento')) || 0;
                        const imagenProducto = event.target.dataset.imagen;
                        const puntosOtorgados = parseInt(event.target.dataset.puntos) || 0;
                        const cantidadMinimaParaPuntos = parseInt(event.target.dataset.minimo) || 0;
                
                        // 🚀 LOG de verificación
                        console.log('🛒 Producto agregado al carrito:');
                        console.log('ID:', productoId);
                        console.log('Nombre:', nombreProducto);
                        console.log('Precio original:', precioProducto);
                        console.log('Descuento (%):', descuento);
                        console.log('Imagen:', imagenProducto);
                        console.log('Puntos otorgados:', puntosOtorgados);
                        console.log('Cantidad mínima para puntos:', cantidadMinimaParaPuntos);
                
                        const precioConDescuento = precioProducto - (precioProducto * descuento / 100);
                
                        const productoExistente = carrito.find(producto => producto.id === productoId);
                
                        if (productoExistente) {
                            productoExistente.cantidad++;
                        } else {
                            carrito.push({
                                id: productoId,
                                nombre: nombreProducto,
                                precio: precioConDescuento,
                                cantidad: 1,
                                precioOriginal: precioProducto,
                                imagen: imagenProducto,
                                descuento: descuento,
                                puntos_otorgados: puntosOtorgados,
                                cantidad_minima_para_puntos: cantidadMinimaParaPuntos
                            });
                        }
                
                        guardarCarrito();
                        actualizarCarrito();
                
                        Swal.fire({
                            position: 'bottom-left',
                            icon: 'success',
                            title: '¡Agregado correctamente!',
                            showConfirmButton: false,
                            timer: 4000,
                            toast: true,
                            timerProgressBar: true,
                        });
                
                        actualizarContadorCarrito();
                    });
                });
                
                // Generar los botones de paginación
                generarPaginacion(pagina, data.totalPaginas);
            }
        })
        .catch(error => console.error('Error al cargar productos:', error));
}


function generarPaginacion(paginaActual, totalPaginas) {
    const paginationContainer = document.querySelector('.pagination');
    paginationContainer.innerHTML = '';

    // Botón de "Anterior"
    paginationContainer.innerHTML += `
        <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
            <a class="page-link mx-1 rounded-3" href="#" onclick="cargarProductos(${paginaActual - 1})">
                <i class="feather-icon icon-chevron-left"></i>
            </a>
        </li>
    `;

    // Botones de páginas
    for (let i = 1; i <= totalPaginas; i++) {
        paginationContainer.innerHTML += `
            <li class="page-item ${paginaActual === i ? 'active' : ''}">
                <a class="page-link mx-1 rounded-3" href="#" onclick="cargarProductos(${i})">${i}</a>
            </li>
        `;
    }

    // Botón de "Siguiente"
    paginationContainer.innerHTML += `
        <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
            <a class="page-link mx-1 rounded-3" href="#" onclick="cargarProductos(${paginaActual + 1})">
                <i class="feather-icon icon-chevron-right"></i>
            </a>
        </li>
    `;
}

// Funciones para cambiar cantidad
function aumentarCantidad(event) {
    const index = event.target.dataset.index;
    carrito[index].cantidad++;
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}

function disminuirCantidad(event) {
    const index = event.target.dataset.index;
    if (carrito[index].cantidad > 1) {
        carrito[index].cantidad--;
    } else {
        carrito.splice(index, 1); // Eliminar producto si la cantidad es 1
    }
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}

// Función para eliminar un producto del carrito
function eliminarProducto(event) {
    const index = event.target.dataset.index;
    carrito.splice(index, 1);
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}
// Llamada inicial para cargar productos
cargarProductos(1);
