<?php
require_once "../../Controllers/CategoriaController.php";
require_once "../../Models/CategoriaModel.php";
require_once "../../Config/db.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Producto</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
            }
            h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            .form-container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: auto;
            }
            .btn-custom {
                margin: 10px 0;
            }
        </style>
    </head>
    <body>
        <h2>Agregar Nuevo Producto</h2>
        <div class="form-container">
            <form action="index.php?action=insertarProducto" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="form-group">
                    <label for="precio1">Precio de compra:</label>
                    <input type="number" class="form-control" id="precio1" name="precioC" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="precio1">Precio 1:</label>
                    <input type="number" class="form-control" id="precio1" name="precio1" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="precio2">Precio 2:</label>
                    <input type="number" class="form-control" id="precio2" name="precio2" step="0.01">
                </div>

                <div class="form-group">
                    <label for="precio3">Precio 3:</label>
                    <input type="number" class="form-control" id="precio3" name="precio3" step="0.01">
                </div>

                <div class="form-group">
                    <label for="precio4">Precio 4:</label>
                    <input type="number" class="form-control" id="precio4" name="precio4" step="0.01">
                </div>
                <div class="form-group">
                    <label for="precio4">Stock:</label>
                    <input type="number" class="form-control" id="precio4" name="stock" step="0.01">
                </div>
                <div class="form-group">
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" name="categoria_id" class="form-control" onchange="cargarSubcategorias(this.value);" required>
                        <option value="">-- Selecciona una categoría --</option>
                        <?php
                        // Conexión a la base de datos
                        require_once "../../Config/db.php";
                        $db = new Database1();
                        $conn = $db->getConnection();

                        // Consulta para obtener las categorías activas
                        $query = "SELECT id, nombreCategoria FROM Categorias WHERE isActive = 1";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Mostrar las opciones en el <select>
                        foreach ($categorias as $categoria) {
                            echo "<option value='" . htmlspecialchars($categoria['id']) . "'>" . htmlspecialchars($categoria['nombreCategoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="subcategoria_id">SubCategoría:</label>
                    <select id="categoria_id" name="subcategoria_id" class="form-control" onchange="cargarSubcategorias(this.value);" required>
                        <option value="">-- Selecciona una Subcategoría --</option>
                        <?php
                        // Conexión a la base de datos
                        require_once "../../Config/db.php";
                        $db = new Database1();
                        $conn = $db->getConnection();

                        // Consulta para obtener las categorías activas
                        $query = "SELECT id, nombrSubcategoria FROM Subcategorias";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $Subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Mostrar las opciones en el <select>
                        foreach ($Subcategorias as $Subcategoria) {
                            echo "<option value='" . htmlspecialchars($Subcategoria['id']) . "'>" . htmlspecialchars($Subcategoria['nombrSubcategoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen del Producto:</label>
                    <input type="file" class="form-control-file" id="imagen" name="Imagen" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="precio4">Codigo de barras:</label>
                    <input type="text" class="form-control" id="precio4" name="codbarras" step="0.01">
                </div>

                <div class="form-group">
                    <label for="isActive">¿Activo?:</label>
                    <input type="checkbox" id="isActive" name="isActive" value="1" checked>
                    <span>Marcar para activar</span>
                </div>

                <button type="submit" class="btn btn-primary">Agregar Producto</button>
                <a href="index.php" class="btn btn-secondary btn-custom">Regresar a la Consulta</a>
            </form>
        </div>
    </body>
</html>
