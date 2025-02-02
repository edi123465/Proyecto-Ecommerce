// Función para cargar los usuarios desde el servidor
async function cargarUsuarios() {
    try {
        // Cambiar la ruta para apuntar al controlador con la acción 'read' para obtener los usuarios
        const response = await fetch('http://localhost:8088/Milogar/Controllers/UsuarioController.php?action=read');

        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }

        const data = await response.json(); // Convertir la respuesta en JSON
        console.log(data.usuarios)
        // Verificar si los datos son válidos
        if (data.success && Array.isArray(data.usuarios)) {
            const usuariosTableBody = document.getElementById('usuarioTableBody');
            usuariosTableBody.innerHTML = ''; // Limpiar la tabla antes de agregar los datos

            // Recorrer los usuarios y agregar filas a la tabla
            data.usuarios.forEach(usuario => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${usuario.ID}</td>
                    <td>${usuario.NombreUsuario}</td>
                    <td>${usuario.Email}</td>
                    <td>${usuario.RolName}</td>
                    <td>${usuario.IsActive ? 'Sí' : 'No'}</td>
                    <td>${usuario.FechaCreacion}</td>
                    <td>
                        <img src="/Milogar/assets/imagenesMilogar/usuarios/${usuario.Imagen}" alt="Imagen del usuario" width="75" height="75">
                    </td>
                    <td>
                        <a href="#" class="action-btn edit" onclick="editarUsuario(${usuario.ID})">
                            <button class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </a>
                        <a href="#" class="action-btn delete" onclick="eliminarUsuario(${usuario.ID})">
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </a>
                    </td>
                `;

                usuariosTableBody.appendChild(row); // Agregar la fila a la tabla
            });

        } else {
            console.error('Datos no válidos o vacíos:', data);
        }
    } catch (error) {
        console.error('Error al cargar los usuarios:', error);
    }
}




// Cargar los usuarios cuando la página cargue
document.addEventListener('DOMContentLoaded', function () {
    cargarUsuarios();
});
