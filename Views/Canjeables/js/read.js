// Función para obtener los canjeables y llenar la tabla
function cargarCanjeables() {
    // Hacemos el fetch para obtener los canjeables
    fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=obtenerCanjes')
        .then(response => response.json())
        .then(data => {
            // Ver los datos recibidos desde la base de datos
            console.log('Datos obtenidos del servidor:', data);

            // Obtener el cuerpo de la tabla
            const tbody = document.querySelector('#tabla_canjes tbody');

            // Limpiar la tabla antes de agregar nuevos canjeables
            tbody.innerHTML = '';

            // Iteramos sobre los canjeables y llenamos la tabla
            data.forEach(canje => {
                console.log('Canje individual:', canje);  // Ver los datos de cada canje

                const row = document.createElement('tr');

                // Crear las celdas para cada campo
                row.innerHTML = `
                    <td>${canje.id}</td>
                    <td>${canje.producto_id}</td>
                    <td>${canje.nombre}</td>
                    <td>${canje.descripcion}</td>
                    <td>${canje.tipo}</td>
                    <td>${canje.valor_descuento}</td>
                    <td>${canje.puntos_necesarios}</td>
                    <td>${canje.estado}</td>
                    <td><img src="http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${canje.imagen}" alt="${canje.nombre}" style="width: 50px; height: 50px;"></td>
                    <td>${canje.fecha_creacion}</td>
                    <td>
                        <!-- Aquí puedes agregar los botones de acciones como editar o eliminar -->
                        <button class="btn btn-warning" onclick="editarCanjeable(${canje.id})">Editar</button>
                        <button class="btn btn-danger" onclick="eliminarCanjeable(${canje.id})">Eliminar</button>
                    </td>
                `;

                // Agregar la fila a la tabla
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error al obtener los canjeables:', error);
        });
}

// Llamar a la función cuando se cargue la página
document.addEventListener('DOMContentLoaded', () => {
    cargarCanjeables();  // Cargar los canjeables al cargar la página
});

// Función para llenar el modal con los datos del canje
function editarCanjeable(id) {
    // Obtener el canje correspondiente
    fetch(`http://localhost:8080/Milogar/Controllers/CanjeController.php?action=obtenerCanjeporId&id=${id}`)
        .then(response => response.json())
        .then(data => {
            // Ver los datos que trae el servidor
            console.log('Datos recibidos del servidor:', data);

            // Verificar si 'data' contiene los datos del canje
            if (!data || data.message) {
                console.error('Error al obtener el canje:', data.message);
                return;
            }

            // Llenar el modal con los datos del canje
            const canje = data;  // Suponiendo que la respuesta es un objeto con los datos

            // Verificar que el objeto 'canje' tenga los datos esperados
            if (!canje.nombre) {
                console.error('El canje no tiene la propiedad "nombre".', canje);
                return;
            }

            // Llenar los campos del modal con los datos
            document.getElementById('canjeable_id').value = canje.id;
            document.getElementById('nombreEditar').value = canje.nombre;
            document.getElementById('descripcionEditar').value = canje.descripcion;
            document.getElementById('tipoEditar').value = canje.tipo;
            document.getElementById('valor_descuentoEditar').value = canje.valor_descuento;
            document.getElementById('puntos_necesariosEditar').value = canje.puntos_necesarios;
            document.getElementById('estadoEditar').value = canje.estado;
            document.getElementById('imagenPreviewEditar').src = `http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${canje.imagen}`;

            // Cargar productos en el select
            cargarProductosEdit(canje.producto_id); // Cargar productos y seleccionar el que tiene el canje

            // Mostrar el modal
            $('#modalEditarCanjeable').modal('show');
        })
        .catch(error => {
            console.error('Error al obtener el canje:', error);
        });
}
// Función para cargar los productos en el select
function cargarProductosEdit(selectedProductId) {
    // Realizamos una solicitud fetch para obtener los productos
    fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=getProductos')
        .then(response => response.json())
        .then(data => {
            // Verificar los datos obtenidos
            console.log('Respuesta del servidor:', data);

            // Obtener el elemento select
            const selectProducto = document.getElementById('producto_idEditar');

            // Limpiar el select antes de llenarlo
            selectProducto.innerHTML = '';

            // Crear la opción por defecto
            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Seleccionar producto';
            selectProducto.appendChild(optionDefault);

            // Llenar el select con los productos
            data.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.nombreProducto;

                // Seleccionar el producto que corresponde al canje
                if (producto.id === selectedProductId) {
                    option.selected = true;
                }

                selectProducto.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar productos:', error);
        });
}

// Función para cargar los productos en el select
function cargarProductos() {
    // Realizamos una solicitud fetch para obtener los productos
    fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=getProductos')
        .then(response => response.json())
        .then(data => {
            // Verificar los datos obtenidos
            console.log('Respuesta del servidor:', data);

            // Obtener el elemento select
            const selectProducto = document.getElementById('producto_id');

            // Limpiar el select antes de llenarlo
            selectProducto.innerHTML = '';

            // Crear la opción por defecto
            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Seleccionar producto';
            selectProducto.appendChild(optionDefault);

            // Llenar el select con los productos
            data.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.nombreProducto;
                selectProducto.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar productos:', error);
        });
}

// Llamar a la función para cargar los productos cuando se abre el modal
$('#modalCanjeable').on('show.bs.modal', function () {
    cargarProductos(); // Cargar los productos cuando se muestra el modal
});

function enviarCanjeable(event) {
    event.preventDefault();  // Evitar que se recargue la página al enviar el formulario

    // Obtener los datos del formulario
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const tipo = document.getElementById('tipo').value;
    const valor_descuento = document.getElementById('valor_descuento').value;
    const puntos_necesarios = document.getElementById('puntos_necesarios').value;
    const producto_id = document.getElementById('producto_id').value;
    const estado = document.getElementById('estado').value;
    const imagenInput = document.getElementById('imagen');  // Campo para la nueva imagen
    let imagen = document.getElementById('imagenPreview').src;  // Usar la URL de la imagen previa

    // Si se ha seleccionado una nueva imagen, usamos esa en lugar de la previa
    if (imagenInput.files && imagenInput.files[0]) {
        imagen = imagenInput.files[0];  // Usar el archivo de imagen seleccionado
    }

    // Crear un objeto FormData para enviar los datos del formulario
    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('descripcion', descripcion);
    formData.append('tipo', tipo);
    formData.append('valor_descuento', valor_descuento);
    formData.append('puntos_necesarios', puntos_necesarios);
    formData.append('producto_id', producto_id);
    formData.append('estado', estado);
    
    // Si la imagen es un archivo (es decir, si se seleccionó una nueva imagen)
    if (imagen instanceof File) {
        formData.append('imagen', imagen);
    } else {
        formData.append('imagen', imagen);  // Usar la URL de la imagen previa
    }

    // Realizar la solicitud fetch para enviar los datos al backend
    fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=crearCanjeable', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Suponiendo que el backend devuelve una respuesta en JSON
    .then(data => {
        console.log('Respuesta del servidor:', data);
        if (data.success) {
            alert('Canjeable creado exitosamente.');
            $('#modalCanjeable').modal('hide');  // Cerrar el modal
            location.reload();  // Recargar la página después de mostrar el mensaje de éxito
        } else {
            // Si el mensaje de error es de que ya existe un canjeable con el mismo nombre
            if (data.message === 'Ya existe un canjeable con ese nombre.') {
                alert(data.message);  // Mostrar el mensaje de duplicado
            } else {
                alert('Hubo un error al crear el canjeable. ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error al enviar los datos:', error);
        alert('Hubo un error al enviar el formulario.');
    });
}
// Asignar la función al formulario
document.querySelector('form').addEventListener('submit', enviarCanjeable);

document.getElementById('producto_id').addEventListener('change', function() {
    var productoId = this.value;

    if (productoId) {
        console.log('Producto seleccionado:', productoId);

        // Realizar una solicitud fetch para obtener los detalles del producto
        fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=obtenerProductoDetalles&id=' + productoId)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos del servidor:', data);

                // Verificar si se recibieron los datos del producto
                if (data && data.nombreProducto) {
                    console.log('Nombre del producto:', data.nombreProducto);
                    console.log('Descripción del producto:', data.descripcionProducto);
                    console.log('Imagen del producto:', data.imagen);

                    // Llenar los campos con los datos del producto
                    document.getElementById('nombre').value = data.nombreProducto;
                    document.getElementById('descripcion').value = data.descripcionProducto;

                    // Mostrar la imagen del producto
                    var imagenUrl = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + data.imagen;
                    console.log('URL de la imagen:', imagenUrl);
                    document.getElementById('imagenPreview').src = imagenUrl;

                    // Asignar la imagen al campo de imagen para enviar
                } else {
                    console.error('Error: Producto no encontrado');
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del producto:', error);
            });
    } else {
        console.log('No se ha seleccionado un producto');

        // Si no se seleccionó producto, limpiar los campos
        document.getElementById('nombre').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('imagenPreview').src = '';  // Limpiar la imagen
        document.getElementById('imagen').value = '';  // Limpiar el campo de imagen también
    }
});

// Función para cargar los detalles del producto en el formulario de edición
document.getElementById('producto_idEditar').addEventListener('change', function() {
    var productoId = this.value;

    if (productoId) {
        console.log('Producto seleccionado:', productoId);

        // Realizar una solicitud fetch para obtener los detalles del producto
        fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=obtenerProductoDetalles&id=' + productoId)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos del servidor:', data);

                // Verificar si se recibieron los datos del producto
                if (data && data.nombreProducto) {
                    console.log('Nombre del producto:', data.nombreProducto);
                    console.log('Descripción del producto:', data.descripcionProducto);
                    console.log('Imagen del producto:', data.imagen);

                    // Llenar los campos con los datos del producto
                    document.getElementById('nombreEditar').value = data.nombreProducto;
                    document.getElementById('descripcionEditar').value = data.descripcionProducto;

                    // Mostrar la imagen del producto
                    var imagenUrl = 'http://localhost:8080/Milogar/assets/imagenesMilogar/productos/' + data.imagen;
                    console.log('URL de la imagen:', imagenUrl);
                    document.getElementById('imagenPreviewEditar').src = imagenUrl;

                    // Asignar la imagen al campo de imagen para enviar
                } else {
                    console.error('Error: Producto no encontrado');
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del producto:', error);
            });
    } else {
        console.log('No se ha seleccionado un producto');

        // Si no se seleccionó producto, limpiar los campos
        document.getElementById('nombreEditar').value = '';
        document.getElementById('descripcionEditar').value = '';
        document.getElementById('imagenPreviewEditar').src = '';  // Limpiar la imagen
        document.getElementById('imagenEditar').value = '';  // Limpiar el campo de imagen también
    }
});
document.getElementById("formEditarCanjeable").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    // Mostrar una alerta de confirmación antes de editar
    const confirmar = confirm("¿Estás seguro de que deseas editar este canjeable?");

    if (!confirmar) {
        // Si el usuario cancela, no hacer nada
        return;
    }

    // Obtener los datos del formulario
    const id = document.getElementById("canjeable_id").value;
    const producto_id = document.getElementById("producto_idEditar").value; // Obtener el ID del producto seleccionado
    
    const nombre = document.getElementById("nombreEditar").value;
    const descripcion = document.getElementById("descripcionEditar").value;
    const tipo = document.getElementById("tipoEditar").value;
    const valor_descuento = document.getElementById("valor_descuentoEditar").value;
    const puntos_necesarios = document.getElementById("puntos_necesariosEditar").value;
    const estado = document.getElementById("estadoEditar").value;

    // Si se ha seleccionado una nueva imagen, obtener la imagen del input
    const imagen = document.getElementById("imagenEditar").files[0] || ''; // Si no se seleccionó nueva imagen, enviar vacío

    // FormData para enviar los datos del formulario y la imagen
    const formData = new FormData();
    formData.append('id', id);
    formData.append('producto_id', producto_id); // Incluir el ID del producto seleccionado
    formData.append('nombre', nombre);
    formData.append('descripcion', descripcion);
    formData.append('tipo', tipo);
    formData.append('valor_descuento', valor_descuento);
    formData.append('puntos_necesarios', puntos_necesarios);
    formData.append('estado', estado);

    // Si hay una nueva imagen, adjuntarla al FormData
    if (imagen) {
        formData.append('imagen', imagen);
    }

    // Incluir la imagen anterior para usarla si no se cambia la imagen
    formData.append('imagen_anterior', document.getElementById("imagenPreviewEditar").src);

    // Imprimir los datos que se van a enviar
    console.log("Datos que se están enviando al backend:");
    const formObject = {};
    formData.forEach((value, key) => {
        formObject[key] = value;
    });
    console.log(formObject);  // Esto imprimirá los datos del formulario

    // Realizar la solicitud Fetch para editar el canjeable
    fetch('http://localhost:8080/Milogar/Controllers/CanjeController.php?action=editarCanjeable', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Manejar la respuesta del servidor
        if (data.success) {
            // Mostrar mensaje de éxito
            alert(data.message);
            $('#modalEditarCanjeable').modal('hide'); // Cerrar el modal
            location.reload(); // Recargar la página
        } else {
            // Mostrar mensaje de error
            alert(data.message);
        }
    })
    .catch(error => {
        // Manejar errores en la solicitud
        console.error('Error:', error);
        alert('Hubo un error al actualizar el canjeable.');
    });
});

