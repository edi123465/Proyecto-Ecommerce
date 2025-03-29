<?php
$query = isset($_GET['q']) ? $_GET['q'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
</head>
<body>

<h1>Resultados de búsqueda para "<?php echo htmlspecialchars($query); ?>"</h1>

<div id="resultados-container"></div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const resultadosContainer = document.getElementById("resultados-container");
        const resultadosGuardados = localStorage.getItem("resultadosBusqueda");

        if (resultadosGuardados) {
            const data = JSON.parse(resultadosGuardados);

            if (data.error) {
                resultadosContainer.innerHTML = `<h1 class='text-danger'>${data.error}</h1>`;
            } else {
                let productosHTML = '<div class="row">';
                data.productos.forEach(producto => {
                    const rutaImagen = `/Milogar/assets/imagenesMilogar/productos/${producto.imagen}`;
                    productosHTML += `
                        <div class="col-md-4">
                            <div class="card">
                                <img src="${rutaImagen}" class="card-img-top" alt="Producto">
                                <div class="card-body">
                                    <h5 class="card-title">${producto.nombreProducto}</h5>
                                    <p class="card-text">$${parseFloat(producto.precio).toFixed(2)}</p>
                                    <a href="#" class="btn btn-primary">Ver más</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                productosHTML += '</div>';
                resultadosContainer.innerHTML = productosHTML;
            }

            // Limpiar los resultados de localStorage después de mostrarlos
            localStorage.removeItem("resultadosBusqueda");
        } else {
            resultadosContainer.innerHTML = "<p>No se encontraron resultados.</p>";
        }
    });
</script>

</body>
</html>
