document.addEventListener("DOMContentLoaded", () => {
    // Obtener los elementos del DOM
    const formularioBusqueda = document.getElementById('searchForm');
    const campoBusqueda = document.getElementById('busqueda');
    const resultadosDiv = document.getElementById('resultados');


    // Verificar si el contenedor de resultados existe
    if (!resultadosDiv) {
        console.log("El contenedor de resultados no se encuentra en esta vista.");
        return;
    }

    // Función para realizar la búsqueda y mostrar los resultados
    const realizarBusqueda = async (query) => {
        if (query === "") {
            resultadosDiv.innerHTML = "<h1 class='text-muted'>Escribe algo para buscar.</h1>";
            return;
        }

        try {
            const response = await fetch(`http://localhost:8088/Milogar/Controllers/ProductoController.php?action=search&q=${encodeURIComponent(query)}`);

            if (response.ok) {
                const data = await response.json(); // Espera la respuesta como JSON

                if (data.error) {
                    resultadosDiv.innerHTML = `<h1 class='text-danger'>${data.error}</h1>`;
                } else {
                    // Aquí debes renderizar los productos en el DOM, por ejemplo:
                    let productosHTML = '';
                    data.productos.forEach(producto => {
                        productosHTML += `<div class="producto">
                                            <h2>${producto.nombre}</h2>
                                            <p>${producto.descripcion}</p>
                                            </div>`;
                    });
                    resultadosDiv.innerHTML = productosHTML; // Mostrar los productos en el contenedor
                }
            } else {
                resultadosDiv.innerHTML = "<h1 class='text-danger'>Hubo un error al realizar la búsqueda.</h1>";
            }
        } catch (error) {
            resultadosDiv.innerHTML = "<h1 class='text-danger'>Error al conectar con el servidor.</h1>";
        }
    };

    // Evento cuando se envía el formulario
    formularioBusqueda.addEventListener("submit", (e) => {
        e.preventDefault();  // Evita que se recargue la página al enviar el formulario
        const query = campoBusqueda.value.trim();
        realizarBusqueda(query);  // Realiza la búsqueda
    });

    // Evento al presionar Enter en el campo de búsqueda
    campoBusqueda.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();  // Evita que el formulario se envíe de forma predeterminada
            const query = campoBusqueda.value.trim();
            realizarBusqueda(query);  // Realiza la búsqueda
        }
    });
});
