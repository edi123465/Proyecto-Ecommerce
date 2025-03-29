// Selecciona todos los botones "Add to Cart"
const addToCartButtons = document.querySelectorAll('.add-to-cart');
// Selecciona el elemento del contador
const cartCounter = document.querySelector('#cart_counter');
// Array para almacenar los productos en el carrito
let carrito = [];
// Variable para mantener el conteo actual de productos (solo declarada una vez)

// Función para guardar el carrito en localStorage
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();

}

// Función para cargar el carrito desde localStorage
function cargarCarrito() {
    const carritoGuardado = localStorage.getItem('carrito');
    if (carritoGuardado) {
        carrito = JSON.parse(carritoGuardado);
        actualizarCarrito(); // Actualiza el modal para mostrar los productos
    }
    actualizarContadorCarrito(); // Asegúrate de que el contador se cargue correctamente

}
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', (event) => {
        // Obtén los datos del producto desde los atributos data-* del botón
        const productoId = event.target.dataset.id;
        const nombreProducto = event.target.dataset.nombre;
        const precioProducto = parseFloat(event.target.dataset.precio);
        const descuento = parseFloat(event.target.getAttribute('data-descuento')) || 0; // Aseguramos que el descuento sea un número
        const imagenProducto = event.target.dataset.imagen;

        // Calcular el precio con descuento
        const precioConDescuento = precioProducto - (precioProducto * descuento / 100);

        // Verifica si el producto ya está en el carrito
        const productoExistente = carrito.find(producto => producto.id === productoId);

        // Si el producto ya está en el carrito, incrementa la cantidad
        if (productoExistente) {
            productoExistente.cantidad++;
        } else {
            // Si no está, lo añade al carrito con cantidad 1
            carrito.push({
                id: productoId,
                nombre: nombreProducto,
                precio: precioConDescuento, // Guardamos el precio con descuento
                cantidad: 1,
                precioOriginal: precioProducto, // Añadir el precio original
                imagen: imagenProducto,
                descuento: descuento,

            });
        }

        // Guarda el carrito en localStorage
        guardarCarrito();

        // Actualiza el carrito en el modal (si es necesario)
        actualizarCarrito();

        // Mostrar la alerta con SweetAlert2
        Swal.fire({
            position: 'bottom-left',  // Ubicación de la alerta (abajo a la izquierda)
            icon: 'success',  // Tipo de alerta (puede ser 'success', 'error', etc.)
            title: '¡Agregado correctamente!',  // Mensaje de la alerta
            showConfirmButton: false,  // No mostrar el botón de confirmación
            timer: 4000,  // La alerta desaparecerá después de 4 segundos
            toast: true,  // Activar la opción de "toast" para que sea una alerta pequeña
            timerProgressBar: true,  // Mostrar barra de progreso en el timer
        });

        // Actualiza el contador en el icono
        actualizarContadorCarrito();
    });
});

// Llamar a cargarCarrito al cargar la página
document.addEventListener('DOMContentLoaded', cargarCarrito);

function actualizarCarrito() {
    const carritoModal = document.querySelector('.list-group');
    const totalElement = document.querySelector('.fw-bold');
    const checkoutTotal = document.querySelector('#cart-total'); // Elemento para el total en el botón de checkout
    carritoModal.innerHTML = ''; // Limpiar contenido previo
    let total = 0;

    carrito.forEach((producto, index) => {
        const precioConDescuento = producto.precio;  // Precio con descuento
        const precioUnitario = producto.precioOriginal;  // Precio original sin descuento
        const descuento = producto.descuento || 0;  // El descuento en porcentaje

        // Calcular el subtotal con el precio con descuento
        const subtotal = precioConDescuento * producto.cantidad;

        total += subtotal;  // Sumar el subtotal al total

        // Crear el elemento HTML para cada producto en el carrito
        carritoModal.innerHTML += `
            <li class="list-group-item py-3 px-0 border-bottom">
                <div class="row align-items-center">
                    <div class="col-2">
                        <img src="${producto.imagen}" alt="${producto.nombre}" class="img-fluid">
                    </div>
                    <div class="col-5">
                        <h6 class="mb-0">${producto.nombre}</h6>
                        <span><small class="text-muted">${producto.cantidad} items</small></span>
                        <div class="mt-2 small">
                            <span class="text-muted">Descuento: ${descuento}%</span>
                        </div>
                        <div class="mt-2 small">
                            <span class="text-muted">Valor Unitario: $${precioUnitario.toFixed(2)}</span>
                        </div>
                        <div class="mt-2 small">
                            <a href="#!" class="text-decoration-none remove-item" data-index="${index}">
                                <span class="me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y1="17"></line>
                                    </svg>
                                </span>Remove
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group flex-nowrap justify-content-center">
                            <input type="button" value="-" class="button-minus form-control text-center" data-index="${index}">
                            <input type="number" value="${producto.cantidad}" style="width: 60px;" min="1" class="quantity-field form-control text-center" data-index="${index}">
                            <input type="button" value="+" class="button-plus form-control text-center" data-index="${index}">
                        </div>
                    </div>
                    <div class="col-2 text-end">
                        <span class="fw-bold">$${subtotal.toFixed(2)}</span>
                    </div>
                </div>
            </li>
        `;
    });

    // Actualizar el total en el modal (redondeado solo para mostrar)
    totalElement.textContent = `$${total.toFixed(2)}`;
    checkoutTotal.textContent = `$${total.toFixed(2)}`;

    // Asignar los eventos a los botones después de cargar el carrito
    asignarEventosCarrito();
}

