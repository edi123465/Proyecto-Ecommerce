<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
//trae informacion del carrito de compras
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>MILOGAR - Términos y condiciones</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Milogar - Tienda en línea con los mejores productos del hogar. Compra fácil, rápido y seguro. ¡Descubre nuestras ofertas!">
  <meta content="Codescandy" name="author" />


  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/x-icon" href="assets/imagenesMilogar/logomilo.jpg">



  <!-- Libs CSS -->
  <link href="assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet" />
  <link href="assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
  <link href="assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
  <link href="assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />
  <link href="assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
  <link href="assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
  <link href="assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
  <link href="assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">

  <!-- Theme CSS -->
  <link rel="stylesheet" href="assets/css/theme.min.css">
  <style>
    .whatsapp-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .whatsapp-icon {
            position: relative;
            display: inline-block;
        }

        .whatsapp-icon img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .whatsapp-icon:hover img {
            transform: scale(1.1);
        }

        .whatsapp-text {
            position: absolute;
            bottom: 70px;
            right: 0;
            background-color: #25D366;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .whatsapp-icon:hover .whatsapp-text {
            opacity: 1;
        }
        .chatbot-container {
  position: fixed;
  bottom: 100px;
  right: 20px;
  z-index: 1001;
}

.chatbot-icon {
  position: relative;
  display: inline-block;
  cursor: pointer;
}

.chatbot-icon img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  transition: transform 0.3s ease;
}

.chatbot-icon:hover img {
  transform: scale(1.1);
}

.chatbot-text {
  position: absolute;
  bottom: 70px;
  right: 0;
  background-color: #343a40;
  color: white;
  padding: 8px 12px;
  border-radius: 5px;
  white-space: nowrap;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
  font-size: 14px;
  font-family: Arial, sans-serif;
}

.chatbot-icon:hover .chatbot-text {
  opacity: 1;
}

.chatbot-box {
  display: none;
  width: 300px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
  overflow: hidden;
  flex-direction: column;
  font-family: Arial, sans-serif;
}

.chatbot-header {
  background-color: #343a40;
  color: #fff;
  padding: 10px;
  font-weight: bold;
  position: relative;
}

.chatbot-close {
  position: absolute;
  right: 10px;
  top: 5px;
  cursor: pointer;
}

.chatbot-log {
  height: 250px;
  overflow-y: auto;
  padding: 10px;
  border-top: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  background: #f9f9f9;
}

.chatbot-input {
  display: flex;
  border-top: 1px solid #ccc;
}

.chatbot-input input {
  flex: 1;
  padding: 10px;
  border: none;
  outline: none;
}

.chatbot-input button {
  background-color: #343a40;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
}
  </style>
  <script>
          const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

  </script>
</head>

<body>

  <?php
  require_once "Views/Navigation/navigation.php";
  require_once "Models/UsuarioModel.php";
  // Verificar si el usuario está logueado y si es un usuario registrado o invitado
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $ocultarDireccion = true;
    if ($_SESSION['user_name'] === 'Invitado') {
      // Si está logueado como invitado, mostrar mensaje específico
      $esInvitado = true;
    } else {
      // Si está logueado como usuario registrado, mostrar su nombre
      $esInvitado = false;
    }
  } else {
    $ocultarDireccion = false;
    // Si no está logueado, mostrar un mensaje o algo diferente
    $esInvitado = true;  // Consideramos a los no logueados como invitados
  }





  require_once "Controllers/ProductoController.php";
  require_once "Config/db.php";

  function getDatabaseConnection()
  {
    $db = new Database1();
    return $db->getConnection();
  }

  $connection = getDatabaseConnection();
  $numeroPedido = new ProductoController($connection);
  $numPedido = $numeroPedido->generarNumeroPedido();
  $fechaActual = $numeroPedido->obtenerFechaActual();

  $usuarioModel = new UsuarioModel($connection);
  $email = null;

  if ($userId) {
    $email = $usuarioModel->getEmailByUserId($userId);
  }

  ?>

