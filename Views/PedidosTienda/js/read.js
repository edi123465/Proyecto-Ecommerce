document.addEventListener('DOMContentLoaded', function () {
    const fechaFinInput = document.getElementById('fechaFin');
    const today = new Date().toISOString().split('T')[0]; // Obtener la fecha en formato yyyy-mm-dd
    fechaFinInput.value = today; // Establecer la fecha de fin como la fecha actual
});

document.addEventListener("DOMContentLoaded", function () {
    let paginaActual = 1;
    const limitePorPagina = 10;

    // Función para obtener los pedidos con filtros y paginación
    const obtenerPedidos = async (pagina = 1) => {
        // Obtener los valores del formulario de búsqueda
        const numeroPedido = document.getElementById('numeroPedido').value;
        const nombreUsuario = document.getElementById('nombreUsuario').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;

        try {
            // Filtrar los valores vacíos para no enviarlos como parámetros
            const params = new URLSearchParams();
            if (numeroPedido) params.append('numeroPedido', numeroPedido);
            if (nombreUsuario) params.append('nombreUsuario', nombreUsuario);
            if (fechaInicio) params.append('fechaInicio', fechaInicio);
            if (fechaFin) {
                // Asegurarnos de que incluya todo el día hasta las 23:59:59
                const fechaFinCompleta = fechaFin.length === 10 ? `${fechaFin} 23:59:59` : fechaFin;
                params.append('fechaFin', fechaFinCompleta);
            }
            // Log de depuración: Verifica qué parámetros se están enviando
            console.log(`Buscando con parámetros: ${params.toString()}`);

            const url = `http://localhost:8080/Milogar/Controllers/PedidosController.php?action=obtenerTodo&page=${pagina}&limit=${limitePorPagina}&${params.toString()}`;
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            const respuestaJson = await response.json();
            console.log("Datos recibidos: ", respuestaJson);

            if (respuestaJson.status === 'success' && Array.isArray(respuestaJson.data)) {
                mostrarPedidosEnTabla(respuestaJson.data);
                actualizarControlesPaginacion(respuestaJson.paginaActual, respuestaJson.totalPaginas);
            } else {
                throw new Error(respuestaJson.message || "No se pudo cargar la lista de pedidos.");
            }
        } catch (error) {
            console.error('Error al obtener los pedidos: ', error);
            alert('Hubo un error al cargar los pedidos');
        }
    };

    // Mostrar pedidos en tabla
    const mostrarPedidosEnTabla = (pedidos) => {
        const tablaBody = document.querySelector('#tabla_Pedidos tbody');
        tablaBody.innerHTML = '';

        pedidos.forEach(pedido => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${pedido.PedidoID}</td>
                <td>${pedido.NumeroPedido}</td>
                <td>${pedido.NombreUsuario}</td>
                <td>${new Date(pedido.FechaCreacion).toLocaleDateString()}</td>
                 <td>${pedido.EstadoPedido}</td>
                <td>${pedido.DireccionPedido || 'Sin dirección'}</td>
                <td>${pedido.SubtotalPedido}</td>
                <td>${pedido.IVAPedido}</td>
                <td>${pedido.DescuentoPedido}</td>
                <td>${pedido.TotalPedido}</td>
                <td>${pedido.ItemsPedido}</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="mostrarDetallesPedido(${pedido.PedidoID})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarPedido(${pedido.PedidoID})">Eliminar</button>
                </td>
            `;
            tablaBody.appendChild(row);
        });
    };

    const actualizarControlesPaginacion = (pagina, totalPaginas) => {
        const paginacion = document.querySelector("#paginacion");
        paginacion.innerHTML = '';

        // Botón Anterior
        const btnAnterior = document.createElement('button');
        btnAnterior.textContent = 'Anterior';
        btnAnterior.className = 'btn btn-secondary mx-1';
        btnAnterior.disabled = pagina <= 1;
        btnAnterior.onclick = () => {
            paginaActual--;
            obtenerPedidos(paginaActual);
        };
        paginacion.appendChild(btnAnterior);

        // Botones numéricos
        for (let i = 1; i <= totalPaginas; i++) {
            const btnPagina = document.createElement('button');
            btnPagina.textContent = i;
            btnPagina.className = `btn mx-1 ${i === pagina ? 'btn-primary' : 'btn-outline-primary'}`;
            btnPagina.onclick = () => {
                paginaActual = i;
                obtenerPedidos(paginaActual);
            };
            paginacion.appendChild(btnPagina);
        }

        // Botón Siguiente
        const btnSiguiente = document.createElement('button');
        btnSiguiente.textContent = 'Siguiente';
        btnSiguiente.className = 'btn btn-secondary mx-1';
        btnSiguiente.disabled = pagina >= totalPaginas;
        btnSiguiente.onclick = () => {
            paginaActual++;
            obtenerPedidos(paginaActual);
        };
        paginacion.appendChild(btnSiguiente);
    };

    // Crear contenedor de paginación si no existe
    const crearContenedorPaginacion = () => {
        let paginacion = document.querySelector("#paginacion");
        if (!paginacion) {
            paginacion = document.createElement("div");
            paginacion.id = "paginacion";
            paginacion.className = "my-3 text-center";
            document.body.appendChild(paginacion); // Puedes ubicarlo donde prefieras
        }
    };

    // Asignar evento al botón de búsqueda
    document.getElementById('btnBuscar').addEventListener('click', () => {
        paginaActual = 1; // Reiniciar la página actual al realizar una nueva búsqueda
        obtenerPedidos(paginaActual);
    });

    // Asignar evento para limpiar los campos de búsqueda (nombre de usuario o número de pedido)
    document.getElementById('nombreUsuario').addEventListener('input', function () {
        if (!this.value) {
            obtenerPedidos(paginaActual); // Actualizar si el campo se limpia
        }
    });

    document.getElementById('numeroPedido').addEventListener('input', function () {
        if (!this.value) {
            obtenerPedidos(paginaActual); // Actualizar si el campo se limpia
        }
    });

    // Asignar eventos para limpiar los campos de fecha
    document.getElementById('fechaInicio').addEventListener('input', function () {
        if (!this.value || !document.getElementById('fechaFin').value) {
            obtenerPedidos(paginaActual); // Actualizar si el campo de fecha se limpia
        }
    });

    document.getElementById('fechaFin').addEventListener('input', function () {
        if (!this.value || !document.getElementById('fechaInicio').value) {
            obtenerPedidos(paginaActual); // Actualizar si el campo de fecha se limpia
        }
    });

    crearContenedorPaginacion();
    obtenerPedidos(paginaActual);
});

let currentPedidoId = null;  // Variable global para almacenar el ID del pedido

// Función para mostrar los detalles del pedido
function mostrarDetallesPedido(pedidoId) {
    currentPedidoId = pedidoId;  // Guardamos el ID del pedido cuando se abre el modal

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

function mostrarEnModal(pedido) {
    const modalBody = document.querySelector("#modal-pedido-body");

    // Limpiar contenido previo
    modalBody.innerHTML = "";

    // Calcular el subtotal total
    let subtotalTotal = 0;
    pedido.forEach(producto => {
        subtotalTotal += parseFloat(producto.subtotal); // Sumar los subtotales de todos los productos
    });

    // Obtener el valor del descuento del primer producto
    const descuentoTotal = parseFloat(pedido[0].descuento); // Asegúrate de que este valor esté correctamente en la respuesta

    // Calcular el total con descuento
    const totalConDescuento = subtotalTotal - descuentoTotal;

    // Llenar el modal con los detalles del pedido
    modalBody.innerHTML = `
        <div class="form-group">
            <label for="estadoPedido"><strong>Estado:</strong></label>
          <select class="form-control" id="estadoPedido">
                <option value="Pendiente" ${pedido[0].estado === 'Pendiente' ? 'selected' : ''}>Pendiente</option>
                <option value="Autorizado" ${pedido[0].estado === 'Autorizado' ? 'selected' : ''}>Autorizado</option>
                <option value="En camino" ${pedido[0].estado === 'En camino' ? 'selected' : ''}>En camino</option>
                <option value="Entregado" ${pedido[0].estado === 'Entregado' ? 'selected' : ''}>Entregado</option>
                <option value="Cancelado" ${pedido[0].estado === 'Cancelado' ? 'selected' : ''}>Cancelado</option>
            </select>
        </div>
        <p><strong>Numero de Pedido:</strong> ${pedido[0].numeroPedido}</p>
        <p><strong>ID del Pedido:</strong> ${pedido[0].id}</p>
        <p><strong>Fecha de Creación:</strong> ${pedido[0].fechaCreacion || 'Fecha no disponible'}</p>
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

        <!-- Sección de totales -->
        <div class="mt-3">
            <p><strong>Subtotal Neto:</strong> $${subtotalTotal.toFixed(2)}</p>
            <p><strong>Descuento:</strong> $${descuentoTotal.toFixed(2)}</p>
            <p><strong>Total a Pagar:</strong> $${totalConDescuento.toFixed(2)}</p>
        </div>
    
        <!-- Botones de acción en el pie del modal -->
        <div class="modal-footer">
<button class="btn btn-primary" onclick="guardarEstadoPedido(${pedido[0].id})">Guardar Estado</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
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
function guardarEstadoPedido() {
    const estadoSeleccionado = document.querySelector("#estadoPedido").value;

    // Usar la variable global para obtener el ID del pedido
    console.log("Datos enviados para actualizar estado:", {
        pedido_id: currentPedidoId,
        estado: estadoSeleccionado
    });

    fetch(`http://localhost:8080/Milogar/Controllers/PedidosController.php?action=actualizarEstado`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            pedido_id: currentPedidoId,  // Usamos currentPedidoId aquí
            estado: estadoSeleccionado
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al actualizar el estado del pedido.");
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {  // Asegúrate de que el controlador devuelva 'status' como 'success'
                alert("Estado actualizado correctamente.");
                $('#modalDetalles').modal('hide');
                location.reload();  // Recargar la página después de la actualización
            } else {
                alert("No se pudo actualizar el estado: " + (data.message || "Error desconocido"));
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error al intentar actualizar el estado.");
        });
}

