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
            const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;

              const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);

              const productHTML = `
                  <div class="col-md-4 mb-4">
                      <div class="card card-product h-100 shadow-sm">
                          <div class="card-body">
                              <div class="text-center position-relative">

                                  ${producto.descuento > 0 ? `
                                      <div class="position-absolute top-0 start-0 p-2">
                                          <span class="badge bg-warning">${producto.descuento}% Desc.</span>
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
                              <p class="text-success fw-bold">Compra 4 y gana ${producto.puntos_otorgados} puntos de canje</p>

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
                                          data-imagen="${imagenUrl}">
                                          <i class="bi bi-cart-plus"></i> Agregar
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              `;
              contenedor.innerHTML += productHTML;
          });
      })
      .catch(error => {
          console.error("❌ Error al obtener productos con puntos:", error);
      });
});