<br>
  <div class="container">
        <div class="terms-container">
            <h2 class="text-center">Términos y Condiciones</h2>
            <p class="text-muted text-center">Última actualización: <strong>Marzo 2025</strong></p>

            <section id="politica-datos">
            <h2>Política de Uso de Datos del Cliente</h2>
            <p>En <strong>Milogar</strong>, nos comprometemos a proteger la privacidad y seguridad de los datos personales de nuestros clientes. Esta política describe cómo recopilamos, usamos, almacenamos y protegemos su información cuando utiliza nuestra aplicación y servicios.</p>

            <h3>1. Datos que recopilamos</h3>
            <p>Recopilamos información personal que usted nos proporciona al registrarse, realizar pedidos o comunicarse con nuestro equipo, incluyendo pero no limitado a:</p>
            <ul>
                <li>Nombre completo</li>
                <li>Correo electrónico</li>
                <li>Dirección de facturación y envío</li>
                <li>Número de teléfono</li>
                <li>Información de pago</li>
            </ul>

            <h3>2. Uso de la información</h3>
            <p>Utilizamos sus datos para los siguientes propósitos:</p>
            <ul>
                <li>Procesar y gestionar sus pedidos.</li>
                <li>Comunicarle información relevante sobre su compra y estado del pedido.</li>
                <li>Mejorar nuestros productos, servicios y experiencia de usuario.</li>
                <li>Cumplir con obligaciones legales y fiscales.</li>
            </ul>
            
            </section>

            <h3>3. Gestión de Pedidos</h3>
            <p>Los pedidos se procesan en un plazo de 24 a 48 horas. Se enviará una confirmación por correo electrónico con los detalles del pedido y el estado del envío.</p>

            <h3>4. Métodos de Pago</h3>
            <p>Aceptamos pagos seguros a través de <strong>PayPhone</strong>, garantizando la protección de sus datos mediante encriptación avanzada y <strong>certificado SSL</strong>.</p>

            <h3>5. Confirmación de Pagos</h3>
            <p>Una vez realizado el pago, el usuario deberá enviar el comprobante correspondiente al administrador para su validación:</p>
            <ul>
                <li><strong>Depósito o transferencia bancaria:</strong> Enviar el comprobante del depósito o transferencia al correo <a href="mailto:pagos@milogar.com">pagos@milogar.com</a> o mediante nuestro chat en vivo.</li>
                <li><strong>PayPhone:</strong> Captura de pantalla o comprobante del pago realizado por PayPhone debe ser enviado por los mismos medios mencionados.</li>
            </ul>
            <p>El pedido será procesado una vez que se confirme el pago con el comprobante correspondiente.</p>

            <h3>6. Seguridad y Protección de Datos</h3>
            <p>Implementamos medidas de seguridad de nivel bancario para proteger su información personal. No compartimos datos con terceros sin consentimiento.</p>

            <h3>7. Devoluciones y Reembolsos</h3>
            <p>Las solicitudes de reembolso deben realizarse en un plazo de 2 días tras la compra. Se evaluarán caso por caso para garantizar la satisfacción del cliente.</p>

            <h3>8. Contacto</h3>
            <p>Para cualquier consulta, contáctenos a través de <a href="mailto:soporte@milogar.com">soporte@milogar.com</a> o mediante nuestro chat en vivo.</p>
            <h3>9. Acumulación de Puntos</h3>
            <p>Los usuarios registrados podrán acumular puntos realizando compras que cumplan con las condiciones establecidas por el administrador. Cada producto o conjunto de productos puede otorgar una cantidad específica de puntos, según lo determinado por la administración. Los puntos se suman automáticamente al perfil del usuario tras la validación de la compra.</p>

            <h3>10. Canje de Puntos</h3>
            <p>Los puntos acumulados podrán ser canjeados por:</p>
            <ul>
            <li>Productos seleccionados como canjeables por puntos (producto gratis).</li>
            <li>Descuentos aplicables mediante cupones asignados por el administrador.</li>
            </ul>
            <p>Los puntos solo podrán ser utilizados cuando el valor a descontar sea igual o menor al valor total de la compra. En ningún caso se otorgará cambio en efectivo ni se podrá usar los puntos para cubrir valores inferiores al mínimo establecido.</p>

            <h3>11. Condiciones del Programa de Puntos</h3>
            <ul>
            <li>La asignación y cantidad de puntos por producto está sujeta a cambios y condiciones establecidas por el administrador.</li>
            <li>Los puntos no tienen valor monetario y no son transferibles entre cuentas.</li>
            <li>Los puntos se mantienen acumulados en el perfil del usuario hasta que este decida usarlos o eliminar su cuenta.</li>
            <li>Al eliminar la cuenta, se perderán automáticamente todos los puntos acumulados.</li>
            <li>En caso de devolución de productos que generaron puntos, dichos puntos serán descontados del total acumulado del usuario.</li>
            <li>En caso de devolución de un producto obtenido mediante canje de puntos, los puntos utilizados para dicho canje no serán reembolsados.</li>
            </ul>


