<!-- search_results.php -->
<h2>Resultados de búsqueda para: <?php echo htmlspecialchars($_GET['search']); ?></h2>

<?php if (count($products) > 0): ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <h3><?php echo htmlspecialchars($product['nombreProducto']); ?></h3>
                <p><?php echo htmlspecialchars($product['descripcionProducto']); ?></p>
                <p>Precio: $<?php echo htmlspecialchars($product['precio']); ?></p>
                <p>Categoría: <?php echo htmlspecialchars($product['nombreCategoria']); ?></p>
                <!-- Otros detalles del producto -->
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No se encontraron productos.</p>
<?php endif; ?>

