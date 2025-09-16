document.addEventListener("DOMContentLoaded", function () {
    const slider = document.getElementById("slider-mas-vendidos");

    function cargarMasVendidos() {
        fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=productosMasVendidos&pagina=1')
            .then(res => res.json())
            .then(data => {
                console.log("Datos recibidos para productos más vendidos:", data);
                if (!data.productos || data.productos.length === 0) {
                    slider.innerHTML = "<p>No hay productos más vendidos.</p>";
                    return;
                }

                slider.innerHTML = ""; // Limpiar contenedor

                data.productos.forEach(producto => {
                    const imagenUrl = producto.imagen.startsWith('http')
                        ? producto.imagen
                        : 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

                    const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);


                    slider.innerHTML += `
    <div id="slider-mas-vendidos">
    <div class="item">
        <div class="card card-product h-100" style="max-width: 320px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 12px;">
            <div class="card-body position-relative p-3">
                <div class="text-center position-relative">
                    <a href="#!">
                        <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="mb-3 img-fluid" style="max-height:240px; object-fit:cover; border-radius:8px;">
                    </a>

                </div>
                                               ${producto.descuento > 0 ? `
                                                                  <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-danger rounded-circle d-flex justify-content-center align-items-center text-white"
                                                style="width: 60px; height: 60px; font-size: 0.8rem;">
                                                ${producto.descuento}%<br>Desc.
                                            </span>
                                        </div>
                                    ` : ''}
                <h2 class="fs-5 text-truncate" style="font-weight:600;">
                    <a href="#!" class="text-inherit text-decoration-none">${producto.nombreProducto}</a>
                </h2>
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:0.95rem;">
                    <small class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </small>
                    <span class="text-muted small">4.7(${producto.total_vendidos})</span>
                </div>
                ${usuarioSesion !== null && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0 ? `
    <p class="text-success fw-bold">
        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
    </p>` : ''}
                <div class="d-flex justify-content-between align-items-center mt-2" style="font-size:1.1rem; font-weight:500;">
                    <div>
                        <span class="text-dark">$${precioFinal.toFixed(2)}</span>
                        ${producto.descuento > 0 ? `<span class="text-decoration-line-through text-muted ms-2">$${producto.precio_1}</span>` : ''}
                    </div>
                    <div>
                        <span class="text-uppercase small text-primary">${producto.stock > 0 ? 'In Stock' : 'Out of Stock'}</span>
                    </div>
                </div>
<div class="d-grid mt-3">
        ${producto.is_talla == 1 
            ? '' 
            : `
    <a href="#!" 
                                    class="btn btn-danger w-100 d-flex justify-content-center align-items-center add-to-cartt" 
                                    style="font-size: 1rem; padding: 0.6rem 0; border-radius: 12px;"
                                    data-id="${producto.id}" 
                                    data-nombre="${producto.nombreProducto}"
                                    data-precio="${producto.precio_1}"
                                    data-descuento="${producto.descuento}"
                                    data-imagen="${imagenUrl}"
                                    data-puntos="${producto.puntos_otorgados}"
                                    data-minimo="${producto.cantidad_minima_para_puntos}">
                                    <i class="bi bi-cart-plus me-2"></i> Agregar al carrito
                                    </a>
              `
        }

        <!-- Botón Ver Detalle siempre visible -->
        <a href="#!" 
           class="btn btn-primary w-100" 
           data-bs-toggle="modal" 
           data-bs-target="#quickViewModal" 
           data-id="${producto.id}"
           style="font-size: 1rem; padding: 0.6rem 0; border-radius: 12px;">
           Ver más Detalles
        </a>
    </div>
            </div>
        </div>
    </div>
</div>
`;


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

                // Inicializar Slick Slider **después de que el HTML esté renderizado**
                if ($(slider).hasClass('slick-initialized')) {
                    $(slider).slick('unslick'); // Reiniciar si ya estaba inicializado
                }

                $(slider).slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: false,
                    infinite: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 3 } },
                        { breakpoint: 992, settings: { slidesToShow: 2 } },
                        { breakpoint: 576, settings: { slidesToShow: 1 } }
                    ]
                });


                // Inicializar Slick Slider **después de que el HTML esté renderizado**
                if ($(slider).hasClass('slick-initialized')) {
                    $(slider).slick('unslick'); // Reiniciar si ya estaba inicializado
                }

                $(slider).slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: false,
                    infinite: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 3 } },
                        { breakpoint: 992, settings: { slidesToShow: 2 } },
                        { breakpoint: 576, settings: { slidesToShow: 1 } }
                    ]
                });

                document.querySelectorAll('.add-to-cartt').forEach(btn => {
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

            })
            .catch(err => console.error("Error al cargar productos más vendidos:", err));
    }

    cargarMasVendidos();
});
