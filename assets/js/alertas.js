document.addEventListener("DOMContentLoaded", function () {
    // Solo mostrar la alerta si el usuario NO está autenticado
    if (!isUserLoggedIn) {
        const ALERT_INTERVAL = 10 * 60 * 1000; // 10 minutos en milisegundos
        const lastAlertTime = localStorage.getItem("lastAlertTime"); // Obtener el último tiempo de alerta

        const now = new Date().getTime(); // Tiempo actual en milisegundos

        // Mostrar la alerta solo si han pasado 10 minutos o nunca se ha mostrado
        if (!lastAlertTime || now - lastAlertTime > ALERT_INTERVAL) {
            
            // Mostrar la alerta de términos y condiciones
            Swal.fire({
                text: 'Por favor, revisa nuestras políticas y términos de condiciones antes de continuar.',
                showCancelButton: false, // Sin botón de cancelar
                confirmButtonText: 'Aceptar', // Solo un botón de "Aceptar"
                confirmButtonColor: '#007bff', // Color del botón "Aceptar"
                backdrop: true,
                html: `Por favor, revisa nuestras <a href="terminos_condiciones.php" target="_blank" style="color: #007bff;">políticas y términos de condiciones</a> para tener una mejor experiencia de compra.`, // Enlace a los términos y condiciones
                imageUrl: '7Milogar/assets/imagenesMilogar/logomilo.jpg', // Ruta a tu logo (solo imagen, sin icono)
                imageWidth: 100,
                imageHeight: 100,
                imageAlt: 'Logo de la Empresa', // Cambia esta descripción si es necesario
            }).then((result) => {
                if (result.isConfirmed) {
                    // Acción cuando el usuario acepta los términos
                    console.log('El usuario aceptó los términos y condiciones.');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Acción cuando el usuario rechaza los términos
                    console.log('El usuario rechazó los términos y condiciones.');
                }

                // Actualizar el tiempo de la última alerta en localStorage
                localStorage.setItem("lastAlertTime", now);
            });
        }
    }
});
