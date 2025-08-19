document.addEventListener("DOMContentLoaded", function () {
    let paginaActual = 1;
    const limitePorPagina = 10;

    // Función para obtener las categorías y mostrar la paginación
    function obtenerCategorias(pagina = 1) {
        paginaActual = pagina; // <--- Agrega esto para mantener la página actual actualizada
        const searchValue = document.getElementById('searchInput').value.trim();
        const offset = (pagina - 1) * limitePorPagina;

        // Log para ver los parámetros antes de hacer el fetch
        console.log(`Obteniendo categorías para la página ${pagina} con offset ${offset} y búsqueda: "${searchValue}"`);

        fetch(`http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=obtenerCategorias&search=${encodeURIComponent(searchValue)}&limit=${limitePorPagina}&page=${pagina}`)
        .then(response => response.json())
            .then(data => {
                // Log para ver los datos que llega del servidor
                console.log('Datos recibidos:', data);

                if (data.status === 'success') {
                    const tbody = document.getElementById('tablaCategoriasBody');
                    tbody.innerHTML = ''; // Limpiamos las filas anteriores

                    // Log para ver los datos de cada categoría
                    data.data.forEach(categoria => {
                        console.log(`Categoria ID: ${categoria.id}, Nombre: ${categoria.nombreCategoria}`);

                        const imagenUrl = categoria.imagen && categoria.imagen.startsWith('http')
                            ? categoria.imagen
                            : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Categorias/' + (categoria.imagen || 'default.png');
                        const estado = (categoria.isActive === 1 || categoria.isActive === '1')
                            ? '<span class="badge bg-success">Activo</span>'
                            : '<span class="badge bg-danger">Inactivo</span>';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${categoria.id}</td>
                            <td>${categoria.nombreCategoria}</td>
                            <td>${categoria.descripcionCategoria}</td>
                            <td>${estado}</td>
                            <td>${categoria.fechaCreacion}</td>
                            <td class="text-center">
                                <img src="${imagenUrl}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="obtenerCategoriaId(${categoria.id})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarCategoria(${categoria.id})">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });

                    // Llamamos a la función para renderizar la paginación
                    renderizarPaginacion(data.totalPages, pagina);
                } else {
                    
                }
            })
            .catch(error => {
                console.error('Error al obtener las categorías:', error);
                alert('Error al obtener las categorías.');
            });
    }

    // Función para renderizar los botones de paginación
    function renderizarPaginacion(totalPages, paginaActual) {
        const contenedorPaginacion = document.getElementById('pagination');
        contenedorPaginacion.innerHTML = '';
    
        console.log(`Renderizando paginación con un total de ${totalPages} páginas`);
    
        // Botón "Anterior"
        const btnAnterior = document.createElement('button');
        btnAnterior.className = 'btn btn-sm btn-outline-secondary m-1';
        btnAnterior.textContent = 'Anterior';
        btnAnterior.disabled = paginaActual === 1;
        btnAnterior.onclick = () => obtenerCategorias(paginaActual - 1);
        contenedorPaginacion.appendChild(btnAnterior);
    
        // Botones de página numerados
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = `btn btn-sm ${i === paginaActual ? 'btn-primary' : 'btn-outline-primary'} m-1`;
            btn.textContent = i;
            btn.onclick = () => obtenerCategorias(i);
            contenedorPaginacion.appendChild(btn);
        }
    
        // Botón "Siguiente"
        const btnSiguiente = document.createElement('button');
        btnSiguiente.className = 'btn btn-sm btn-outline-secondary m-1';
        btnSiguiente.textContent = 'Siguiente';
        btnSiguiente.disabled = paginaActual === totalPages;
        btnSiguiente.onclick = () => obtenerCategorias(paginaActual + 1);
        contenedorPaginacion.appendChild(btnSiguiente);
    }
    

    // Llamar a la función para obtener las categorías al cargar la página
    obtenerCategorias(paginaActual);

    // Filtro de búsqueda
    document.getElementById('searchInput').addEventListener('input', function () {
        obtenerCategorias(1); // Volver a cargar la primera página con el nuevo filtro
    });
});



// Escuchar el evento de envío del formulario
document.getElementById('createCategoryForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el comportamiento por defecto del formulario

    const formData = new FormData(this); // 'this' hace referencia al formulario

    // Opcional: mostrar en consola los datos del formulario
    console.log('Datos del formulario:');
    formData.forEach((value, key) => {
        if (value instanceof File) {
            console.log(`${key}: ${value.name} (${value.size} bytes, ${value.type})`);
        } else {
            console.log(`${key}: ${value}`);
        }
    });

    // Enviar la solicitud
    fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=createCategory', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Categoría creada',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recargar la página después de aceptar
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: data.message || 'No se pudo crear la categoría.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al enviar los datos.',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    });
});


