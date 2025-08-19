// Escuchar el evento de envío del formulario
document.getElementById('createCategoryForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el comportamiento por defecto del formulario (recargar la página)

    // Crear un objeto FormData para manejar los datos del formulario, incluyendo los archivos
    const formData = new FormData(this); // 'this' hace referencia al formulario

       // Mostrar los datos recogidos en la consola para depuración
       console.log('Datos del formulario:');
       formData.forEach((value, key) => {
           if (value instanceof File) {
               // Si el valor es un archivo, muestra información del archivo
               console.log(`${key}: ${value.name} (${value.size} bytes, ${value.type})`);
           } else {
               // Si el valor no es un archivo, simplemente muestra el valor
               console.log(`${key}: ${value}`);
           }
       });

    // Enviar la solicitud Fetch
    fetch('http://localhost:8080/Milogar/controllers/CategoriaController.php?action=createCategory', {
        method: 'POST',
        body: formData // Pasar los datos del formulario, incluyendo la imagen
    })
    .then(response => response.json())  // Convertir la respuesta del servidor a formato JSON
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito si la categoría se creó correctamente
            alert(data.message);
            window.location.reload(); // Recargar la página para mostrar la nueva categoría
        } else {
            // Mostrar mensaje de error si hubo algún problema
            alert(data.message);
        }
    })
    .catch(error => {
        // Capturar errores si la solicitud falla
        console.error('Error:', error);
        alert('Hubo un problema con la solicitud');
    });
});
