document.addEventListener("DOMContentLoaded", function () {
    // Llamada a la API para obtener los productos en promoción
    fetch('http://localhost:8088/Milogar/controllers/ProductoController.php?action=obtenerProductosPromocion')
        .then(response => response.json())  // Convierte la respuesta en JSON
        .then(data => {
            const productosPromocionContainer = document.getElementById('productos-promocion-container');
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
                                modal.find('#product-image').attr('src', 'http://localhost:8088/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);

                                // Generar miniaturas
                                var thumbnailsHTML = '';
                                if (producto.imagen) {
                                    const imagenRuta = 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
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
});

// Función para abrir el modal de edición y cargar los datos del producto
function editarProducto(productoId) {
    // Hacer una solicitud para obtener los detalles del producto a editar
    fetch(`http://localhost:8088/Milogar/Controllers/ProductoController.php?action=obtenerProductoPorId&id=${productoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const producto = data.data;

                // Llenar el formulario del modal con los datos del producto
                const productoIdInput = document.querySelector('#productoId');
                if (productoIdInput) {
                    productoIdInput.value = producto.id || '';
                }

                document.querySelector('#nombre_Producto').value = producto.nombreProducto || '';
                document.querySelector('#descripcion_Producto').value = producto.descripcionProducto || '';
                document.querySelector('#categoriaa').value = producto.categoria_id || '';  // Asegúrate de tener la categoría en los datos
                document.querySelector('#subcategoriaa').value = producto.subcategoria_id || ''; // Asegúrate de tener la subcategoría en los datos
                document.querySelector('#precioCompra').value = producto.precio || '';
                document.querySelector('#precio1').value = producto.precio_1 || '';
                document.querySelector('#precio2').value = producto.precio_2 || '';
                document.querySelector('#precio3').value = producto.precio_3 || '';
                document.querySelector('#precio4').value = producto.precio_4 || '';
                document.querySelector('#codigoBarras').value = producto.codigo_barras || '';
                document.querySelector('#stocks').value = producto.stock || '';
                document.querySelector('#imagen_Producto').src = producto.imagen ? 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen : 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/default.png';
                document.querySelector('#estado').value = producto.isActive || '';
                document.querySelector('#isPromocion').value = producto.is_promocion || '';
                document.querySelector('#desc').value = producto.descuento || '';
                // Aquí, en lugar de modificar el valor del campo file, mostramos la imagen.
                const imagenPreview = document.querySelector('#imagenPreview');
                if (producto.imagen) {
                    imagenPreview.src = 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
                } else {
                    imagenPreview.src = 'http://localhost:8088/Milogar/assets/imagenesMilogar/Productos/default.png';
                }
                // Cargar las categorías
                fetch('http://localhost:8088/Milogar/controllers/ProductoController.php?action=obtenerCategorias')
                    .then(response => response.json())
                    .then(data => {
                        const categoriaSelect = document.querySelector('#categoriaa');
                        categoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>';

                        if (data.status === 'success' && data.data.length > 0) {
                            data.data.forEach(categoria => {
                                const option = document.createElement('option');
                                option.value = categoria.id;
                                option.textContent = categoria.nombreCategoria;
                                categoriaSelect.appendChild(option);
                            });

                            // Establecer la categoría seleccionada
                            categoriaSelect.value = producto.categoria_id || '';

                            // Simular el evento de cambio para cargar las subcategorías
                            categoriaSelect.dispatchEvent(new Event('change'));
                        } else {
                            categoriaSelect.innerHTML = '<option value="" disabled>No hay categorías disponibles</option>';
                        }
                    })
                    .catch(error => console.error('Error al obtener las categorías:', error));

                // Cuando se selecciona una categoría, cargar las subcategorías correspondientes
                document.querySelector('#categoriaa').addEventListener('change', function () {
                    const categoriaId = this.value;
                    const subcategoriaSelect = document.querySelector('#subcategoriaa');
                    subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';

                    // Si se selecciona una categoría, cargar las subcategorías
                    if (categoriaId) {
                        fetch(`http://localhost:8088/Milogar/controllers/ProductoController.php?action=obtenerSubcategoriasPorCategoria&categoria_id=${categoriaId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success' && data.data.length > 0) {
                                    data.data.forEach(subcategoria => {
                                        const option = document.createElement('option');
                                        option.value = subcategoria.id;
                                        option.textContent = subcategoria.nombrSubcategoria;
                                        subcategoriaSelect.appendChild(option);
                                    });

                                    // Establecer la subcategoría seleccionada (si existe)
                                    subcategoriaSelect.value = producto.subcategoria_id || '';
                                } else {
                                    // Si no hay subcategorías, mostrar un mensaje
                                    subcategoriaSelect.innerHTML = '<option value="" disabled>No hay subcategorías disponibles</option>';
                                }
                            })
                            .catch(error => console.error('Error al obtener las subcategorías:', error));
                    } else {
                        // Si no se selecciona una categoría, limpiar las subcategorías
                        subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';
                    }
                });

                // Mostrar el modal de edición
                $('#editarProductoModal').modal('show');

            } else {
                console.error("Producto no encontrado");
            }
        })
        .catch(error => console.error('Error al obtener los detalles del producto:', error));
}

