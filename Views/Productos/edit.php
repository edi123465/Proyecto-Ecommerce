<?php
// Iniciar sesión para manejar mensajes
session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../../Controllers/ProductoController.php';
require_once __DIR__ . '/../../Config/db.php';

// Obtener la conexión a la base de datos
$db = new Database1();
$connection = $db->getConnection();

// Crear una instancia del controlador de productos
$controller = new ProductoController($connection);

// Obtener todas las categorías y subcategorías
$categoriaModel = $controller->obtenerCategorias();
$subcategoriaModel = $controller->obtenerSubcategorias();

// Verificar si se ha pasado un ID de producto
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtén el ID del producto desde la URL
    // Cargar los datos del producto
    $producto = $controller->getProductoById($id); // Asegúrate de tener un método para obtener el producto por ID
    // Verificar si se está enviando el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recolectar datos del formulario
        $data = [
            'nombreProducto' => $_POST['nombreProducto'],
            'descripcionProducto' => $_POST['descripcionProducto'],
            'precio' => $_POST['precio'],
            'precio1' => $_POST['precio1'],
            'precio2' => $_POST['precio2'],
            'precio3' => $_POST['precio3'],
            'precio4' => $_POST['precio4'],
            'existencias' => $_POST['existencias'],
            'subcategoria' => $_POST['subcategoria'], // Asegúrate de que este nombre coincide con el del select
            'codBarras' => $_POST['codBarras'], // Asegúrate de capturar el código de barras
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        // Llamar a updateProducto con el ID del producto y el array de datos
        $resultado = $controller->updateProducto($id, $data);

        if ($resultado) {
            $_SESSION['message'] = "Producto actualizado correctamente.";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Error al actualizar el producto.";
        }
    }
} else {
    echo "No se ha proporcionado un ID de producto.";
}
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Producto</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 20px;
            }

            h1 {
                text-align: center;
                color: #333;
            }

            .alert {
                color: red;
                margin: 10px 0;
                padding: 10px;
                border: 1px solid red;
                border-radius: 5px;
                background-color: #ffe6e6;
            }

            form {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="text"],
            input[type="number"],
            textarea,
            select {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            button {
                width: 100%;
                padding: 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
            }

            button:hover {
                background-color: #0056b3;
            }

            a {
                display: inline-block;
                margin-top: 15px;
                text-align: center;
                color: #007bff;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <h1>Editar Producto</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $producto['id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="nombreProducto">Nombre del Producto:</label>
            <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $producto['nombreProducto']; ?>" required>

            <label for="descripcionProducto">Descripción:</label>
            <textarea id="descripcionProducto" name="descripcionProducto" required><?php echo $producto['descripcionProducto']; ?></textarea>

            <label for="precio1">Costo de compra:</label>
            <input type="number" step="0.01" id="precio1" name="precio" value="<?php echo $producto['precio']; ?>" required>


            <label for="precio1">Precio 1:</label>
            <input type="number" step="0.01" id="precio1" name="precio1" value="<?php echo $producto['precio_1']; ?>" required>

            <label for="precio2">Precio 2:</label>
            <input type="number" step="0.01" id="precio2" name="precio2" value="<?php echo $producto['precio_2']; ?>">

            <label for="precio3">Precio 3:</label>
            <input type="number" step="0.01" id="precio3" name="precio3" value="<?php echo $producto['precio_3']; ?>">

            <label for="precio4">Precio 4:</label>
            <input type="number" step="0.01" id="precio4" name="precio4" value="<?php echo $producto['precio_4']; ?>">

            <label for="precio4">Existencias</label>
            <input type="number" step="0.01" id="precio4" name="existencias" value="<?php echo $producto['stock']; ?>">

            <label for="subcategoria">Subcategoría:</label>
            <select name="subcategoria" id="subcategoria" required>
                <?php foreach ($subcategoriaModel as $subcategoria): ?>
                    <option value="<?php echo $subcategoria['id']; ?>" <?php echo isset($producto['subcategoria_id']) && $producto['subcategoria_id'] == $subcategoria['id'] ? 'selected' : ''; ?>>
                        <?php echo $subcategoria['nombrSubcategoria']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="precio4">Codigo de barras</label>
            <input type="number" step="0.01" id="precio4" name="codBarras" value="<?php echo $producto['codigo_barras']; ?>">

            <label for="imagen">Imagen actual:</label>
            <?php if (!empty($producto['imagen'])): ?>
                <?php var_dump($producto['imagen']); ?>    
                <img src="<?php echo "/assets/imagenesMilogar/productos/" . $producto['imagen']; ?>" alt="Imagen del producto" name="imagen" style="width: 100px; height: auto;">
            <?php else: ?>
                <p>No hay imagen disponible.</p>
            <?php endif; ?>

            <label for="nueva_imagen">Selecciona una nueva imagen:</label>
            <input type="file" id="nueva_imagen" name="nueva_imagen">

            <!-- Si deseas conservar el nombre de la imagen actual -->
            <input type="hidden" name="imagen_actual" value="<?php echo $producto['imagen']; ?>">

            <label for="isActive">¿Activo?</label>
            <input type="checkbox" id="isActive" name="isActive" value="1" <?php echo $producto['isActive'] ? 'checked' : ''; ?>>

            <button type="submit">Actualizar Producto</button>
        </form>

        <a href="index.php">Regresar al listado de productos</a>
    </body>
</html>
