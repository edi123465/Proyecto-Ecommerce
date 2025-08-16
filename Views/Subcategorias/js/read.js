document.addEventListener('DOMContentLoaded', function () {
    // Función para obtener las subcategorías desde el servidor
    function obtenerSubcategorias() {
        fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=obtenerSubcategorias')
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    // Si la respuesta es exitosa, llenamos la tabla con las subcategorías
                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = ''; // Limpiamos el contenido de la tabla
                    result.data.forEach(subcategoria => {
                        const row = document.createElement('tr');
                        const estado = (subcategoria.isActive === 1 || subcategoria.isActive === '1')
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-danger">Inactivo</span>';
                        row.innerHTML = `
                            <td>${subcategoria.id}</td>
                            <td>${subcategoria.nombrSubcategoria}</td>
                            <td>${subcategoria.descripcionSubcategoria}</td>
                            <td>${subcategoria.nombreCategoria}</td> <!-- Mostramos el nombre de la categoría -->
                            <td>${estado}</td>
                            <td>${subcategoria.fechaCreacion}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarSubcategoria(${subcategoria.id})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarSubcategoria(${subcategoria.id})">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    alert(result.message); // Si hay algún error
                }
            })
            .catch(error => {
                console.error('Error al obtener las subcategorías:', error);
                alert('Hubo un error al obtener las subcategorías.');
            });
    }

    // Llamar a la función para obtener las subcategorías cuando la página cargue
    obtenerSubcategorias();
});


document.addEventListener('DOMContentLoaded', function () {
    // Función para obtener las categorías desde el servidor
    function obtenerCategorias() {
        fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=obtenerCategorias')
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const selectCategory = document.getElementById('categoryId');
                    result.data.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id;
                        option.textContent = categoria.nombreCategoria; // Asegúrate de que el nombre de la categoría sea 'nombre' en el JSON
                        selectCategory.appendChild(option);
                    });
                } else {
                    alert(result.message); // Si hay algún error
                }
            })
            .catch(error => {
                console.error('Error al obtener las categorías:', error);
                alert('Hubo un error al obtener las categorías.');
            });
    }

    // Llamar a la función para obtener las categorías cuando la página cargue
    obtenerCategorias();
});

// Evento de click para el botón "Crear Subcategoría"
document.getElementById('submitCreateSubCategory').addEventListener('click', function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    // Obtener los datos del formulario
    const subCategoryName = document.getElementById('subCategoryName').value;
    const subCategoryDescription = document.getElementById('subCategoryDescription').value;
    const categoryId = document.getElementById('categoryId').value;
    const isActive = document.getElementById('isActive').value;

    // Crear el objeto de datos
    const data = {
        nombre: subCategoryName,
        descripcion: subCategoryDescription,
        categoria_id: categoryId,
        isActive: isActive
    };

    // Log de los datos que se enviarán
    console.log("Datos a insertar:", data);

    // Realizar la solicitud fetch para insertar la subcategoría
    fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=insertarSubcategoria', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data) // Convertir los datos a JSON
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert(result.message); // Mostrar el mensaje de éxito
                $('#createSubCategoryModal').modal('hide'); // Cerrar el modal
                location.reload(); // Recargar la página después de la inserción exitosa
            } else {
                alert(result.message); // Mostrar el mensaje de error
            }
        })
        .catch(error => {
            console.error('Error al crear la subcategoría:', error);
            alert('Hubo un error al crear la subcategoría.');
        });
});

// Función para editar subcategoría
function editarSubcategoria(id) {
    console.log(`Editar subcategoría con ID: ${id}`);

    // Primero, obtener las categorías para llenar el select
    fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=obtenerCategorias')
        .then(response => response.json())
        .then(data => {
            console.log('Categorías obtenidas:', data); // Verifica los datos obtenidos
            if (data.status === 'success') {
                // Verificar que 'data.data' sea un arreglo
                if (Array.isArray(data.data)) {
                    // Limpiar el select de categorías antes de agregar nuevas opciones
                    const categorySelect = document.getElementById('editCategoryId');
                    categorySelect.innerHTML = ''; // Limpiar el select

                    // Crear una opción por defecto
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.text = 'Selecciona una categoría';
                    categorySelect.appendChild(defaultOption);

                    // Agregar las opciones de categorías
                    data.data.forEach(categoria => {
                        console.log('Categoría:', categoria); // Verifica cada categoría que se agrega
                        const option = document.createElement('option');
                        option.value = categoria.id;  // Asignar el ID de la categoría
                        option.text = categoria.nombreCategoria; // Mostrar el nombre de la categoría
                        categorySelect.appendChild(option);
                    });
                } else {
                    alert('Las categorías no están disponibles o no son válidas.');
                }

                // Luego de cargar las categorías, obtener la subcategoría
                fetch(`http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=obtenerSubcategoriaId&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);  // Agrega esto para ver lo que devuelve el servidor

                        if (data.status === 'success') {
                            // Llenar los campos del modal con los datos obtenidos
                            document.getElementById('editSubCategoryId').value = data.subcategoria.id;
                            document.getElementById('editSubCategoryName').value = data.subcategoria.nombrSubcategoria;
                            document.getElementById('editSubCategoryDescription').value = data.subcategoria.descripcionSubcategoria;

                            // Seleccionar la categoría correspondiente
                            document.getElementById('editCategoryId').value = data.subcategoria.categoria_id;

                            document.getElementById('editIsActive').value = data.subcategoria.isActive;

                            // Mostrar el modal
                            $('#editSubCategoryModal').modal('show');
                        } else {
                            alert('Error al obtener los datos de la subcategoría.');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los datos de la subcategoría:', error);
                        alert('Hubo un error al obtener los datos de la subcategoría.');
                    });
            } else {
                alert('Error al obtener las categorías.');
            }
        })
        .catch(error => {
            console.error('Error al obtener las categorías:', error);
            alert('Hubo un error al obtener las categorías.');
        });
}

