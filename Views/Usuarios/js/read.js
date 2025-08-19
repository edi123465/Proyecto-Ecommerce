// Función para cargar los usuarios desde el servidor
let paginaActual = 1;
const usuariosPorPagina = 5;

async function cargarUsuarios(pagina = 1) {
    try {
        const response = await fetch(`http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=read&page=${pagina}&limit=${usuariosPorPagina}`);

        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }

        const data = await response.json();

        if (data.success && Array.isArray(data.usuarios)) {
            const usuariosTableBody = document.getElementById('usuarioTableBody');
            usuariosTableBody.innerHTML = '';

            data.usuarios.forEach(usuario => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.ID}</td>
                    <td>${usuario.NombreUsuario}</td>
                    <td>${usuario.Email}</td>
                    <td>${usuario.RolName}</td>
                    <td>
                        <span class="badge ${usuario.IsActive ? 'badge-success' : 'badge-danger'}">
                            ${usuario.IsActive ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td>${usuario.FechaCreacion}</td>
                    <td>${usuario.total_puntos}</td>

                    <td>
                        <button class="btn btn-info btn-sm" onclick="editarUsuario(${usuario.ID})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${usuario.ID})">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                `;
                usuariosTableBody.appendChild(row);
            });

            actualizarPaginacion(data.total, pagina);
        } else {
            console.error('Datos no válidos o vacíos:', data);
        }
    } catch (error) {
        console.error('Error al cargar los usuarios:', error);
    }
}

function actualizarPaginacion(totalUsuarios, pagina) {
    const totalPaginas = Math.ceil(totalUsuarios / usuariosPorPagina);
    const paginacionDiv = document.getElementById('paginacion');

    paginacionDiv.innerHTML = ''; // Limpiar antes de generar los botones

    const contenedor = document.createElement('div');
    contenedor.classList.add('d-flex', 'justify-content-center', 'align-items-center', 'mt-3');

    // Botón Anterior
    if (pagina > 1) {
        const btnAnterior = document.createElement('button');
        btnAnterior.textContent = '← Anterior';
        btnAnterior.classList.add('btn', 'btn-lg', 'btn-secondary', 'm-2');
        btnAnterior.onclick = () => cargarUsuarios(pagina - 1);
        contenedor.appendChild(btnAnterior);
    }

    // Botones de páginas numeradas
    for (let i = 1; i <= totalPaginas; i++) {
        const boton = document.createElement('button');
        boton.textContent = i;
        boton.classList.add('btn', 'btn-lg', 'btn-primary', 'm-2');
        if (i === pagina) {
            boton.classList.add('active');
        }
        boton.onclick = () => cargarUsuarios(i);
        contenedor.appendChild(boton);
    }

    // Botón Siguiente
    if (pagina < totalPaginas) {
        const btnSiguiente = document.createElement('button');
        btnSiguiente.textContent = 'Siguiente →';
        btnSiguiente.classList.add('btn', 'btn-lg', 'btn-secondary', 'm-2');
        btnSiguiente.onclick = () => cargarUsuarios(pagina + 1);
        contenedor.appendChild(btnSiguiente);
    }

    paginacionDiv.appendChild(contenedor);
}


// Llamar a la función para cargar los usuarios en la página 1
document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();
});




// Cargar los usuarios cuando la página cargue
document.addEventListener('DOMContentLoaded', function () {
    cargarUsuarios();
});

// Función para eliminar usuario
function eliminarUsuario(id) {
    // Confirmación antes de eliminar
    if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
        // Realizar solicitud fetch al servidor para eliminar usuario
        fetch(`http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=delete&id=${id}`, {
            method: "POST", // Cambié GET por POST para un método adecuado
        })
        .then(response => response.json())
        .then(data => {
            
            if (data.success) {
                // Mostrar mensaje de éxito
                alert(data.message);
                // Realizar alguna acción después de la eliminación (como recargar la página o actualizar la lista)
                window.location.reload(); // Recargar la página, puedes adaptarlo según necesites
            } else {
                // Si hubo un error, mostrar el mensaje
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error al eliminar el usuario:", error);
            alert("Hubo un problema al eliminar el usuario. Intenta nuevamente.");
        });
    }
}
  