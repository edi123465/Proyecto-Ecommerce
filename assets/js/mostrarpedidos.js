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
                                                        <td>${pedido.DescuentoPedido}</td>

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

function mostrarDetallesPedido(pedidoId) {
    console.log("Obteniendo detalles del pedido con ID:", pedidoId);

    fetch(`http://localhost:8080/Milogar/Controllers/PedidosController.php?action=obtenerDetallePedidosPorId&id=${pedidoId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                if (data.pedido && data.pedido.productos) {
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

function mostrarEnModal(pedido) {
    const modalBody = document.querySelector("#modal-pedido-body");
    modalBody.innerHTML = "";

    // Información general del pedido
    modalBody.innerHTML = `
        <div class="mb-3">
            <p><strong>Número de Pedido:</strong> ${pedido.numeroPedido}</p>
            <p><strong>Fecha de Creación:</strong> ${pedido.fechaCreacion || 'Fecha no disponible'}</p>
            
            <p><strong>Estado:</strong> ${pedido.estado || 'Estado no disponible'}</p>
        </div>

        <h5>Productos:</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="productos-lista"></tbody>
            </table>
            <p><strong>Subtotal:</strong> $${parseFloat(pedido.subtotal).toFixed(2)}</p>
            <p><strong>IVA:</strong> $${parseFloat(pedido.iva).toFixed(2)}</p>
            <p><strong>Descuento:</strong> $${parseFloat(pedido.descuento).toFixed(2)}</p>
            <p><strong>Total:</strong> $${parseFloat(pedido.total).toFixed(2)}</p>
        </div>
    `;

    const productosLista = document.querySelector("#productos-lista");
    pedido.productos.forEach(producto => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${producto.nombreProducto}</td>
            <td>${producto.cantidad}</td>
            <td>$${parseFloat(producto.precio_unitario).toFixed(2)}</td>
            <td>$${parseFloat(producto.subtotal).toFixed(2)}</td>
        `;
        productosLista.appendChild(tr);
    });

    $('#modalDetalles').modal('show');
}






