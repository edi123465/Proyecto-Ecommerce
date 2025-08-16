document.addEventListener("DOMContentLoaded", function () {
    // Cuando se abre el modal, cargar las categorías y subcategorías
    $('#createProductModal').on('show.bs.modal', function () {
        // Llamada a la API para obtener las categorías
        fetch('http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerCategorias')
            .then(response => response.json())
            .then(data => {
                console.log('Categorías recibidas:', data); // Muestra las categorías

                const categoriaSelect = document.getElementById('categoria');
                categoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>'; // Limpiar opciones

                if (data.status === 'success' && data.data.length > 0) {
                    // Llenar las opciones de categoría
                    data.data.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id;
                        option.textContent = categoria.nombreCategoria;
                        categoriaSelect.appendChild(option);
                    });
                } else {
                    categoriaSelect.innerHTML = '<option value="" disabled>No hay categorías disponibles</option>';
                }
            })
            .catch(error => {
                console.error('Error al obtener las categorías:', error);
            });

        // Cuando se selecciona una categoría, cargar las subcategorías correspondientes
        document.getElementById('categoria').addEventListener('change', function () {
            const categoriaId = this.value;
            console.log('Categoría seleccionada:', categoriaId);  // Muestra la categoría seleccionada

            const subcategoriaSelect = document.getElementById('subcategoria');
            subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>'; // Limpiar opciones

            if (categoriaId) {
                fetch(`http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerSubcategoriasPorCategoria&categoria_id=${categoriaId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Subcategorías recibidas:', data);  // Muestra las subcategorías recibidas

                        if (data.status === 'success' && data.data.length > 0) {
                            // Llenar las opciones de subcategoría
                            data.data.forEach(subcategoria => {
                                const option = document.createElement('option');
                                option.value = subcategoria.id;
                                option.textContent = subcategoria.nombrSubcategoria;
                                subcategoriaSelect.appendChild(option);
                            });

                            // Activar el dropdown de subcategorías
                            subcategoriaSelect.disabled = false;
                        } else {
                            // Si no hay subcategorías, mostrar mensaje
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No hay subcategorías para esta categoría";
                            subcategoriaSelect.appendChild(option);

                            // Desactivar el dropdown de subcategorías
                            subcategoriaSelect.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener las subcategorías:', error);
                    });
            } else {
                // Si no hay categoría seleccionada, vaciar las subcategorías
                subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';
                subcategoriaSelect.disabled = true;  // Desactivar el dropdown de subcategorías
            }
        });
    });

    // **Comenzar a recibir los datos del formulario para inserción**

    // Obtiene el formulario de creación de producto
    document.getElementById('createProductForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío por defecto del formulario

        // Recoge los datos del formulario en un objeto FormData
        const formData = new FormData(this); // Recoge los datos del formulario (incluyendo imágenes)

        // Comienza la inserción (esto es donde se enviará la solicitud POST, pero está comentado por ahora)
        /*
        fetch('http://localhost:8088/Milogar/controllers/ProductoController.php?action=insertarProducto', {
            method: 'POST',
            body: formData // Enviar todos los datos como POST (incluyendo la imagen)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Producto agregado correctamente');
                // Aquí puedes realizar alguna acción adicional como cerrar el modal o limpiar el formulario
            } else {
                alert('Error al insertar el producto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al enviar la solicitud de inserción:', error);
        });
        */
    });
});
