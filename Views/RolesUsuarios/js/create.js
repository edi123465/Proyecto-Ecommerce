document.getElementById("regresarBtn").addEventListener("click", function() {
    // Redirigir directamente a la página del menú sin confirmación
    window.location.href = "../../menu"; // Redirige en la misma pestaña
});


// Elementos del DOM
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const createRoleModal = document.getElementById('createRoleModal');
const createRoleForm = document.getElementById('createRoleForm');




// Enviar el formulario con HTTP Request
createRoleForm.addEventListener('submit', async (e) => {
    e.preventDefault(); // Evitar el envío tradicional del formulario

    // Recopilar datos del formulario
    const formData = new FormData(createRoleForm);
    const data = Object.fromEntries(formData);

    // Agregar la fecha de creación al objeto data
    data.fechaCreacion = new Date().toISOString(); // Genera la fecha en formato ISO

    console.log("Datos del formulario recolectados: ", data);

    // Enviar datos al servidor con fetch
    try {
        console.log("Enviando solicitud al servidor...");
        const response = await fetch('http://localhost:8080/Milogar/Controllers/RolController.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        console.log("Respuesta del servidor recibida.");

        if (response.ok) {
            console.log('Rol creado con éxito');
            // Usar SweetAlert2 para mostrar la alerta de éxito
            Swal.fire({
                title: '¡Éxito!',
                text: 'El rol se ha creado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                imageWidth: 100,
                imageHeight: 100,
                imageAlt: 'Logo de la empresa',
            }).then(() => {
                createRoleModal.style.display = 'none';  // Cerrar el modal
                createRoleForm.reset();  // Limpiar el formulario
                location.reload();  // Recargar la página para actualizar la lista
            });
        } else {
            const error = await response.text();
            console.error('Error al crear el rol: ', error);
            // Usar SweetAlert2 para mostrar el error
            Swal.fire({
                title: '¡Error!',
                text: 'Hubo un problema al crear el rol: ' + error,
                icon: 'error',
                confirmButtonText: 'Aceptar',
            });
        }
    } catch (err) {
        console.error('Error de red: ', err.message);
        // Usar SweetAlert2 para manejar el error de red
        Swal.fire({
            title: '¡Error!',
            text: 'Hubo un error de red: ' + err.message,
            icon: 'error',
            confirmButtonText: 'Aceptar',
        });
    }
});

