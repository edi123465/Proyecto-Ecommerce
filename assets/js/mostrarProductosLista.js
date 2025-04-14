document.addEventListener("DOMContentLoaded", function () {
    const categoriaList = document.getElementById("categoryCollapseMenu");
    const productosContainer = document.getElementById("productos-container"); // Contenedor donde se mostrarán los productos
    const productosPopularesContainer = document.getElementById("productos-populares-container");

    // Ocultar productos populares cuando se selecciona una categoría o subcategoría
    function ocultarProductosPopulares() {
        productosPopularesContainer.style.display = "none";  // Ocultar el contenedor de productos populares
    }

    // Mostrar productos populares nuevamente
    function mostrarProductosPopulares() {
        productosPopularesContainer.style.display = "block";  // Mostrar el contenedor de productos populares
    }

    // Obtener categorías y subcategorías
    fetch("http://localhost:8088/Milogar/Controllers/TiendaController.php?action=getCategoriasConSubcategorias")
        .then(response => response.json())
        .then(data => {
            categoriaList.innerHTML = ""; // Limpiar lista antes de agregar los nuevos datos

            if (data.length === 0) {
                categoriaList.innerHTML = `<li class="nav-item">No hay categorías disponibles</li>`;
                return;
            }

            data.forEach(categoria => {
                let subcategoriaHTML = "";

                if (categoria.subcategorias.length > 0) {
                    subcategoriaHTML = `
                        <div class="subcategories-menu">
                            <ul class="nav flex-column ms-3">
                                ${categoria.subcategorias.map(sub => `
                                    <li class="nav-item">
                                        <a href="#" class="nav-link subcategoria-item" data-id="${sub.id}">
                                            ${sub.nombrSubcategoria}
                                        </a>
                                    </li>
                                `).join("")}
                            </ul>
                        </div>
                    `;
                }

                categoriaList.innerHTML += `
                    <li class="nav-item border-bottom w-100 collapsed category-item" aria-expanded="false">
                        <a href="#" class="nav-link category-item-link" data-id="${categoria.id}">
                            ${categoria.nombreCategoria} <i class="feather-icon icon-chevron-right"></i>
                        </a>
                        ${subcategoriaHTML}
                    </li>
                `;
            });

            // Evento para categorías
            document.querySelectorAll(".category-item-link").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const categoriaId = e.target.getAttribute("data-id");
                    ocultarProductosPopulares();  // Ocultar productos populares al seleccionar una categoría
                    cargarProductos(categoriaId, null);  // Cargar productos filtrados por categoría
                });
            });

            // Evento para subcategorías
            document.querySelectorAll(".subcategoria-item").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const subcategoriaId = e.target.getAttribute("data-id");
                    ocultarProductosPopulares();  // Ocultar productos populares al seleccionar una subcategoría
                    cargarProductos(null, subcategoriaId);  // Cargar productos filtrados por subcategoría
                });
            });
        })
        .catch(error => console.error("Error al obtener categorías:", error));

    // Función para cargar productos según la categoría o subcategoría
    function cargarProductos(categoriaId = null, subcategoriaId = null) {
        let url = "http://localhost:8088/Milogar/Controllers/tiendaController.php?action=obtenerProductos";
        if (categoriaId) {
            url += `&categoria_id=${categoriaId}`;
        } else if (subcategoriaId) {
            url += `&subcategoria_id=${subcategoriaId}`;
        }
        // Ocultar el contenedor de resultados de búsqueda
        document.getElementById("resultados-busqueda-container").style.display = "none";

        // Mostrar el contenedor de productos generales
        let productosContainer = document.getElementById("productos-container");
        productosContainer.style.display = "flex";

        fetch(url)
            .then(response => response.json())
            .then(productos => {
                productosContainer.innerHTML = ""; // Limpiar productos anteriores

                if (productos.length === 0) {
                    productosContainer.innerHTML = "<p>No hay productos disponibles para esta categoría o subcategoría.</p>";
                    return;
                }

                productos.forEach(producto => {
                    const rutaImagen = `/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;
                    productosContainer.innerHTML += `
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
                                            <span class="text-dark">$${parseFloat(producto.precio_1).toFixed(2)}</span>
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
                
            })
            .catch(error => console.error("Error al obtener los productos:", error));
    }
});
