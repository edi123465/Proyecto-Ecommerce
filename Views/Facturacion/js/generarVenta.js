const inputProducto = document.getElementById('producto');
const sugerenciasDiv = document.getElementById('sugerencias');
const tablaProductos = document.getElementById('tablaProductos').querySelector('tbody');

let productosSeleccionados = [];

inputProducto.addEventListener('input', function () {
    const valor = this.value.trim();
    if (valor.length === 0) {
        sugerenciasDiv.style.display = 'none';
        return;
    }

    // Endpoint de código exacto
    const fetchCodigo = fetch(`http://localhost:8080/Milogar/Controllers/FacturacionController.php?action=obtenerProductoPorCodigo&codigo=${encodeURIComponent(valor)}`)
        .then(res => res.json())
        .then(prod => prod.error ? [] : [prod]);

    // Endpoint de búsqueda por nombre/caracteres
    const fetchRelacionados = fetch(`http://localhost:8080/Milogar/Controllers/FacturacionController.php?action=buscarProductosRelacionados&q=${encodeURIComponent(valor)}`)
        .then(res => res.json())
        .then(prods => Array.isArray(prods) ? prods : []);

    // Esperamos ambos resultados
    Promise.all([fetchCodigo, fetchRelacionados])
        .then(([porCodigo, relacionados]) => {
            const productos = [...porCodigo, ...relacionados].filter((v, i, a) => a.findIndex(p => p.id === v.id) === i); // Evitar duplicados
            sugerenciasDiv.innerHTML = '';

            if (productos.length > 0) {
                productos.forEach(prod => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    const precio = parseFloat(prod.precio_1 || prod.precio);
                    item.textContent = `${prod.nombreProducto} - $${precio.toFixed(2)}`;
                    item.addEventListener('click', function (e) {
                        e.preventDefault();
                        agregarProductoTabla(prod);
                        sugerenciasDiv.style.display = 'none';
                        inputProducto.value = '';
                    });
                    sugerenciasDiv.appendChild(item);
                });
                sugerenciasDiv.style.display = 'block';
            } else {
                sugerenciasDiv.style.display = 'none';
            }
        })
        .catch(err => console.error(err));
});

// Cerrar sugerencias si se hace clic fuera
document.addEventListener('click', function (e) {
    if (!sugerenciasDiv.contains(e.target) && e.target !== inputProducto) {
        sugerenciasDiv.style.display = 'none';
    }
});

function agregarProductoTabla(producto) {
    if (productosSeleccionados.includes(producto.id)) return;
    productosSeleccionados.push(producto.id);

    const tr = document.createElement('tr');

    // Opciones de precios disponibles
    const precios = [
        { label: 'Precio 1', value: parseFloat(producto.precio_1 || producto.precio) },
        { label: 'Precio 2', value: parseFloat(producto.precio_2 || producto.precio) },
        { label: 'Precio 3', value: parseFloat(producto.precio_3 || producto.precio) },
        { label: 'Precio 4', value: parseFloat(producto.precio_4 || producto.precio) },
    ];

    // Crear select con precios
    const selectPrecios = document.createElement('select');
    selectPrecios.className = 'form-control select-precio';
    selectPrecios.style.width = '120px';
    precios.forEach(p => {
        const option = document.createElement('option');
        option.value = p.value;
        option.textContent = `${p.label} - $${p.value.toFixed(2)}`;
        selectPrecios.appendChild(option);
    });

    tr.innerHTML = `
    <td>${producto.nombreProducto}</td>
    <td></td> <!-- Aquí se insertará el select de precios -->
    <td><input type="number" value="1" class="form-control cantidad" style="width:70px"></td>
    <td class="subtotal">${parseFloat(producto.precio_1 || producto.precio).toFixed(2)}</td>
    <td class="text-center">
        <button type="button" class="btn btn-info btn-sm btnVerImagen" title="Ver Imagen">
            <i class="fas fa-image"></i>
        </button>
        <button type="button" class="btn btn-danger btn-sm btnEliminar">Eliminar</button>
    </td>
`;


    // Insertamos el select en la celda de precio
    tr.children[1].appendChild(selectPrecios);
    tablaProductos.appendChild(tr);

    actualizarTotales();

    // Evento: cambiar precio según el select
    selectPrecios.addEventListener('change', actualizarTotales);

    // Evento: cambiar cantidad
    tr.querySelector('.cantidad').addEventListener('input', actualizarTotales);
    // Evento para ver imagen
    tr.querySelector('.btnVerImagen').addEventListener('click', () => {
        const imagenUrl = `http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;

        const modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${producto.nombreProducto}</h5>
                    <button type="button" class="close">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img src="${imagenUrl}" alt="${producto.nombreProducto}" class="img-fluid">
                </div>
            </div>
        </div>
    `;
        document.body.appendChild(modal);

        // Cerrar modal
        modal.querySelector('.close').addEventListener('click', () => document.body.removeChild(modal));
        modal.addEventListener('click', (e) => {
            if (e.target === modal) document.body.removeChild(modal);
        });
    });

    // Evento: eliminar fila
    tr.querySelector('.btnEliminar').addEventListener('click', () => {
        tr.remove();
        productosSeleccionados = productosSeleccionados.filter(id => id !== producto.id);
        actualizarTotales();
    });
}
// Limpiar tabla
document.getElementById('btnLimpiarTabla').addEventListener('click', () => {
    tablaProductos.innerHTML = '';
    productosSeleccionados = [];
    actualizarTotales();
});

