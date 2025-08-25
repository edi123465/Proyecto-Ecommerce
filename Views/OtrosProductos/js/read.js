document.addEventListener('DOMContentLoaded', () => {
    const inputBuscar = document.getElementById('buscarProducto');
    const form = document.getElementById('formAsignarTalla');

    // Buscar productos por nombre
    inputBuscar.addEventListener('input', manejarBusquedaProducto);

    // Cargar tallas en el select
    cargarTallas();

    // Manejar envío del formulario
    form.addEventListener('submit', enviarFormulario);

    // Cargar productos con tallas en la tabla
    cargarProductosConTallas();
});

/* ─────────────────────────────────────────────────────────── */
/* FUNCIONES DE BÚSQUEDA Y SUGERENCIAS */
/* ─────────────────────────────────────────────────────────── */

function manejarBusquedaProducto() {
    const termino = document.getElementById('buscarProducto').value.trim();
    if (termino.length < 2) {
        limpiarSugerencias();
        return;
    }
    fetchProductoPorNombre(termino);
}

function fetchProductoPorNombre(termino) {
    const formData = new FormData();
    formData.append('action', 'searchProducts');
    formData.append('termino', termino);

    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => mostrarSugerencias(data))
        .catch(err => console.error('Error al buscar producto:', err));
}

function mostrarSugerencias(productos) {
    const contenedor = document.getElementById('sugerenciasProducto');
    contenedor.innerHTML = '';

    if (!productos.length) {
        contenedor.style.display = 'none';
        return;
    }

    productos.forEach(p => {
        const item = document.createElement('a');
        item.classList.add('list-group-item', 'list-group-item-action');
        item.textContent = p.nombreProducto;
        item.addEventListener('click', () => seleccionarProducto(p));
        contenedor.appendChild(item);
    });

    contenedor.style.display = 'block';
}

function seleccionarProducto(producto) {
    document.getElementById('producto_id').value = producto.id;
    document.getElementById('nombreProducto').value = producto.nombreProducto;
    document.getElementById('descripcionProducto').value = producto.descripcionProducto;

    const img = document.getElementById('imagenProducto');
    img.src = `http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${producto.imagen || 'default.png'}`;
    img.style.display = 'block';

    document.getElementById('buscarProducto').value = '';
    limpiarSugerencias();
}

function limpiarSugerencias() {
    const sugerencias = document.getElementById('sugerenciasProducto');
    sugerencias.innerHTML = '';
    sugerencias.style.display = 'none';
}

/* ─────────────────────────────────────────────────────────── */
/* FUNCIONES PARA FORMULARIO Y MODAL */
/* ─────────────────────────────────────────────────────────── */

function cargarTallas() {
    fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=obtenerTallas')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('talla_id');
            if (data.status === 'success') {
                data.data.forEach(talla => {
                    const option = document.createElement('option');
                    option.value = talla.id;
                    option.textContent = talla.talla;
                    select.appendChild(option);
                });
            }
        })
        .catch(err => console.error('Error al cargar tallas:', err));
}

function enviarFormulario(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const data = {
        producto_id: formData.get('producto_id'),
        talla_id: formData.get('talla_id'),
        stock: formData.get('stock')
    };

    fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=asignarTalla', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(res => res.text())
        .then(text => {
            if (!text) throw new Error('Respuesta vacía del servidor');
            const result = JSON.parse(text);

            if (result.status === 'success') {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Talla asignada correctamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    form.reset();
                    document.getElementById('imagenProducto').style.display = 'none';
                    $('#createProductModal').modal('hide');
                    cargarProductosConTallas();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error: ' + result.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(err => {
            console.error('❌ Error al enviar los datos:', err);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al enviar los datos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
}


function limpiarFormularioProducto() {
    document.getElementById('formAsignarTalla').reset();
    document.getElementById('producto_id').value = '';
    document.getElementById('imagenProducto').src = '';
    document.getElementById('imagenProducto').style.display = 'none';
}

/* ─────────────────────────────────────────────────────────── */
/* FUNCIONES DE PRODUCTOS Y EDICIÓN */
/* ─────────────────────────────────────────────────────────── */

let paginaActual = 1;
const limitePorPagina = 10;
let terminoBusqueda = ''; // Para mantener el filtro actual

function cargarProductosConTallas(search = terminoBusqueda, pagina = 1) {
    paginaActual = pagina;
    terminoBusqueda = search;

    const offset = (pagina - 1) * limitePorPagina;
    const url = `http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductosConTallas&search=${encodeURIComponent(search)}&limit=${limitePorPagina}&offset=${offset}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tablaBody = document.querySelector('#productosTallasTable tbody');
            tablaBody.innerHTML = '';

            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(p => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${p.ID}</td>
                        <td>${p.nombreProducto}</td>
                        <td>${p.descripcion}</td>
                        <td>${p.talla}</td>
                        <td>${p.stock}</td>
                        <td><img src="http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${p.imagen || 'default.png'}" width="50" height="50"></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editarProducto(${p.ID})">
                                <i class="fa fa-pencil-alt"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarProductoTalla(${p.ID})">
                                <i class="fa fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    `;
                    tablaBody.appendChild(fila);
                });

                renderizarPaginacion(data.totalPages, pagina);
            } else {
                tablaBody.innerHTML = '<tr><td colspan="7">No se encontraron productos.</td></tr>';
                renderizarPaginacion(1, 1);
            }
        })
        .catch(err => console.error('❌ Error al obtener productos con tallas:', err));
}
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchValue = e.target.value.trim();
    cargarProductosConTallas(searchValue, 1);  // Cargar los productos a medida que se escribe
});

