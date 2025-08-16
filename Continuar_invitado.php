<?php
session_start();

// Configurar las variables de sesión para el usuario invitado
$_SESSION['user_name'] = 'Invitado';
$_SESSION['is_logged_in'] = true;
$_SESSION['show_welcome_alert'] = true; // Mostrar la alerta de bienvenida para invitados

// Redirigir a la página principal de la tienda
header('Location: index.php');
exit;
?>