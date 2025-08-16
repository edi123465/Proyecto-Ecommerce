document.addEventListener("DOMContentLoaded", function () {
    const formularioBusqueda = document.getElementById("searchForm");
    const campoBusqueda = document.getElementById("busqueda");
    const resultadosDiv = document.getElementById("resultados-busqueda-container");
    const productosContainer = document.getElementById("productos-container");
    const productosPopularesContainer = document.getElementById("productos-populares-container");
    const BASE_URL = window.location.origin + "";
    const estaEnShopGrid = window.location.pathname.includes("/shop-grid");
    
    document.getElementById("searchForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const query = document.getElementById("busqueda").value.trim();
        
        if (query !== "") {
            // Si ya estamos en shop-grid.php, solo actualizamos la URL
            if (estaEnShopGrid) {
                window.location.search = `search=${encodeURIComponent(query)}`;
            } else {
                // Si estamos en otra página, redirigimos a Views/shop-grid.php con la búsqueda
                window.location.href = `${BASE_URL}/shop-grid?search=${encodeURIComponent(query)}`;
            }
        }
    });
    
    // Función para realizar la búsqueda
    const realizarBusqueda = async (query) => {
        if (query === "") {
            resultadosDiv.innerHTML = "<h1 class='text-muted'>Escribe algo para buscar.</h1>";
            return;
        }

        // Si no estamos en shop-grid.php, redirigir la búsqueda a esa página
        if (!estaEnShopGrid) {
            window.location.href = `${BASE_URL}/shop-grid?search=${encodeURIComponent(query)}`;
            return;
        }

        // Ocultar productos generales y populares
        if (productosContainer) productosContainer.style.display = "none";
        if (productosPopularesContainer) productosPopularesContainer.style.display = "none";

        // Mostrar el contenedor de resultados
        resultadosDiv.style.display = "flex";

        try {
            
            const response = await fetch(`${BASE_URL}/Controllers/ProductoController.php?action=search&q=${encodeURIComponent(query)}`);

            if (response.ok) {
                const data = await response.json();

                if (data.error) {
                    resultadosDiv.innerHTML = `<h1 class='text-danger'>${data.error}</h1>`;
                } else {
                    let productosHTML = "";
                                    const userRole = document.getElementById('role').getAttribute('data-role');
                const isAdmin = userRole === 'Administrador';
                    data.productos.forEach(producto => {
                        const rutaImagen = `/assets/imagenesMilogar/productos/${producto.imagen}`;
                        productosHTML += `
                            <div class="col">
                        <div class="card card-product">
                            <div class="card-body">
                                <div class="text-center position-relative">
                                    <!-- Badge de descuento redondo dentro de la tarjeta -->
                                    ${producto.descuento > 0 ? `
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-danger rounded-circle d-flex justify-content-center align-items-center text-white"
                                                style="width: 60px; height: 60px; font-size: 0.8rem;">
                                                ${producto.descuento}%<br>Desc.
                                            </span>
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
                                        <img src="${rutaImagen}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                                    </a>
                                    <br><br><br>
                                    <!-- Salto de línea antes del botón -->
                                    <a href="#!" class="btn btn-success btn-sm w-100 position-absolute bottom-0 start-0 mb-3" data-bs-toggle="modal" data-bs-target="#quickViewModal"                                               data-id="${producto.id}" style="border-radius: 12px;">
                                        Ver Detalle
                                    </a>
                                </div>
                
                                <div class="text-small mb-1">
                                    <a href="#!" class="text-decoration-none text-muted">
                                        <small>${producto.nombreSubcategoria}</small>
                                    </a>
                                </div>
                
                                <h2 class="fs-6">
                                    <a href="#!" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
                                </h2>
                
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
                
                                    ${producto.is_talla == 0 ? `
                                        <div>
                                            <a href="#!" class="btn btn-primary btn-sm add-to-cart" 
                                                data-id="${producto.id}" 
                                                data-nombre="${producto.nombreProducto}"
                                                data-precio="${producto.precio_1}"
                                                data-descuento="${producto.descuento}"
                                                data-imagen="${rutaImagen}"
                                                data-puntos="${producto.puntos_otorgados}"
                                                data-minimo="${producto.cantidad_minima_para_puntos}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-plus">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                                Add
                                            </a>
                                        </div>
                                    ` : ''}
                                    
                                </div>

                                                 <!-- Comentario (formulario) -->
                                <div class="mt-3">
                                    <h6>Deja tu comentario:</h6>
                                    <textarea id="comentarioSS-${producto.id}" class="form-control mb-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                    <select id="calificacionSearch-${producto.id}" class="form-select mb-2">
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                        <option value="4">⭐️⭐️⭐️⭐️</option>
                                        <option value="3">⭐️⭐️⭐️</option>
                                        <option value="2">⭐️⭐️</option>
                                        <option value="1">⭐️</option>
                                    </select>
                                    <button onclick="enviarComentarioSearch(${producto.id})" class="btn btn-outline-primary btn-sm">Enviar comentario</button>
                                </div>
                                <!-- Mostrar Comentarios -->
                                <div class="mt-4">
                                    <h6>Comentarios:</h6>
                                    <div id="comentarioSearch-${producto.id}">
                                        <p class="text-muted">Cargando comentarios...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        `;
                    });

                    resultadosDiv.innerHTML = productosHTML;
                                        // ✅ Ahora que los elementos están en el DOM, cargamos los comentarios:
                    data.productos.forEach(producto => {
                        const testContenedor = document.getElementById(`comentarios-${producto.id}`);
                        console.log(`Contenedor de comentarios para producto ${producto.id}:`, testContenedor);
                        cargarComentariosActivos(producto.id);
                    });
                }

// Escucha el evento cuando el modal se abre
                        $('#quickViewModal').off('show.bs.modal').on('show.bs.modal', function (event) {
                        var button = $(event.relatedTarget);
                        var productoId = button.data('id');
                        var modal = $(this);
                        const imagenProducto = "imagenesMilogar/productos/" + $(this).data('imagen');

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
                                    modal.find('#product-image').attr('src', 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen);
                            
                            // Buscar si el producto ya está en el carrito
                                const productoEnCarrito = carrito.find(item => item.id === producto.id);
                                if (productoEnCarrito) {
                                    modal.find('.quantity-field').val(productoEnCarrito.cantidad);
                                } else {
                                    modal.find('.quantity-field').val(1);
                                }

                                    if (producto.is_talla == 1) {
                                    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerTallasPorProducto&id=' + productoId, {
                                        method: 'GET',
                                        headers: { 'Content-Type': 'application/json' }
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
                                                modal.find('#product-sizes .btn').click(function () {
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


                                modal.find('#add-to-cart2').off('click').on('click', function () {
                                    const cantidadSeleccionada = parseInt(modal.find('.quantity-field').val()) || 1;
                                    const productoId = parseInt(producto.id);
                                    const nombreProductoBase = producto.nombreProducto;
                                    const precioProducto = parseFloat(producto.precio_1);
                                    const descuento = parseFloat(producto.descuento) || 0;
                                    const imagenProducto = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;
                                    const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

                                    // Obtener talla seleccionada (si aplica)
                                    const tallaSeleccionada = producto.is_talla == 1
                                        ? modal.find('input[name="select-talla"]:checked').val()
                                        : null;

                                    const nombreProducto = tallaSeleccionada 
                                        ? `${nombreProductoBase} - Talla ${tallaSeleccionada}` 
                                        : nombreProductoBase;

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
                                talla: null  // ✅ AÑADIR ESTO

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
                                            document.getElementById('ver-carrito').addEventListener('click', function (e) {
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

            }
        } catch (error) {
            console.error("Error al realizar la búsqueda:", error);
            resultadosDiv.innerHTML = `<h1 class='text-danger'>Hubo un problema al buscar productos.</h1>`;
        }
    };
function cargarComentariosActivos(productoId) {
    console.log(`Iniciando carga de comentarios para producto ID: ${productoId}`);

    fetch(`http://localhost:8080/Milogar/Controllers/TiendaController.php?action=getComentariosPorProducto&producto_id=${productoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const contenedorComentarios = document.querySelector(`#comentarioSearch-${productoId}`);

            if (!contenedorComentarios) {
                console.warn(`No se encontró el contenedor para comentarios con ID: comentarios-${productoId}`);
                return;
            }

            contenedorComentarios.innerHTML = ''; // Limpiar antes de insertar

            if (data.success) {
                const comentarios = data.data.sort((a, b) => new Date(b.fecha) - new Date(a.fecha)); // ✅ ordenar más nuevos primero

                if (comentarios.length > 0) {
                    const comentario = comentarios[0]; // ✅ ahora sí el más reciente

                    contenedorComentarios.innerHTML += `
                        <div class="comentario border rounded p-2 mb-2">
                            <p><strong>${comentario.nombreUsuario}</strong> <small class="text-muted">(${new Date(comentario.fecha).toLocaleString()})</small></p>
                            <p>${comentario.comentario}</p>
                            <p>Calificación: ${'⭐️'.repeat(comentario.calificacion)} (${comentario.calificacion}/5)</p>
                        </div>
                    `;

                    if (comentarios.length > 1) {
                        contenedorComentarios.innerHTML += `
                            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1"
                                    onclick="mostrarModalTodosComentarios(${productoId})">
                                <i class="bi bi-chat-dots"></i> Ver todos los comentarios
                            </button>
                        `;
                    }

                } else {
                    contenedorComentarios.innerHTML = '<p class="text-muted">Aún no hay comentarios.</p>';
                }

            } else {
                contenedorComentarios.innerHTML = '<p class="text-danger">Error al cargar comentarios.</p>';
                console.error("Error al cargar comentarios:", data.message);
            }
        })
        .catch(error => {
            console.error("Error en la petición de comentarios:", error);
        });
}

    // Capturar la búsqueda desde el formulario
    formularioBusqueda.addEventListener("submit", function (event) {
        event.preventDefault();
        const query = campoBusqueda.value.trim();
        realizarBusqueda(query);
    });

    // Si hay un parámetro de búsqueda en la URL, ejecutar la búsqueda automáticamente
    const params = new URLSearchParams(window.location.search);
    if (params.has("search")) {
        const query = params.get("search");
        campoBusqueda.value = query;
        realizarBusqueda(query);
    }
});
function enviarComentarioSearch(productoId, event) {
    if (event) event.preventDefault();

    const userId = usuarioSesion;
    const textarea = document.getElementById(`comentarioSS-${productoId}`);
    const comentario = textarea ? textarea.value.trim() : '';
    const calificacion = document.getElementById(`calificacionSearch-${productoId}`).value;
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
                return null;
            } else {
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
                });
            }
        })
        .then(response => {
            if (!response) return null;
            return response.json();
        })
        .then(data => {
            if (!data) return;
            if (data.success) {
                // ✅ Mostrar alerta de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Comentario enviado con éxito!',
                    text: 'Gracias por dejar tu comentario.',
                    confirmButtonText: 'Aceptar'
                });


                // ✅ Limpiar formulario
                document.getElementById(`comentarioSS-${productoId}`).value = '';
                document.getElementById(`calificacionSearch-${productoId}`).value = '5';

                // ✅ Agregar dinámicamente el nuevo comentario
                const nuevoComentario = {
                    nombreUsuario: data.nombreUsuario || 'Tú',
                    fecha: data.fecha || new Date().toLocaleDateString(),
                    comentario: comentario,
                    calificacion: parseInt(calificacion)
                };

                const contenedor = document.getElementById(`comentarioSearch-${productoId}`);
                if (contenedor) {
                    // Elimina el mensaje "Aún no hay comentarios" si existe
                    const mensajeVacio = contenedor.querySelector('p.text-muted');
                    if (mensajeVacio) {
                        mensajeVacio.remove();
                    }

                    const div = document.createElement("div");
                    div.className = "comentario border rounded p-2 mb-2";
                    div.innerHTML = `
                        <p><strong>${nuevoComentario.nombreUsuario}</strong> <small class="text-muted">(${nuevoComentario.fecha})</small></p>
                        <p>${nuevoComentario.comentario}</p>
                        <p>Calificación: ${'⭐️'.repeat(nuevoComentario.calificacion)} (${nuevoComentario.calificacion}/5)</p>
                    `;
                    contenedor.prepend(div); // lo coloca arriba del resto
                }


            } else {
                alert('Error al enviar comentario: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al enviar el comentario.');
        });
}



