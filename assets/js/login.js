document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evitar el envío del formulario

    // Crear una instancia de FormData a partir del formulario
    const form = document.getElementById('loginForm');
    const formData = new FormData(form);

    // Validar que los campos no estén vacíos
    const username = formData.get('txt_nombreUsuario').trim();
    const password = formData.get('txt_password').trim();

    if (!username || !password) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    console.log(username);
// Enviar solicitud al backend
try {
    const response = await fetch('http://localhost:8080/Milogar/Controllers/LoginController.php?action=login', {
        method: 'POST',
        body: formData, // Enviar datos como FormData
    });

    const result = await response.json(); // Parsear respuesta

    if (result.success) {
        // Sonido opcional (puedes poner otro archivo .mp3 o .wav)
        const audio = new Audio('/Milogar/assets/sounds/success.mp3');
        audio.play();

        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: result.message,
            confirmButtonText: 'Continuar',
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = result.redirect;
        });
    } else {
        const audio = new Audio('/Milogar/assets/sounds/error.mp3');
        audio.play();

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: result.message,
            confirmButtonText: 'Intentar de nuevo'
        });
    }
} catch (error) {
    console.error('Error en la solicitud:', error);

    const audio = new Audio('/Milogar/assets/sounds/error.mp3');
    audio.play();

    Swal.fire({
        icon: 'error',
        title: 'Error de conexión',
        text: 'Hubo un problema con el inicio de sesión. Inténtelo de nuevo.',
        confirmButtonText: 'Cerrar'
    });
}

});

//const response = await fetch('https://milogar.wuaze.com/Controllers/LoginController.php?action=login', {
