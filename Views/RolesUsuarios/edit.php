<?php
session_start(); // Iniciar sesión para acceder al mensaje
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php'; // Asegúrate de que la ruta sea correcta
require_once __DIR__ . '/../../Controllers/RolController.php'; // Cargar el controlador de roles
// Crear una instancia de la conexión
$db = new Database1();
$connection = $db->getConnection();

// Instanciar el controlador
$controller = new RolController($connection);

// Verificar si se proporciona un ID
if (isset($_GET['id'])) {
    // Llamar al método edit para obtener los datos del rol por su ID
    $rol = $controller->edit((int) $_GET['id']);

    // Verificar si el rol fue encontrado
    if (!$rol) {
        echo "Rol no encontrado.";
        exit;
    }
} else {
    echo "ID no válido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MILOGAR - Editar Rol</title>
        <link rel="icon" href="../../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">

        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            h1 {
                color: #333;
                text-align: center;
            }

            form {
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="text"],
            textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input[type="checkbox"] {
                margin-bottom: 15px;
            }

            button {
                width: 100%;
                padding: 10px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }

            button:hover {
                background-color: #218838;
            }

            a {
                display: inline-block;
                margin-top: 10px;
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

        <form action="index.php?action=update" method="POST">
            <h1>Editar Rol </h1>
            <!-- ID oculto -->
            <input type="hidden" name="id" value="<?php echo $rol['ID']; ?>">

            <label for="RolName">Nombre del Rol:</label>
            <input type="text" id="RolName" name="RolName" value="<?php echo $rol['RolName']; ?>" required>

            <label for="RolDescription">Descripción del Rol:</label>
            <textarea id="RolDescription" name="RolDescription" rows="4" required><?php echo $rol['RolDescription']; ?></textarea>

            <input type="hidden" name="isActive" value="0"> <!-- Este input oculto garantiza que se envíe 0 si el checkbox no está marcado -->
            <label for="isActive">¿Rol Activo?</label>
            <input type="checkbox" id="isActive" name="isActive" value="1" <?php echo $rol['IsActive'] ? 'checked' : ''; ?>>

            <button type="submit">Actualizar Rol</button>
            <a href="index.php">Regresar a la consulta</a>

        </form>

    </body>
</html>
