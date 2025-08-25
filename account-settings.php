<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;


?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

<head>

  <title>MILOGAR - Términos y Condiciones</title>
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
<?php $sessionActiva = (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Administrador' || $_SESSION['user_role'] === 'Cliente')); ?>

  <!-- Modal de Bootstrap para editar los datos -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Actualizar Información de Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Nombre de usuario (editable en el modal) -->
          <div class="mb-3">
            <label class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="modal_user_name" require>
          </div>

          <!-- Email (editable en el modal) -->
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="modal_email" require>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="saveChangesButton">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- section -->
  <section>
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row">
        <!-- col -->
        <div class="col-12">
          <div class="p-6 d-flex justify-content-between align-items-center d-md-none">
            <!-- heading -->
            <h3 class="fs-5 mb-0">Configuración de la cuenta</h3>
            <!-- btn -->
            <button class="btn btn-outline-gray-400 text-muted d-md-none" type="button" data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasAccount" aria-controls="offcanvasAccount">
              <i class="feather-icon icon-menu"></i>
            </button>
          </div>
        </div>
        <!-- col -->
        <div class="col-lg-3 col-md-4 col-12 border-end  d-none d-md-block">
          <div class="pt-10 pe-lg-10">
            <!-- nav item -->
            <ul class="nav flex-column nav-pills nav-pills-dark">
              <li class="nav-item">
                <a class="nav-link " aria-current="page" href="account-orders"><i
                    class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <a class="nav-link active" href="account-settings"><i
                    class="feather-icon icon-settings me-2"></i>Configuración</a>
              </li>
              <!-- nav item -->
              <li class="nav-item">
                <hr>
              </li>
                <!-- nav item -->
                <li class="nav-item">
                <a href="#" class="nav-link logout-link" data-url="Views/login/logout.php">
                    <i class="feather-icon icon-log-out me-2"></i>Cerrar Sesión
                </a>
                </li>

            </ul>
          </div>
        </div>
        <div class="col-lg-9 col-md-8 col-12">
          <div class="p-6 p-lg-10">
            <div class="mb-6">
              <!-- heading -->
              <h2 class="mb-0">Configuración de la cuenta</h2>
            </div>
            <div>
              <!-- heading -->
              <h5 class="mb-4">Detalles</h5>
              <div class="row">
                <div class="col-lg-5">
                  <!-- form -->
                  <form id="userForm">
                    <!-- Nombre de usuario (inicialmente bloqueado) -->
                    <input type="hidden" id="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">

                    <div class="mb-3">
                      <label class="form-label">Nombre de usuario</label>
                      <input type="text" class="form-control" id="user_name" value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>" readonly>
                    </div>

                    <!-- Email (inicialmente bloqueado) -->
                    <div class="mb-3">
                      <label class="form-label">Dirección de correo</label>
                      <input type="email" class="form-control" id="email" value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') : ''; ?>" readonly>
                    </div><br>

                    <!-- Botón para abrir el modal -->
                    <div class="mb-3">
                      <button type="button" class="btn btn-primary" id="openModalButton">Actualizar Información</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <hr class="my-10">
            <div class="pe-lg-14">
              <!-- heading -->
              <h5 class="mb-4">Actualizar Contraseña</h5>
              <form id="formCambiarContrasenia" onsubmit="cambiarContrasenia(event)">
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Contraseña Actual</label>
                  <input type="password" class="form-control" placeholder="" id="contrasenia_actual" required>
                </div>
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Nueva Contraseña</label>
                  <input type="password" class="form-control" placeholder="" id="nueva_contrasenia" required>
                </div>
                <!-- input -->
                <div class="mb-3 col">
                  <label class="form-label">Confirmar nueva Contraseña</label>
                  <input type="password" class="form-control" placeholder="" id="confirmar_contrasenia" required>
                </div>
                <!-- input -->
                <div class="col-12">
                  <p class="mb-4">¿No recuerdas tu contraseña actual?<a href="forgot-password"> Restablece tu contraseña.</a></p>
                  <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                </div>
              </form>
              <div id="mensaje"></div> <!-- Aquí mostraremos la respuesta -->
            </div>
            <hr class="my-10">
            <div>
              <!-- heading -->
              <h5 class="mb-4">Eliminar Cuenta</h5>
              <p class="mb-2">¿Quieres eliminar tu cuenta?</p>
              <p class="mb-5">Esta cuenta puede contener pedidos. Al eliminarla, se eliminarán todos los detalles de los pedidos asociados.</p>
              <!-- btn -->
              <button id="eliminarCuenta" class="btn btn-danger">Eliminar Cuenta</button>
              <div id="mensaje"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- modal -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasAccount" aria-labelledby="offcanvasAccountLabel">
    <!-- offcanvas header -->
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasAccountLabel">Offcanvas</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- offcanvas body -->
    <div class="offcanvas-body">
      <ul class="nav flex-column nav-pills nav-pills-dark">
        <!-- nav item -->
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="account-orders"><i
              class="feather-icon icon-shopping-bag me-2"></i>Tus pedidos</a>
        </li>
        <!-- nav item -->

        <li class="nav-item">
          <a class="nav-link active" href="account-settings"><i
              class="feather-icon icon-settings me-2"></i>Configuración</a>
        </li>
       
      </ul>
      <hr class="my-6">
      <div>
        <!-- navs -->
        <ul class="nav flex-column nav-pills nav-pills-dark">
                <!-- nav item -->
                <li class="nav-item">
                <a href="#" class="nav-link logout-link" data-url="Views/login/logout.php">
                    <i class="feather-icon icon-log-out me-2"></i>Cerrar Sesión
                </a>
                </li>
        </ul>
      </div>
    </div>
  </div>

  <?php require_once "Views/Navigation/footer.php"; ?>
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

  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  <!-- choose one -->

  <script src="assets/js/cart.js"></script>
  <script src="assets/js/login.js"></script>
  <script src="assets/js/BusquedaDinamica.js"></script>
  <script src="assets/js/actualizarInformacion.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const logoutLinks = document.querySelectorAll('.logout-link');

      logoutLinks.forEach(link => {
          link.addEventListener('click', function (e) {
              e.preventDefault();

              Swal.fire({
                  title: '¿Cerrar sesión?',
                  text: 'Se cerrará tu sesión actual. ¿Deseas continuar?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Sí, cerrar sesión',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Eliminar carrito del localStorage si existe
                      localStorage.removeItem('carrito');
                      localStorage.removeItem('ultimaActividad');

                      // Redireccionar a la URL de logout
                      const logoutUrl = link.dataset.url;
                      window.location.href = logoutUrl;
                  }
              });
          });
      });
  });
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


<!-- Mirrored from freshcart.codescandy.com/pages/account-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->

</html>