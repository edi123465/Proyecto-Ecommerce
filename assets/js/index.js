// Variables de paginación
let paginaActual = 1;
const productosPorPagina = 8; // Puedes ajustar cuántos productos mostrar por página

function cargarProductosPromocion(pagina = 1) {
    fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductosPromocion&page=${pagina}&limit=${productosPorPagina}`)
        .then(response => response.json())
        .then(data => {
            const productosPromocionContainer = document.getElementById('productos-promocion-container');
            productosPromocionContainer.innerHTML = ''; // Limpiar productos anteriores

            if (data.status === 'success' && data.data.length > 0) {
                const userRole = document.getElementById('role').getAttribute('data-role');
                const isAdmin = userRole === 'Administrador';

                data.data.forEach(producto => {
                    const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

                    const productHTML = `
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card card-product">
                            <div class="card-body">
                                <div class="text-center position-relative">
                                    ${producto.descuento > 0 ? `
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-warning"> ${producto.descuento}% Desc.</span>
                                        </div>
                                    ` : ''}

                                    ${isAdmin ? `
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <a href="#" class="text-decoration-none text-primary" onclick="editarProducto(${producto.id})">
                                                <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                                            </a>
                                        </div>
                                    ` : ''}

                                    <a href="#">
                                        <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                                    </a>

                                    <a href="#" class="btn btn-success btn-sm w-100 position-absolute bottom-0 start-0 mb-3" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}" style="border-radius: 12px;">
                                        Ver Detalle
                                    </a>
                                </div>

                                <div class="text-small mb-1">
                                    <small>${producto.nombreSubcategoria}</small>
                                </div>

                                <h2 class="fs-6">
                                    <a href="#" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
                                </h2>

                                ${(usuarioSesion && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0) ? `
                                    <p class="text-success fw-bold">
                                        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
                                    </p>
                                ` : ''}

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="text-dark">$${producto.precio_1 - (producto.precio_1 * producto.descuento / 100)}</span>
                                        <span class="text-decoration-line-through text-muted">$${producto.precio_1}</span>
                                    </div>

                                    ${producto.is_talla == 0 ? `
                                        <div>
                                            <a href="#" class="btn btn-primary btn-sm add-to-cart" 
                                                data-id="${producto.id}" 
                                                data-nombre="${producto.nombreProducto}"
                                                data-precio="${producto.precio_1}"
                                                data-descuento="${producto.descuento}"
                                                data-imagen="${imagenUrl}"
                                                data-puntos="${producto.puntos_otorgados}"
                                                data-minimo="${producto.cantidad_minima_para_puntos}">
                                                <i class="feather feather-plus"></i> Add
                                            </a>
                                        </div>
                                    ` : ''}
                                </div>

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

                    productosPromocionContainer.insertAdjacentHTML('beforeend', productHTML);
                    cargarComentariosActivos(producto.id);

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
                                    // Generar botones de compartir
                                    const urlProducto = window.location.origin + window.location.pathname; // URL base de la página actual
                                    const imagenUrl = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;
                                    const textoCompartir = encodeURIComponent(`Mira este producto: ${producto.nombreProducto} \n${urlProducto}`);

                                    // Crear HTML para botones compartir
                                    const shareButtonsHTML = `
    <div class="d-flex align-items-center gap-2 mt-3">
        <span class="fw-semibold me-2">Compartir:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(urlProducto)}" target="_blank" class="btn btn-outline-primary btn-sm" title="Compartir en Facebook" rel="noopener">
            <i class="bi bi-facebook"></i>
        </a>
        <a href="https://api.whatsapp.com/send?text=${textoCompartir}" target="_blank" class="btn btn-outline-success btn-sm" title="Compartir en WhatsApp" rel="noopener">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>
`;

                                    // Eliminar botones previos para evitar duplicados
                                    modal.find('.share-buttons-container').remove();

                                    // Insertar los botones de compartir
                                    modal.find('#add-to-cart2').parent().append(`<div class="share-buttons-container">${shareButtonsHTML}</div>`);



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

                });

                construirPaginacion(data.total, pagina);

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

            } else {
                productosPromocionContainer.innerHTML = '<p class="text-muted">No se encontraron productos en promoción.</p>';
            }


        });
}

function construirPaginacion(totalProductos) {
    const totalPaginas = Math.ceil(totalProductos / productosPorPagina);
    const paginacionContainer = document.getElementById('paginacion-productos');
    paginacionContainer.innerHTML = '';

    const ul = document.createElement('ul');
    ul.className = 'pagination';

    // Botón Anterior
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#">Anterior</a>`;
    prevLi.addEventListener('click', function (e) {
        e.preventDefault();
        if (paginaActual > 1) {
            paginaActual--;
            cargarProductosPromocion(paginaActual);
            document.getElementById('productos-promocion').scrollIntoView({ behavior: 'smooth' });
        }
    });
    ul.appendChild(prevLi);

    // Botones de páginas
    for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${paginaActual === i ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.addEventListener('click', function (e) {
            e.preventDefault();
            paginaActual = i;
            cargarProductosPromocion(paginaActual);
            document.getElementById('productos-promocion').scrollIntoView({ behavior: 'smooth' });

        });
        ul.appendChild(li);
    }

    // Botón Siguiente
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${paginaActual === totalPaginas ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#">Siguiente</a>`;
    nextLi.addEventListener('click', function (e) {
        e.preventDefault();
        if (paginaActual < totalPaginas) {
            paginaActual++;
            cargarProductosPromocion(paginaActual);
            document.getElementById('productos-promocion').scrollIntoView({ behavior: 'smooth' });

        }
    });
    ul.appendChild(nextLi);

    paginacionContainer.appendChild(ul);
}
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
                body: JSON.stringify({ id: idComentario })
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




document.addEventListener('DOMContentLoaded', () => cargarProductosPromocion());
