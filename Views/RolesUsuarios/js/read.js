// Función para obtener los roles y mostrarlos en la tabla
async function cargarRoles() {
    try {
        // Cambiar la ruta para apuntar al controlador con la acción 'read' para obtener los roles
        const response = await fetch('http://localhost:8080/Milogar/Controllers/RolController.php?action=read');

        if (!response.ok) {
            throw new Error('Error al obtener los roles');
        }

        const data = await response.json(); // Convertir la respuesta en JSON

        // Usar 'data' y no 'result' para acceder a la propiedad 'data'
        if (data.status === 'success' && Array.isArray(data.data)) {
            const rolesTableBody = document.getElementById('rolesTableBody');
            rolesTableBody.innerHTML = ''; // Limpiar la tabla antes de agregar los datos
            // Recorrer los roles y agregar filas a la tabla
            data.data.forEach(rol => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${rol.ID}</td>
            
                    <td>${rol.RolName}</td>
                                        <td>${rol.RolDescription}</td>

                    <td>${String(rol.IsActive) === "1" ? 'Activo' : 'Inactivo'}</td>
                    <td>${rol.CreatedAt}</td>
                    <td>
                        <a href="#" class="action-btn edit" onclick="openEditModal(${rol.ID}, '${encodeURIComponent(rol.RolName)}', '${encodeURIComponent(rol.RolDescription)}', ${rol.IsActive})">
                            <button class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </a>
                        <a href="#" class="action-btn delete" onclick="eliminarRol(${rol.ID})">
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </a>
                    </td>
                `;

                rolesTableBody.appendChild(row); // Agregar la fila a la tabla
            });
        } else {
            console.error('Datos no válidos o vacíos:', data);
        }

    } catch (error) {
        console.error('Error al cargar los roles:', error);
    }
}

// Llamar a la función para cargar los roles al cargar la página
window.onload = function () {
    cargarRoles();
};


function openEditModal(roleId, roleName, roleDescription, isActive) {
    // Decodificar los valores para que los saltos de línea y caracteres especiales se muestren correctamente
    const decodedRoleName = decodeURIComponent(roleName);
    const decodedRoleDescription = decodeURIComponent(roleDescription);

    // Establecer los valores de los campos en el modal
    document.getElementById('roleId').value = roleId;
    document.getElementById('roleName').value = decodedRoleName;
    document.getElementById('roleDescription').value = decodedRoleDescription;

    // Establecer el valor de 'isActive' en el select
    document.getElementById('roleIsActive').value = isActive;  // Asegúrate de que isActive sea 0 o 1

    // Mostrar el modal
    $('#editRoleModal').modal('show');
}


document.getElementById('editRoleForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar que se recargue la página al enviar el formulario

    // Obtener los valores del formulario
    const roleId = parseInt(document.getElementById('roleId').value, 10);
    const roleName = document.getElementById('roleName').value;
    const roleDescription = document.getElementById('roleDescription').value;
    const isActive = document.getElementById('roleIsActive').checked ? 1 : 0;

    // Crear un objeto con los datos a enviar
    const data = {
        roleId: roleId,
        roleName: roleName,
        roleDescription: roleDescription,
        isActive: isActive
    };

    // Log para ver los datos antes de enviarlos
    console.log("Datos a enviar al servidor:", data);

    // Usamos SweetAlert2 para confirmar la acción
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción actualizará el rol.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Usar XMLHttpRequest para enviar los datos al servidor con el método POST y formato JSON
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://localhost:8080/Milogar/Controllers/RolController.php?action=edit', true);
            xhr.setRequestHeader('Content-Type', 'application/json'); // Especificamos que los datos son JSON

            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log("Respuesta del servidor:", xhr.responseText); // Muestra toda la respuesta

                    try {
                        const response = JSON.parse(xhr.responseText); // Intentamos parsear la respuesta JSON
                        console.log("Datos de respuesta del servidor:", response);

                        if (response.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Rol actualizado con éxito.',
                                icon: 'success',
                                imageWidth: 150,  // Aumenta el tamaño de la imagen
                                imageHeight: 150, // Aumenta el tamaño de la imagen
                                imageAlt: 'Logo de la empresa',
                                showConfirmButton: true,
                            }).then(() => {
                                $('#editRoleModal').modal('hide'); // Cerrar el modal
                                location.reload(); // Recargar la página o actualizar la tabla de roles
                            });
                        } else {
                            // Mostrar alerta de error con SweetAlert2
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Hubo un error al actualizar el rol: ' + response.message,
                            });
                        }
                    } catch (error) {
                        console.error("Error al parsear la respuesta como JSON:", error);
                        alert('Error al recibir la respuesta del servidor. Detalles en la consola.');
                    }
                } else {
                    alert('Hubo un error al comunicarse con el servidor. Status: ' + xhr.status);
                }
            };

            // Enviar los datos al servidor en formato JSON
            xhr.send(JSON.stringify(data));
        } else {
            console.log("Actualización cancelada");
        }
    });
});


// Cargar los usuarios cuando la página cargue
document.addEventListener('DOMContentLoaded', function () {
    cargarRoles();
});
