document.addEventListener('DOMContentLoaded', function () {
    // Obtener el valor del atributo data-role
    var userRole = document.getElementById('role').getAttribute('data-role');

    // Verificar si el rol es Administrador
    var isAdmin = userRole === 'Administrador';

    // Lógica para mostrar el botón de edición si es administrador
    if (isAdmin) {
        console.log("Usuario es Administrador. Mostrar botón de editar.");
    } else {
        console.log("Usuario no es Administrador.");
    }

function obtenerCategorias() {
    fetch('http://localhost:8080/Milogar/Controllers/TiendaController.php?action=obtenerCategorias')
        .then(response => {
            console.log("✅ Respuesta cruda del fetch:", response);
            return response.json();
        })
        .then(result => {
            console.log("📦 Resultado parseado (JSON):", result);

            if (result.status === 'success') {
                // Si la respuesta es exitosa, llenamos el contenedor con las categorías
                const contenedor = document.querySelector('.product-carousel'); 
                contenedor.innerHTML = ''; 

                console.log("🔎 Categorías recibidas:", result.data);

                result.data.forEach((categoria, index) => {
                    console.log(`➡️ Categoría [${index}]:`, categoria);

                    // Comprobamos si la imagen está vacía y asignamos una imagen predeterminada si es necesario
                    const imagen = categoria.imagen 
                        ? `assets/imagenesMilogar/Categorias/${categoria.imagen}` 
                        : 'assets/imagenesMilogar/Categorias/default-category.jpg';

                    console.log(`🖼️ Imagen usada para ${categoria.nombreCategoria}: ${imagen}`);
let itemHTML = `
    <div class="swiper-slide">
        <div class="card categoria-card h-100 text-center p-4">
            <img src="${imagen}" 
                alt="${categoria.nombreCategoria}" 
                class="categoria-img mx-auto mb-3">
            <h6 class="fw-bold mb-2">${categoria.nombreCategoria}</h6>
            <p class="categoria-desc text-muted small">
                ${categoria.descripcionCategoria || "Sin descripción disponible"}
            </p>
        </div>
    </div>
`;

                    contenedor.innerHTML += itemHTML;
                });

                // Iniciar el carrusel después de insertar las categorías
                iniciarCarrusel();
            } else {
                console.warn("⚠️ Error en la respuesta del backend:", result.message);
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            console.error('❌ Error al obtener las categorías:', error);
            alert('Hubo un error al obtener las categorías.');
        });
}


    // Función para iniciar el carrusel con Slick
    function iniciarCarrusel() {
        // Configurar el carrusel de Slick
        $('.product-carousel').slick({
            slidesToShow: 5,           // Mostrar 6 tarjetas por vez
            slidesToScroll: 1,         // Cambiar una tarjeta a la vez
            infinite: true,            // Hacer el carrusel infinito
            autoplay: true,            // Activar autoplay
            autoplaySpeed: 2000,       // Cambiar la imagen cada 2 segundos
            dots: true,                // Mostrar los puntos de navegación
            arrows: false,             // Ocultar las flechas de navegación
            responsive: [
                {
                    breakpoint: 1024,   // Pantallas más grandes de 1024px
                    settings: {
                        slidesToShow: 4,  // Mostrar 4 tarjetas en pantallas medianas
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,    // Pantallas más grandes de 768px
                    settings: {
                        slidesToShow: 2,  // Mostrar 2 tarjetas en pantallas más pequeñas
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,    // Pantallas más grandes de 480px
                    settings: {
                        slidesToShow: 1,  // Mostrar 1 tarjeta en pantallas muy pequeñas
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    // Llamar a la función para obtener las categorías cuando la página cargue
    obtenerCategorias();
});
