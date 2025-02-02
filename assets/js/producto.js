document.addEventListener('click', function (event) {
    // Verificar si el clic es en el botón de vista rápida
    if (event.target.closest('.btn-action[data-bs-toggle="modal"]')) {
        const productoId = event.target.closest('.btn-action').getAttribute('data-id');

        if (productoId) {
            // Llamada a la API para obtener el producto seleccionado
            fetch(`http://localhost:8088/Milogar/controllers/ProductoController.php?action=verDetalle&id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Verifica la estructura de los datos
                
                    if (data.status === 'success' && data.data) {
                        const producto = data.data;
                
                        // Llenar el modal con los datos del producto
                        document.getElementById('product-name').textContent = producto.nombreProducto;
                        
                        // Concatenar la ruta base con el nombre de la imagen
                        const imagePath = `../assets/imagenesMilogar/productos/${producto.imagen}`;
                        document.querySelector('.product-image').src = imagePath; // Usar la propiedad 'imagen' concatenada con la ruta base
                
                        // Como no hay miniaturas (solo hay una imagen), eliminamos la lógica de las miniaturas
                        const thumbnailsContainer = document.getElementById('productModalThumbnails');
                        thumbnailsContainer.innerHTML = '<p>No hay imágenes adicionales disponibles.</p>';
                
                        // Actualizar precio
                        document.querySelector('.product-price .text-dark').textContent = `$${producto.precio_1 - (producto.precio_1 * producto.descuento / 100)}`;
                        document.querySelector('.product-price .text-muted').textContent = `$${producto.precio_1}`;
                        document.querySelector('.product-price .text-danger').textContent = `${producto.descuento}% Off`;
                
                        // Descripción
                        document.querySelector('.product-description').textContent = producto.descripcionProducto;
                
                        // Mostrar categoría y subcategoría
                        document.getElementById('Category').textContent = producto.categoria_nombre;
                        document.getElementById('Subcategory').textContent = producto.subcategoria_nombre;
                
                        // Botón de añadir al carrito
                        const addToCartButton = document.querySelector('.btn.btn-primary');
                        addToCartButton.setAttribute('data-id', producto.id);
                        addToCartButton.setAttribute('data-nombre', producto.nombreProducto);
                        addToCartButton.setAttribute('data-precio', producto.precio);
                        addToCartButton.setAttribute('data-imagen', imagePath); // Pasar la ruta completa de la imagen al botón
                
                        // Mostrar el modal
                        const myModal = new bootstrap.Modal(document.getElementById('quickViewModal'));
                        myModal.show();
                    } else {
                        console.error('Error al cargar los detalles del producto');
                    }
                })
                
                .catch(error => {
                    console.error('Error al obtener los detalles del producto:', error);
                });
        } else {
            console.error('No se encontró el ID del producto');
        }
    }
});