// Función para asignar eventos de clic a los botones de cantidad y eliminar
function asignarEventosCarrito() {
    const botonesMinus = document.querySelectorAll('.button-minus');
    const botonesPlus = document.querySelectorAll('.button-plus');
    const botonesRemove = document.querySelectorAll('.remove-item');

    botonesMinus.forEach(boton => {
        boton.addEventListener('click', disminuirCantidad);
    });

    botonesPlus.forEach(boton => {
        boton.addEventListener('click', aumentarCantidad);
    });

    botonesRemove.forEach(boton => {
        boton.addEventListener('click', eliminarProducto);
    });
}



// Función para actualizar el contador del carrito
function actualizarContadorCarrito() {
    // Obtiene el número total de productos en el carrito
    const totalProductos = carrito.reduce((total, producto) => total + producto.cantidad, 0);

    // Actualiza el contador en el icono
    cartCounter.textContent = totalProductos;
}


// Funciones para cambiar cantidad
function aumentarCantidad(event) {
    const index = event.target.dataset.index;
    carrito[index].cantidad++;
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}

function disminuirCantidad(event) {
    const index = event.target.dataset.index;
    if (carrito[index].cantidad > 1) {
        carrito[index].cantidad--;
    } else {
        carrito.splice(index, 1); // Eliminar producto si la cantidad es 1
    }
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}

// Función para eliminar un producto del carrito
function eliminarProducto(event) {
    const index = event.target.dataset.index;
    carrito.splice(index, 1);
    guardarCarrito(); // Guarda el carrito actualizado
    actualizarCarrito();
}

// SE ACTIVA ESTE EVENTO CUANDO DAMOS CLIC EN EL BOTÓN GO TO CHECKOUT
document.getElementById('checkout-button').addEventListener('click', function (event) {
    event.preventDefault(); // Evita el comportamiento predeterminado del botón

    // Captura los items del carrito antes de vaciarlo
    const cartItems = carrito.map(producto => ({
        nombre: producto.nombre,
        cantidad: producto.cantidad,
        precio: producto.precio,
        subtotal: producto.precio * producto.cantidad
    }));

    // Vaciando el carrito de compras
    guardarCarrito(); // Guardar el carrito vacío en localStorage

    // Guardar los datos del carrito en localStorage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    // Actualizar la vista del carrito
    actualizarCarrito();

    // Redirigir a la página de checkout en la misma ventana
    window.location.href = '/Milogar/shop-checkout';
});

