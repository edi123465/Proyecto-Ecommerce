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
            alert(result.message); // Mostrar mensaje de éxito
            window.location.href = result.redirect; // Redirigir al destino
        } else {
            alert(result.message); // Mostrar mensaje de error
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Hubo un problema con el inicio de sesión. Inténtelo de nuevo.');
    }
});

//const response = await fetch('https://milogar.wuaze.com/Controllers/LoginController.php?action=login', {