<h3>12. Política de Envíos – Favorito Logistics</h3>
<p>Milogar trabaja junto con <strong>Favorito Logistics</strong>, nuestra empresa aliada encargada del transporte y entrega de los pedidos. A continuación se detallan las condiciones aplicables:</p>

<h3>Zonas de cobertura</h3>
<ul>
  <li><strong>Zonas urbanas de Quito:</strong> Norte, Centro, Sur</li>
  <li><strong>Valles:</strong> Cumbayá, Tumbaco, Los Chillos</li>
  <li><strong>Periferia cercana:</strong> El Quinche, Calderón, Pifo, Tababela, Puembo</li>
  <li><strong>Otras provincias:</strong> Coordinación especial</li>
</ul>

<h3>Tarifas de envío por zona, monto de compra y empresa de transporte</h3>
<p><strong>Milogar trabaja con dos empresas de transporte:</strong></p>
<ul>
  <li><strong>Favorito Logistics:</strong> Disponible únicamente en zonas cercanas a nuestra tienda (ej. El Quinche, Guayllabamba, Yaruquí, Checa, Cuscungo, Calderón). El cliente puede elegir esta opción si se encuentra dentro del rango.</li>
  <li><strong>Servientrega:</strong> Se usa para entregas a zonas más alejadas o cuando el cliente la prefiere.</li>
</ul>

<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead class="table-light text-center">
      <tr>
        <th rowspan="2">Zona</th>
        <th rowspan="2">Ejemplos de Localidades</th>
        <th colspan="2">Compra menor a $20</th>
        <th colspan="2">Compra entre $20 y $50</th>
        <th colspan="2">Compra mayor a $50</th>
      </tr>
      <tr>
        <th>Favorito Logistics</th>
        <th>Servientrega</th>
        <th>Favorito Logistics</th>
        <th>Servientrega</th>
        <th>Favorito Logistics</th>
        <th>Servientrega</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Zonas cercanas</strong></td>
        <td>El Quinche, Guayllabamba, Yaruquí, Checa, Cuscungo, Calderón</td>
        <td>$2.00</td>
        <td>Según tarifa Servientrega</td>
        <td>Gratis</td>
        <td>Según tarifa Servientrega</td>
        <td>Gratis</td>
        <td>—</td>
      </tr>
      <tr>
        <td><strong>Quito Urbano</strong></td>
        <td>Norte, Centro, Sur</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
      </tr>
      <tr>
        <td><strong>Valles</strong></td>
        <td>Cumbayá, Tumbaco, Los Chillos</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
      </tr>
      <tr>
        <td><strong>Otras provincias</strong></td>
        <td>Guayaquil, Cuenca, Ambato, etc.</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
        <td>—</td>
        <td>Según tarifa Servientrega</td>
      </tr>
    </tbody>
  </table>