// Actualizamos el cambio cuando cambia el pago del cliente
document.getElementById('pagoCliente').addEventListener('input', actualizarCambio);

function actualizarCambio() {
    const total = parseFloat(document.getElementById('total').textContent) || 0;
    const pagoCliente = parseFloat(document.getElementById('pagoCliente').value) || 0;
    const cambio = pagoCliente - total;
    document.getElementById('cambio').textContent = (cambio > 0 ? cambio : 0).toFixed(2);
}

// Modificar actualizarTotales para recalcular cambio automáticamente
function actualizarTotales() {
    let total = 0;
    tablaProductos.querySelectorAll('tr').forEach(row => {
        const precio = parseFloat(row.querySelector('.select-precio').value);
        const cantidad = parseFloat(row.querySelector('.cantidad').value);
        const subtotal = precio * cantidad;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total').textContent = total.toFixed(2);

    // Llamamos a la función que actualiza el cambio
    actualizarCambio();
}


document.getElementById('btnTerminarVenta').addEventListener('click', async () => {
    if (productosSeleccionados.length === 0) {
        alert('No hay productos en la tabla.');
        return;
    }

    const tipoCliente = document.getElementById('tipoCliente').value;
    const fecha = document.getElementById('fecha').value;
    const metodoPago = document.getElementById('metodoPago').value;
    const pagoCliente = parseFloat(document.getElementById('pagoCliente').value || 0);
    const total = parseFloat(document.getElementById('total').textContent);

    if (!fecha) { alert('Seleccione la fecha de la venta.'); return; }
    if (pagoCliente < total) { alert('El pago del cliente es insuficiente.'); return; }

    // --- Datos del cliente ---
    let cliente = {};
    if (tipoCliente === 'datos') {
        const nombre = document.getElementById('nombreCliente').value.trim();
        const cedula = document.getElementById('cedulaRuc').value.trim();
        const direccion = document.getElementById('direccionCliente').value.trim();
        const telefono = document.getElementById('telefonoCliente').value.trim();
        const correo = document.getElementById('correoCliente').value.trim();

        if (!nombre || !cedula || !direccion || !telefono || !correo) {
            alert('Complete todos los campos obligatorios del cliente.');
            return;
        }

        cliente = { nombre, cedula, direccion, telefono, correo };
    }

    // --- Preparar detalle de productos ---
    const productos = [];
    tablaProductos.querySelectorAll('tr').forEach(row => {
        const nombre = row.children[0].textContent;
        const cantidad = parseFloat(row.querySelector('.cantidad').value);
        const precio = parseFloat(row.querySelector('.select-precio').value);
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent);
        productos.push({ nombre, cantidad, precio, subtotal });
    });

    // --- Generar PDF ---
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: "portrait", unit: "mm", format: [80, 250] });
    let y = 10;

    doc.setFontSize(10); doc.setFont(undefined, 'bold');
    doc.text("Milogar S.A.", 40, y, { align: "center" });
    y += 5; doc.setFontSize(8); doc.setFont(undefined, 'normal');
    doc.text("Calle Quito y Túlcan", 40, y, { align: "center" });
    y += 4; doc.text("Tel: 0993694429", 40, y, { align: "center" });
    y += 4; doc.text("RUC: 29387728910001", 40, y, { align: "center" });
    y += 6; doc.setFontSize(9);
    doc.text(`Fecha: ${fecha}`, 10, y); y += 4;
    doc.text(`Método de Pago: ${metodoPago}`, 10, y); y += 6;

    // Datos cliente
    if (tipoCliente === 'datos') {
        doc.setFont(undefined, 'bold'); doc.text("Cliente:", 10, y); y += 4;
        doc.setFont(undefined, 'normal');
        doc.text(`Nombre: ${cliente.nombre}`, 10, y); y += 4;
        doc.text(`Cédula/RUC: ${cliente.cedula}`, 10, y); y += 4;
        doc.text(`Dirección: ${cliente.direccion}`, 10, y); y += 4;
        doc.text(`Teléfono: ${cliente.telefono}`, 10, y); y += 4;
        doc.text(`Correo: ${cliente.correo}`, 10, y); y += 6;
    } else {
        doc.setFont(undefined, 'bold'); doc.text("Cliente: Consumidor Final", 10, y); y += 6;
    }

    // Tabla productos
    doc.setFont(undefined, 'bold');
    doc.text("Producto", 10, y); doc.text("Cant", 50, y); doc.text("Precio", 60, y); doc.text("Subtotal", 70, y);
    y += 3; doc.setLineWidth(0.3); doc.line(10, y, 75, y); y += 2;
    doc.setFont(undefined, 'normal');
    productos.forEach(p => {
        doc.text(p.nombre, 10, y);
        doc.text(p.cantidad.toString(), 50, y, { align: "right" });
        doc.text(`$${p.precio.toFixed(2)}`, 60, y, { align: "right" });
        doc.text(`$${p.subtotal.toFixed(2)}`, 75, y, { align: "right" });
        y += 4;
    });

    y += 2; doc.setLineWidth(0.3); doc.line(10, y, 75, y); y += 2;
    doc.setFont(undefined, 'bold');
    doc.text(`Total: $${total.toFixed(2)}`, 10, y); y += 4;
    doc.text(`Pago: $${pagoCliente.toFixed(2)}`, 10, y); y += 4;
    doc.text(`Cambio: $${(pagoCliente - total).toFixed(2)}`, 10, y);

    // Descargar PDF
    doc.save(`Factura_${tipoCliente}_${fecha.replace(/[/ ]/g, "_")}.pdf`);

    // --- Guardar en base de datos ---
    const formData = new FormData();
    formData.append('action', tipoCliente === 'datos' ? 'guardarVentaCliente' : 'guardarVentaConsumidor');
    formData.append('fecha', fecha);
    formData.append('metodoPago', metodoPago);
    formData.append('total', total);
    formData.append('pagoCliente', pagoCliente);
    formData.append('productos', JSON.stringify(productos));
    if (tipoCliente === 'datos') {
        formData.append('cliente', JSON.stringify(cliente));
    }

    try {
        const response = await fetch('VentaController.php', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.success) {
            console.log('Venta guardada correctamente');
        } else {
            console.error('Error al guardar la venta:', result.message);
        }
    } catch (error) {
        console.error('Error en la solicitud fetch:', error);
    }

    // Limpiar tabla y formulario
    tablaProductos.innerHTML = '';
    productosSeleccionados = [];
    actualizarTotales();
    document.getElementById('formFactura').reset();
});

const fechaInput = document.getElementById('fecha');

// Crear objeto Date con la fecha actual
const hoy = new Date();

// Convertir a formato YYYY-MM-DD
const año = hoy.getFullYear();
const mes = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
const dia = String(hoy.getDate()).padStart(2, '0');
const fechaFormateada = `${año}-${mes}-${dia}`;

// Asignar al input
fechaInput.value = fechaFormateada;
// Actualizamos la función de totales para tomar en cuenta el select de precio
function actualizarTotales() {
    let total = 0;
    tablaProductos.querySelectorAll('tr').forEach(row => {
        const precio = parseFloat(row.querySelector('.select-precio').value);
        const cantidad = parseFloat(row.querySelector('.cantidad').value);
        const subtotal = precio * cantidad;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total').textContent = total.toFixed(2);

    const pagoCliente = parseFloat(document.getElementById('pagoCliente').value || 0);
    const cambio = pagoCliente - total;
    document.getElementById('cambio').textContent = (cambio > 0 ? cambio : 0).toFixed(2);
}


