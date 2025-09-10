<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
//trae informacion del carrito de compras


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre nosotros</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

    <style>
        .about-us .card {
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .about-us .card:hover {
            transform: translateY(-5px);
        }

        .shadow-lg {
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .shadow-sm {
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.15) !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .swiper-slide img {
            width: 100%;
            height: 150px;
            object-fit: contain;
        }

        .swiper {
            position: relative;
            padding: 20px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #333;
            /* Color de las flechas */
        }
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
.comentarios-usuario-container {
            max-width: 1300px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            padding: 20px 25px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .comentarios-usuario-container h2 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 8px;
            color: #2a2a72;
        }

        .comentarios-lista {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* dos columnas iguales */
            gap: 18px 20px;
            /* espacio vertical y horizontal */
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Opcional: si quieres que en pantallas pequeñas pase a 1 columna */
        @media (max-width: 600px) {
            .comentarios-lista {
                grid-template-columns: 1fr;
            }
        }

        /* Estilo scrollbar para navegadores modernos */
        .comentarios-lista::-webkit-scrollbar {
            width: 7px;
        }

        .comentarios-lista::-webkit-scrollbar-thumb {
            background-color: #4a90e2;
            border-radius: 10px;
        }

        /* Comentario individual */
        .comentario-item {
            background: #f9faff;
            border-radius: 10px;
            padding: 15px 18px;
            box-shadow: inset 0 0 6px rgba(74, 144, 226, 0.15);
            transition: background-color 0.3s ease;
        }

        .comentario-item:hover {
            background: #e6f0ff;
        }

        .comentario-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .usuario-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 12px;
            border: 2px solid #4a90e2;
            flex-shrink: 0;
        }

        .usuario-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .usuario-info strong {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: #2a2a72;
        }

        .fecha-comentario {
            font-size: 0.85rem;
            color: #777;
        }

        .comentario-texto {
            font-size: 1rem;
            line-height: 1.4;
            color: #444;
            white-space: pre-wrap;
            /* respeta saltos de línea */
        }

        .btn-agregar-comentario {
            display: block;
            /* cambiar inline-block a block */
            margin: 15px auto 25px auto;
            /* vertical arriba y abajo, centrado horizontal */
            /* resto igual */
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(45deg, #4a90e2, #357ABD);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgb(74 144 226 / 0.4);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }


        .btn-agregar-comentario:hover {
            background: linear-gradient(45deg, #357ABD, #2A5EAB);
            box-shadow: 0 8px 25px rgb(53 122 189 / 0.6);
        }

        .btn-agregar-comentario:active {
            transform: translateY(2px);
            box-shadow: 0 3px 10px rgb(53 122 189 / 0.7);
        }

        .btn-suscribirse {
            display: block;
            /* cambio aquí */
            margin: 15px auto 25px auto;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 600;
            color: #4a90e2;
            background: transparent;
            border: 2px solid #4a90e2;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            user-select: none;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }

        .btn-suscribirse:hover {
            background-color: #4a90e2;
            color: white;
        }

        /* Botón igual que tu estilo pero verde menta */
        .btn-agregar-comentario {
            display: inline-block;
            margin: 20px auto 30px auto;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(45deg, #3eb489, #2a9d8f);
            /* verde menta */
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(62, 180, 137, 0.4);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-agregar-comentario:hover {
            background: linear-gradient(45deg, #2a9d8f, #23867a);
            box-shadow: 0 8px 25px rgba(36, 134, 114, 0.6);
        }

        /* Personaliza inputs y selects dentro del modal */
        .modal-content .form-control,
        .modal-content .form-select {
            border-radius: 12px;
            border: 1.5px solid #3eb489;
            /* verde menta */
            box-shadow: inset 0 2px 8px rgba(62, 180, 137, 0.15);
            transition: border-color 0.3s ease;
        }

        .modal-content .form-control:focus,
        .modal-content .form-select:focus {
            border-color: #23867a;
            box-shadow: 0 0 10px rgba(35, 134, 122, 0.3);
            outline: none;
        }

        /* Botón enviar dentro del modal */
        .modal-content .btn-primary {
            background: linear-gradient(45deg, #3eb489, #2a9d8f);
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 12px 0;
            box-shadow: 0 6px 18px rgba(62, 180, 137, 0.4);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .modal-content .btn-primary:hover {
            background: linear-gradient(45deg, #2a9d8f, #23867a);
            box-shadow: 0 10px 28px rgba(36, 134, 114, 0.6);
        }

        .valoracion-estrellas {
            font-size: 1.3rem;
            user-select: none;
            display: inline-flex;
            gap: 4px;
        }

        .valoracion-estrellas .estrella {
            position: relative;
            color: #ccc;
            /* color estrellas vacías */
            cursor: default;
            transition: color 0.3s ease;
            width: 24px;
            height: 24px;
            display: inline-block;
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.1));
        }

        /* Estrellas activas con degradado dorado */
        .valoracion-estrellas .estrella.activa {
            color: transparent;
            /* para aplicar gradiente */
        }

        .valoracion-estrellas .estrella.activa::before {
            content: '★';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #FFD700, #FFC107 70%, #FFB300);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 2px rgba(255, 215, 0, 0.6));
            transition: background 0.3s ease;
        }

        .texto-iniciar-suscribir {
            text-align: center;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            font-size: 1rem;
            margin: 20px 0 30px 0;
        }

        .texto-iniciar-suscribir a {
            color: #4a90e2;
            text-decoration: none;
            margin: 0 8px;
            transition: color 0.3s ease;
            font-weight: 700;
        }

        .texto-iniciar-suscribir a:hover {
            color: #357abd;
            text-decoration: underline;
        }

        .texto-iniciar-suscribir a:first-child {
            margin-left: 0;
        }

        .texto-iniciar-suscribir a:last-child {
            margin-right: 0;
        }
        
    </style>

</head>

<body>
    <?php
    require_once "Views/Navigation/navigation.php";
    ?>
    <!-- Sección Acerca de Nosotros -->
    <section class="about-us py-5">
        <div class="container">
            <h1 class="text-center fw-bold mb-5">Acerca de Nosotros</h1>

            <!-- Introducción -->
            <div class="row align-items-center bg-light p-5 shadow rounded" data-aos="fade-up">
                <div class="col-md-6">
                    <p class="lead">
                        En <strong>MILOGAR</strong>, nos especializamos en ofrecer una amplia gama de productos esenciales para el día a día, brindando soluciones confiables tanto para hogares como para negocios. Nos destacamos por nuestro compromiso con la excelencia y la satisfacción de nuestros clientes.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="assets/imagenesMilogar/logomilo.jpg" alt="Logo Milogar" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>

            <!-- Misión -->
            <h1 class="text-center fw-bold mt-5">Misión</h1>
            <div class="row align-items-center bg-white p-5 shadow rounded" data-aos="fade-right">
                <div class="col-md-6 text-center">
                    <img src="assets/imagenesMilogar/mision.webp" alt="Misión" class="img-fluid rounded-3 shadow-lg">
                </div>
                <div class="col-md-6">
                    <p class="lead">
                        Proveer productos de alta calidad a precios competitivos, asegurando disponibilidad y accesibilidad para nuestros clientes. Nos esforzamos por ser un socio estratégico para hogares, emprendedores y empresas que buscan abastecerse con los mejores productos del mercado.
                    </p>
                </div>
            </div>

            <!-- Visión -->
            <h1 class="text-center fw-bold mt-5">Visión</h1>
            <div class="row align-items-center bg-light p-5 shadow rounded" data-aos="fade-left">
                <div class="col-md-6">
                    <p class="lead">
                        Convertirnos en el referente líder en la distribución de productos de primera necesidad, artículos para el hogar y desechables para alimentos, ofreciendo innovación, eficiencia y un servicio al cliente excepcional en cada compra.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="assets/imagenesMilogar/VISION.jpg" alt="Visión" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>

            <!-- Nuestros Valores -->
            <h2 class="text-center fw-bold mt-5">Nuestros Valores</h2>
            <div class="row mt-4">
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card text-center border-0 shadow-sm p-4">
                        <i class="bi bi-hand-thumbs-up-fill fs-1 text-primary"></i>
                        <h4 class="mt-3">Compromiso</h4>
                        <p>Trabajamos con pasión y dedicación para garantizar la mejor experiencia de compra.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card text-center border-0 shadow-sm p-4">
                        <i class="bi bi-award-fill fs-1 text-primary"></i>
                        <h4 class="mt-3">Calidad</h4>
                        <p>Seleccionamos cuidadosamente cada producto para asegurar su excelencia.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card text-center border-0 shadow-sm p-4">
                        <i class="bi bi-shield-check fs-1 text-primary"></i>
                        <h4 class="mt-3">Responsabilidad</h4>
                        <p>Priorizamos la ética y la sostenibilidad en cada proceso de compra.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container mt-4">
        <h1 class="text-center">Trabajamos con las mejores marcas</h1><br>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="assets/imagenesMilogar/casapycalogo.png" alt="Marca 1"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/reylogo.png" alt="Marca 2"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/plapasa.png" alt="Marca 3"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/logo-consuplast.png" alt="Marca 4"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/logobacan.avif" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/hglogo.png" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/plastife.png" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/plastiutil.png" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/goldery.png" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/cristar.webp" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/umco.png" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/pronaca.jpg" alt="Marca 5"></div>
                <div class="swiper-slide"><img src="assets/imagenesMilogar/cerveceriaNacional.png" alt="Marca 5"></div>


            </div>
            <!-- Botones de navegación -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div class="comentarios-usuario-container">
        <h2>Reseña de nuestros clientes</h2>

        <div class="comentarios-lista">
            <!-- Cada comentario -->
            <div class="comentario-item">
                <div class="comentario-header">
                    <div class="usuario-avatar">
                        <img src="https://i.pravatar.cc/50?img=5" alt="Avatar usuario">
                    </div>
                    <div class="usuario-info">
                        <strong>Nombre Usuario</strong>
                        <div class="valoracion-estrellas" data-valor="4">
                            <div class="valoracion-estrellas" data-valor="3">
                                <span class="estrella activa">&#9733;</span>
                                <span class="estrella activa">&#9733;</span>
                                <span class="estrella activa">&#9733;</span>
                                <span class="estrella">&#9733;</span>
                                <span class="estrella">&#9733;</span>
                            </div>

                        </div>
                        <span class="fecha-comentario">10 Ago 2025</span>
                    </div>
                </div>
                <div class="comentario-texto">
                    Este es un comentario de ejemplo. Me gusta mucho esta plataforma y sus funcionalidades.
                </div>
            </div>
            <!-- Repetir comentario-item para más comentarios -->
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <button type="button" class="btn btn-primary btn-agregar-comentario" data-bs-toggle="modal" data-bs-target="#comentarioModal">
                Agregar comentario
            </button>
        <?php else: ?>
            <p class="texto-iniciar-suscribir">
                <a href="#" data-bs-toggle="modal" data-bs-target="#userModal">Inicia sesión</a>o
                <a href="signup.php">Suscríbete</a> para poder dejar un comentario.
            </p>


        <?php endif; ?>

    </div>
    <!-- Modal Bootstrap -->
    <div class="modal fade" id="comentarioModal" tabindex="-1" aria-labelledby="comentarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="comentarioModalLabel">Deja tu comentario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formComentarioBootstrap">
                        <div class="mb-3">
                            <label for="textoComentario" class="form-label">Comentario</label>
                            <textarea class="form-control" id="textoComentario" name="textoComentario" rows="4" placeholder="Escribe tu comentario aquí..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="valoracion" class="form-label">Valoración</label>
                            <select class="form-select" id="valoracion" name="valoracion" required>
                                <option value="" disabled selected>Selecciona una valoración</option>
                                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                <option value="4">⭐️⭐️⭐️⭐️</option>
                                <option value="3">⭐️⭐️⭐️ </option>
                                <option value="2">⭐️⭐️</option>
                                <option value="1">⭐️</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar comentario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Editar Comentario -->
<div class="modal fade" id="editarComentarioModal" tabindex="-1" aria-labelledby="editarComentarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarComentario">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarComentarioModalLabel">Editar Comentario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editarComentarioId" name="comentario_id" />
          <div class="mb-3">
            <label for="editarDescripcion" class="form-label">Comentario</label>
            <textarea id="editarDescripcion" name="descripcion" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editarValoracion" class="form-label">Valoración</label>
            <select id="editarValoracion" name="valoracion" class="form-select" required>
              <option value="">Selecciona una valoración</option>
              <option value="1">1 ⭐</option>
              <option value="2">2 ⭐⭐</option>
              <option value="3">3 ⭐⭐⭐</option>
              <option value="4">4 ⭐⭐⭐⭐</option>
              <option value="5">5 ⭐⭐⭐⭐⭐</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>

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
    </div>
        <div class="whatsapp-container">
        <a href="https://wa.me/593967342065" target="_blank" class="whatsapp-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            <span class="whatsapp-text">¿Cómo podemos ayudarte?</span>
        </a>
    </div>
    <?php
    require_once "Views/Navigation/footer.php";
    ?>
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
    <!-- Incluye SweetAlert desde el CDN -->
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"> </script>
    <script src="assets/js/enviarComentarios.js"></script>

    <!-- Agregar AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
            
    <script>
        AOS.init({
            duration: 1000, // Duración de la animación en ms
            easing: "ease-in-out", // Tipo de animación
            once: true // Solo se ejecuta una vez
        });
    </script>
    <script>
            const usuarioSesion = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                320: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 5
                }
            }
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

<script>
        // Función para crear el HTML de estrellas según la valoración (1-5)
        function crearEstrellas(valoracion) {
            let estrellasHTML = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= valoracion) {
                    estrellasHTML += '<span class="estrella activa">&#9733;</span>'; // estrella llena
                } else {
                    estrellasHTML += '<span class="estrella">&#9733;</span>'; // estrella vacía
                }
            }
            return `<div class="valoracion-estrellas" data-valor="${valoracion}">${estrellasHTML}</div>`;
        }

        // Función para formatear la fecha (ejemplo simple)
        function formatearFecha(fechaStr) {
            const opciones = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            const fecha = new Date(fechaStr);
            return fecha.toLocaleDateString('es-ES', opciones);
        }

        // Función para cargar los comentarios y renderizarlos
        async function cargarComentarios() {
            const contenedor = document.querySelector('.comentarios-lista');
            contenedor.innerHTML = 'Cargando comentarios...';

            try {
                const res = await fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=listarComentariosActivos');
                const json = await res.json();

                if (json.success && json.data.length > 0) {
                    contenedor.innerHTML = ''; // limpiar

                    json.data.forEach(comentario => {
                        // Botones solo si el comentario es del usuario logueado
                        const botonesAccion = (comentario.usuario_id === usuarioSesion) ? `
                        <div class="comentario-acciones d-flex ms-auto gap-2">
                        <button class="btn btn-outline-info btn-sm btn-editar-comentario" data-id="${comentario.id}" title="Editar">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-outline-danger btn-sm btn-eliminar-comentario" data-id="${comentario.id}" title="Eliminar">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                        </div>


                    ` : '';

                        const comentarioHTML = `
                            <div class="comentario-item d-flex flex-column gap-2 p-3 border-bottom">
                            <div class="comentario-header d-flex align-items-center w-100">
                                <div class="usuario-avatar flex-shrink-0">
                                <img src="https://i.pravatar.cc/50?img=${comentario.usuario_id % 70 + 1}" alt="Avatar usuario" class="rounded-circle">
                                </div>
                                <div class="usuario-info ms-3">
                                <strong>${comentario.nombreUsuario}</strong>
                                ${crearEstrellas(comentario.valoracion)}
                                <span class="fecha-comentario d-block small text-muted">${formatearFecha(comentario.fecha_creacion)}</span>
                                </div>
                                ${botonesAccion}
                            </div>
                            <div class="comentario-texto fs-5 text-dark">
                                ${comentario.descripcion}
                            </div>
                            </div>
                        `;
                        contenedor.insertAdjacentHTML('beforeend', comentarioHTML);
                    });

                } else {
                    contenedor.innerHTML = '<p>No hay comentarios para mostrar.</p>';
                }
            } catch (error) {
                contenedor.innerHTML = '<p>Error al cargar los comentarios.</p>';
                console.error('Error fetch comentarios:', error);
            }
        }


        // Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', cargarComentarios);

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('btn-eliminar-comentario')) {
    const comentarioId = e.target.getAttribute('data-id');
    if (!comentarioId) return;

    // Confirmación con SweetAlert2
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esta acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=eliminarComentarioUsuario', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `comentario_id=${encodeURIComponent(comentarioId)}`
        })
        .then(response => response.json())
.then(data => {
  if (data.success) {
    Swal.fire({
      icon: 'success',
      title: 'Comentario eliminado',
      text: 'El comentario fue eliminado correctamente',
      showConfirmButton: true,
      confirmButtonText: 'Aceptar'
    });
    if (typeof cargarComentarios === 'function') cargarComentarios();
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: data.message || 'Error desconocido al eliminar el comentario',
    });
  }
})

        .catch(error => {
          console.error('Error en la petición de eliminar:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al eliminar el comentario.'
          });
        });
      }
    });
  }
});
document.addEventListener('click', function(e) {
  if (e.target.closest('.btn-editar-comentario')) {
    const btn = e.target.closest('.btn-editar-comentario');
    const comentarioId = btn.getAttribute('data-id');

    // Aquí deberías obtener los datos del comentario a editar.
    // Por ejemplo, si ya tienes cargados los comentarios en memoria o puedes hacer fetch.

    // Para este ejemplo, supongamos que extraemos la descripción y valoración del DOM:
    const comentarioItem = btn.closest('.comentario-item');
    const descripcion = comentarioItem.querySelector('.comentario-texto').textContent.trim();

    // Extraer valoracion: asumamos que la tienes en data-valor de div.valoracion-estrellas
    const valoracion = comentarioItem.querySelector('.valoracion-estrellas').getAttribute('data-valor');

    // Setear valores en el modal
    document.getElementById('editarComentarioId').value = comentarioId;
    document.getElementById('editarDescripcion').value = descripcion;
    document.getElementById('editarValoracion').value = valoracion;

    // Mostrar modal con Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('editarComentarioModal'));
    modal.show();
  }
});
document.getElementById('formEditarComentario').addEventListener('submit', async function(e) {
  e.preventDefault();

  const comentarioId = this.comentario_id.value;
  const descripcion = this.descripcion.value.trim();
  const valoracion = this.valoracion.value;

  if (!descripcion) {
    alert('El comentario no puede estar vacío');
    return;
  }

  if (!valoracion || valoracion < 1 || valoracion > 5) {
    alert('Valoración inválida');
    return;
  }

  try {
    const response = await fetch('http://localhost:8080/Milogar/Controllers/ComentariosController.php?action=editarComentarioUsuario', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `comentario_id=${encodeURIComponent(comentarioId)}&descripcion=${encodeURIComponent(descripcion)}&valoracion=${encodeURIComponent(valoracion)}`
    });

    const data = await response.json();

    if (data.success) {
      // Cierra el modal
      const modalEl = document.getElementById('editarComentarioModal');
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();

      // Recarga los comentarios para ver los cambios
      if (typeof cargarComentarios === 'function') cargarComentarios();

      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Comentario editado correctamente'
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message || 'No se pudo editar el comentario'
      });
    }
  } catch (error) {
    console.error('Error al editar comentario:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error al enviar la petición'
    });
  }
});

    </script>

</body>

</html>