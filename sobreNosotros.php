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
    </style>
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
            <h3>Tienes <span id="puntosUsuario">0</span> puntos acumulados 🎁</h3>

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

    <!-- Theme JS -->
    <script src="assets/js/theme.min.js"></script>
    <!-- Incluye SweetAlert desde el CDN -->
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/BusquedaDinamica.js"> </script>
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
</body>

</html>