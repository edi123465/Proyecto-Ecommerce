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
                                                <a href="#" class="btn btn-primary btn-sm add-to-cart" 
                                                   data-id="${producto.id}" 
                                                   data-nombre="${producto.nombreProducto}" 
                                                   data-precio="${parseFloat(producto.precio_1).toFixed(2)}" 
                                                   data-imagen="${rutaImagen}"
                                                   data-descuento="${producto.descuento || 0}">
                                                    <i class="feather feather-plus"></i> Add
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    resultadosDiv.innerHTML = productosHTML;
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
