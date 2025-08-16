document.addEventListener("DOMContentLoaded", function () {
    fetch(`http://localhost:8088/Milogar/Controllers/UsuarioController.php?action=obtenerPuntos&usuario_id=${usuarioSesion}`)
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

// Función para obtener los canjeables activos de la tienda
function obtenerCanjeablesActivos() {
    fetch('http://localhost:8088/Milogar/Controllers/CanjeController.php?action=obtenerCanjeablesTienda')
        .then(response => response.json())
        .then(data => {
            if (data.success) {

                const productosContainer = document.getElementById('productos-canjeables');
                productosContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar los productos

                // Iterar sobre los productos y crear las tarjetas dinámicamente
                data.canjeables.forEach(producto => {
                    console.log(data);                    // Verificar si la imagen tiene una URL completa
                    const imagenUrl = producto.imagen.startsWith('http') ? producto.imagen : 'http://localhost:8088/Milogar/assets/imagenesMilogar/productos/' + producto.imagen;

                    // Crear el HTML dinámicamente para cada producto
                    const productHTML = `
                    <div class="col">
                        <div class="card card-product">
                            <div class="card-body">
                                <div class="text-center position-relative">
                                    <!-- Badge de descuento dentro de la tarjeta -->
                                    ${producto.valor_descuento > 0 ? `
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-warning"> ${producto.valor_descuento}% Desc.</span>
                                        </div>
                                    ` : ''}

                                    <a href="#!">
                                        <img src="${imagenUrl}" alt="${producto.nombre}" class="mb-3 img-fluid">
                                    </a>
                                    
                                    <div class="card-product-action">
                                        <a href="#!" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-id="${producto.id}">
                                            Ver Detalle
                                        </a>
                                    </div>
                                </div>
                                <h2 class="fs-6">
                                    <a href="#!" class="text-inherit text-decoration-none">${producto.nombre}</a>
                                </h2>
                                <p class="text-muted">${producto.descripcion}</p>
                                
                                <div>
                                    <span class="text-dark">Puntos necesarios: ${producto.puntos_necesarios}</span>
                                </div>
                                
                               <div class="d-flex justify-content-between align-items-center mt-3">
  
    
                                <!-- Input numérico elegante para cambiar cantidad -->
                                <div>
                                    <input type="number" class="form-control cantidad-producto" 
                                        data-id="${producto.producto_id}"
                                        data-puntos="${producto.puntos_necesarios}" 
                                            data-nombre="${producto.nombre}" 
                                        min="0" value="" 
                                        placeholder="Cantidad"
                                        style="width: 120px; text-align: center; border-radius: 5px;">
                                </div>


                                <div>
                                    <a href="#!" class="btn btn-primary btn-sm add-to-cart" 
                                    data-id="${producto.id}" 
                                    data-producto-id="${producto.producto_id}" 
                                    data-nombre="${producto.nombre}"

                                    data-puntos="${producto.puntos_necesarios}"
                                    data-imagen="${imagenUrl}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-plus">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Agregar
                                    </a>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>
                    `;
                    // Agregar el HTML del producto al contenedor
                    productosContainer.innerHTML += productHTML;
                });
                // Prevenir el aumento de cantidad con las teclas de flecha
                document.addEventListener('keydown', function (e) {
                    const cantidadInput = e.target;
                    if (cantidadInput.classList.contains('cantidad-producto')) {
                        // Prevenir las teclas de flecha
                        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                            e.preventDefault();
                        }
                    }
                });

                // Al cargar la página
                document.addEventListener('DOMContentLoaded', function () {
                    // Verificar si hay puntos guardados en localStorage
                    const puntosGuardados = localStorage.getItem('puntosUsuario');
                    if (puntosGuardados !== null) {
                        // Si hay puntos guardados, actualizamos el texto del contenedor
                        document.getElementById('puntosUsuario').textContent = puntosGuardados;
                    } else {
                        // Si no hay puntos guardados, mostrar un valor predeterminado (opcional)
                        document.getElementById('puntosUsuario').textContent = '0';
                    }
                });

                document.addEventListener('DOMContentLoaded', function () {
                    mostrarPuntosRestantes();

                    const puntosDisponibles = parseInt(document.getElementById('puntosUsuario').textContent);
                    const puntosUsados = calcularPuntosUsados();
                    const puntosRestantes = puntosDisponibles - puntosUsados;

                    actualizarDisponibilidadDeCanjes(puntosRestantes);
                });

                document.addEventListener('click', function (e) {
                    if (e.target.closest('.add-to-cart')) {
                        let puntosDisponibles = parseInt(document.getElementById('puntosUsuario').textContent);

                        e.preventDefault();
                        const boton = e.target.closest('.add-to-cart');
                        const imagenProducto = boton.dataset.imagen;

                        const puntosProducto = parseInt(boton.dataset.puntos);
                        const nombreProducto = boton.dataset.nombre;
                        
                        const idProducto = boton.dataset.productoId;
                        const cantidadInput = document.querySelector(`input[data-id="${idProducto}"]`);
                        let cantidadProducto = parseInt(cantidadInput.value);

                        if (isNaN(cantidadProducto) || cantidadProducto <= 0) {
                            cantidadProducto = 0;
                            cantidadInput.value = cantidadProducto;
                            Swal.fire({ icon: 'warning', title: 'Cantidad no válida', text: `Debes ingresar una cantidad mayor que 0 para agregar "${nombreProducto}".` });
                            return;
                        }

                        const totalPuntosNecesarios = cantidadProducto * puntosProducto;

                        if (puntosDisponibles >= totalPuntosNecesarios) {
                            const agregado = agregarProductoAlCarrito(idProducto, nombreProducto, cantidadProducto, puntosProducto, puntosDisponibles, imagenProducto);

                            if (agregado) {
                                Swal.fire({
                                    position: 'bottom-left',
                                    icon: 'success',
                                    title: `¡"${nombreProducto}" agregado correctamente!`,
                                    showConfirmButton: false,
                                    timer: 4000,
                                    toast: true,
                                    timerProgressBar: true,
                                });

                                mostrarPuntosRestantes(); // Recalcula puntos virtuales usados y muestra
                                actualizarDisponibilidadDeCanjes(puntosDisponibles - calcularPuntosUsados());
                            }
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Puntos insuficientes',
                                text: `No tienes suficientes puntos para canjear "${nombreProducto}".`
                            });
                        }
                    }
                });

                function agregarProductoAlCarrito(id, nombre, cantidad, puntos, puntosUsuario, imagen) {
                    const productoId = parseInt(id);
                    const productoExistente = carrito.find(item => item.id === productoId);
                
                    const puntosYaUsados = calcularPuntosUsados();
                    const puntosDisponiblesActuales = puntosUsuario - puntosYaUsados;
                
                    const puntosNecesariosParaEstaCantidad = cantidad * puntos;
                
                    if (puntosNecesariosParaEstaCantidad > puntosDisponiblesActuales) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Puntos insuficientes',
                            text: `Agregar "${nombre}" supera tus puntos restantes (${puntosDisponiblesActuales} puntos disponibles).`,
                            confirmButtonColor: '#d33',
                        });
                        return false;
                    }
                
                    if (productoExistente) {
                        const puntosTotalesConNuevoIntento = (productoExistente.cantidad + cantidad) * puntos;
                
                        if (puntosTotalesConNuevoIntento > puntosUsuario) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Límite alcanzado',
                                text: `Ya tienes "${productoExistente.cantidad}" unidades de "${nombre}". Agregar más supera tus puntos disponibles.`,
                                confirmButtonColor: '#d33',
                            });
                            return false;
                        }
                
                        productoExistente.cantidad += cantidad;
                        productoExistente.puntos_otorgados = productoExistente.puntos_necesarios * productoExistente.cantidad;
                    } else {
                        const producto = {
                            id: productoId,
                            nombre: nombre,
                            cantidad: cantidad,
                            puntos_necesarios: puntos,
                            precio: 0,
                            imagen: imagen,
                            puntos_otorgados: puntos * cantidad,
                        };
                        carrito.push(producto);
                    }
                
                    guardarCarrito();
                    actualizarCarrito();
                    actualizarContadorCarrito();
                
                    return true;
                }
                
                function calcularPuntosUsados() {
                    return carrito
                        .filter(producto => producto.precio === 0)
                        .reduce((total, producto) => total + (producto.puntos_necesarios * producto.cantidad), 0);
                }
                function mostrarPuntosRestantes() {
                    const puntosUsados = calcularPuntosUsados();
                    const puntosRestantes = parseInt(document.getElementById('puntosUsuario').textContent) - puntosUsados;

                    const resultado = document.getElementById('resultadoPuntos');
                    resultado.textContent = `Puntos restantes: ${puntosRestantes}`;
                    resultado.style.color = puntosRestantes >= 0 ? 'green' : 'red';
                }

                function actualizarDisponibilidadDeCanjes(puntosRestantes) {
                    // Aquí puedes recorrer tus botones o cards y habilitar/deshabilitar visualmente
                    document.querySelectorAll('.add-to-cart').forEach(boton => {
                        const puntosProducto = parseInt(boton.dataset.puntos);
                        if (puntosProducto > puntosRestantes) {
                            boton.disabled = true;
                        } else {
                            boton.disabled = false;
                        }
                    });
                }
                document.addEventListener('DOMContentLoaded', function () {
                    cargarCarritoDesdeStorage(); // Tu función para recuperar el carrito
                    actualizarCarrito(); // Mostrar el carrito si es visual
                    actualizarContadorCarrito();
                    mostrarPuntosRestantes(); // 👈 Recalcula puntos usados
                    actualizarDisponibilidadDeCanjes(
                        parseInt(document.getElementById('puntosUsuario').textContent) - calcularPuntosUsados()
                    );
                });


                function cargarCarritoDesdeStorage() {
                    const carritoGuardado = localStorage.getItem('carrito');
                    if (carritoGuardado) {
                        carrito = JSON.parse(carritoGuardado);
                    } else {
                        carrito = [];
                    }
                }



                // Evento para actualizar los puntos solo cuando se haga clic en el "Agregar al carrito"
                document.addEventListener('input', function (e) {
                    if (e.target.classList.contains('cantidad-producto')) {
                        const cantidadInput = e.target;

                        // Eliminar ceros a la izquierda y convertir a número entero
                        let cantidadProducto = parseInt(cantidadInput.value.replace(/^0+/, ''), 10);
                        const puntosProducto = parseInt(cantidadInput.dataset.puntos, 10);

                        // Asegurarse de que el valor ingresado sea un número válido
                        if (isNaN(cantidadProducto) || cantidadProducto < 0) {
                            cantidadProducto = ""; // Dejar el campo vacío si el valor no es válido
                            cantidadInput.value = "";  // Ajustar la cantidad a vacío
                        }

                        // Guardar los puntos iniciales antes de hacer cualquier cambio
                        const puntosUsuario = parseInt(document.getElementById('puntosUsuario').textContent, 10);

                        // Si la cantidad ha aumentado o disminuido, solo se ajustará visualmente sin cambiar los puntos
                        const cantidadAnterior = parseInt(cantidadInput.dataset.cantidadAnterior || 0, 10);

                        // Mostrar el mensaje de error si el límite se alcanza, pero sin restar puntos
                        if (cantidadProducto > cantidadAnterior) {
                            const cantidadMaxima = Math.floor(puntosUsuario / puntosProducto);
                            if (cantidadProducto > cantidadMaxima) {
                                cantidadProducto = cantidadMaxima;  // Ajustar la cantidad al máximo posible
                                cantidadInput.value = cantidadProducto;
                                console.log(`❌ No tienes suficientes puntos para agregar más productos. Ajustando cantidad a ${cantidadMaxima}`);

                                // Mostrar un mensaje de error en el HTML
                                let mensajeError = document.getElementById('mensajeError');
                                if (!mensajeError) {
                                    mensajeError = document.createElement('div');
                                    mensajeError.id = 'mensajeError';
                                    mensajeError.style.color = 'red';
                                    mensajeError.textContent = `¡Has alcanzado el límite! Solo puedes agregar hasta ${cantidadMaxima} unidades.`;
                                    cantidadInput.parentElement.appendChild(mensajeError);
                                }
                            }
                        } else {
                            // Eliminar el mensaje de error si la cantidad se reduce
                            const mensajeError = document.getElementById('mensajeError');
                            if (mensajeError) {
                                mensajeError.remove();
                            }
                        }

                        // Evitar que la cantidad sea menor que cero
                        if (cantidadProducto < 0) {
                            cantidadInput.value = ""; // Ajustar a vacío si es menor que cero
                        }

                        // Actualizamos la cantidad anterior para futuras comparaciones
                        cantidadInput.dataset.cantidadAnterior = cantidadProducto;
                    }
                });

            } else {
                alert(data.message); // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error al obtener los canjeables:', error);
            alert('Hubo un error al obtener los canjeables.');
        });
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


// Llamar a la función para obtener los canjeables activos al cargar la página
document.addEventListener('DOMContentLoaded', obtenerCanjeablesActivos);

// function agregarProductoAlCarrito(producto) {
//     let carrito = JSON.parse(localStorage.getItem('carritoCanje')) || [];

//     const index = carrito.findIndex(item => item.id === producto.id);
//     const puntosElemento = document.getElementById('puntosUsuario');
//     let puntosActuales = parseInt(puntosElemento.textContent);

//     if (puntosActuales < producto.puntos) {
//         alert(`❌ No tienes suficientes puntos para agregar otra unidad de "${producto.nombre}".`);
//         return;
//     }

//     if (index !== -1) {
//         // Ya existe, sumamos cantidad
//         carrito[index].cantidad += 1;
//     } else {
//         // Nuevo producto al carrito
//         carrito.push({
//             ...producto,
//             cantidad: 1
//         });
//     }

//     // Guardar en localStorage
//     localStorage.setItem('carritoCanje', JSON.stringify(carrito));

//     // Restar puntos
//     puntosElemento.textContent = puntosActuales - producto.puntos;

//     alert(`✅ "${producto.nombre}" agregado al carrito.`);
// }