document.getElementById('submitEditSubCategory').addEventListener('click', function (event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

    // Recoger los datos del formulario
    const id = document.getElementById('editSubCategoryId').value;
    const nombre = document.getElementById('editSubCategoryName').value;
    const descripcion = document.getElementById('editSubCategoryDescription').value;
    const categoriaId = document.getElementById('editCategoryId').value;
    const isActive = document.getElementById('editIsActive').value; // Obtener valor del dropdown

    // Depurar: Verificar el valor de isActive
    console.log('Estado (isActive):', isActive);

    // Crear un objeto con los datos
    const subCategoriaData = {
        id: id,
        nombre: nombre,
        descripcion: descripcion,
        categoriaId: categoriaId,
        isActive: isActive // Asegúrate de que este valor sea correctamente pasado
    };

    // Mostrar un cuadro de confirmación antes de proceder con la edición
    const confirmEdit = confirm('¿Estás seguro de que quieres editar esta subcategoría?');

    if (confirmEdit) {
        // Si el usuario confirma, enviar los datos al servidor para actualizar la subcategoría
        fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=editarSubcategoria', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(subCategoriaData) // Enviar los datos en formato JSON
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.status === 'success') {
                    alert('Subcategoría actualizada exitosamente');
                    $('#editSubCategoryModal').modal('hide'); // Cerrar el modal
                    // Recargar la página después de la actualización
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al actualizar la subcategoría.');
                }
            })
            .catch(error => {
                console.error('Error al actualizar la subcategoría:', error);
                alert('Hubo un error al actualizar la subcategoría.');
            });
    } else {
        // Si el usuario cancela la acción, no hacer nada
        console.log('Edición cancelada');
    }
});




function eliminarSubcategoria(id) {
    // Confirmación antes de eliminar
    const confirmDelete = confirm('¿Estás seguro de que deseas eliminar esta subcategoría?');

    if (confirmDelete) {
        fetch('http://localhost:8080/Milogar/Controllers/SubcategoriaController.php?action=eliminarSubcategoria&id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Subcategoría eliminada exitosamente');
                    // Opcional: Recargar o actualizar la tabla
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al eliminar la subcategoría.');
                }
            })
            .catch(error => {
                console.error('Error al eliminar la subcategoría:', error);
                alert('Hubo un error al eliminar la subcategoría.');
            });
    } else {
        console.log('Eliminación cancelada');
    }
}