</div>

<h3>Tiempos de entrega</h3>
<ul>
  <li><strong>Quito y alrededores:</strong> 24–48 horas hábiles</li>
  <li><strong>Provincias:</strong> 2–5 días hábiles</li>
</ul>

<h3>Condiciones de entrega</h3>
<ul>
  <li>Los pedidos son entregados de lunes a sábado, entre 09:00 y 18:00.</li>
  <li>El cliente será notificado por correo o WhatsApp previo a la entrega.</li>
  <li>Si no se encuentra el destinatario, se reprogramará una sola vez sin recargo. Un segundo intento tendrá costo adicional.</li>
</ul>

<h3>Reenvíos y devoluciones por no entrega</h3>
<ul>
  <li>Después de 2 intentos fallidos de entrega, el paquete será retornado a la bodega en El Quinche.</li>
  <li>El cliente podrá solicitar un nuevo envío pagando nuevamente el valor del flete.</li>
</ul>
<h3>Envíos por Servientrega</h3>
<ul>
  <li>Los envíos por Servientrega aplican para zonas donde no cubre nuestro reparto propio.</li>
  <li>El costo del envío será calculado según el peso y destino del paquete.</li>
  <li>El cliente recibirá un número de guía para el seguimiento del pedido directamente en la web de Servientrega.</li>
  <li>El tiempo de entrega dependerá de las políticas y tiempos operativos de Servientrega.</li>
  <li>En caso de devolución por parte de Servientrega, el cliente asumirá el costo de un nuevo envío si así lo solicita.</li>
</ul>
<section id="compras-mayoristas">
  <h3>13. Compras Mayoristas</h3>
  <p>Las compras al por mayor están sujetas a las siguientes condiciones:</p>
  <ul>
    <li>El cliente deberá completar la información de facturación de manera completa y precisa, incluyendo razón social o nombre, RUC o número de identificación tributaria, dirección fiscal, teléfono y correo electrónico.</li>
    <li>El método de pago exclusivo para compras mayoristas es transferencia o depósito bancario.</li>
    <li>Una vez confirmado el pedido en el sitio web, se generará un PDF con el detalle del pedido y datos para el pago.</li>
    <li>El cliente podrá enviar dicho PDF al administrador junto con el comprobante de pago, o bien esperar que el administrador revise el pedido y se ponga en contacto.</li>
    <li>El administrador verificará el pedido y, de corresponder, aplicará los descuentos por compras mayoristas antes de la confirmación final.</li>
    <li>El pedido será procesado únicamente después de la validación del pago y la confirmación de los descuentos aplicables.</li>
  </ul>
</section>

<h4>Contacto para consultas de envío</h4>
<p>📞 <strong>0989082073</strong>  
<br>✉️ <strong>envios@apolologistics.com</strong></p>


            
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary">Aceptar y Continuar</a>
            </div>
        </div>
    </div>
    <br><br>
    <!-- Chatbot Bubble Container -->
<div class="chatbot-container">
  <div class="chatbot-icon" onclick="toggleChatbot()">
    <img src="https://cdn-icons-png.flaticon.com/512/4712/4712104.png" alt="Chatbot">
    <span class="chatbot-text">¿Necesitas ayuda?</span>
  </div>

  <div id="chatbot-box" class="chatbot-box">
    <div class="chatbot-header">
      Chat Milogar
      <span class="chatbot-close" onclick="toggleChatbot()">✖</span>
    </div>
    <div id="chatlog" class="chatbot-log"></div>
    <div class="chatbot-input">
      <input id="userInput" type="text" placeholder="Escribe tu mensaje...">
      <button onclick="sendMessage()">Enviar</button>
    </div>
  </div>
