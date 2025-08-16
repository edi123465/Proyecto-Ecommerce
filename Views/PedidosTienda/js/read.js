document.addEventListener("DOMContentLoaded", function () {
    // Función para obtener los pedidos
    const obtenerPedidos = async () => {
        try {
            // Realizando la solicitud fetch
            const response = await fetch('http://localhost:8080/Milogar/Controllers/PedidosController.php?action=obtenerTodo');

            // Verificar si la respuesta fue exitosa
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            // Obtener los datos en formato JSON
            const respuestaJson = await response.json();

            // Verificar el tipo de la respuesta
            console.log("Datos recibidos: ", respuestaJson);
            console.log("Tipo de respuesta:", Array.isArray(respuestaJson.data) ? "Es un arreglo" : "No es un arreglo");

            // Verificar si la respuesta contiene datos
            if (respuestaJson.status === 'success' && Array.isArray(respuestaJson.data)) {
                mostrarPedidosEnTabla(respuestaJson.data); // Si es un arreglo, muestra los pedidos
            } else {
                throw new Error(respuestaJson.message || "No se pudo cargar la lista de pedidos.");
            }
        } catch (error) {
            console.error('Error al obtener los pedidos: ', error);
            alert('Hubo un error al cargar los pedidos');
        }
    };

    // Función para mostrar los pedidos en la tabla
    const mostrarPedidosEnTabla = (pedidos) => {
        const tablaBody = document.querySelector('#tabla_Pedidos tbody');

        // Limpiar la tabla antes de agregar los nuevos datos
        tablaBody.innerHTML = '';

        // Recorrer los pedidos y agregar las filas en la tabla
        pedidos.forEach(pedido => {
            const row = document.createElement('tr');

            // Crear las celdas con los datos del pedido
            row.innerHTML = `
            <td>${pedido.PedidoID}</td>
            <td>${pedido.NumeroPedido}</td>
            <td>${pedido.NombreUsuario}</td>
            <td>${new Date(pedido.FechaCreacion).toLocaleDateString()}</td>
            <td>${pedido.DireccionPedido || 'Sin dirección'}</td>
            <td>${pedido.EstadoPedido}</td>
            <td>${pedido.SubtotalPedido}</td>
            <td>${pedido.IVAPedido}</td>
            <td>${pedido.DescuentoPedido}</td> <!-- Agregado aquí -->
            <td>${pedido.TotalPedido}</td>
            <td>${pedido.ItemsPedido}</td>
            <td>
                <button class="btn btn-info btn-sm" onclick="mostrarDetallesPedido(${pedido.PedidoID})">Ver</button>
                <button class="btn btn-danger btn-sm" onclick="eliminarPedido(${pedido.idPedido})">Eliminar</button>
            </td>
            `;


            // Agregar la fila a la tabla
            tablaBody.appendChild(row);
        });
    };

    // Llamar la función para cargar los pedidos cuando se cargue la página
    obtenerPedidos();
});

// Función para mostrar los detalles del pedido
function mostrarDetallesPedido(pedidoId) {
    console.log("Obteniendo detalles del pedido con ID:", pedidoId);

    fetch(`http://localhost:8080/Milogar/Controllers/PedidosController.php?action=obtenerDetallePedidosPorId&id=${pedidoId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                // Verificar que 'data.pedido' es un array
                if (Array.isArray(data.pedido)) {
                    // Llamar a la función para mostrar los detalles en el modal
                    mostrarEnModal(data.pedido);
                } else {
                    alert("Los detalles del pedido no tienen el formato esperado.");
                }
            } else {
                alert("Error al obtener los detalles del pedido.");
            }
        })
        .catch(error => {
            console.error("Error en la solicitud de detalles:", error);
            alert("Hubo un problema al obtener los detalles del pedido.");
        });
}

// Función para mostrar los detalles en el modal
function mostrarEnModal(pedido) {
    const modalBody = document.querySelector("#modal-pedido-body");

    // Limpiar contenido previo
    modalBody.innerHTML = "";

    // Llenar el modal con los detalles del pedido
    modalBody.innerHTML = `
        <p><strong>Numero de Pedido:</strong> ${pedido[0].numeroPedido}</p>
        <p><strong>Fecha de Creación:</strong> ${pedido[0].fechaCreacion || 'Fecha no disponible'}</p>
        <p><strong>Subtotal:</strong> $${pedido[0].subtotal}</p>
        <p><strong>Estado:</strong> ${pedido[0].estado || 'Estado no disponible'}</p>
                <p><strong>Usuario:</strong> ${pedido[0].nombreUsuario || 'Nombre no disponible'}</p> <!-- Agregado nombre del usuario -->

        <h5>Productos:</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="productos-lista"></tbody>
        </table>
    `;

    // Mostrar los productos asociados al pedido
    const productosLista = document.querySelector("#productos-lista");
    pedido.forEach(producto => {
        const tr = document.createElement("tr");

        // Crear las celdas de la tabla
        const tdProducto = document.createElement("td");
        tdProducto.textContent = producto.nombreProducto;

        const tdCantidad = document.createElement("td");
        tdCantidad.textContent = producto.cantidad;

        const tdPrecioUnitario = document.createElement("td");
        tdPrecioUnitario.textContent = `$${producto.precio_unitario}`;

        const tdTotal = document.createElement("td");
        tdTotal.textContent = `$${(producto.cantidad * producto.precio_unitario).toFixed(2)}`;

        // Agregar las celdas a la fila
        tr.appendChild(tdProducto);
        tr.appendChild(tdCantidad);
        tr.appendChild(tdPrecioUnitario);
        tr.appendChild(tdTotal);

        // Agregar la fila al cuerpo de la tabla
        productosLista.appendChild(tr);
    });

    // Mostrar el modal (usando Bootstrap)
    $('#modalDetalles').modal('show');
}

