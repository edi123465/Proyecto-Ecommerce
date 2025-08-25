let comentariosGlobal = [];

function cargarComentarios() {
    fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=listar')
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta: ' + response.status);
            return response.json();
        })
        .then(data => {
            if(data.success){
                mostrarComentarios(data.data);  // <-- Aquí va el array
            } else {
                console.error('Error del servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al obtener los comentarios:', error);
        });
}

function mostrarComentarios(data) {
    console.log('Comentarios recibidos:', data);
    const tbody = document.getElementById('comentarios-tbody');
    tbody.innerHTML = '';

    data.forEach(c => {
        tbody.innerHTML += `
            <tr>
                <td>${c.comentario_id}</td>
                <td>${c.producto_id}</td>
                <td>${c.nombreProducto}</td>
                <td><img src="http://localhost:8080/Milogar/assets/imagenesMilogar/productos/${c.imagen}" alt="${c.nombreProducto}" style="width:50px; height:auto;"></td>
                <td>${c.nombreUsuario}</td>
                <td>${c.comentario}</td>
                <td>${c.fecha}</td>
                <td>${c.estado == 1 ? 'Autorizado' : 'Rechazado'}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="eliminarComentario(${c.comentario_id || 0})">Eliminar</button>
                </td>
            </tr>
        `;
    });
}



function eliminarComentario(comentario_id) {
    if (!comentario_id) {
        Swal.fire({
            icon: 'error',
            title: 'ID no proporcionado',
            text: 'No se recibió el ID del comentario.'
        });
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('comentario_id', comentario_id);

            fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=eliminar', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Eliminado',
                        'Comentario eliminado correctamente',
                        'success'
                    );
                    cargarComentarios();
                } else {
                    Swal.fire(
                        'Error',
                        'No se pudo eliminar el comentario: ' + (data.message || 'Error desconocido'),
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error al eliminar el comentario:', error);
                Swal.fire(
                    'Error',
                    'Ocurrió un error al eliminar el comentario.',
                    'error'
                );
            });
        }
    });
}



document.addEventListener('DOMContentLoaded', cargarComentarios);
