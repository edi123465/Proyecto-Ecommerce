let currentPage = 1;
const limit = 5;
let currentSearch = '';

// Función para cargar productos con paginación
function cargarProductos(page = 1, search = '') {
    currentPage = page;
    currentSearch = search;

    fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerTodo&page=${page}&limit=${limit}&search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos de la API:", data);

            const tableBody = document.querySelector("#productosTable tbody");
            const pagination = document.querySelector("#paginationProductos");

            tableBody.innerHTML = '';
            pagination.innerHTML = '';

            if (data.status === 'success' && data.data.length > 0) {
                let rowsHTML = '';

                data.data.forEach(producto => {
                    const imagenUrl = producto.imagen && producto.imagen.startsWith('http')
                        ? producto.imagen
                        : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + (producto.imagen || 'default.png');

                    const estado = producto.isActive == "1" || producto.isActive == 1
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-danger">Inactivo</span>';

                    const promocion = producto.is_promocion == "1" || producto.is_promocion == 1
                        ? '<span class="badge bg-success">Sí</span>'
                        : '<span class="badge bg-danger">No</span>';

                    rowsHTML += `
                        <tr>
                            <td>${producto.id || 'N/A'}</td>
                            <td>${producto.nombreProducto || 'Sin nombre'}</td>
                            <td>${producto.descripcionProducto || 'Sin descripción'}</td>
                            <td>$${producto.precio || '0.00'}</td>
                            <td>$${producto.precio_1 || '0.00'}</td>
                            <td>$${producto.precio_2 || '0.00'}</td>
                            <td>$${producto.precio_3 || '0.00'}</td>
                            <td>$${producto.precio_4 || '0.00'}</td>
                            <td>${producto.nombreCategoria || '0.00'}</td>
                            <td>${producto.nombreSubcategoria || 'No asignada'}</td>
                            <td>${producto.codigo_barras || 'No disponible'}</td>
                            <td class="text-center">
                                <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            </td>
                            <td>${estado}</td>
                            <td>${producto.fechaCreacion || 'Fecha no disponible'}</td>
                            <td>${promocion}</td>
                            <td>${producto.descuento ? producto.descuento + '%' : '0%'}</td>
                            
                            <td>${producto.stock || '0'}</td>
                            <td>${producto.cantidad_minima_para_puntos || 0}</td>
                            <td>${producto.puntos_otorgados || 0}</td>
                            <td>${producto.is_talla || 0}</td>

                            <td>
                                <button class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="${producto.id}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm eliminar-producto" data-id="${producto.id}">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });

                tableBody.innerHTML = rowsHTML;

                // Event listener for the Edit buttons
                const editButtons = document.querySelectorAll('.btn-edit');
                editButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const productoId = this.getAttribute('data-id');
                        // Hacer una solicitud para obtener los detalles del producto a editar
                        fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductoPorId&id=${productoId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success' && data.data) {
                                    const producto = data.data;

                                    // Llenar el formulario del modal con los datos del producto
                                    const productoIdInput = document.querySelector('#productoId');
                                    if (productoIdInput) {
                                        productoIdInput.value = producto.id || '';
                                    }

                                    document.querySelector('#nombre_Producto').value = producto.nombreProducto || '';
                                    document.querySelector('#descripcion_Producto').value = producto.descripcionProducto || '';
                                    document.querySelector('#categoriaa').value = producto.categoria_id || '';  // Asegúrate de tener la categoría en los datos
                                    document.querySelector('#subcategoriaa').value = producto.subcategoria_id || ''; // Asegúrate de tener la subcategoría en los datos
                                    document.querySelector('#precioCompra').value = producto.precio || '';
                                    document.querySelector('#precio1').value = producto.precio_1 || '';
                                    document.querySelector('#precio2').value = producto.precio_2 || '';
                                    document.querySelector('#precio3').value = producto.precio_3 || '';
                                    document.querySelector('#precio4').value = producto.precio_4 || '';
                                    document.querySelector('#codigoBarras').value = producto.codigo_barras || '';
                                    document.querySelector('#stocks').value = producto.stock || '';
                                    document.querySelector('#imagen_Producto').src = producto.imagen ? 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen : 'http://localhost:0/Milogar/assets/imagenesMilogar/Productos/default.png';
                                    document.querySelector('#estado').value = (producto.isActive === 1) ? '1' : (producto.isActive === 0 ? '0' : '');
                                    document.querySelector('#isPromocion').value = (producto.is_promocion === 1) ? '1' : (producto.is_promocion === 0 ? '0' : '');

                                    document.querySelector('#desc').value = producto.descuento || '';
                                    document.querySelector('#puntosOtorgados').value = producto.puntos_otorgados || 0;
                                    document.querySelector('#cantidadMinimaPuntos').value = producto.cantidad_minima_para_puntos || 0;

                                    // Aquí, en lugar de modificar el valor del campo file, mostramos la imagen.
                                    const imagenPreview = document.querySelector('#imagenPreview');
                                    if (producto.imagen) {
                                        imagenPreview.src = 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
                                    } else {
                                        imagenPreview.src = 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/default.png';
                                    }
                                    // Cargar las categorías
                                    fetch('http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerCategorias')
                                        .then(response => response.json())
                                        .then(data => {
                                            const categoriaSelect = document.querySelector('#categoriaa');
                                            categoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>';

                                            if (data.status === 'success' && data.data.length > 0) {
                                                data.data.forEach(categoria => {
                                                    const option = document.createElement('option');
                                                    option.value = categoria.id;
                                                    option.textContent = categoria.nombreCategoria;
                                                    categoriaSelect.appendChild(option);
                                                });

                                                // Establecer la categoría seleccionada
                                                categoriaSelect.value = producto.categoria_id || '';

                                                // Simular el evento de cambio para cargar las subcategorías
                                                categoriaSelect.dispatchEvent(new Event('change'));
                                            } else {
                                                categoriaSelect.innerHTML = '<option value="" disabled>No hay categorías disponibles</option>';
                                            }
                                        })
                                        .catch(error => console.error('Error al obtener las categorías:', error));

                                    // Cuando se selecciona una categoría, cargar las subcategorías correspondientes
                                    document.querySelector('#categoriaa').addEventListener('change', function () {
                                        const categoriaId = this.value;
                                        const subcategoriaSelect = document.querySelector('#subcategoriaa');
                                        subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';

                                        if (categoriaId) {
                                            fetch(`http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerSubcategoriasPorCategoria&categoria_id=${categoriaId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.status === 'success' && data.data.length > 0) {
                                                        data.data.forEach(subcategoria => {
                                                            const option = document.createElement('option');
                                                            option.value = subcategoria.id;
                                                            option.textContent = subcategoria.nombrSubcategoria;
                                                            subcategoriaSelect.appendChild(option);
                                                        });

                                                        // Establecer la subcategoría seleccionada
                                                        subcategoriaSelect.value = producto.subcategoria_id || '';
                                                    } else {
                                                        subcategoriaSelect.innerHTML = '<option value="" disabled>No hay subcategorías para esta categoría</option>';
                                                    }
                                                })
                                                .catch(error => console.error('Error al obtener las subcategorías:', error));
                                        } else {
                                            subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';
                                            subcategoriaSelect.disabled = true;
                                        }
                                    });

                                    // Mostrar el modal de edición
                                    $('#editarProductoModal').modal('show');

                                } else {
                                    console.error("Producto no encontrado");
                                }
                            })
                            .catch(error => console.error('Error al obtener los detalles del producto:', error));
                    });
                });

                // Event listener for the Delete buttons
                const deleteButtons = document.querySelectorAll('.eliminar-producto');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const productoId = this.getAttribute('data-id');
                        console.log('Producto ID a eliminar:', productoId);  // Verifica si se obtiene el ID correctamente

                        var confirmar = confirm("¿Estás seguro de que quieres eliminar este producto?");
                        if (!confirmar) {
                            return; // Detener la ejecución de la función
                        }

                        // Realizar la solicitud fetch para eliminar el producto
                        fetch(`http://localhost:8080/Milogar/controllers/ProductoController.php?action=deleteProducto&idProducto=${productoId}`, {
                            method: 'GET', // Usamos GET ya que no se requieren datos adicionales en el cuerpo
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Respuesta del servidor:', data);
                                if (data.success) {
                                    alert('Producto eliminado correctamente');
                                    window.location.href = "./index.php";
                                    if (filaProducto) {
                                        filaProducto.remove(); // Eliminar la fila del producto de la tabla
                                    }
                                } else {
                                    alert('Error al eliminar el producto');
                                }
                            })
                            .catch(error => {
                                console.error('Error al hacer la solicitud:', error);
                            });
                    });
                });

                // PAGINACIÓN
                const total = data.total;
                const totalPages = Math.ceil(total / limit);

                if (totalPages > 1) {
                    // Botón anterior
                    pagination.innerHTML += `
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" onclick="cargarProductos(${currentPage - 1})">Anterior</a>
                        </li>
                    `;

                    // Páginas numeradas
                    for (let i = 1; i <= totalPages; i++) {
                        pagination.innerHTML += `
                            <li class="page-item ${i === currentPage ? 'active' : ''}">
                                <a class="page-link" href="#" onclick="cargarProductos(${i})">${i}</a>
                            </li>
                        `;
                    }

                    // Botón siguiente
                    pagination.innerHTML += `
                        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                            <a class="page-link" href="#" onclick="cargarProductos(${currentPage + 1})">Siguiente</a>
                        </li>
                    `;
                }

            } else {
                tableBody.innerHTML = `
                    <tr><td colspan="18" class="text-center text-danger">No se encontraron productos</td></tr>
                `;
            }

        })
        .catch(error => {
            console.error("Error al cargar productos:", error);
        });
}

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

    // **Manejar la inserción del producto**
    document.getElementById('createProductForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío por defecto del formulario

        // Recoge los datos del formulario en un objeto FormData
        const formData = new FormData(this); // Recoge todos los datos del formulario, incluidos los archivos

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
        // Llamada para insertar el producto
        fetch('http://localhost:8080/Milogar/controllers/ProductoController.php?action=insertarProducto', {
            method: 'POST',
            body: formData // Enviar los datos como POST (incluyendo la imagen y otros datos)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto agregado correctamente');
                    window.location.href = "./index.php";
                    // Aquí puedes realizar alguna acción adicional como cerrar el modal o limpiar el formulario
                    $('#createProductModal').modal('hide'); // Cerrar el modal
                    document.getElementById('createProductForm').reset(); // Limpiar el formulario
                } else {
                    alert('Error al insertar el producto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al enviar la solicitud de inserción:', error);
            });
    });
});


