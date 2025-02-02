function cargarRoles() {
    fetch('http://localhost:8088/Milogar/Controllers/RolController.php?action=getRoles')
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos de la API:', data); // Ver todo lo que devuelve el servidor

            if (data.success && data.data) { // Acceder a 'data' en lugar de 'roles'
                const selectRol = document.getElementById('editarRol');
                selectRol.innerHTML = ''; // Limpiar opciones anteriores

                data.data.forEach(rol => { // Usar 'data' para acceder a los roles
                    console.log('Rol:', rol); // Ver cada rol que se está agregando al select

                    const option = document.createElement('option');
                    option.value = rol.ID; // Usar 'ID' en lugar de 'id'
                    option.textContent = rol.RolName; // Usar 'RolName' en lugar de 'nombre'
                    selectRol.appendChild(option);
                });
            } else {
                console.error('No se encontraron roles:', data.error);
            }
        })
        .catch(error => {
            console.error('Error al cargar los roles:', error);
        });
}

document.getElementById('editarUsuarioModal').addEventListener('show.bs.modal', function () {
    cargarRoles();
});


// Función para editar el usuario
function editarUsuario(usuarioID) {
    console.log('ID del usuario a editar:', usuarioID);
    // Buscar el usuario por ID en la tabla cargada
    const usuario = Array.from(document.querySelectorAll('#usuarioTableBody tr'))
        .map(row => {
            const cells = row.children;
            return {
                ID: cells[0].innerText,
                NombreUsuario: cells[1].innerText,
                Email: cells[2].innerText,
                RolName: cells[3].innerText,
                IsActive: cells[4].innerText === 'Sí' ? '1' : '0',
            };
        })
        .find(usuario => usuario.ID == usuarioID);

    if (usuario) {
        // Rellenar el modal con los datos del usuario
        document.getElementById('editarUsuarioID').value = usuario.ID;
        document.getElementById('editarNombreUsuario').value = usuario.NombreUsuario;
        document.getElementById('editarEmail').value = usuario.Email;
        document.getElementById('editarIsActive').value = usuario.IsActive;

        // Llamar a la función para cargar los roles
        cargarRoles();

        // Establecer el rol seleccionado en el select
        const selectRol = document.getElementById('editarRol');
        for (let i = 0; i < selectRol.options.length; i++) {
            if (selectRol.options[i].textContent === usuario.RolName) {
                selectRol.selectedIndex = i;
                break;
            }
        }

        // Abrir el modal
        const editarUsuarioModal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
        editarUsuarioModal.show();
    } else {
        console.error('Usuario no encontrado');
    }
}
