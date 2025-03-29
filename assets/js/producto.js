document.addEventListener("DOMContentLoaded", function () {

    // Llamada a la API para obtener los productos en promoción
    fetch('http://localhost:8088/Milogar/controllers/ProductoController.php?action=obtenerProductosPopulares')
        .then(response => response.json())  // Convierte la respuesta en JSON
        .then(data => {
            const productosPromocionContainer = document.getElementById('productos-populares-container');
            console.log('Datos recibidos de la API:', data);

            // Verificar si la respuesta contiene productos en la propiedad 'data'
            if (data.status === 'success' && data.data.length > 0) {
                const userRole = document.getElementById('role').getAttribute('data-role');
                const isAdmin = userRole === 'Administrador';

                data.data.forEach(producto => {
                    // Verifica si la imagen tiene una URL completa
                    const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;

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
                                        data-imagen="${imagenUrl}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>Agregar al carrito
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    // Agregar el HTML del producto al contenedor
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
                    fetch('http://localhost:8088/Milogar/Controllers/ProductoController.php?action=verDetalle&id=' + productoId, {
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
                                modal.find('.product-image').attr('src', 'http://localhost:8088/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);

                                // Generar miniaturas
                                var thumbnailsHTML = '';
                                if (producto.imagen) {
                                    var imagenRuta = 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
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
                                    const imagenProducto = 'http://localhost:8088/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

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
                        // Obtén los datos del producto desde los atributos data-* del botón
                        const productoId = event.target.dataset.id;
                        const nombreProducto = event.target.dataset.nombre;
                        const precioProducto = parseFloat(event.target.dataset.precio);
                        const descuento = parseFloat(event.target.getAttribute('data-descuento')) || 0; // Aseguramos que el descuento sea un número
                        const imagenProducto = event.target.dataset.imagen;

                        // Calcular el precio con descuento
                        const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                        // Verifica si el producto ya está en el carrito
                        const productoExistente = carrito.find(producto => producto.id === productoId);

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

                            });
                        }

                        // Guarda el carrito en localStorage
                        guardarCarrito();

                        // Actualiza el carrito en el modal (si es necesario)
                        actualizarCarrito();

                        // Mostrar la alerta con SweetAlert2
                        Swal.fire({
                            position: 'bottom-left',  // Ubicación de la alerta (abajo a la izquierda)
                            icon: 'success',  // Tipo de alerta (puede ser 'success', 'error', etc.)
                            title: '¡Agregado correctamente!',  // Mensaje de la alerta
                            showConfirmButton: false,  // No mostrar el botón de confirmación
                            timer: 4000,  // La alerta desaparecerá después de 4 segundos
                            toast: true,  // Activar la opción de "toast" para que sea una alerta pequeña
                            timerProgressBar: true,  // Mostrar barra de progreso en el timer
                        });

                        // Actualiza el contador en el icono
                        actualizarContadorCarrito();
                    });
                });

            } else {
                // Si no hay productos en promoción, mostrar mensaje
                productosPromocionContainer.innerHTML = '<p>No hay productos en promoción en este momento.</p>';
            }
        })
        .catch(error => {
            console.error('Error al obtener los productos en promoción:', error);
        });
})
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