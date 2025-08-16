function actualizarRol() {
    // Obtener los valores del formulario
    const roleId = document.getElementById('roleId').value;
    const roleName = document.getElementById('roleName').value;
    const roleDescription = document.getElementById('roleDescription').value;
    const isActive = document.getElementById('roleIsActive').value; // Cambié .checked por .value

    // Crear un objeto con los datos a enviar
    const data = {
        roleId: roleId,
        roleName: roleName,
        roleDescription: roleDescription,
        isActive: isActive
    };

    // Log para ver los datos antes de enviarlos
    console.log("Datos a enviar al servidor:", data);

    // Usar fetch para enviar los datos al servidor con el método POST y formato JSON
    fetch('http://localhost:8088/Milogar/Controllers/RolController.php?action=edit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data) // Enviar los datos en formato JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar alerta de éxito usando SweetAlert2
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Rol actualizado con éxito.',
                imageUrl: 'http://localhost:8088/Milogar/imagenesMilogar/', // Aquí pones la ruta de tu logo
                imageWidth: 100,  // Ajusta el tamaño del logo
                imageHeight: 100, // Ajusta el tamaño del logo
                imageAlt: 'Logo de la empresa', // Descripción del logo
                showConfirmButton: true, // Botón de confirmación
            }).then(() => {
                $('#editRoleModal').modal('hide'); // Cerrar el modal
                location.reload(); // Recargar la página o actualizar la tabla de roles
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un error al actualizar el rol: ' + data.message,
            });
        }
    })
    .catch(error => {
        console.error('Error al actualizar el rol:', error);
    });
}