document.getElementById('formEditarProducto').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario de forma tradicional

    // Mostrar la alerta de confirmación antes de continuar
    var confirmar = confirm("¿Estás seguro de que quieres actualizar este producto?");

    if (!confirmar) {
        // Si el usuario selecciona "No", se cancela la actualización
        alert("No se ha realizado ninguna actualización.");
        return; // Detener la ejecución de la función
    }

    // Captura los datos del formulario
    const id = parseInt(document.getElementById('productoId').value, 10);
    const nombreProducto = document.getElementById('nombre_Producto').value;
    const descripcionProducto = document.getElementById('descripcion_Producto').value;
    const precio = parseFloat(document.getElementById('precioCompra').value);
    const precio1 = parseFloat(document.getElementById('precio1').value);
    const precio2 = parseFloat(document.getElementById('precio2').value);
    const precio3 = parseFloat(document.getElementById('precio3').value);
    const precio4 = parseFloat(document.getElementById('precio4').value);
    const stock = parseInt(document.getElementById('stocks').value);
    const codBarras = document.getElementById('codigoBarras').value;
    // Aquí están los valores de isActive y isPromocion
    const isActive = document.getElementById('estado').value; // Asegúrate de que 'estado' está correcto
    const isPromocion = document.getElementById('isPromocion').value; // Asegúrate de que 'isPromocion' está correcto
    const descuento = parseFloat(document.getElementById('desc').value);
    const cantidadMinimaPuntos = parseInt(document.getElementById('cantidadMinimaPuntos').value);
    const puntosOtorgados = parseInt(document.getElementById('puntosOtorgados').value);

    // Captura los valores de categoría y subcategoría
    const categoria_id = document.getElementById('categoriaa').value;
    const subcategoria_id = document.getElementById('subcategoriaa').value;

    // Prepara los datos para enviar en la solicitud
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombreProducto', nombreProducto);
    formData.append('descripcionProducto', descripcionProducto);
    formData.append('precio', precio);
    formData.append('precio1', precio1);
    formData.append('precio2', precio2);
    formData.append('precio3', precio3);
    formData.append('precio4', precio4);
    formData.append('stock', stock);
    formData.append('codBarras', codBarras);
    formData.append('isActive', isActive);
    formData.append('isPromocion', isPromocion);
    formData.append('descuento', descuento);
    formData.append('cantidad_minima_para_puntos', cantidadMinimaPuntos);
    formData.append('puntos_otorgados', puntosOtorgados);
    formData.append('categoria_id', categoria_id);  // Añadir categoría
    formData.append('subcategoria_id', subcategoria_id);  // Añadir subcategoría

    // Verificar si hay una nueva imagen
    const imagenProducto = document.getElementById('imagen_Producto').files[0];
    if (imagenProducto) {
        formData.append('nueva_imagen', imagenProducto);
    }

    // Consola para ver los datos antes de enviarlos
    console.log('Datos a enviar:', {
        id,
        nombreProducto,
        descripcionProducto,
        precio,
        precio1,
        precio2,
        precio3,
        precio4,
        stock,
        codBarras,
        isActive,
        isPromocion,
        descuento,
        categoria_id,
        subcategoria_id,
        nueva_imagen: imagenProducto ? imagenProducto.name : 'No hay imagen'
    });

    // Hacer la solicitud fetch
    fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=actualizarProducto&idProducto=${id}`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json()) // Suponiendo que el servidor responde con JSON
        .then(data => {
            // Consola para verificar la respuesta del servidor
            console.log('Respuesta del servidor:', data);

            if (data.success) {
                // Mostrar un mensaje de éxito (puedes usar alert o actualizar el DOM)
                alert('Producto actualizado correctamente');
                window.location.href = "./index.php";
                // Cerrar el modal
                $('#editarProductoModal').modal('hide');
                // Actualizar la lista de productos o redirigir si es necesario
            } else {
                // Mostrar un mensaje de error
                alert('Error al actualizar el producto');
            }
        })
        .catch(error => {
            // Consola para capturar errores en la solicitud fetch
            console.error('Error al hacer la solicitud:', error);
            alert('Hubo un error al actualizar el producto.');
        });
});

function searchProducts() {
    // Obtener el valor del campo de búsqueda
    const query = document.getElementById('searchInput').value.trim();  // Asegúrate de tener el input con id="searchInput"

    // Verificar que el campo no esté vacío
    if (query.length > 0) {
        // Realizar la solicitud fetch al controlador PHP sin un formulario tradicional
        fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=searchProducts&q=${encodeURIComponent(query)}`)
            .then(response => response.json())  // Convertir la respuesta a JSON
            .then(data => {
                // Limpiar resultados previos
                const tableBody = document.querySelector("#productosTable tbody");
                tableBody.innerHTML = '';

                // Verificar si hay resultados
                if (data && data.length > 0) {
                    // Iterar sobre los productos y mostrarlos
                    data.forEach(product => {
                        const imagenUrl = product.imagen && product.imagen.startsWith('http')
                            ? product.imagen
                            : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + (product.imagen || 'default.png');

                        const estado = product.isActive == "1" || product.isActive == 1
                            ? '<span class="badge bg-success">Activo</span>'
                            : '<span class="badge bg-danger">Inactivo</span>';

                        const promocion = product.is_promocion == "1" || product.is_promocion == 1
                            ? '<span class="badge bg-success">Sí</span>'
                            : '<span class="badge bg-danger">No</span>';

                        const row = `
                            <tr>
                                <td>${product.id || 'N/A'}</td>
                                <td>${product.nombreProducto || 'Sin nombre'}</td>
                                <td>${product.descripcionProducto || 'Sin descripción'}</td>
                                <td>$${product.precio || '0.00'}</td>
                                <td>$${product.precio_1 || '0.00'}</td>
                                <td>$${product.precio_2 || '0.00'}</td>
                                <td>$${product.precio_3 || '0.00'}</td>
                                <td>$${product.precio_4 || '0.00'}</td>
                                <td>${product.nombreCategoria || '0.00'}</td>
                                <td>${product.nombrSubcategoria || 'No asignada'}</td>
                                <td>${product.codigo_barras || 'No disponible'}</td>
                                
                                <td class="text-center">
                                    <img src="${imagenUrl}" alt="${product.nombreProducto}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                </td>
                                <td>${estado}</td>
                                <td>${product.fechaCreacion || 'Fecha no disponible'}</td>
                                <td>${promocion}</td>
                                <td>${product.descuento ? product.descuento + '%' : '0%'}</td>
                          

                                <td>${product.stock || '0'}</td>
                                      <td>${product.cantidad_minima_para_puntos || 0}</td>
                                <td>${product.puntos_otorgados || 0}</td>
                                <td>
                                    <button class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="${product.id}">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm eliminar-producto" data-id="${product.id}">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });

                    // Event listener for the Edit buttons
                    const editButtons = document.querySelectorAll('.btn-edit');
                    editButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const productoId = this.getAttribute('data-id');
                            // Hacer una solicitud para obtener los detalles del producto a editar
                            fetch(`http://localhost:8080/Milogar/Controllers/ProductoController.php?action=obtenerProductoPorId&id=${productoId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success' && data.data) {
                                        const producto = data.data;

                                        // Llenar el formulario del modal con los datos del producto
                                        const productoIdInput = document.querySelector('#productoId');
                                        if (productoIdInput) {
                                            productoIdInput.value = producto.id || '';
                                        }

                                        document.querySelector('#nombre_Producto').value = producto.nombreProducto || '';
                                        document.querySelector('#descripcion_Producto').value = producto.descripcionProducto || '';
                                        document.querySelector('#categoriaa').value = producto.categoria_id || '';  // Asegúrate de tener la categoría en los datos
                                        document.querySelector('#subcategoriaa').value = producto.subcategoria_id || ''; // Asegúrate de tener la subcategoría en los datos
                                        document.querySelector('#precioCompra').value = producto.precio || '';
                                        document.querySelector('#precio1').value = producto.precio_1 || '';
                                        document.querySelector('#precio2').value = producto.precio_2 || '';
                                        document.querySelector('#precio3').value = producto.precio_3 || '';
                                        document.querySelector('#precio4').value = producto.precio_4 || '';
                                        document.querySelector('#codigoBarras').value = producto.codigo_barras || '';
                                        document.querySelector('#stocks').value = producto.stock || '';
                                        document.querySelector('#imagen_Producto').src = producto.imagen ? 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen : 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/default.png';
                                        document.querySelector('#estado').value = (producto.isActive === 1) ? '1' : (producto.isActive === 0 ? '0' : '');
                                        document.querySelector('#isPromocion').value = (producto.is_promocion === 1) ? '1' : (producto.is_promocion === 0 ? '0' : '');

                                        document.querySelector('#desc').value = producto.descuento || '';
                                        // Aquí, en lugar de modificar el valor del campo file, mostramos la imagen.
                                        const imagenPreview = document.querySelector('#imagenPreview');
                                        if (producto.imagen) {
                                            imagenPreview.src = 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/' + producto.imagen;
                                        } else {
                                            imagenPreview.src = 'http://localhost:8080/Milogar/assets/imagenesMilogar/Productos/default.png';
                                        }
                                        // Cargar las categorías
                                        fetch('http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerCategorias')
                                            .then(response => response.json())
                                            .then(data => {
                                                const categoriaSelect = document.querySelector('#categoriaa');
                                                categoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>';

                                                if (data.status === 'success' && data.data.length > 0) {
                                                    data.data.forEach(categoria => {
                                                        const option = document.createElement('option');
                                                        option.value = categoria.id;
                                                        option.textContent = categoria.nombreCategoria;
                                                        categoriaSelect.appendChild(option);
                                                    });

                                                    // Establecer la categoría seleccionada
                                                    categoriaSelect.value = producto.categoria_id || '';

                                                    // Simular el evento de cambio para cargar las subcategorías
                                                    categoriaSelect.dispatchEvent(new Event('change'));
                                                } else {
                                                    categoriaSelect.innerHTML = '<option value="" disabled>No hay categorías disponibles</option>';
                                                }
                                            })
                                            .catch(error => console.error('Error al obtener las categorías:', error));

                                        // Cuando se selecciona una categoría, cargar las subcategorías correspondientes
                                        document.querySelector('#categoriaa').addEventListener('change', function () {
                                            const categoriaId = this.value;
                                            const subcategoriaSelect = document.querySelector('#subcategoriaa');
                                            subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';

                                            if (categoriaId) {
                                                fetch(`http://localhost:8080/Milogar/controllers/ProductoController.php?action=obtenerSubcategoriasPorCategoria&categoria_id=${categoriaId}`)
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.status === 'success' && data.data.length > 0) {
                                                            data.data.forEach(subcategoria => {
                                                                const option = document.createElement('option');
                                                                option.value = subcategoria.id;
                                                                option.textContent = subcategoria.nombrSubcategoria;
                                                                subcategoriaSelect.appendChild(option);
                                                            });

                                                            // Establecer la subcategoría seleccionada
                                                            subcategoriaSelect.value = producto.subcategoria_id || '';
                                                        } else {
                                                            subcategoriaSelect.innerHTML = '<option value="" disabled>No hay subcategorías para esta categoría</option>';
                                                        }
                                                    })
                                                    .catch(error => console.error('Error al obtener las subcategorías:', error));
                                            } else {
                                                subcategoriaSelect.innerHTML = '<option value="" disabled selected>Selecciona una subcategoría</option>';
                                                subcategoriaSelect.disabled = true;
                                            }
                                        });

                                        // Mostrar el modal de edición
                                        $('#editarProductoModal').modal('show');

                                    } else {
                                        console.error("Producto no encontrado");
                                    }
                                })
                                .catch(error => console.error('Error al obtener los detalles del producto:', error));
                        });
                    });

                    // Agregar los event listeners a los botones de eliminar
                    document.querySelectorAll('.eliminar-producto').forEach(button => {
                        button.addEventListener('click', function () {
                            const productoId = this.getAttribute('data-id');
                            var confirmar = confirm("¿Estás seguro de que quieres eliminar este producto?");
                            if (!confirmar) return; // Detener la ejecución si no confirmamos

                            fetch(`http://localhost:8080/Milogar/controllers/ProductoController.php?action=deleteProducto&idProducto=${productoId}`, {
                                method: 'GET',
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Producto eliminado correctamente');
                                        searchProducts(); // Vuelve a cargar los productos después de eliminar
                                    } else {
                                        alert('Error al eliminar el producto');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al eliminar el producto:', error);
                                });
                        });
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="14">No se encontraron productos.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error en la búsqueda de productos:', error);
            });
    } else {
        cargarProductos(currentPage, '');  // Pasar la búsqueda vacía

    }
}

// Añadir un evento para que la búsqueda se realice cuando el usuario teclee
document.getElementById('searchInput').addEventListener('input', searchProducts);
// Cargar productos al iniciar
document.addEventListener("DOMContentLoaded", () => {
    cargarProductos();
});


