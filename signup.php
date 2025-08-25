<?php
session_start();
$form_disabled = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>Empieza a comprar</title>
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
  
</head>

<body>

  <?php
  require_once "Views/Navigation/navigation.php";
  ?>


  <!-- section -->
  <section class="my-lg-14 my-8">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-6 col-lg-4 order-lg-1 order-2">
          <!-- img -->
          <img src="assets/imagenesMilogar/logomilo.jpg" alt="" class="img-fluid">
        </div>
                                <h3>Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

        <!-- col -->
        <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1">
          <div class="mb-lg-9 mb-5">
            <h1 class="mb-1 h2 fw-bold">Empiece a comprar</h1>
            <p>¡Bienvenido a MILOGAR! Ingrese sus datos para comenzar.</p>
            
          </div>
          <!-- form -->
          <form id="registerForm" method="POST">
            <div class="row g-3">
              <!-- col -->
              <div class="col-12">
                <!-- input --><input type="text" class="form-control" placeholder="Nombre de usuario" id="nombreUsuario" name="nombre_usuario" aria-label="First name" required <?php echo $form_disabled ? 'disabled' : ''; ?>
                  required>
              </div>
              <div class="col-12">
                <!-- input --><input type="email" class="form-control" placeholder="Correo electrónico" id="email" name="correo_electronico" aria-label="Last name" <?php echo $form_disabled ? 'disabled' : ''; ?>
                  required>
              </div>
              <div class="col-12">

                <!-- input --><input type="password" class="form-control" id="password" name="contrasenia" placeholder="Contraseña" <?php echo $form_disabled ? 'disabled' : ''; ?> required>
              </div>
              <div class="col-12">
                <!-- input --><input type="password" class="form-control" id="confirmPassword" name="confirmar_contrasenia" <?php echo $form_disabled ? 'disabled' : ''; ?> placeholder="Confirma tu contraseña"
                  required>
              </div>
               
<?php if ($form_disabled): ?>
    <p>
      Cierra la sesión actual para poder crear una nueva cuenta.
      <a href="../Views/login/logout.php">Cerrar Sesión</a>
    </p>
<?php else: ?>
    <div class="col-12 d-grid mb-3">
        <button type="submit" class="btn btn-primary">Registrar cuenta</button>
    </div>

<?php endif; ?>
    <div class="col-12 d-grid mb-4">
        <div id="g_id_onload"
             data-client_id="TU_CLIENT_ID_DE_GOOGLE"
             data-callback="handleCredentialResponse"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-size="large"
             data-theme="outline"
             data-text="sign_in_with"
             data-shape="rectangular"
             data-logo_alignment="left">
        </div>
    </div>
<div class="col-12 d-grid">
    <a class="btn btn-light" href="../index.php">Visitar la tienda</a>
</div>

<!-- text -->
<p><small>Al continuar, aceptas nuestros <a href="terminos_condiciones.php">Términos de Servicio</a> y nuestra <a href="terminos_condiciones.php">Política de Privacidad</a></small></p>
</div>
          </form>
        </div>
      </div>
    </div>


  </section>
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

  <?php require_once "Views/Navigation/footer.php";  ?>


  </div>
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
<script src="https://accounts.google.com/gsi/client" async defer></script>

  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <!-- choose one -->


  <script src="assets/js/registrarse.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/cart.js"></script>
  <script src="assets/js/BusquedaDinamica.js"> </script>
    <script>
            const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

    </script>
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


<!-- Mirrored from freshcart.codescandy.com/pages/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>