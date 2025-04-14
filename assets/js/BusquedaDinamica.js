document.addEventListener("DOMContentLoaded", function () {
    const formularioBusqueda = document.getElementById("searchForm");
    const campoBusqueda = document.getElementById("busqueda");
    const resultadosDiv = document.getElementById("resultados-busqueda-container");
    const productosContainer = document.getElementById("productos-container");
    const productosPopularesContainer = document.getElementById("productos-populares-container");
    const BASE_URL = window.location.origin + "/Milogar";
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
            const BASE_URL = window.location.origin + "/Milogar";
            const response = await fetch(`${BASE_URL}/Controllers/ProductoController.php?action=search&q=${encodeURIComponent(query)}`);
    
            if (response.ok) {
                const data = await response.json();
    
                if (data.error) {
                    resultadosDiv.innerHTML = `<h1 class='text-danger'>${data.error}</h1>`;
                } else {
                    let productosHTML = "";
                    data.productos.forEach(producto => {
                        const rutaImagen = `/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;
                        productosHTML += `
                            <div class="col">
                                <div class="card card-product">
                                    <div class="card-body">
                                        <div class="text-center position-relative">
                                            <img class="mb-3 img-fluid" src="${rutaImagen}" style="max-width:100%; height:auto;">
                                            <div class="card-product-action">
                                                <a href="#!" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}">
                                                    Ver Detalle
                                                </a>
                                            </div>
                                        </div>
                                        <h2 class="fs-6"><a href="#" class="text-inherit text-decoration-none">${producto.nombreProducto}</a></h2>
                                               <!-- Mostrar texto de puntos solo si aplica -->
                                                       ${(usuarioSesion && producto.puntos_otorgados > 0 && producto.cantidad_minima_para_puntos > 0) ? `
                                    <p class="text-success fw-bold">
                                        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
                                    </p>
                                ` : ''}
                                        <div class="text-warning">
                                            <small><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></small>
                                            <span class="text-muted small">4.5 (67)</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <span class="text-dark">$${parseFloat(producto.precio).toFixed(2)}</span>
                                                <span class="text-decoration-line-through text-muted">$5</span>
                                            </div>
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
                                            Agregar al carrito
                                        </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
    
                    resultadosDiv.innerHTML = productosHTML;
    
                    document.querySelectorAll('.add-to-cart').forEach(btn => {
                        btn.addEventListener('click', (event) => {
                            // Obtén los datos del producto desde los atributos data-* del botón
                            const productoId = event.target.dataset.id;
                            const nombreProducto = event.target.dataset.nombre;
                            const precioProducto = parseFloat(event.target.dataset.precio);
                            const descuento = parseFloat(event.target.getAttribute('data-descuento')) || 0; // Aseguramos que el descuento sea un número
                            const imagenProducto = event.target.dataset.imagen;
                            const puntosOtorgados = parseInt(event.target.dataset.puntos) || 0; // Obtener los puntos otorgados
                            const cantidadMinimaParaPuntos = parseInt(event.target.dataset.minimo) || 0; // Obtener la cantidad mínima para puntos
                    
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
                                    puntos_otorgados: puntosOtorgados, // Añadir los puntos otorgados
                                    cantidad_minima_para_puntos: cantidadMinimaParaPuntos // Añadir la cantidad mínima para obtener puntos
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
                    
                }
            }
        } catch (error) {
            console.error("Error al realizar la búsqueda:", error);
            resultadosDiv.innerHTML = `<h1 class='text-danger'>Hubo un problema al buscar productos.</h1>`;
        }
    };
    

    
    
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
