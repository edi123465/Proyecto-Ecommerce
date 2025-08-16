// Función para cargar roles y devolver una promesa
function cargarRoles() {
    return fetch('http://localhost:8080/Milogar/Controllers/RolController.php?action=getRoles')
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos de la API:', data);

            if (data.roles && Array.isArray(data.roles)) {
                const selectRol = document.getElementById('editarRol');
                selectRol.innerHTML = '<option value="">Seleccione un rol</option>'; // Opción por defecto

                data.roles.forEach(rol => {
                    console.log('Agregando rol:', rol);

                    const option = document.createElement('option');
                    option.value = rol.ID;
                    option.textContent = rol.RolName;
                    selectRol.appendChild(option);
                });

                return true; // Indica que la carga de roles ha finalizado
            } else {
                console.error('No se encontraron roles válidos:', data);
                return false;
            }
        })
        .catch(error => {
            console.error('Error al cargar los roles:', error);
            return false;
        });
}


// Ejecutar la función cuando se abra el modal
document.getElementById('editarUsuarioModal').addEventListener('show.bs.modal', function () {
    cargarRoles();
});


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
                IsActive: cells[4].innerText === 'Activo' ? '1' : '0', // Esto debería funcionar correctamente
            };
        })
        .find(usuario => usuario.ID == usuarioID);

    if (usuario) {
        // Rellenar el modal con los datos del usuario
        document.getElementById('editarUsuarioID').value = usuario.ID;
        document.getElementById('editarNombreUsuario').value = usuario.NombreUsuario;
        document.getElementById('editarEmail').value = usuario.Email;

        // Establecer el valor del estado
        const selectEstado = document.getElementById('editarIsActive');
        selectEstado.value = usuario.IsActive; // Esto seleccionará el valor correcto (1 o 0)

        // Llamar a la función para cargar los roles y luego establecer el rol seleccionado
        cargarRoles().then(() => {
            const selectRol = document.getElementById('editarRol');
            for (let i = 0; i < selectRol.options.length; i++) {
                if (selectRol.options[i].textContent === usuario.RolName) {
                    selectRol.selectedIndex = i; // Establecer el rol seleccionado
                    break;
                }
            }
        });

        // Abrir el modal
        const editarUsuarioModal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
        editarUsuarioModal.show();
    } else {
        console.error('Usuario no encontrado');
    }
}

// Función para actualizar el usuario
function actualizarUsuario() {
    // Obtener los datos del formulario
    const id = document.getElementById('editarUsuarioID').value;
    const nombreUsuario = document.getElementById('editarNombreUsuario').value;
    const email = document.getElementById('editarEmail').value;
    const rolID = document.getElementById('editarRol').value;
    const isActive = document.getElementById('editarIsActive').value;
    const fechaCreacion = new Date().toISOString(); // Fecha actual, puedes modificar si es necesario

    // Crear el objeto con los datos
    const data = {
        NombreUsuario: nombreUsuario,
        Email: email,
        RolID: rolID,
        IsActive: isActive,
        FechaCreacion: fechaCreacion
    };

    // Mostrar alerta de confirmación
    const confirmacion = confirm("¿Estás seguro de que quieres actualizar este usuario?");
    
    if (confirmacion) {
        // Enviar la solicitud con fetch
        fetch(`http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=actualizarUsuario&id=${id}`, {
            method: 'POST', // Método de la solicitud
            headers: {
                'Content-Type': 'application/json' // Indicamos que los datos se enviarán en formato JSON
            },
            body: JSON.stringify(data) // Convertir los datos a formato JSON
        })
        .then(response => response.json()) // Procesar la respuesta JSON
        .then(data => {
            if (data.success) {
                alert(data.success); // Mostrar mensaje de éxito
                location.reload(); // Recargar la página después de la actualización
            } else {
                alert(data.error); // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error al actualizar el usuario:', error);
            alert('Hubo un problema al actualizar el usuario.');
        });
    } else {
        
    }
}
