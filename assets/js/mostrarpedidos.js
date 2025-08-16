document.addEventListener("DOMContentLoaded", function () {
    // Verificar si userId está definido en el frontend (si no está, muestra un error)
    if (typeof userId === "undefined") {
            console.error("userId no está definido. Verifica que el archivo `account-orders.php` esté configurado correctamente.");
            return;
    }

    // Función para obtener los pedidos
    function obtenerPedidosUsuario() {
        console.log("Solicitando pedidos del usuario autenticado con ID:", userId);

        fetch(`http://localhost:8080/Milogar/Controllers/PedidosController.php?action=obtenerPedidosUsuario&userId=${userId}`)
            .then(response => {
                // Verificar si la respuesta fue exitosa
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status + ' ' + response.statusText);
                }
                return response.json();  // Parsear la respuesta JSON
            })
            .then(data => {
                console.log(data);
                // Verificar si la propiedad 'success' es true
                if (data.success) {
                    // Mostrar los pedidos en la tabla
                    mostrarPedidosEnHTML(data.pedidos);
                } else {
                    // Si 'success' es false, mostrar el mensaje de error
                    alert(data.message || "No se pudieron cargar los pedidos.");
                }
            })
            .catch(error => {
                // Capturar errores de la solicitud o errores en el procesamiento de la respuesta
                console.error("Error en la solicitud:", error);
                alert("Hubo un problema al procesar la respuesta del servidor.");
            });
    }

    // Llamar a la función para obtener los pedidos
    obtenerPedidosUsuario();
});

// Función para mostrar los pedidos en la tabla
function mostrarPedidosEnHTML(pedidos) {
    const tbody = document.querySelector("#tabla-pedidos tbody");

    if (!tbody) {
        console.error("Elemento tbody no encontrado en el DOM");
        return;
    }

    tbody.innerHTML = "";  // Limpiar contenido previo

    // Iterar sobre los pedidos y crear filas de tabla
    pedidos.forEach(pedido => {
        const total = parseFloat(pedido.TotalPedido);

        // Verificar si 'total' es un número válido
        if (isNaN(total)) {
            console.error("Error: 'total' no es un número válido para el pedido", pedido);
            return; // Salir si 'total' no es un número válido
        }

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${pedido.NombreUsuario}</td>
            <td>${pedido.NumeroPedido}</td>
                <td>${new Date(pedido.FechaCreacion).toLocaleDateString()}</td>
                <td>${pedido.ItemsPedido}</td>
                <td>${pedido.EstadoPedido}</td>
            <td>${pedido.SubtotalPedido}</td> <!-- Usar el valor convertido a número -->
                            <td>${pedido.IVAPedido}</td>
                                                        <td>${total}</td>


            <td>
                <button class="btn btn-sm btn-primary ver-detalles" data-id="${pedido.PedidoID}">Ver detalles</button>
            </td>
        `;

        tbody.appendChild(tr);  // Añadir la fila a la tabla
    });
    // Asignar el evento click a los botones "Ver detalles"
    document.querySelectorAll('.ver-detalles').forEach(button => {
        button.addEventListener('click', function () {
            const pedidoId = this.getAttribute('data-id'); // Obtener el ID del pedido
            mostrarDetallesPedido(pedidoId);  // Llamar a la función para mostrar los detalles
        });
    });
}
// Añadir un evento de clic para cada botón de "Ver detalles"
document.querySelectorAll('.btn-primary').forEach(button => {
    button.addEventListener('click', function () {
        const pedidoId = this.getAttribute('data-id'); // Obtener el ID del pedido

        // Llamar a la función para mostrar los detalles del pedido
        mostrarDetallesPedido(pedidoId);
    });
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
        <p><strong>Numero de Pedido:</strong> ${pedido[0].numeroPedido}</p> <!-- Asumiendo que el primer producto tiene la info del pedido -->
        <p><strong>Fecha de Creación:</strong> ${pedido[0].fechaCreacion || 'Fecha no disponible'}</p>
        <p><strong>Subtotal:</strong> $${pedido[0].subtotal}</p>
        <p><strong>Estado:</strong> ${pedido[0].estado || 'Estado no disponible'}</p>
        
        <h5>Productos:</h5>
        <ul id="productos-lista"></ul>
    `;

    // Mostrar los productos asociados al pedido
    const productosLista = document.querySelector("#productos-lista");
    pedido.forEach(producto => {
        const li = document.createElement("li");
        li.textContent = `${producto.nombreProducto} - ${producto.cantidad} x $${producto.precio_unitario}`;
        productosLista.appendChild(li);
    });

    // Mostrar el modal (usando Bootstrap)
    $('#modalDetalles').modal('show');
}