// Función para actualizar el detalle del pedido
function actualizarDetallePedido() {
    const orderDetails = document.getElementById('order-details');
    const subtotalElement = document.getElementById('subtotal');
    const descuentoElement = document.getElementById('descuento');
    const totalElement = document.getElementById('total');

    if (!orderDetails || !subtotalElement || !descuentoElement || !totalElement) {
        console.error("Uno o más elementos no existen en el DOM.");
        return;
    }

    // Limpiar los productos previos en el detalle
    orderDetails.innerHTML = '';

    let subtotal = 0;

    // Iterar sobre el carrito para agregar los productos al detalle
    carrito.forEach((producto, index) => {
        const subtotalProducto = producto.precio * producto.cantidad;
        subtotal += subtotalProducto;

        orderDetails.innerHTML += `
            <li class="list-group-item px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-2 col-md-2">
                        <img src="${producto.imagen}" alt="${producto.nombre}" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-4">
                        <h6 class="mb-0">${producto.nombre}</h6>
                    </div>
                    <div class="col-2 col-md-2 text-center">
                        <button class="btn btn-sm btn-outline-danger" onclick="cambiarCantidad(${index}, -1)">-</button>
                        <input type="number" id="cantidad-${index}" class="form-control d-inline text-center" value="${producto.cantidad}" min="1" style="width: 50px;" onchange="actualizarCantidad(${index})">
                        <button class="btn btn-sm btn-outline-success" onclick="cambiarCantidad(${index}, 1)">+</button>
                    </div>
                    <div class="col-2 text-lg-end text-start text-md-end col-md-2">
                        <span class="fw-bold">$${subtotalProducto.toFixed(2)}</span>
                    </div>
                    <div class="col-2 col-md-2 text-center">
                  <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">
                    <i class="bi bi-trash-fill"></i>
                    </button>
                    </div>
                </div>
            </li>
        `;
    });

    // Inicialmente, el descuento es cero
    let descuento = 0.00;

    // Calcular el total (subtotal - descuento)
    const total = (subtotal - descuento).toFixed(2);

    // Actualizar los valores en el HTML
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    descuentoElement.textContent = `-$${descuento.toFixed(2)}`; // Muestra -$0.00 en la vista
    totalElement.textContent = `$${total}`
    // Guardar el estado actualizado del carrito en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    actualizarCarrito();
}

// Función para cambiar la cantidad con los botones + y -
function cambiarCantidad(index, cambio) {
    if (carrito[index].cantidad + cambio >= 1) {
        carrito[index].cantidad += cambio;
        actualizarDetallePedido(); // Volver a actualizar el detalle del pedido
    }
}

// Función para actualizar la cantidad cuando se edita manualmente
function actualizarCantidad(index) {
    const inputCantidad = document.getElementById(`cantidad-${index}`);
    const nuevaCantidad = parseInt(inputCantidad.value);

    if (!isNaN(nuevaCantidad) && nuevaCantidad >= 1) {
        carrito[index].cantidad = nuevaCantidad;
        actualizarDetallePedido();
    } else {
        inputCantidad.value = carrito[index].cantidad; // Restaurar cantidad válida
    }
}

// Función para eliminar un producto del carrito
function eliminarProducto(index) {
    carrito.splice(index, 1); // Elimina el producto del array
    actualizarDetallePedido(); // Vuelve a actualizar la interfaz
}

// Llamar a la función para actualizar el detalle de pedido cuando se cargue el carrito
document.addEventListener('DOMContentLoaded', actualizarDetallePedido);

const radios = document.querySelectorAll('input[name="opcion"]');

// Agrega un listener a cada radio button para cuando se selecciona uno
radios.forEach(radio => {
    radio.addEventListener('change', () => {
        // Muestra el valor del radio seleccionado en la consola
        console.log(`Método de pago seleccionado: ${radio.value}`);
    });
});

