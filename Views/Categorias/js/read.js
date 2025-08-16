document.addEventListener("DOMContentLoaded", function () {
    // Función para obtener las categorías desde el servidor
    function obtenerCategorias() {
        fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=obtenerCategorias')
            .then(response => response.json()) // Parseamos la respuesta JSON
            .then(data => {
                if (data.status === 'success') {
                    // Recorremos las categorías y las agregamos a la tabla
                    const tbody = document.getElementById('tablaCategoriasBody');
                    tbody.innerHTML = ''; // Limpiamos las filas anteriores

                    data.data.forEach(categoria => {
                        // Definir la URL de la imagen de la categoría, con un valor por defecto si no tiene imagen
                        const imagenUrl = categoria.imagen && categoria.imagen.startsWith('http')
                            ? categoria.imagen
                            : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Categorias/' + (categoria.imagen || 'default.png');
                        const estado = (categoria.isActive === 1 || categoria.isActive === '1')
                            ? '<span class="badge bg-success">Activo</span>'
                            : '<span class="badge bg-danger">Inactivo</span>';

                        const tr = document.createElement('tr');
                        console.log(imagenUrl);
                        console.log(categoria.imagen); // Verifica el nombre de la imagen que se está obteniendo

                        // Creamos las celdas para cada categoría
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

                } else {
                    alert('No se pudieron obtener las categorías.');
                }
            })
            .catch(error => {
                console.error('Error al obtener las categorías:', error);
                alert('Error al obtener las categorías.');
            });
    }

    // Llamar a la función para obtener las categorías al cargar la página
    obtenerCategorias();
});


// Escuchar el evento de envío del formulario
document.getElementById('createCategoryForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el comportamiento por defecto del formulario (recargar la página)

    // Crear un objeto FormData para manejar los datos del formulario, incluyendo los archivos
    const formData = new FormData(this); // 'this' hace referencia al formulario

    // Mostrar los datos recogidos en la consola para depuración
    console.log('Datos del formulario:');
    formData.forEach((value, key) => {
        if (value instanceof File) {
            // Si el valor es un archivo, muestra información del archivo
            console.log(`${key}: ${value.name} (${value.size} bytes, ${value.type})`);
        } else {
            // Si el valor no es un archivo, simplemente muestra el valor
            console.log(`${key}: ${value}`);
        }
    });

    // Enviar la solicitud Fetch
    fetch('http://localhost:8080/Milogar/controllers/CategoriaController.php?action=createCategory', {
        method: 'POST',
        body: formData // Pasar los datos del formulario, incluyendo la imagen
    })
        .then(response => response.json())  // Convertir la respuesta del servidor a formato JSON
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito si la categoría se creó correctamente
                alert(data.message);
                window.location.reload(); // Recargar la página para mostrar la nueva categoría
            } else {
                // Mostrar mensaje de error si hubo algún problema
                alert(data.message);
            }
        })
        .catch(error => {
            // Capturar errores si la solicitud falla
            console.error('Error:', error);
            alert('Hubo un problema con la solicitud');
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
                    document.getElementById('imagenCategoriaPreview').src = `http://localhost:8080/Milogar/assets/imagenesMilogar/Categorias/${data.data.imagen}`;
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

// Función para guardar los cambios de la categoría
function guardarCambios() {
    const form = document.getElementById('formEditarCategoria');
    const id = form.dataset.id; // Obtenemos el ID desde el atributo data-id del formulario

    if (!id) {
        alert("No se pudo obtener el ID de la categoría.");
        return;
    }

    // Obtenemos los valores del formulario
    const nombre = document.getElementById('nombreCategoria').value;
    const descripcion = document.getElementById('descripcionCategoria').value;
    const estado = document.getElementById('isActive').value;
    const imagenInput = document.getElementById('imagenCategoria');
    const imagen = imagenInput.files[0];

    // Preguntar al usuario si está seguro de guardar los cambios
    const confirmacion = confirm('¿Estás seguro de que deseas actualizar la categoría?');

    // Si el usuario hace clic en "Cancelar", no proceder con la actualización
    if (!confirmacion) {
        return; // Detener el proceso
    }

    // Crear un objeto FormData para enviar los datos
    const formData = new FormData();
    formData.append('id', id); // Incluimos el ID de la categoría
    formData.append('nombreCategoria', nombre);
    formData.append('descripcionCategoria', descripcion);
    formData.append('isActive', estado);

    if (imagen) {
        formData.append('imagenCategoria', imagen); // Adjuntar la imagen solo si fue seleccionada
    }

    // Mostrar los datos que se enviarán al servidor en la consola
    console.log('Datos a enviar al servidor:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }

    // Enviamos la solicitud fetch al servidor para actualizar la categoría
    fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=editarCategoria', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);

            if (result.status === 'success') {
                alert('Categoría actualizada correctamente.');
                $('#modalEditarCategoria').modal('hide'); // Cerrar el modal
                location.reload(); // Recargar la página para reflejar los cambios (opcional)
            } else {
                alert('Error al actualizar la categoría. Por favor, intenta de nuevo.');
            }
        })
        .catch(error => {
            console.error("Hubo un error al enviar los datos:", error);
        });
}


// Función para eliminar categoría
function eliminarCategoria(id) {
    // Confirmación antes de eliminar
    const confirmacion = confirm('¿Estás seguro de que deseas eliminar esta categoría?');
    
    if (!confirmacion) {
        return; // Detener el proceso si el usuario cancela
    }

    // Crear el objeto FormData para enviar el ID de la categoría
    const formData = new FormData();
    formData.append('id', id); // Añadir el ID de la categoría a eliminar

    // Enviar la solicitud fetch al servidor para eliminar la categoría
    fetch('http://localhost:8080/Milogar/Controllers/CategoriaController.php?action=eliminarCategoria', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())  // Parsear la respuesta JSON
        .then(result => {
            console.log(result);

            if (result.status === 'success') {
                alert('Categoría eliminada correctamente.');
                // Recargar la página o actualizar el UI para reflejar el cambio
                location.reload();
            } else {
                alert('Error al eliminar la categoría. Por favor, intenta de nuevo.');
            }
        })
        .catch(error => {
            console.error("Hubo un error al enviar los datos:", error);
        });
}