</div>

        <div class="whatsapp-container">
        <a href="https://wa.me/593967342065" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
    </div>

  <!-- Javascript-->
  <!-- Libs JS -->
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
  <script src="assets/libs/slick-carousel/slick/slick.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="assets/libs/nouislider/dist/nouislider.min.js"></script>
  <script src="assets/libs/wnumb/wNumb.min.js"></script>
  <script src="assets/libs/rater-js/index.js"></script>
  <script src="assets/libs/prismjs/prism.js"></script>
  <script src="assets/libs/prismjs/components/prism-scss.min.js"></script>
  <script src="assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
  <script src="assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
  <script src="assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
  <script src="assets/libs/dropzone/dist/min/dropzone.min.js"></script>
  <script src="assets/libs/flatpickr/dist/flatpickr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <!-- choose one -->

  <script src="assets/js/cart.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/BusquedaDinamica.js"></script>
  <script src="assets/js/actualizarInformacion.js"></script>
  <script>
  const respuestas = {
    hola: "¡Hola! ¿En qué puedo ayudarte hoy?",
    horario: "Atendemos de lunes a domingo de 07:15 a 21:00.",
    envio: "Realizamos envíos a Quito (24–48h) y a provincias (2–5 días hábiles).",
    precios: "Puedes ver los precios actualizados directamente en cada producto.",
    ayuda: "Estoy aquí para ayudarte. ¿Sobre qué necesitas información?",
    
    puntos: "Puedes ganar puntos con cada compra registrada en tu cuenta. Estos puntos pueden canjearse por premios o descuentos especiales.",
    canje: "Para canjear tus puntos, ve a tu perfil y entra en la sección 'Mis puntos'. Ahí verás las opciones disponibles de canje.",
    ganar: "Ganas puntos por cada compra registrada. También puedes ganar puntos adicionales en promociones o campañas especiales.",
    contraseña: "Para recuperar tu contraseña, haz clic en '¿Olvidaste tu contraseña?' en la página de inicio de sesión y sigue las instrucciones que se envían a tu correo.",
    mayor: "Para compras al por mayor, realiza tu pedido seleccionando pago por transferencia. Se generará un PDF con el resumen que debes validar.",
    pdf: "El PDF del pedido se genera automáticamente y será revisado por nuestro equipo. Recibirás el precio final validado por WhatsApp en breve.",
    
    pago: "Aceptamos pagos con tarjeta, transferencia bancaria y depósitos. Elige tu método preferido en el proceso de compra.",
    devolucion: "Aceptamos devoluciones por productos dañados o errores en el envío. Contáctanos dentro de las 48 horas de haber recibido tu pedido.",
    
    default: "Lo siento, aún no entiendo esa pregunta. ¿Puedes intentar con otra más específica?"
  };

  function toggleChatbot() {
    const box = document.getElementById('chatbot-box');
    box.style.display = box.style.display === 'block' ? 'none' : 'block';
  }

  function sendMessage() {
    const input = document.getElementById("userInput");
    const userMessage = input.value.trim();
    if (!userMessage) return;

    appendMessage("Tú", userMessage);
    input.value = "";

    const lowerMsg = userMessage.toLowerCase();
    let respuesta = respuestas.default;

    for (const key in respuestas) {
      if (lowerMsg.includes(key)) {
        respuesta = respuestas[key];
        break;
      }
    }

    setTimeout(() => {
      appendMessage("MILOGAR", respuesta);
    }, 600);
  }

  function appendMessage(sender, text) {
    const chatlog = document.getElementById("chatlog");
    const newMsg = document.createElement("div");
    newMsg.innerHTML = `<strong>${sender}:</strong> ${text}`;
    newMsg.style.marginBottom = "10px";
    chatlog.appendChild(newMsg);
    chatlog.scrollTop = chatlog.scrollHeight;
  }
</script>


</body>


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>