document.getElementById("proceder").addEventListener("click", function (event) {
    event.preventDefault();
    // Verificar si el carrito tiene productos
    if (carrito.length === 0) {
        alert("Tu carrito está vacío. Agrega al menos un producto para realizar un pedido.");
        return; // Detiene la ejecución si el carrito está vacío
    }
    // Obtener la opción seleccionada en el radio button
    const opcionSeleccionada = document.querySelector("input[name='opcion']:checked");
    const metodoPago = opcionSeleccionada ? opcionSeleccionada.value : null; // El valor del método de pago seleccionado

    // Obtener la opción de entrega seleccionada
    const deliveryOption = document.querySelector("input[name='deliveryOption']:checked")?.value || "recogerEnTienda";

    // Obtener correo y teléfono si el usuario es invitado
    const email = document.getElementById("email-address")?.value.trim() || null;
    const telefono = document.getElementById("celular")?.value.trim() || null;
    const direccion = document.getElementById("direccion").value.trim() || null;
    const nombreUsuario = document.getElementById('user_name').value;

    // Obtener los datos del botón y del HTML
    const userId = parseInt(event.target.dataset.userId, 10);
    const numeroPedido = event.target.dataset.numeroPedido;
    const fecha = event.target.dataset.fecha;
    const estado = "Pendiente";
    const productId = this.getAttribute('data-product-id');

    // Obtener valores numéricos (subtotal, iva, total) del HTML y convertirlos a float
    const subtotalneto = parseFloat(document.getElementById('subtotal').textContent.replace(/[^0-9.]/g, ''));
    const ivaNeto = parseFloat(document.getElementById('iva').textContent.replace(/[^0-9.]/g, ''));
    const totalNeto = parseFloat(document.getElementById('total').textContent.replace(/[^0-9.]/g, ''));

    // Obtener el total de productos en el carrito
    const totalProductos = carrito.reduce((total, producto) => total + producto.cantidad, 0);

    // Crear un array de productos con los datos que vamos a enviar
    const productos = carrito.map(producto => {
        console.log("Imagen del producto:", producto.imagen); // Log para la imagen de cada producto
        console.log("Id del producto:", producto.id); // Log para el ID de cada producto
        console.log("Nombre del producto:", producto.nombre); // Log para verificar el nombre

        return {
            id: producto.id,                // Incluir el id del producto
            nombre: producto.nombre,        // Nombre del producto
            cantidad: producto.cantidad,    // Cantidad de producto
            precio: producto.precio,        // Precio unitario
            subtotal: producto.precio * producto.cantidad, // Subtotal (precio * cantidad)
            imagen: producto.imagen.split('/').pop()         // Imagen del producto
        };
    });
    let usuarioId = userId || "invitado";  // Si userId es null, asignar "invitado"

    // Construir el objeto con los datos necesarios para el pedido
    const data = {
        usuario_id: usuarioId,
        usuario_nombre: nombreUsuario || "invitado",  // Campo adicional para almacenar el nombre de usuario
        fecha: fecha,
        subtotal: subtotalneto,
        iva: ivaNeto,
        total: totalNeto,
        estado: estado,
        numeroPedido: numeroPedido,
        totalProductos: totalProductos,
        metodoPago: metodoPago,
        productos: productos,
        deliveryOption: deliveryOption,  // Método de entrega (Recoger en tienda o Envío)
        email: email,
        telefono: telefono,
        direccion: direccion
    };

    // Mostrar los datos antes de enviarlos para depuración
    console.log("Data en formato JSON:", JSON.stringify(data));

    // Verificar si el usuario está logeado (userId no es null)
    if (!userId) {
        // Si el usuario no está logeado, solicita el correo desde el formulario (asegúrate de tener un input con id "email")
        if (!email) {
            alert("Por favor, ingresa tu correo electrónico para recibir la información del pedido.");
            return;
        }

        fetch("http://localhost:8088/Milogar/Controllers/PedidosController.php?action=generarPDF", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("El correo con el PDF ha sido enviado correctamente. Sigue las instrucciones enviadas al correo para la respectiva entrega de tu pedido");

                    // Vaciar el carrito
                    carrito = [];
                    localStorage.removeItem("carrito"); // Asegurar que también se elimine del almacenamiento local

                    // Actualizar la interfaz del carrito
                    actualizarContadorCarrito();
                    actualizarCarrito();

                    // Redirigir a la página principal
                    window.location.href = "/Milogar/index.php";
                } else {
                    alert("Hubo un problema al generar el reporte.");
                }
            })
            .catch(error => {
                console.error("Error al generar el reporte:", error);
                alert("Error al generar el reporte.");
            });

    } else {
        // Si el usuario está logeado, procede con el fetch normal para crear el pedido
        fetch("http://localhost:8088/Milogar/Controllers/PedidosController.php?action=ordenPedido", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) throw new Error("Error en la respuesta del servidor");
                return response.text();
            })
            .then(text => {
                console.log("Respuesta del servidor:", text);

                if (!text) {
                    throw new Error("Respuesta vacía del servidor");
                }

                // Verificar si la respuesta es JSON válido
                let result;
                try {
                    result = JSON.parse(text);
                } catch (error) {
                    throw new Error("Error al parsear la respuesta JSON: " + error.message);
                }

                // Manejar la respuesta del servidor
                if (result.success) {
                    alert("Pedido creado exitosamente con ID: " + result.pedido_id);
                    // Vaciar el carrito
                    carrito = [];
                    // Enviar el pedido al backend para generar el PDF y enviarlo por email
                    fetch("http://localhost:8088/Milogar/Controllers/PedidosController.php?action=generarPDF", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("El correo con el PDF ha sido enviado correctamente. Sigue las instrucciones enviadas al correo.");
                                // Vaciar el carrito
                                carrito = [];
                                localStorage.removeItem("carrito");

                                // Actualizar la interfaz del carrito
                                actualizarContadorCarrito();
                                actualizarCarrito();

                                // Redirigir a la página principal
                                window.location.href = "/Milogar/index.php";
                            } else {
                                alert("Hubo un problema al generar el reporte.");
                            }
                        })
                        .catch(error => {
                            console.error("Error al generar el reporte:", error);
                            alert("Error al generar el reporte.");
                        });
                } else {
                    alert("Error al crear el pedido: " + result.message);
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    }
});


