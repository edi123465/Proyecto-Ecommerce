document.addEventListener("DOMContentLoaded", function () {
    // URL del controlador para obtener las direcciones
    const userId = $('#usuario_id').val(); // Usamos jQuery para obtener el valor del campo
    console.log("User ID:", userId);

    if (!userId) {
        console.error("User ID no encontrado.");
        return; // Detener ejecución si no se encuentra el userId
    }

    const url = `http://localhost:8088/Milogar/Controllers/EntregasController.php?action=obtenerDirecciones&userId=${userId}`;

    // Realiza la llamada AJAX para obtener las direcciones
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error de red: ' + response.statusText); // Detectar errores de red
            }
            return response.json();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data); // Verifica la respuesta
            const direccionesContainer = document.querySelector(".direccionesContainer");

            if (data.status === "success") {
                const direcciones = data.direcciones; // Datos de las direcciones
                direccionesContainer.innerHTML = ""; // Limpia el contenedor antes de agregar datos

                // Verificar si se excede el límite de direcciones
                if (direcciones.length >= 2) {
                    // Aquí puedes añadir lógica si es necesario para el límite de direcciones
                }

                // Iterar y mostrar direcciones
                direcciones.forEach((direccion, index) => {
                    const direccionHTML = `
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="border p-4 rounded-3 mb-4 position-relative">
                            <!-- Icono de edición en la parte superior derecha -->
                            <button type="button" class="btn btn-edit position-absolute top-0 end-0 p-2" data-bs-toggle="modal" data-bs-target="#editAddressModal" onclick="editarDireccion(${index})">
                                <i class="fas fa-pencil-alt"></i> <!-- Icono de lápiz -->
                            </button>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="direccionRadio${index}" ${direccion.predeterminada ? 'checked' : ''}>
                                <label class="form-check-label text-dark" for="direccionRadio${index}">
                                    Dirección ${index + 1}
                                </label>
                            </div>
                            <address>
                                <strong>${direccion.nombre}</strong><br>
                                ${direccion.direccion},<br>
                                ${direccion.estado}, ${direccion.pais}
                            </address>
                            ${direccion.predeterminada ? '<span class="text-danger">Dirección predeterminada</span>' : ''}
                        </div>
                    </div>
                    `;

                    // Insertar el HTML de cada dirección en el contenedor
                    direccionesContainer.innerHTML += direccionHTML;
                });
            } else if (data.status === "empty") {
                direccionesContainer.innerHTML = '<p class="text-muted">No tienes direcciones guardadas.</p>';
            } else {
                direccionesContainer.innerHTML = '<p class="text-danger">Error al cargar las direcciones.</p>';
            }
        })
        .catch(error => {
            console.error("Error en la solicitud AJAX:", error);
            document.querySelector(".direccionesContainer").innerHTML = '<p class="text-danger">Hubo un problema al cargar las direcciones.</p>';
        });


    // Capturamos el evento de submit del formulario para guardar la dirección
    const direccionForm = document.getElementById("direccionForm");

    direccionForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Evitar el envío real del formulario

        // Recoger los valores del formulario
        const usuarioId = document.getElementById("usuario_id").value;
        const pais = document.getElementById("pais").value;
        const estado = document.getElementById("estado").value;
        const direccion = document.getElementById("direccion").value;
        const referencia = document.getElementById("referencia").value;
        const telefono = document.getElementById("telefono").value;
        const direccionPredeterminada = document.getElementById("flexCheckDefault").checked ? 1 : 0;

        // Mostrar los datos en consola para verificar
        console.log("Datos del formulario:", {
            usuarioId,
            pais,
            estado,
            direccion,
            referencia,
            telefono,
            direccionPredeterminada
        });

        // Realizar la solicitud fetch para guardar la dirección
        const saveUrl = `http://localhost:8088/Milogar/Controllers/EntregasController.php?action=insertarDireccion`;
        
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                usuarioId,
                pais,
                estado,
                direccion,
                referencia,
                telefono,
                direccionPredeterminada
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.status === "success") {
                alert("Dirección guardada correctamente.");
                window.location.href = "http://localhost:8088/Milogar/Views/shop-checkout.php";  // Cambia esto por la ruta deseada
            } else {
                alert("Limite de direcciones alcanzado");
                window.location.href = "http://localhost:8088/Milogar/Views/shop-checkout.php";  // Cambia esto por la ruta deseada

            }
        })
        .catch(error => {
            console.error("Error al guardar la dirección:", error);
            alert("Hubo un problema al guardar la dirección.");
        });
    });
});
