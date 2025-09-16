document.addEventListener("DOMContentLoaded", function () {
    const contenedor = document.getElementById("productos-con-puntos");
    const paginacionContenedor = document.getElementById("paginacion-productos");
    let paginaActual = 1;

    function crearPaginacion(pagina, totalPaginas) {
        paginacionContenedor.innerHTML = "";

        if (totalPaginas <= 1) return;

        const nav = document.createElement("nav");
        const ul = document.createElement("ul");
        ul.className = "pagination justify-content-center flex-wrap gap-2";

        // Botón "Anterior"
        const liPrev = document.createElement("li");
        liPrev.className = `page-item ${pagina === 1 ? 'disabled' : ''}`;
        liPrev.innerHTML = `
            <a class="page-link mx-1 rounded-3" href="#" aria-label="Anterior">
                <i class="feather-icon icon-chevron-left"></i>
            </a>
        `;
        liPrev.addEventListener("click", (e) => {
            e.preventDefault();
            if (pagina > 1) cargarProductos(pagina - 1);
        });
        ul.appendChild(liPrev);

        // Botones numerados
        for (let i = 1; i <= totalPaginas; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${i === pagina ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link mx-1 rounded-3 ${i === pagina ? '' : 'text-body'}" href="#">${i}</a>`;
            li.addEventListener("click", (e) => {
                e.preventDefault();
                cargarProductos(i);
            });
            ul.appendChild(li);
        }

        // Botón "Siguiente"
        const liNext = document.createElement("li");
        liNext.className = `page-item ${pagina === totalPaginas ? 'disabled' : ''}`;
        liNext.innerHTML = `
            <a class="page-link mx-1 rounded-3" href="#" aria-label="Siguiente">
                <i class="feather-icon icon-chevron-right"></i>
            </a>
        `;
        liNext.addEventListener("click", (e) => {
            e.preventDefault();
            if (pagina < totalPaginas) cargarProductos(pagina + 1);
        });
        ul.appendChild(liNext);

        nav.appendChild(ul);
        paginacionContenedor.appendChild(nav);

        // Feather icons refresh
        if (window.feather) feather.replace();
    }

    function cargarProductos(pagina = 1) {
        fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=productosConPuntos&pagina=${pagina}`)
            .then(res => res.json())
            .then(data => {
                const productos = data.productos;
                const totalPaginas = data.totalPaginas;

                contenedor.innerHTML = ""; // Limpiar productos
                if (!productos.length) {
                    contenedor.innerHTML = "<p>No hay productos con puntos disponibles.</p>";
                    paginacionContenedor.innerHTML = "";
                    return;
                }

                productos.forEach(producto => {
                    const imagenUrl = producto.imagen.startsWith('http') 
                        ? producto.imagen 
                        : 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

                    const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);

                    contenedor.innerHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card card-product h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                                    <h2 class="fs-6">${producto.nombreProducto}</h2>
                                    <p class="text-success fw-bold">
                                        Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos
                                    </p>
                                    <span class="text-dark">$${precioFinal.toFixed(2)}</span>
                                    ${producto.descuento > 0 ? `<span class="text-decoration-line-through text-muted ms-2">$${producto.precio_1}</span>` : ''}
                                    <div class="mt-2">
                                        <button class="btn btn-primary btn-sm add-to-cart"
                                            data-id="${producto.id}" 
                                            data-nombre="${producto.nombreProducto}"
                                            data-precio="${producto.precio_1}"
                                            data-descuento="${producto.descuento}"
                                            data-imagen="${imagenUrl}"
                                            data-puntos="${producto.puntos_otorgados}"
                                            data-minimo="${producto.cantidad_minima_para_puntos}">
                                            Agregar al carrito
                                        </button>
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


                // Crear paginación con estilo
                crearPaginacion(pagina, totalPaginas);
            })
            .catch(error => console.error("❌ Error al cargar productos con puntos:", error));
    }

    // Cargar la primera página
    cargarProductos(paginaActual);
});