document.getElementById('formEditarProducto').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario de forma tradicional

    // Mostrar la alerta de confirmación antes de continuar
    var confirmar = confirm("¿Estás seguro de que quieres actualizar este producto?");

    if (!confirmar) {
        // Si el usuario selecciona "No", se cancela la actualización
        alert("No se ha realizado ninguna actualización.");
        return; // Detener la ejecución de la función
    }

    // Captura los datos del formulario
    const id = parseInt(document.getElementById('productoId').value, 10);
    const nombreProducto = document.getElementById('nombre_Producto').value;
    const descripcionProducto = document.getElementById('descripcion_Producto').value;
    const precio = parseFloat(document.getElementById('precioCompra').value);
    const precio1 = parseFloat(document.getElementById('precio1').value);
    const precio2 = parseFloat(document.getElementById('precio2').value);
    const precio3 = parseFloat(document.getElementById('precio3').value);
    const precio4 = parseFloat(document.getElementById('precio4').value);
    const stock = parseInt(document.getElementById('stocks').value);
    const codBarras = document.getElementById('codigoBarras').value;
    const isActive = document.getElementById('estado').value;
    const isPromocion = document.getElementById('isPromocion').value;
    const descuento = parseFloat(document.getElementById('desc').value);

    // Captura los valores de categoría y subcategoría
    const categoria_id = document.getElementById('categoriaa').value;
    const subcategoria_id = document.getElementById('subcategoriaa').value;

    // Prepara los datos para enviar en la solicitud
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombreProducto', nombreProducto);
    formData.append('descripcionProducto', descripcionProducto);
    formData.append('precio', precio);
    formData.append('precio1', precio1);
    formData.append('precio2', precio2);
    formData.append('precio3', precio3);
    formData.append('precio4', precio4);
    formData.append('stock', stock);
    formData.append('codBarras', codBarras);
    formData.append('isActive', isActive);
    formData.append('isPromocion', isPromocion);
    formData.append('descuento', descuento);
    formData.append('categoria_id', categoria_id);  // Añadir categoría
    formData.append('subcategoria_id', subcategoria_id);  // Añadir subcategoría

    // Verificar si hay una nueva imagen
    const imagenProducto = document.getElementById('imagen_Producto').files[0];
    if (imagenProducto) {
        formData.append('nueva_imagen', imagenProducto);
    }

    // Consola para ver los datos antes de enviarlos
    console.log('Datos a enviar:', {
        id,
        nombreProducto,
        descripcionProducto,
        precio,
        precio1,
        precio2,
        precio3,
        precio4,
        stock,
        codBarras,
        isActive,
        isPromocion,
        descuento,
        categoria_id,
        subcategoria_id,
        nueva_imagen: imagenProducto ? imagenProducto.name : 'No hay imagen'
    });

    // Hacer la solicitud fetch
    fetch(`http://localhost:8088/Milogar/controllers/ProductoController.php?action=actualizarProducto&idProducto=${id}`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json()) // Suponiendo que el servidor responde con JSON
        .then(data => {
            // Consola para verificar la respuesta del servidor
            console.log('Respuesta del servidor:', data);

            if (data.success) {
                // Mostrar un mensaje de éxito (puedes usar alert o actualizar el DOM)
                alert('Producto actualizado correctamente');
                window.location.href = "./index.php";
                // Cerrar el modal
                $('#editarProductoModal').modal('hide');
                // Actualizar la lista de productos o redirigir si es necesario
            } else {
                // Mostrar un mensaje de error
                alert('Error al actualizar el producto');
            }
        })
        .catch(error => {
            // Consola para capturar errores en la solicitud fetch
            console.error('Error al hacer la solicitud:', error);
            alert('Hubo un error al actualizar el producto.');
        });
});

