<?php

// Verificar si el usuario no ha iniciado sesión y si la cookie 'alert_shown' no existe
if ((!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) && !isset($_COOKIE['alert_shown'])) {
    // Establecer una cookie para que la alerta no se muestre más de una vez
    setcookie('alert_shown', 'true', time() + (86400 * 1), "/"); // La cookie durará 1 día

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Obtén Beneficios!',
                text: 'Suscríbete a Milogar para obtener mayores beneficios como descuentos y ofertas exclusivas.',
                icon: 'info',
                confirmButtonText: 'Registrarse',
                showCancelButton: true,
                cancelButtonText: 'Más tarde',
                imageUrl: 'ruta_a_tu_imagen.png', // Ruta de la imagen que quieras mostrar
                imageWidth: 150, // Ancho de la imagen
                imageHeight: 150, // Alto de la imagen
                imageAlt: 'Imagen de suscripción', // Texto alternativo para la imagen
                width: '400px', // Ajusta el ancho de la alerta
                padding: '2rem', // Ajusta el padding para darle un poco más de espacio
                background: '#fff', // Color de fondo de la alerta
                backdrop: `
                    rgba(0,0,123,0.4)
                    url('ruta_a_tu_gif.gif') // Puedes añadir un gif o imagen en el fondo
                    left top
                    no-repeat
                `, // Fondo personalizado con una imagen o color
                customClass: {
                    popup: 'custom-popup', // Clases CSS para personalizar el popup
                    title: 'custom-title', // Clases CSS para personalizar el título
                    confirmButton: 'custom-confirm-btn', // Clases CSS para el botón de confirmación
                    cancelButton: 'custom-cancel-btn' // Clases CSS para el botón de cancelar
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown' // Animación de entrada
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp' // Animación de salida
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'registro.php'; // Redirige a la página de registro
                }
            });
        });
    </script>";
}
?>