// 🔁 Mostrar todos los productos si el campo está vacío
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchValue = e.target.value.trim();
    if (searchValue === '') {
        cargarProductosConTallas('', 1);  // Si se borra el texto, mostrar todos los productos
    }
});


function renderizarPaginacion(totalPaginas, paginaActual) {
    const contenedor = document.getElementById('pagination');
    contenedor.innerHTML = '';

    const nav = document.createElement('nav');
    const ul = document.createElement('ul');
    ul.className = 'pagination justify-content-center';

    // Botón Anterior
    const anteriorLi = document.createElement('li');
    anteriorLi.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
    const anteriorBtn = document.createElement('button');
    anteriorBtn.className = 'page-link';
    anteriorBtn.textContent = 'Anterior';
    anteriorBtn.onclick = () => {
        if (paginaActual > 1) {
            cargarProductosConTallas('', paginaActual - 1);
        }
    };
    anteriorLi.appendChild(anteriorBtn);
    ul.appendChild(anteriorLi);

    // Botones de página
    for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === paginaActual ? 'active' : ''}`;
        const btn = document.createElement('button');
        btn.className = 'page-link';
        btn.textContent = i;
        btn.onclick = () => {
            cargarProductosConTallas('', i);
        };
        li.appendChild(btn);
        ul.appendChild(li);
    }

    // Botón Siguiente
    const siguienteLi = document.createElement('li');
    siguienteLi.className = `page-item ${paginaActual === totalPaginas ? 'disabled' : ''}`;
    const siguienteBtn = document.createElement('button');
    siguienteBtn.className = 'page-link';
    siguienteBtn.textContent = 'Siguiente';
    siguienteBtn.onclick = () => {
        if (paginaActual < totalPaginas) {
            cargarProductosConTallas('', paginaActual + 1);
        }
    };
    siguienteLi.appendChild(siguienteBtn);
    ul.appendChild(siguienteLi);

    nav.appendChild(ul);
    contenedor.appendChild(nav);
}


// Llamar al cargar la página
cargarProductosConTallas();


function editarProducto(id) {
    fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductoPorIdConTalla&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const p = data.data[0];

                // Llenar campos del modal de edición
                document.getElementById('edit_producto_id').value = p.ID;
                document.getElementById('edit_nombreProducto').value = p.nombreProducto;
                document.getElementById('edit_descripcionProducto').value = p.descripcion;
                document.getElementById('edit_stock').value = p.stock;

                const imagen = document.getElementById('edit_imagenProducto');
                imagen.src = `http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${p.imagen || 'default.png'}`;
                imagen.style.display = 'block';

                // Primero cargar tallas si aún no están cargadas
                fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=obtenerTallas')
                    .then(res => res.json())
                    .then(tallasData => {
                        if (tallasData.status === 'success') {
                            const select = document.getElementById('edit_talla_id');
                            select.innerHTML = '<option value="">Seleccione una talla</option>'; // limpiar

                            tallasData.data.forEach(talla => {
                                const option = document.createElement('option');
                                option.value = talla.id;
                                option.textContent = talla.talla;
                                if (talla.talla === p.talla) {
                                    option.selected = true;
                                }
                                select.appendChild(option);
                            });

                            // Mostrar el modal de edición
                            $('#editProductModal').modal('show');
                        }
                    });
            } else {
                alert('No se pudo cargar el producto: ' + data.message);
            }
        })
        .catch(err => console.error('❌ Error al obtener producto:', err));
}

document.addEventListener('DOMContentLoaded', () => {
    const formEdit = document.getElementById('formEditProduct');
    formEdit.addEventListener('submit', enviarFormularioEdicion);
});

function enviarFormularioEdicion(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const data = {
        producto_id: formData.get('producto_id'),
        talla_id: formData.get('talla_id'),
        stock: formData.get('stock')
    };

    console.log('📦 Datos a enviar al servidor:', data);

    // 🧁 Confirmación con SweetAlert2
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Vas a actualizar el stock de este producto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // ✅ Si confirma, enviamos el fetch
            fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=editarTallaYStock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(result => {
                    if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: 'Producto actualizado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#editProductModal').modal('hide');
                        cargarProductosConTallas();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        });
                    }
                })
                .catch(err => {
                    console.error('❌ Error al actualizar producto:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo contactar al servidor'
                    });
                });
        }
    });
}


function eliminarProductoTalla(productoTallaId) {
    if (!productoTallaId) {
        Swal.fire('Error', 'ID de talla no proporcionado', 'error');
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará la talla del producto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('producto_talla_id', productoTallaId);

            fetch('http://localhost:8080/Milogar/Controllers/ProductoController.php?action=eliminarTalla', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del servidor:", data);
                if (data.status === 'success') {
                    Swal.fire('Eliminado', 'La talla fue eliminada correctamente', 'success');
                    cargarProductosConTallas(); // Refresca la lista
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error al eliminar la talla:', error);
                Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
            });
        }
    });
}


/* ─────────────────────────────────────────────────────────── */
/* ABRIR MODAL DE CREACIÓN */
/* ─────────────────────────────────────────────────────────── */
function abrirModalCrearProducto() {
    limpiarFormularioProducto();
    $('#createProductModal').modal('show');
}
