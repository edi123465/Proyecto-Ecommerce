// carrito.js
const carrito = [
    { nombre: 'Producto 1', cantidad: 2, precio: 10, imagen: 'ruta/a/imagen1.jpg' },
    { nombre: 'Producto 2', cantidad: 1, precio: 20, imagen: 'ruta/a/imagen2.jpg' },
    // Añade más productos según sea necesario
];

function generarReporte() {
    const carritoJSON = JSON.stringify(carrito); // Convertir el carrito a JSON

    fetch('ruta/a/generar_reporte.php', { // Cambia 'ruta/a/generar_reporte.php' por la ruta correcta
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'carrito=' + encodeURIComponent(carritoJSON)
    })
    .then(response => {
        if (response.ok) {
            return response.blob(); // Obtener el blob del PDF
        }
        throw new Error('Error al generar el PDF');
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'detalle_venta.pdf'; // Nombre del archivo
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(error => {
        console.error(error);
    });
}

document.querySelector('#btnGenerarReporte').addEventListener('click', generarReporte);
