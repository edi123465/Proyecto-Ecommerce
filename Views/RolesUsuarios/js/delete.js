 // Función para eliminar el rol usando SweetAlert2
 function eliminarRol(rolId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Este rol será eliminado permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '¡Sí, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, enviar la solicitud al controlador para eliminar el rol
            fetch(`http://localhost:8088/Milogar/Controllers/RolController.php?action=delete&id=${rolId}`, {
                method: 'GET', // Usar GET ya que estamos pasando el ID como parámetro en la URL
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El rol ha sido eliminado con éxito.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); // Recargar la página para actualizar la lista de roles
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Hubo un problema al eliminar el rol.',
                    });
                }
            })
            .catch(error => {
                console.error('Error al eliminar el rol:', error);
            });
        }
    });
}