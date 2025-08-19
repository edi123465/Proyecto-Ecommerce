document.addEventListener("DOMContentLoaded", function () {
    fetch(`http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=obtenerPuntos&usuario_id=${usuarioSesion}`)
        .then((response) => response.json())
        .then((data) => {
            // Actualiza el contenido en el navegador
            document.getElementById("puntosUsuario").textContent = data.puntos;

            // Guarda los puntos en localStorage para persistir entre recargas
            localStorage.setItem('puntosUsuario', data.puntos);
        })
        .catch((error) => {
            console.error("Error al obtener puntos:", error);
            // Si hay un error, muestra un mensaje de error y establece el valor a 0
            document.getElementById("puntosUsuario").textContent = "Error";
            // En caso de error, puedes guardar un valor predeterminado (opcional)
            localStorage.setItem('puntosUsuario', '0');
        });

    // Verificar si ya existen puntos en localStorage al cargar la página
    const puntosGuardados = localStorage.getItem('puntosUsuario');
    if (puntosGuardados !== null) {
        document.getElementById("puntosUsuario").textContent = puntosGuardados;
    }
});
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
    actualizarTiempoUltimaActividad();
}

// Función para actualizar el tiempo de la última actividad
function actualizarTiempoUltimaActividad() {
    const ahora = new Date().getTime();
    localStorage.setItem('ultimaActividad', ahora);
}


// Función para vaciar el carrito
function vaciarCarrito() {
    carrito = [];
    localStorage.removeItem('carrito');
    localStorage.removeItem('ultimaActividad');
    actualizarCarrito();  // Actualiza el carrito en el modal
    Swal.fire({
        position: 'bottom-left',
        icon: 'info',
        title: 'Tu carrito se ha vaciado por inactividad.',
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        timerProgressBar: true,
    });
}

