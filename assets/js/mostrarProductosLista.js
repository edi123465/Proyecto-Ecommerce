document.addEventListener("DOMContentLoaded", function () {
    const toggleCategoriasBtn = document.getElementById("toggleCategorias");
    const categoriasMenuMobile = document.getElementById("categoryMenuMobile");
    const categoriaListDesktop = document.getElementById("categoryMenuDesktop");
    const productosContainer = document.getElementById("productos-container");
    const productosPopularesContainer = document.getElementById("productos-populares-container");

    // Alternar menú en móviles
    toggleCategoriasBtn.addEventListener("click", function () {
        categoriasMenuMobile.classList.toggle("d-none");
    });

    function ocultarProductosPopulares() {
        productosPopularesContainer.style.display = "none";
    }

    function mostrarProductosPopulares() {
        productosPopularesContainer.style.display = "block";
    }

    fetch("http://localhost:8080/Milogar/Controllers/TiendaController.php?action=getCategoriasConSubcategorias")
        .then(response => response.json())
        .then(data => {
            categoriaListDesktop.innerHTML = "";
            categoriasMenuMobile.innerHTML = "";

            if (data.length === 0) {
                const emptyHTML = `<li class="nav-item">No hay categorías disponibles</li>`;
                categoriaListDesktop.innerHTML = emptyHTML;
                categoriasMenuMobile.innerHTML = emptyHTML;
                return;
            }

            data.forEach(categoria => {
                let subHTML = "";
                if (categoria.subcategorias.length > 0) {
                    subHTML = `
                        <div class="subcategorias-wrapper d-none ms-3 mt-2">
                            <ul class="nav flex-column">
                                ${categoria.subcategorias.map(sub => `
                                    <li class="nav-item">
                                        <a href="#" class="nav-link subcategoria-item" data-id="${sub.id}">
                                            <i class="feather-icon icon-chevron-right me-2"></i>${sub.nombrSubcategoria}
                                        </a>
                                    </li>
                                `).join("")}
                            </ul>
                        </div>
                    `;
                }

                const categoryHTML = `
                    <li class="nav-item category-item">
                        <a href="#" class="nav-link category-item-link" data-id="${categoria.id}">
                            <i class="feather-icon icon-folder me-2"></i>${categoria.nombreCategoria}
                        </a>
                        ${subHTML}
                    </li>
                `;

                // Insertar en ambos menús
                categoriaListDesktop.innerHTML += categoryHTML;
                categoriasMenuMobile.innerHTML += categoryHTML;
            });

            function clearActiveLinks() {
                document.querySelectorAll(".category-item-link, .subcategoria-item").forEach(link => {
                    link.classList.remove("active");
                });
            }

            function agregarEventos() {
                document.querySelectorAll(".category-item-link").forEach(link => {
                    link.addEventListener("click", function (e) {
                        e.preventDefault();
                        const categoriaId = e.target.getAttribute("data-id");
                        ocultarProductosPopulares();
                        cargarProductos(categoriaId, null);
                        clearActiveLinks();
                        e.target.classList.add("active");

                        document.querySelectorAll(".subcategorias-wrapper").forEach(sub => {
                            sub.classList.add("d-none");
                        });

                        const subMenu = e.target.closest(".nav-item").querySelector(".subcategorias-wrapper");
                        if (subMenu) {
                            subMenu.classList.remove("d-none");
                        }

                        productosContainer.scrollIntoView({ behavior: "smooth" });
                    });
                });

                document.querySelectorAll(".subcategoria-item").forEach(link => {
                    link.addEventListener("click", function (e) {
                        e.preventDefault();
                        const subcategoriaId = e.target.getAttribute("data-id");
                        ocultarProductosPopulares();
                        cargarProductos(null, subcategoriaId);
                        clearActiveLinks();
                        e.target.classList.add("active");
                        productosContainer.scrollIntoView({ behavior: "smooth" });
                    });
                });
            }

            agregarEventos();
        })
        .catch(error => console.error("Error al obtener categorías:", error));


    function cargarProductos(categoriaId = null, subcategoriaId = null) {
        let url = "http://localhost:8080/Milogar/Controllers/TiendaController.php?action=obtenerProductos";
        if (categoriaId) {
            url += `&categoria_id=${categoriaId}`;
        } else if (subcategoriaId) {
            url += `&subcategoria_id=${subcategoriaId}`;
        }

        // Ocultar el contenedor de búsqueda
        document.getElementById("resultados-busqueda-container").style.display = "none";

        const userRole = document.getElementById('role').getAttribute('data-role');
        const isAdmin = userRole === 'Administrador';
        const usuarioSesion = !!userRole; // Si hay rol, se asume sesión iniciada

        productosContainer.style.display = "flex";
        productosContainer.innerHTML = ""; // Limpiar productos anteriores

        fetch(url)
            .then(response => response.json())
            .then(productos => {
                if (productos.length === 0) {
                    productosContainer.innerHTML = "<p>No hay productos disponibles para esta categoría o subcategoría.</p>";
                    return;
                }

                productos.forEach(producto => {
                    const rutaImagen = `/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;
                    const precioFinal = producto.precio_1 - (producto.precio_1 * producto.descuento / 100);

                    const productoHTML = `
                    <div class="col">
                        <div class="card card-product">
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

                                    ${isAdmin ? `
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <a href="#!" class="text-decoration-none text-primary" onclick="editarProducto(${producto.id})">
                                                <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i>
                                            </a>
                                        </div>` : ''}

                                    <a href="#!">
                                        <img src="${rutaImagen}" alt="${producto.nombreProducto}" class="mb-3 img-fluid">
                                    </a>
                                    <br><br><br>
                                    <a href="#!" class="btn btn-success btn-sm w-100 position-absolute bottom-0 start-0 mb-3"
                                        data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}" style="border-radius: 12px;">
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
                                    </p>` : ''}

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
                                        </div>` : ''}
                                </div>

                                <div class="mt-3">
                                    <h6>Deja tu comentario:</h6>
                                    <textarea id="comentarioss-${producto.id}" class="form-control mb-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                    <select id="calificacionn-${producto.id}" class="form-select mb-2">
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                        <option value="4">⭐️⭐️⭐️⭐️</option>
                                        <option value="3">⭐️⭐️⭐️</option>
                                        <option value="2">⭐️⭐️</option>
                                        <option value="1">⭐️</option>
                                    </select>
                                    <button onclick="enviarComentarioss(${producto.id})" class="btn btn-outline-primary btn-sm">Enviar comentario</button>

                                    <!-- Contenedor de comentarios DEBAJO del formulario -->
                                    <div id="comentarios-${producto.id}" class="comentarios mt-3"></div>
                                </div>

                            </div>

                        </div>
                    </div>
                `;
                    productosContainer.innerHTML += productoHTML;

                    const testContenedor = document.getElementById(`comentarios-lista-${producto.id}`);
                    console.log(`Contenedor de comentarios para producto ${producto.id}:`, testContenedor);
                    cargarComentariosActivos(producto.id);

                });




            })
            .catch(error => {
                console.error("Error al cargar productos:", error);
                productosContainer.innerHTML = "<p>Error al cargar productos.</p>";
            });



        function cargarComentariosActivos(productoId) {
    console.log(`Iniciando carga de comentarios para producto ID: ${productoId}`);

    fetch(`http://localhost:8080/Milogar/Controllers/TiendaController.php?action=getComentariosPorProducto&producto_id=${productoId}`)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const contenedorComentarios = document.querySelector(`#comentarios-${productoId}`);

            if (!contenedorComentarios) {
                console.warn(`No se encontró el contenedor para comentarios con ID: comentarios-${productoId}`);
                return;
            }

            contenedorComentarios.innerHTML = ''; // Limpiar antes de insertar

            if (data.success) {
                const comentarios = data.data.sort((a, b) => new Date(b.fecha) - new Date(a.fecha)); // ordenar más recientes primero

                if (comentarios.length > 0) {
                    // Mostrar el comentario más reciente
                    const ultimoComentario = comentarios[0];
                    const divComentario = document.createElement('div');
                    divComentario.className = 'comentario border rounded p-2 mb-2';
                    divComentario.innerHTML = `
                        <p><strong>${ultimoComentario.nombreUsuario}</strong> <small class="text-muted">(${new Date(ultimoComentario.fecha).toLocaleString()})</small></p>
                        <p>${ultimoComentario.comentario}</p>
                        <p>Calificación: ${'⭐️'.repeat(ultimoComentario.calificacion)} (${ultimoComentario.calificacion}/5)</p>
                    `;
                    contenedorComentarios.appendChild(divComentario);

                    // Mostrar el botón solo si hay más de un comentario y no existe
                    if (comentarios.length > 1 && !contenedorComentarios.querySelector('.btn-ver-comentarios')) {
                        const boton = document.createElement('button');
                        boton.className = 'btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1 mt-2 btn-ver-comentarios';
                        boton.setAttribute('onclick', `mostrarModalTodosComentarios(${productoId})`);
                        boton.innerHTML = '<i class="bi bi-chat-dots"></i> Ver todos los comentarios';
                        contenedorComentarios.appendChild(boton);
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



    }

});

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
function enviarComentarioss(productoId, event) {
    if (event) event.preventDefault();

    const userId = usuarioSesion;
    const textarea = document.getElementById(`comentarioss-${productoId}`);
    const comentario = textarea ? textarea.value.trim() : '';
    const calificacion = document.getElementById(`calificacionn-${productoId}`).value;
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
                document.getElementById(`comentarioss-${productoId}`).value = '';
                document.getElementById(`calificacionn-${productoId}`).value = '5';
                // ✅ Recargar los comentarios activos en la tarjeta
                if (typeof cargarComentariosActivos === 'function') {
                    cargarComentariosActivos(productoId);
                }
                // ✅ Agregar dinámicamente el nuevo comentario
                const nuevoComentario = {
                    nombreUsuario: data.nombreUsuario || 'Tú',
                    fecha: data.fecha || new Date().toLocaleDateString(),
                    comentario: comentario,
                    calificacion: parseInt(calificacion)
                };

                const contenedor = document.getElementById(`comentarios-${productoId}`);
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
                    // Después de prepend del nuevo comentario
if (contenedor.querySelectorAll('.comentario').length > 1) {
    // Verificar si ya existe el botón
    if (!contenedor.querySelector('.btn-ver-comentarios')) {
        const boton = document.createElement('button');
        boton.className = 'btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 py-1 mt-2 btn-ver-comentarios';
        boton.setAttribute('onclick', `mostrarModalTodosComentarios(${productoId})`);
        boton.innerHTML = '<i class="bi bi-chat-dots"></i> Ver todos los comentarios';
        contenedor.appendChild(boton);
    }
}
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

function renderizarComentarios(productoId, comentarios) {
    const contenedor = document.getElementById(`comentarios-${productoId}`);

    if (!contenedor) {
        console.warn(`No se encontró contenedor para comentarios del producto ${productoId}`);
        return;
    }

    contenedor.innerHTML = ""; // Limpia comentarios anteriores si los hay

    comentarios.forEach(comentario => {
        const div = document.createElement("div");
        div.className = "comentario";
        div.innerHTML = `
            <p><strong>${comentario.nombreUsuario}</strong> (${comentario.fecha})</p>
            <p>⭐ Calificación: ${comentario.calificacion}</p>
            <p>${comentario.comentario}</p>
        `;
        contenedor.appendChild(div);
    });
}
