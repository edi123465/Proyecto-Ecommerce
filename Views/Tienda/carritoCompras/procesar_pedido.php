<?php

// Incluir la clase PHPMailer y la configuración SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../../assets/lib/PHPMailer/Exception.php';
require_once '../../../assets/lib/PHPMailer/PHPMailer.php';
require_once '../../../assets/lib/PHPMailer/SMTP.php';

session_start();

// Validar que el carrito no esté vacío
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "No hay productos en el carrito.";
    exit;
}

// Aquí puedes agregar lógica para guardar el pedido en la base de datos
// Configurar el correo electrónico
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ddonmilo100@gmail.com'; // Tu correo de Gmail (que usas como autenticación)
    $mail->Password = 'mqbz dyis adsj tcqd'; // Contraseña de aplicación generada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configurar el conjunto de caracteres
    $mail->CharSet = 'UTF-8';

    // Habilitar la depuración
    $mail->SMTPDebug = 2; // Muestra la información de depuración

    // Configuración del remitente y destinatarios
    $mail->setFrom('ddonmilo100@gmail.com', 'Tienda Virtual'); // Establece el remitente
    $mail->addAddress('ddonmilo100@gmail.com', 'Administrador'); // Correo del administrador
    $mail->addAddress('edison.borja100@gmail.com', 'Cliente'); // Correo del cliente

    // Asunto del correo
    $mail->Subject = 'Nuevo Pedido - Detalles del Carrito';

    // Cuerpo del correo
    $bodyContent = '<h1>Nuevo pedido realizado</h1>';
    $bodyContent .= '<p>Detalles del pedido:</p>';

    // Agregar los productos del carrito al cuerpo del correo
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $bodyContent .= '<ul>';
        foreach ($_SESSION['cart'] as $item) {
            $bodyContent .= '<li>' . htmlspecialchars($item['name']) . ' - Cantidad: ' . htmlspecialchars($item['quantity']) . ' - Precio: $' . htmlspecialchars($item['price']) . '</li>';
        }
        $bodyContent .= '</ul>';
    }

    // Calcular el subtotal, IVA y total
    $subtotal = 0;
    $tax_rate = 0.15; // 15% IVA
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $iva = $subtotal * $tax_rate;
    $total = $subtotal + $iva;

    $bodyContent .= '<p>Subtotal: $' . number_format($subtotal, 2) . '</p>';
    $bodyContent .= '<p>IVA (15%): $' . number_format($iva, 2) . '</p>';
    $bodyContent .= '<p>Total: $' . number_format($total, 2) . '</p>';

    // Configurar el cuerpo del mensaje en formato HTML
    $mail->isHTML(true);
    $mail->Body = $bodyContent;

    // Enviar el correo
    $mail->send();
    echo 'Pedido enviado correctamente al administrador y al cliente';

    // Redireccionar al usuario a una página de confirmación o agradecimiento
    header("Location: confirmar_pedido.php");
    exit;
} catch (Exception $e) {
    echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
}
?>
