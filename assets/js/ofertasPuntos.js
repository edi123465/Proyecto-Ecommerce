document.addEventListener("DOMContentLoaded", function () {
  fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=productosConPuntos')
      .then(res => res.json())
      .then(productos => {
          console.log("📦 Productos con puntos:", productos);

          const contenedor = document.getElementById("productos-con-puntos");

          if (!productos.length) {
              contenedor.innerHTML = "<p>No hay productos con puntos disponibles.</p>";
              return;
          }

          productos.forEach(producto => {
            const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

              const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);

              const productHTML = `
                  <div class="col-md-4 mb-4">
                      <div class="card card-product h-100 shadow-sm">
                          <div class="card-body">
                              <div class="text-center position-relative">

                                  ${producto.descuento > 0 ? `
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-danger rounded-circle d-flex justify-content-center align-items-center text-white"
                                                style="width: 60px; height: 60px; font-size: 0.8rem;">
                                                ${producto.descuento}%<br>Desc.
                                            </span>
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

                              <div class="mb-2">
                                  <small class="text-warning">
                                      <i class="bi bi-star-fill"></i>
                                      <i class="bi bi-star-fill"></i>
                                      <i class="bi bi-star-fill"></i>
                                      <i class="bi bi-star-fill"></i>
                                      <i class="bi bi-star-half"></i>
                                  </small>
                                  <span class="text-muted small">4.5(149)</span>
                              </div>
<p class="text-success fw-bold">
    Compra ${producto.cantidad_minima_para_puntos} y gana ${producto.puntos_otorgados} puntos de canje
</p>

                              <div class="d-flex justify-content-between align-items-center mt-3">
                                  <div>
                                      <span class="text-dark">$${precioFinal.toFixed(2)}</span>
                                      ${producto.descuento > 0 ? `<span class="text-decoration-line-through text-muted ms-2">$${producto.precio_1}</span>` : ''}
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
              contenedor.innerHTML += productHTML;
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
      })
      .catch(error => {
          console.error("❌ Error al obtener productos con puntos:", error);
      });
});
