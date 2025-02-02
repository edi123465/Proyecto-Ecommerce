document.addEventListener("DOMContentLoaded", function () {
    // Solo mostrar la alerta si el usuario NO está autenticado
    if (!isUserLoggedIn) {
        const ALERT_INTERVAL = 10 * 60 * 1000; // 10 minutos en milisegundos
        const lastAlertTime = localStorage.getItem("lastAlertTime"); // Obtener el último tiempo de alerta

        const now = new Date().getTime(); // Tiempo actual en milisegundos

        // Mostrar la alerta solo si han pasado 10 minutos o nunca se ha mostrado
        if (!lastAlertTime || now - lastAlertTime > ALERT_INTERVAL) {
            Swal.fire({
                title: '¡Bienvenido!',
                text: '¡Suscríbete y obtén mayores beneficios en tus compras!',
                imageUrl: '/Milogar/assets/imagenesMilogar/logomilo.jpg', // Ruta a tu logo
                imageWidth: 100,
                imageHeight: 100,
                imageAlt: 'Logo de la Empresa',
                showCancelButton: true, // Muestra dos botones
                confirmButtonText: 'Suscribirse',
                cancelButtonText: 'Seguir explorando',
                confirmButtonColor: '#007bff', // Color del botón "Suscribirse"
                cancelButtonColor: '#6c757d', // Color del botón "Seguir explorando"
                backdrop: true, // Fondo sombreado detrás de la alerta
            }).then((result) => {
                if (result.isConfirmed) {
                    // Acción cuando el usuario hace clic en "Suscribirse"
                    window.location.href = './Views/signup.php'; // Cambia esta ruta a la página de suscripción
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Acción cuando el usuario hace clic en "Seguir explorando"
                    console.log('El usuario decidió seguir explorando.');
                }
            });

            // Actualizar el tiempo de la última alerta en localStorage
            localStorage.setItem("lastAlertTime", now);
        }
    }
});