// Llamar a la función verificarInactividad cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    verificarInactividad();
    cargarCarrito();
    // También se actualiza la última actividad cuando se carga la página
    actualizarTiempoUltimaActividad();
});

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
            productoExistente.cantidad += cantidadSeleccionada;
        }
        else {
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
    localStorage.setItem('carrito', JSON.stringify(carrito));

    const carritoModal = document.querySelector('.list-group');
    const totalElement = document.querySelector('.fw-bold');
    const checkoutTotal = document.querySelector('#cart-total');
    const puntosRestantesElement = document.getElementById('puntos-restantes');

    carritoModal.innerHTML = '';
    let total = 0;

    const puntosDisponiblesTotales = parseInt(document.getElementById('puntosUsuario').textContent);
    let puntosUsadosTotales = 0;

    // Primero, calculamos los puntos actualmente usados
    carrito.forEach((producto) => {
        const esCanjeable = producto.precio === 0;
        if (esCanjeable) {
            puntosUsadosTotales += producto.cantidad * producto.puntos_necesarios;
        }
    });

    const puntosRestantesTotales = puntosDisponiblesTotales - puntosUsadosTotales;

    carrito.forEach((producto, index) => {
        const precioConDescuento = producto.precio;
        const precioUnitario = producto.precioOriginal;
        const descuento = producto.descuento || 0;
        const esCanjeable = precioConDescuento === 0;

        const subtotal = precioConDescuento * producto.cantidad;
        total += subtotal;

        let mensajePuntos = '';

        if (producto.cantidad >= producto.cantidad_minima_para_puntos && producto.puntos_otorgados > 0) {
            mensajePuntos = `¡Ganas ${producto.puntos_otorgados} puntos de canje!`;
        }

        const puntosUsadosActuales = producto.cantidad * producto.puntos_necesarios;
        const puntosUsadosProximos = (producto.cantidad + 1) * producto.puntos_necesarios;

        // Recalculamos cuántos puntos quedarían si este producto aumenta
        let puntosSimulados = puntosUsadosTotales;
        if (esCanjeable) {
            puntosSimulados = puntosUsadosTotales - puntosUsadosActuales + puntosUsadosProximos;
        }

        const deshabilitarMas = esCanjeable && (puntosSimulados > puntosDisponiblesTotales);

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
                            ${esCanjeable ? `<span class="text-muted">Canjeado por ${producto.puntos_necesarios * producto.cantidad} puntos (${producto.cantidad} item${producto.cantidad > 1 ? 's' : ''})</span>` :
                `<span class="text-muted">Valor Unitario: $${precioUnitario.toFixed(2)}</span>`}
                        </div>
                        ${mensajePuntos ? `<div class="mt-2 small text-success">${mensajePuntos}</div>` : ''}
                        <div class="mt-2 small">
                            <a href="#!" class="text-decoration-none remove-item" data-index="${index}">
                                <span class="me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </span>Remove
                            </a>
                        </div>
                    </div>
                 <div class="col-3 d-flex justify-content-end">
    <div class="d-flex align-items-center gap-1 flex-nowrap" style="margin-left: 3mm;">
        <button type="button" class="button-minus btn btn-outline-secondary px-2 py-1" data-index="${index}">-</button>
        <input type="number" value="${producto.cantidad}" min="1"
            class="quantity-field text-center" style="width: 50px;" data-index="${index}">
        <button type="button" class="button-plus btn btn-outline-secondary px-2 py-1" data-index="${index}" ${deshabilitarMas ? 'disabled' : ''}>+</button>
    </div>
</div>


                    <div class="col-2 text-end">
                        <span class="fw-bold">$${subtotal.toFixed(2)}</span>
                    </div>
                </div>
            </li>
        `;
    });

    // Mostrar total
    totalElement.textContent = `$${total.toFixed(2)}`;
    checkoutTotal.textContent = `$${total.toFixed(2)}`;

    // Mostrar puntos restantes
    const puntosRestantes = puntosDisponiblesTotales - puntosUsadosTotales;
    if (puntosRestantesElement) {
        puntosRestantesElement.innerHTML = `
            <div class="alert alert-info mt-3">
                Tienes <strong>${puntosRestantes}</strong> puntos disponibles para canjear productos.
            </div>
        `;
    }

    asignarEventosCarrito();
    document.querySelectorAll('.quantity-field').forEach(input => {
        input.addEventListener('blur', (e) => {
            const index = parseInt(e.target.getAttribute('data-index'));
            const nuevaCantidad = parseInt(e.target.value);

            if (!isNaN(nuevaCantidad) && nuevaCantidad > 0) {
                carrito[index].cantidad = nuevaCantidad;
                actualizarCarrito(); // Vuelve a calcular todo
            } else {
                e.target.value = carrito[index].cantidad; // Restaura la cantidad anterior si se ingresó algo inválido
            }
        });
    });

}

// Función para asignar eventos de clic a los botones de cantidad y eliminar
function asignarEventosCarrito() {
    document.querySelectorAll('.button-minus').forEach(btn => {
        btn.addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'));
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
                actualizarCarrito(); // 🔁 Vuelve a renderizar el carrito completo
                actualizarContadorCarrito(); // ✅ Agregado

            }
        });
    });

    document.querySelectorAll('.button-plus').forEach(btn => {
        btn.addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'));
            const producto = carrito[index];

            const puntosDisponibles = parseInt(document.getElementById('puntosUsuario').textContent);
            const puntosNecesariosProximos = (producto.cantidad + 1) * producto.puntos_necesarios;

            // Si es canjeable y hay puntos suficientes
            if (producto.precio === 0 && puntosNecesariosProximos > puntosDisponibles) {
                return; // No agregar más
            }

            carrito[index].cantidad++;
            actualizarCarrito(); // 🔁 Vuelve a renderizar el carrito completo
            actualizarContadorCarrito(); // ✅ Agregado

        });
    });

    // Elimina productos
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'));
            carrito.splice(index, 1);
            actualizarCarrito();
            actualizarContadorCarrito();
        });
    });
}

async function actualizarPuntos(usuarioId, puntos, accion) {
    try {
        const response = await fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                usuarioId: usuarioId,
                puntos: puntos,
                accion: accion
            }),
        });

        const data = await response.json();

        if (data.status === 'ok') {
            document.getElementById('puntosUsuario').textContent = data.puntos;
        } else {
            alert(data.mensaje);
        }
    } catch (error) {
        console.error('Error al actualizar puntos:', error);
    }
}

// Función para actualizar el contador del carrito
function actualizarContadorCarrito() {
    const totalProductos = carrito.reduce((total, producto) => total + producto.cantidad, 0);

    // Actualiza todos los contadores del carrito (para PC y móvil)
    document.querySelectorAll('.cart-counter').forEach(counter => {
        counter.textContent = totalProductos;
    });
}



function aumentarCantidad(event) {
    const index = event.target.dataset.index;
    const producto = carrito[index];

    const puntosNecesarios = producto.puntos_necesarios;
    const usuarioId = document.getElementById('puntosUsuario').dataset.userId;

    // Verificar si es canjeable
    if (producto.precio === 0) {
        const puntosAUsar = puntosNecesarios;

        fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=descontar&usuarioId=${usuarioId}&puntos=${puntosAUsar}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    carrito[index].cantidad++;
                    document.getElementById('puntosUsuario').textContent = data.puntos;
                    guardarCarrito();
                    actualizarCarrito();
                    actualizarContadorCarrito();
                } else {
                    alert(data.mensaje || 'No tienes suficientes puntos para este canje.');
                }
            });
    } else {
        // Producto normal
        carrito[index].cantidad++;
        guardarCarrito();
        actualizarCarrito();
    }
}


function disminuirCantidad(event) {
    const index = event.target.dataset.index;
    const producto = carrito[index];
    const usuarioId = document.getElementById('puntosUsuario').dataset.userId;

    if (producto.cantidad > 1) {
        if (producto.precio === 0) {
            const puntosADevolver = producto.puntos_necesarios;

            fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=devolver&usuarioId=${usuarioId}&puntos=${puntosADevolver}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        carrito[index].cantidad--;
                        document.getElementById('puntosUsuario').textContent = data.puntos;
                        guardarCarrito();
                        actualizarCarrito();
                        actualizarContadorCarrito();

                    }
                });
        } else {
            carrito[index].cantidad--;
            guardarCarrito();
            actualizarCarrito();
        }
    } else {
        // Si es canjeable, devolver puntos al eliminarlo
        if (producto.precio === 0) {
            const puntosADevolver = producto.puntos_necesarios * producto.cantidad;

            fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=devolver&usuarioId=${usuarioId}&puntos=${puntosADevolver}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        carrito.splice(index, 1);
                        document.getElementById('puntosUsuario').textContent = data.puntos;
                        guardarCarrito();
                        actualizarCarrito();
                        actualizarContadorCarrito();

                    }
                });
        } else {
            carrito.splice(index, 1);
            guardarCarrito();
            actualizarCarrito();
        }
    }
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
    window.location.href = 'http://localhost:8080/Milogar/shop-checkout.php';
});

function actualizarDetallePedido() {
    const orderDetails = document.getElementById('order-details');
    const subtotalElement = document.getElementById('subtotal');
    const descuentoElement = document.getElementById('descuento');
    const totalElement = document.getElementById('total');
    const totalPuntosElement = document.getElementById('total-puntos');
    const puntosDescontarElement = document.getElementById('puntos-descontar'); // 🔸 NUEVO

    if (!orderDetails || !subtotalElement || !descuentoElement || !totalElement || !puntosDescontarElement) {
        console.error("Uno o más elementos no existen en el DOM.");
        return;
    }

    // Limpiar los productos previos en el detalle
    orderDetails.innerHTML = '';

    let subtotal = 0;
    let totalPuntos = 0;
    let descuento = 0;
    let puntosADescontar = 0; // 🔸 NUEVO: acumulador de puntos a restar

    console.log("=== Detalles del carrito ===");

    carrito.forEach((producto, index) => {

        const subtotalProducto = producto.precio * producto.cantidad;
        subtotal += subtotalProducto;

        console.log(`Producto ${index + 1}:`);
        console.log(`- Nombre: ${producto.nombre}`);
        console.log(`- Precio: ${producto.precio}`);
        console.log(`- Cantidad: ${producto.cantidad}`);
        console.log(`- Subtotal Producto: ${subtotalProducto}`);
        console.log(`- Puntos necesarios: ${producto.puntos_necesarios}`);
        console.log(`- Puntos otorgados: ${producto.puntos_otorgados}`);
        console.log(`- Cantidad mínima para puntos: ${producto.cantidad_minima_para_puntos}`);
        console.log(`- COSTO ENVIO: ${producto.cantidad_minima_para_puntos}`);

        // Detectar cupones
        if (producto.nombre.startsWith('Cupon de descuento por un valor de')) {
            const match = producto.nombre.match(/(\d+)(?:\$|\s*USD)?/i);
            if (match && match[1]) {
                const valorCupon = parseFloat(match[1]);
                const descuentoProducto = valorCupon * producto.cantidad;
                descuento += descuentoProducto;
                console.log(`✅ Cupón aplicado: -$${descuentoProducto.toFixed(2)} (${producto.cantidad} x $${valorCupon})`);
            }
        }

        // Verificar si gana puntos
        let mensajePuntos = '';
        if (producto.cantidad >= producto.cantidad_minima_para_puntos && producto.puntos_otorgados > 0) {
            mensajePuntos = `<small class="text-success d-block mt-1">¡Ganas ${producto.puntos_otorgados} puntos de canje!</small>`;
            totalPuntos += producto.puntos_otorgados;
        } else {
            console.log(`-> Este producto NO otorga puntos.`);
        }

        let mensajeCanjeado = '';
        if (producto.puntos_necesarios && producto.puntos_necesarios > 0) {
            const puntosPorProducto = producto.puntos_necesarios * producto.cantidad;
            puntosADescontar += puntosPorProducto;
            mensajeCanjeado = `<small class="text-primary d-block mt-1">Canjeado por ${producto.puntos_necesarios} puntos.</small>`;
            console.log(`➡️ Canjeado por puntos: ${producto.cantidad}`);
        }


        orderDetails.innerHTML += `
    <li class="list-group-item py-3 px-2">
    <div class="row align-items-center gy-3 gx-2">

        <!-- Imagen del producto -->
        <div class="col-4 col-md-2 text-center">
        <img src="${producto.imagen}" alt="${producto.nombre}" 
            class="img-fluid rounded-3 border shadow-sm" style="max-width: 80px;">
        </div>

        <!-- Nombre y mensajes -->
        <div class="col-8 col-md-4">
        <h6 class="fw-semibold mb-1 text-break">${producto.nombre}</h6>
        ${mensajeCanjeado ? `<div class="badge bg-warning text-dark small">${mensajeCanjeado}</div>` : ''}
        ${mensajePuntos ? `<div class="text-muted small">${mensajePuntos}</div>` : ''}
        </div>

        <!-- Cantidad -->
        <div class="col-12 col-md-3 text-center">
        <div class="d-flex justify-content-center align-items-center gap-2">
            <button class="btn btn-outline-danger btn-sm" onclick="cambiarCantidad(${index}, -1)">−</button>
            <input type="number" id="cantidad-${index}" class="form-control form-control-sm text-center" 
                value="${producto.cantidad}" min="1" style="width: 60px;" onchange="actualizarCantidad(${index})">
            <button class="btn btn-outline-success btn-sm" onclick="cambiarCantidad(${index}, 1)">+</button>
        </div>
        </div>

        <!-- Subtotal -->
        <div class="col-6 col-md-2 text-center text-md-end">
        <span class="fw-bold text-success">$${subtotalProducto.toFixed(2)}</span>
        </div>

        <!-- Eliminar -->
        <div class="col-6 col-md-1 text-center">
        <button class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(${index})">
            <i class="bi bi-trash-fill"></i>
        </button>
        </div>

    </div>
    </li>
    `;

    });

    console.log("Subtotal general: ", subtotal.toFixed(2));
    console.log("Total puntos otorgados: ", totalPuntos);
    console.log("Puntos a descontar por canje: ", puntosADescontar);



    totalPuntosElement.textContent = totalPuntos;
    puntosDescontarElement.textContent = puntosADescontar; // 🔸 NUEVO: actualiza el elemento

    const totalConDescuento = (subtotal - descuento).toFixed(2);

    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    descuentoElement.textContent = `-$${descuento.toFixed(2)}`;
    totalElement.textContent = `$${totalConDescuento}`;

    localStorage.setItem('carrito', JSON.stringify(carrito));

    actualizarContadorCarrito();
    actualizarCarrito();
    calcularCostoEnvio();
}
const provinciaSelect = document.getElementById("provincia");
const costoEnvioSpan = document.getElementById("costoEnvio");

provinciaSelect.addEventListener("change", calcularCostoEnvio);

function calcularCostoEnvio() {
    let subtotal = parseFloat(document.getElementById("subtotal").textContent.replace('$', '')) || 0;
    let descuento = parseFloat(document.getElementById("descuento").textContent.replace('-$', '')) || 0;
    let total = subtotal - descuento;
    let costoEnvio = 0;

    const tipoEnvio = provinciaSelect.value;

    if (tipoEnvio === "sectorEnvio" && total < 20) {
        costoEnvio = 3;
    }

    // Guardar en localStorage
    localStorage.setItem('costoEnvio', costoEnvio);

    // Actualizar el costo de envío en pantalla
    costoEnvioSpan.textContent = `$${costoEnvio.toFixed(2)}`;

    // Recalcular el total incluyendo el envío
    const totalConEnvio = total + costoEnvio;
    document.getElementById("total").textContent = `$${totalConEnvio.toFixed(2)}`;
}

window.addEventListener("DOMContentLoaded", () => {
    const costoEnvioGuardado = parseFloat(localStorage.getItem("costoEnvio")) || 0;
    costoEnvioSpan.textContent = `$${costoEnvioGuardado.toFixed(2)}`;

    let subtotal = parseFloat(document.getElementById("subtotal").textContent.replace('$', '')) || 0;
    let descuento = parseFloat(document.getElementById("descuento").textContent.replace('-$', '')) || 0;
    let total = subtotal - descuento + costoEnvioGuardado;

    document.getElementById("total").textContent = `$${total.toFixed(2)}`;
});

function cambiarCantidad(index, delta) {
    const puntosUsuario = parseInt(document.getElementById('puntosUsuario').textContent);
    const producto = carrito[index];
    const nuevaCantidad = producto.cantidad + delta;

    if (nuevaCantidad < 1) return;

    const totalPuntosRestantes = carrito.reduce((acc, item, i) => {
        if (item.puntos_necesarios > 0) {
            return acc + ((i === index ? nuevaCantidad : item.cantidad) * item.puntos_necesarios);
        }
        return acc;
    }, 0);

    if (totalPuntosRestantes > puntosUsuario) {
        Swal.fire({
            icon: 'error',
            title: 'Límite de puntos alcanzado',
            text: `No puedes aumentar más la cantidad de "${producto.nombre}" porque superas tus puntos disponibles.`,
            confirmButtonColor: '#d33',
        });
        return;
    }

    producto.cantidad = nuevaCantidad;
    // Solo actualizar si es producto de canje
    if (producto.puntos_necesarios > 0) {
        producto.total_puntos_necesarios = producto.puntos_necesarios * producto.cantidad;
    }

    actualizarDetallePedido();
}


function actualizarCantidad(index) {
    const puntosUsuario = parseInt(document.getElementById('puntosUsuario').textContent);
    const inputCantidad = document.getElementById(`cantidad-${index}`);
    let nuevaCantidad = parseInt(inputCantidad.value);

    if (isNaN(nuevaCantidad) || nuevaCantidad < 1) {
        nuevaCantidad = 1;
        inputCantidad.value = nuevaCantidad;
    }

    const producto = carrito[index];
    const totalPuntosRestantes = carrito.reduce((acc, item, i) => {
        if (item.puntos_necesarios > 0) {
            if (i === index) {
                return acc + (nuevaCantidad * item.puntos_necesarios);
            }
            return acc + (item.cantidad * item.puntos_necesarios);
        }
        return acc;
    }, 0);

    if (totalPuntosRestantes > puntosUsuario) {
        Swal.fire({
            icon: 'error',
            title: 'Límite de puntos alcanzado',
            text: `No puedes establecer esta cantidad para "${producto.nombre}" porque superas tus puntos disponibles.`,
            confirmButtonColor: '#d33',
        });
        inputCantidad.value = producto.cantidad; // Restaurar la cantidad anterior
        return;
    }

    producto.cantidad = nuevaCantidad;
    producto.puntos_otorgados = producto.puntos_necesarios * producto.cantidad;

    actualizarDetallePedido();
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
        Swal.fire({
            icon: 'warning',
            title: 'Carrito vacío',
            text: 'Tu carrito está vacío. Agrega al menos un producto para realizar un pedido.',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3085d6'
        });
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
    const direccion = document.getElementById("calle").value.trim() || null;
    const nombreUsuario = document.getElementById('user_name').value;
    //nuevos campos
    const tipoEnvio = document.getElementById("provincia")?.value || null;
    const empresaEnvio = document.getElementById("empresaEnvio")?.value || null;
    const provinciaEnvio = document.getElementById("provinciaEnvio")?.value || null;
    const ciudad = document.getElementById("ciudad")?.value.trim() || null;
    const referencias = document.getElementById("referencias")?.value.trim() || null;
    // Obtener el texto del span y convertirlo a número
    const costoEnvioTexto = document.getElementById('costoEnvio').textContent || '$0.00';
    const costoEnvio = parseFloat(costoEnvioTexto.replace('$', '')) || 0;

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
    const puntosAcumulados = parseFloat(document.getElementById("total-puntos").textContent) || 0;
    const opcionEntregaSeleccionada = document.querySelector('input[name="deliveryOption"]:checked');

    if (!opcionEntregaSeleccionada) {
        Swal.fire({
            icon: 'warning',
            title: 'Método de entrega requerido',
            text: 'Por favor, selecciona una opción de entrega para continuar.',
            confirmButtonText: 'Entendido',
            timer: 4000,
            timerProgressBar: true
        });
        return;
    }


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
    const descuentoNeto = parseFloat(document.getElementById('descuento').textContent.replace(/[^0-9.]/g, ''));
    // Validar que el descuento no sea mayor al subtotal
    if (descuentoNeto > subtotalneto) {
        Swal.fire({
            icon: 'error',
            title: 'Descuento inválido',
            text: 'El valor del descuento no puede ser mayor al valor a comprar.',
            confirmButtonText: 'Corregir'
        });
        return;
    }
    // Construir el objeto con los datos necesarios para el pedido
    const data = {
        usuario_id: usuarioId,
        usuario_nombre: nombreUsuario || "invitado",  // Campo adicional para almacenar el nombre de usuario
        fecha: fecha,
        subtotal: subtotalneto,
        descuento: descuentoNeto, // <-- Aquí agregamos el campo descuento
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
        direccion: direccion,
        puntos: puntosAcumulados, // <-- Aquí se envían los puntos acumulados
        // Campos adicionales que estás agregando ahora
        tipoEnvio: tipoEnvio,                 // "sectorEnvio" o "otra"
        empresaEnvio: empresaEnvio,           // "servientrega" o "favorcito"
        provinciaEnvio: provinciaEnvio,       // Provincia seleccionada
        ciudad: ciudad,                       // Ciudad ingresada
        referencias: referencias,            // Referencias opcionales
        costoEnvio: costoEnvio

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
        if (!telefono) {
            alert("Por favor, ingresa tu número de celular.");
            return;
        }

        if (!deliveryOption) {
            alert("Por favor, selecciona un método de entrega.");
            return;
        }

        // Si la opción es envío a domicilio, valida campos de envío
        if (deliveryOption === "agregarDireccionEnvio") {
            if (!tipoEnvio) {
                alert("Tipo de envío no seleccionado.");
                return;
            }
            if (!provinciaEnvio) {
                alert("Provincia de envío no seleccionada.");
                return;
            }
            if (!ciudad) {
                alert("Ciudad de envío no seleccionada.");
                return;
            }
            if (!direccion) {
                alert("Dirección de envío no ingresada.");
                return;
            }
        }



        fetch("http://localhost:8080/Milogar/Controllers/PedidosController.php?action=generarPDF", {
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
                    window.location.href = "/index.php";
                } else {
                    alert("Hubo un problema al generar el reporte.");
                }
            })
            .catch(error => {
                console.error("Error al generar el reporte:", error);
                alert("Error al generar el reporte.");
            });

    } else {
        // Validar todos los campos obligatorios
        if (
            !email ||
            !telefono ||
            !tipoEnvio ||
            !provinciaEnvio ||
            !ciudad ||
            !direccion ||
            !fecha ||
            !numeroPedido ||
            !estado ||
            !metodoPago ||
            !deliveryOption ||
            !totalNeto ||
            productos.length === 0
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Faltan datos obligatorios para realizar el pedido. Por favor, completa todos los campos.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Si el usuario está logeado, procede con el fetch normal para crear el pedido
        fetch("http://localhost:8080/Milogar/Controllers/PedidosController.php?action=ordenPedido", {
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
                    // Mostrar mensaje de procesamiento
                    Swal.fire({
                        title: 'Espere...',
                        text: 'Su pedido está siendo procesado.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });                                        // Vaciar el carrito
                    carrito = [];
                    // Enviar el pedido al backend para generar el PDF y enviarlo por email
                    fetch("http://localhost:8080/Milogar/Controllers/PedidosController.php?action=generarPDF", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Correo enviado!',
                                    text: 'El correo con el PDF ha sido enviado correctamente. Sigue las instrucciones enviadas al correo.',
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // ✅ Descontar puntos después del Swal y antes de redirigir
                                        if (userId) {
                                            const puntosADescontar = parseInt(document.getElementById("puntos-descontar").textContent);
                                            console.log("Puntos a descontar antes del fetch:", puntosADescontar);
                                            console.log("USUARIO ID: ", userId);
                                            if (puntosADescontar > 0) {
                                                fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=descontarPuntos', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        usuario_id: userId,
                                                        puntos: puntosADescontar
                                                    })
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log("Respuesta al descontar puntos:", data);
                                                        if (data.success) {
                                                            Swal.fire({
                                                                icon: 'info',
                                                                title: 'Puntos actualizados',
                                                                text: data.message,
                                                                confirmButtonText: 'Ok'
                                                            }).then(() => {
                                                                // Vaciar el carrito
                                                                carrito = [];
                                                                localStorage.removeItem("carrito");

                                                                actualizarContadorCarrito();
                                                                actualizarCarrito();

                                                                // Redirigir
                                                                window.location.href = "/Milogar/index.php";
                                                            });
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'warning',
                                                                title: 'Advertencia',
                                                                text: data.message,
                                                                confirmButtonText: 'Entendido'
                                                            }).then(() => {
                                                                window.location.href = "/Milogar/index.php";
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error al descontar puntos:', error);
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'Ocurrió un error al intentar descontar los puntos.',
                                                            confirmButtonText: 'Ok'
                                                        }).then(() => {
                                                            window.location.href = "/Milogar/index.php";
                                                        });
                                                    });
                                            } else {
                                                // Si no hay puntos a descontar, continuar normal
                                                carrito = [];
                                                localStorage.removeItem("carrito");

                                                actualizarContadorCarrito();
                                                actualizarCarrito();

                                                window.location.href = "/Milogar/index.php";
                                            }
                                        } else {
                                            // Invitado: solo redirigir
                                            carrito = [];
                                            localStorage.removeItem("carrito");

                                            actualizarContadorCarrito();
                                            actualizarCarrito();

                                            window.location.href = "/Milogar/index.php";
                                        }
                                    }
                                });

                            } else {
                                alert("Hubo un problema al generar el reporte.");
                            }
                        })
                        .catch(error => {
                            console.error("Error al generar el reporte:", error);
                            alert("Error al generar el reporte.");
                        });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Atención!',
                        text: result.message, // Muestra el mensaje "No puedes realizar un nuevo pedido mientras el último esté pendiente."
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    }
});


//Para el boton de payphone !
document.addEventListener("DOMContentLoaded", function () {
    const direccionContainer = document.getElementById("direccionContainer");
    const recogerOption = document.getElementById("recogerEnTienda");
    const enviarOption = document.getElementById("agregarDireccionEnvio");
    const provinciaSelect = document.getElementById("provincia");
    const camposAdicionales = document.getElementById("camposDireccionAdicionales");

    function toggleDireccion() {
        if (enviarOption.checked) {
            direccionContainer.style.display = "block";
        } else {
            direccionContainer.style.display = "none";
            provinciaSelect.value = "";
            camposAdicionales.style.display = "none";
        }
    }

    function toggleCamposAdicionales() {
        if (provinciaSelect.value !== "") {
            camposAdicionales.style.display = "block";
        } else {
            camposAdicionales.style.display = "none";
        }
    }

    // Eventos
    recogerOption.addEventListener("change", toggleDireccion);
    enviarOption.addEventListener("change", toggleDireccion);
    provinciaSelect.addEventListener("change", toggleCamposAdicionales);

    // Inicialización
    toggleDireccion();
    toggleCamposAdicionales();
});

function generarPago() {
    // Obtener el valor total desde la tienda virtual
    let totalElement = document.getElementById("total").innerText;

    // Convertirlo a número y asegurarse de que sea válido
    let total = parseFloat(totalElement.replace(/[^0-9.]/g, '')) * 100;

    if (isNaN(total) || total <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Total no válido',
            text: 'El total del pedido no es válido. Verifica el monto antes de continuar.',
            confirmButtonText: 'Aceptar',
            timer: 4000,
            timerProgressBar: true
        });
        return;
    }


    const redirectClientTraxID = Date.now();

    // Preparar cabecera para la solicitud
    const headers = {
        "Content-Type": "application/json",
        "Authorization": "Bearer bYrfxfYmTOu8sFy3hLe6w85G0VXkB97WgrBxCcVmRDh2Z6yrNo88wSvg12Q8l1UlYdXd2iCFB4q-DGllmKJBNTMGCvEF9sjKFAvdP6-qML-kRTlbfKyMTo1xmOWi3AJ0G1XwjRLOBfClgLDfGMd2wdrLA2lReu7iGV3DSjsoco1VbPIhBFVfbTBwZJMj01vjB3aLOce7aKf9VHcSRDqFqDqkrggqYHhwsKoUxaEes27SYLLZVI6_FokDKYDaiDoFFTJHmBHb8nPp-ZUXEmBkIdU36tSs0XQT9WasJyNRut4KnPxVFDmDwDuDNC7nDMmJ32LWWzM02K6NbLOzFg9JO6MKYhA", // Tu API Key
    };

    // Preparamos el objeto JSON para la solicitud
    const bodyJSON = {
        "amount": total,  // Monto total
        "amountWithoutTax": total, // Monto sin impuestos
        "amountWithTax": 0, // Monto con impuestos
        "tax": 0, // Impuesto aplicado
        "reference": "Prueba Boton fetch", // Referencia opcional
        "currency": "USD", // Moneda
        "clientTransactionId": redirectClientTraxID, // ID único de transacción
        "storeId": "757a105c-7d02-4b21-b919-06667a9f4991",  //  Store ID
        "ResponseUrl": "http://localhost:8080/Milogar/respuesta.php" // URL de respuesta
    };

    // URL de la API de PayPhone
    const url = "https://pay.payphonetodoesposible.com/api/button/Prepare";

    // Realizar la solicitud POST
    fetch(url, {
        method: "POST",
        headers: headers,
        body: JSON.stringify(bodyJSON)
    })
        .then(res => res.json())
        .then(data => {
            console.log("Respuesta de PayPhone:", data);

            if (data.payWithPayPhone || data.payWithCard) {
                // Redirigir al usuario a la URL de pago
                if (data.payWithPayPhone) {
                    window.location.href = data.payWithPayPhone;
                } else if (data.payWithCard) {
                    window.location.href = data.payWithCard;
                }
            } else {
                alert("Error al procesar el pago. Inténtalo de nuevo.");
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            alert("Hubo un problema con el pago. Inténtalo más tarde.");
        });
}



