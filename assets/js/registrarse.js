document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del formulario.

    // Obtener datos del formulario
    const formData = new FormData(this);
    const password = formData.get('contrasenia');
    const confirmPassword = formData.get('confirmar_contrasenia');
    const nombreUsuario = formData.get('nombre_usuario');
    const email = formData.get('correo_electronico');

    // Validar contraseñas
    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    // Crear el objeto de datos excluyendo confirmPassword
    const data = {
        name: nombreUsuario,
        email: email,
        password: password,
        rol_id: 20, // Rol para cliente
        status: 1, // Usuario activo por defecto
        imagen: 'sin_imagen' // Valor por defecto para la imagen
    };

    // Verificar si el nombre de usuario o el correo ya existen
    const checkDuplicateResponse = await fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=checkDuplicate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name: nombreUsuario, email: email }) // Enviar nombre y correo a verificar
    });

    const checkDuplicateResult = await checkDuplicateResponse.json(); // Parsear respuesta JSON

if (checkDuplicateResult.success === false) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: checkDuplicateResult.message, // Muestra el mensaje del backend
        confirmButtonText: 'Entendido'
    });
    return; // Detener el proceso si hay duplicados
}


    // Si no hay duplicados, proceder con el registro del usuario
    try {
        // Realizar la solicitud fetch para registrar al usuario
        const response = await fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=suscribirse', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // Convertir el objeto data a JSON
        });

        const result = await response.json(); // Parsear la respuesta como JSON

        if (response.ok) {
            alert('Usuario registrado exitosamente.');
            // Redirigir o realizar alguna acción adicional
            window.location.href = 'http://localhost:8080/Milogar/index.php'; // Redirigir a la página principal
        } else {
            alert(`Error al registrar usuario: ${result.message || 'Error desconocido.'}`);
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Hubo un problema al registrar el usuario.');
    }
});
