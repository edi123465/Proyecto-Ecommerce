async function cargarComentariosGeneral() {
    const tbody = document.getElementById('comentariosGeneral-tbody');
    tbody.innerHTML = '<tr><td colspan="8">Cargando comentarios...</td></tr>';

    try {
        const res = await fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=listarComentariosActivos');
        const json = await res.json();

        if (json.success && json.data.length > 0) {
            tbody.innerHTML = ''; // limpiar

            json.data.forEach(comentario => {
                const fila = `
                    <tr>
                        <td>${comentario.id}</td>
                        <td>${comentario.usuario_id}</td>
                        <td>${comentario.nombreUsuario}</td>
                        <td>${comentario.descripcion}</td>
                        <td>${comentario.valoracion}</td>
                        <td>${new Date(comentario.fecha_creacion).toLocaleString()}</td>
                        <td>${comentario.estado == 1 ? 'Activo' : 'Inactivo'}</td>
                        <td>
                            <!-- Aquí podrías agregar botones para editar/eliminar si quieres -->
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${comentario.id}">Eliminar</button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', fila);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="8">No hay comentarios para mostrar.</td></tr>';
        }
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="8">Error al cargar comentarios.</td></tr>';
        console.error('Error al cargar comentarios:', error);
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', cargarComentariosGeneral);

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('btn-eliminar') || e.target.closest('.btn-eliminar')) {
    // Soporte para clicks en el icono o dentro del botón
    const btn = e.target.closest('.btn-eliminar');
    const comentarioId = btn.getAttribute('data-id');

    if (!comentarioId) return;

    // Confirmar antes de eliminar
    if (confirm('¿Estás seguro que quieres eliminar este comentario?')) {
      fetch(`http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=eliminarComentarioAdmin`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `comentario_id=${encodeURIComponent(comentarioId)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Comentario eliminado correctamente.');
          // Recarga los comentarios para reflejar cambios:
          if (typeof cargarComentariosGeneral === 'function') cargarComentariosGeneral();
        } else {
          alert('Error al eliminar comentario: ' + (data.message || 'Error desconocido'));
        }
      })
      .catch(error => {
        console.error('Error en la petición de eliminar:', error);
        alert('Ocurrió un error al eliminar el comentario.');
      });
    }
  }
});