//PRUEBAS
document.getElementById("generarPDF").addEventListener("click", function (event) {
    event.preventDefault();

    // Obtener la opción seleccionada en el radio button
    const opcionSeleccionada = document.querySelector("input[name='opcion']:checked");
    const metodoPago = opcionSeleccionada ? opcionSeleccionada.value : null; // El valor del método de pago seleccionado

    // Obtener los datos del botón y del HTML
    const userId = parseInt(event.target.dataset.userId, 10);
    const numeroPedido = event.target.dataset.numeroPedido;
    const fecha = event.target.dataset.fecha;
    const estado = "Pendiente"; // Puede cambiar dependiendo de la lógica
    const productId = this.getAttribute('data-product-id'); // No se está usando en el código, pero lo obtienes de todos modos
    console.log(userId);

    // Obtener valores numéricos (subtotal, iva, total) del HTML y convertirlos a float
    const subtotalneto = parseFloat(document.getElementById('subtotal').textContent.replace(/[^0-9.]/g, ''));
    const ivaNeto = parseFloat(document.getElementById('iva').textContent.replace(/[^0-9.]/g, ''));
    const totalNeto = parseFloat(document.getElementById('total').textContent.replace(/[^0-9.]/g, ''));

    // Agregar logs para depuración
    console.log("Subtotal:", subtotalneto);
    console.log("IVA:", ivaNeto);
    console.log("Total:", totalNeto);
    console.log("numero pedido", numeroPedido);
    console.log("Metodo de pago", metodoPago);

    // Obtener el total de productos en el carrito
    const totalProductos = carrito.reduce((total, producto) => total + producto.cantidad, 0);

    // Crear un array de productos con los datos que vamos a enviar
    const productos = carrito.map(producto => {
        console.log("Imagen del producto:", producto.imagen); // Log para la imagen de cada producto
        console.log("Id del producto:", producto.id); // Log para el ID de cada producto
        console.log("Nombre del producto:", producto.nombre); // Log para verificar el nombre

        return {
            id: producto.id,                // Incluir el id del producto
            nombre: producto.nombre, // Nombre del producto
            cantidad: producto.cantidad,     // Cantidad de producto
            precio: producto.precio,         // Precio unitario
            subtotal: producto.precio * producto.cantidad, // Subtotal (precio * cantidad)
            imagen: producto.imagen.split('/').pop()         // Imagen del producto
        };
    });

    // Construir el objeto con los datos necesarios para el reporte PDF
    const data = {
        usuario_id: userId,
        fecha: fecha,
        subtotal: subtotalneto,
        iva: ivaNeto,
        total: totalNeto,
        estado: estado,
        numeroPedido: numeroPedido,
        totalProductos: totalProductos,
        metodoPago: metodoPago,  // Aquí agregamos el método de pago
        productos: productos // Aquí están los productos con el id incluido
    };

    // Mostrar los datos antes de enviarlos para depuración
    console.log("Data en formato JSON:", JSON.stringify(data));

    // Enviar los datos al servidor para generar el PDF
    fetch("http://localhost:8088/Milogar/Controllers/PedidosController.php?action=generarPDF", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data) // Datos del pedido que queremos enviar al reporte PDF
    })
        .then(response => {
            if (!response.ok) throw new Error("Error al generar el reporte");

            // Obtener el PDF como un blob
            return response.blob();
        })
        .then(blob => {
            // Crear un enlace temporal para descargar el PDF
            const link = document.createElement('a');
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = `reporte_pedido_${userId}.pdf`; // Nombre del archivo PDF
            link.click();
            window.URL.revokeObjectURL(url); // Limpiar el objeto URL
        })
        .catch(error => console.error("Error al generar el reporte:", error));
});










