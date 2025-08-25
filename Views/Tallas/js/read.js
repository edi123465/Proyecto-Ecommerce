// Función para cargar las tallas y llenar la tabla
function cargarTallas() {
    // Realizamos el fetch a la URL del controlador, enviando un parámetro 'action' para especificar la operación
    fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=obtenerTallas')  // Se agrega el parámetro 'action'
        .then(response => response.json()) // Convertimos la respuesta en formato JSON
        .then(data => {
            // Verificamos si la respuesta tiene datos
            if (data.status === 'success') {
                // Obtenemos el cuerpo de la tabla
                const tablaBody = document.getElementById('tablaTallasBody');
                // Limpiamos el cuerpo de la tabla antes de llenarlo
                tablaBody.innerHTML = '';

                // Iteramos sobre cada talla y la agregamos a la tabla
                data.data.forEach(talla => {
                    // Creamos una nueva fila de tabla
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${talla.id}</td>
                            <td>${talla.talla}</td>
                            <td>
                                <!-- Aquí puedes agregar botones o enlaces para editar y eliminar -->
                                <button class="btn btn-warning btn-sm" onclick="editarTalla(${talla.id}, '${talla.talla}')">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarTalla(${talla.id})">Eliminar</button>
                            </td>
                        `;
                    // Agregamos la fila al cuerpo de la tabla
                    tablaBody.appendChild(row);
                });
            } else {
                alert(data.message); // Mostramos el mensaje si no hay tallas
            }
        })
        .catch(error => {
            console.error('Error al obtener las tallas:', error);
            alert('Hubo un error al cargar las tallas.');
        });
}

// Llamamos a la función para cargar las tallas cuando la página se cargue
window.onload = function () {
    cargarTallas();
};

document.getElementById("createTallaForm").addEventListener("submit", function (event) {
    event.preventDefault();  // Prevenir el envío tradicional del formulario

    // Capturar los datos del formulario
    const formData = new FormData(this);

    // Realizar el fetch para insertar la talla
    fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=insertarTalla', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#createTallaModal').modal('hide');  // Cierra el modal
                    location.reload();  // Recarga la página
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error: ' + data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al procesar la solicitud.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
});

// Función para cargar los datos de la talla en el modal de edición
function editarTalla(id) {
    console.log(`Cargando los datos de la talla con ID: ${id}`); // Log para verificar el ID que se pasa

    // Realizamos el fetch para obtener la talla por su ID
    fetch(`http://localhost:8080/Milogar/Controllers/TallasController.php?action=obtenerTallaPorId&id=${id}`)
        .then(response => {
            console.log('Respuesta del servidor:', response); // Log para verificar la respuesta del servidor
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos del servidor:', data); // Log para verificar los datos recibidos

            if (data.status === 'success') {
                // Si la talla existe, llenamos los campos del modal con los datos
                const talla = data.data;

                console.log('Talla encontrada:', talla); // Log para verificar los datos de la talla

                document.getElementById('tallaNombre').value = talla.talla; // Llena el campo con el nombre de la talla
                document.getElementById('formEditarTalla').setAttribute('data-id', talla.id); // Establece el ID de la talla en el formulario

                // Muestra el modal
                $('#modalEditarTalla').modal('show');
            } else {
                alert('Error al cargar la talla');
            }
        })
        .catch(error => {
            console.error('Error al obtener la talla:', error); // Log para ver el error
            alert('Hubo un problema al obtener los datos de la talla.');
        });
}

function guardarCambiosTalla() {
    const tallaId = document.getElementById('formEditarTalla').getAttribute('data-id');
    const tallaNombre = document.getElementById('tallaNombre').value;

    if (tallaNombre.trim() === '') {
        Swal.fire({
            title: 'Advertencia',
            text: 'Por favor, ingresa el nombre de la talla.',
            icon: 'warning',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    Swal.fire({
        title: 'Confirmación',
        text: '¿Estás seguro de que deseas guardar los cambios de esta talla?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=actualizarTalla', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: tallaId, talla: tallaNombre }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Talla actualizada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $('#modalEditarTalla').modal('hide');
                        location.reload(); // Recargar la página
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Error al actualizar la talla.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error al actualizar la talla:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al guardar los cambios.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        }
    });
}


function eliminarTalla(id) {
    Swal.fire({
        title: 'Confirmación',
        text: '¿Estás seguro de que deseas eliminar esta talla?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('http://localhost:8080/Milogar/Controllers/TallasController.php?action=eliminarTalla', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Talla eliminada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();  // Recargar la página
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Error al eliminar la talla.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error al eliminar la talla:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al intentar eliminar.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        }
    });
}