// Función para obtener los datos de la categoría y llenar el formulario
function obtenerCategoriaId(id) {
    console.log(`Obteniendo datos de la categoría con ID: ${id}`);

    // Realizamos la solicitud fetch al controlador para obtener los datos de la categoría por ID
    fetch(`http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=obtenerCategoriaPorId&id=${id}`)
        .then(response => response.json()) // Asumimos que el controlador responde con un JSON
        .then(data => {
            // Verificamos los datos recibidos
            console.log(data);

            if (data && data.status === 'success') {
                // Llenamos los campos del formulario con los datos de la categoría
                document.getElementById('nombreCategoria').value = data.data.nombreCategoria;
                document.getElementById('descripcionCategoria').value = data.data.descripcionCategoria;
                document.getElementById('isActive').value = data.data.isActive;

                // Mostrar la imagen de vista previa si existe
                if (data.data.imagen) {
                    document.getElementById('imagenCategoriaPreview').src = `/assets/imagenesMilogar/Categorias/${data.data.imagen}`;
                } else {
                    document.getElementById('imagenCategoriaPreview').src = ''; // En caso de que no haya imagen
                }

                // Almacenamos el ID en el formulario usando un atributo data-id
                document.getElementById('formEditarCategoria').dataset.id = id;

                // Abrir el modal de edición
                $('#modalEditarCategoria').modal('show');
            } else {
                console.error("Error al cargar los datos de la categoría.");
            }
        })
        .catch(error => {
            console.error("Hubo un error en la solicitud:", error);
        });
}

function guardarCambios() {
    const form = document.getElementById('formEditarCategoria');
    const id = form.dataset.id;

    if (!id) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo obtener el ID de la categoría.',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
        return;
    }

    const nombre = document.getElementById('nombreCategoria').value;
    const descripcion = document.getElementById('descripcionCategoria').value;
    const estado = document.getElementById('isActive').value;
    const imagenInput = document.getElementById('imagenCategoria');
    const imagen = imagenInput.files[0];

    Swal.fire({
        title: '¿Actualizar categoría?',
        text: '¿Estás seguro de que deseas guardar los cambios?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745', // Verde para confirmar
        cancelButtonColor: '#007bff', // Azul para cancelar
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('nombreCategoria', nombre);
            formData.append('descripcionCategoria', descripcion);
            formData.append('isActive', estado);
            if (imagen) {
                formData.append('imagenCategoria', imagen);
            }

            fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=editarCategoria', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                if (result.status === 'success') {
                    Swal.fire({
                        title: 'Actualizado',
                        text: 'Categoría actualizada correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        $('#modalEditarCategoria').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar la categoría.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al enviar los datos.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
        }
    });
}


function eliminarCategoria(id) {
    Swal.fire({
        title: '¿Eliminar categoría?',
        text: 'Esta acción no se puede deshacer. ¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Rojo para eliminar
        cancelButtonColor: '#6c757d',  // Gris para cancelar
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=eliminarCategoria', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                if (result.status === 'success') {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'Categoría eliminada correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo eliminar la categoría.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error("Hubo un error:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al enviar la solicitud.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
        }
    });
}