// Función para eliminar un canjeable
function eliminarCanjeable(id) {
    // Confirmar si el usuario realmente desea eliminar el canjeable
    const confirmar = confirm("¿Estás seguro de que deseas eliminar este canjeable?");

    if (!confirmar) {
        // Si el usuario cancela, no hacer nada
        return;
    }

    // Realizar la solicitud Fetch para eliminar el canjeable
    fetch(`http://localhost:8080/Milogar/Controllers/CanjeController.php?action=eliminarCanjeable&id=${id}`, {
        method: 'GET', // Utilizamos GET para enviar el ID a través de la URL
    })
    .then(response => response.json())
    .then(data => {
        // Manejar la respuesta del servidor
        if (data.success) {
            // Mostrar mensaje de éxito
            alert(data.message);
            location.reload();  // Recarga la página

            // Eliminar el canjeable de la interfaz (puedes hacer esto actualizando la tabla o eliminando la fila)
            const row = document.getElementById(`canjeable-row-${id}`); // Suponiendo que cada fila tiene un ID único
            if (row) {
                row.remove(); // Eliminar la fila de la tabla
            }
        } else {
            // Mostrar mensaje de error
            alert(data.message);
        }
    })
    .catch(error => {
        // Manejar errores en la solicitud
        console.error('Error:', error);
        alert('Hubo un error al eliminar el canjeable.');
    });
}
