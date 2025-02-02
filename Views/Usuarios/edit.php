<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/UsuarioController.php";  // Cargar el controlador
require_once "../../Models/UsuarioModel.php";  // Cargar el modelo
// Crear una instancia de la conexión
$db = new Database1();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new UsuarioController($connection);

// Verificar si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Llamar al método getById para obtener los datos del usuario
    $usuario = $controller->getById($id); // Asegúrate de que el método getById devuelva el usuario

    if ($usuario) {
        // Mostrar el formulario con los datos recuperados
        ?>
        <!DOCTYPE html>
        <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Editar Usuario</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 20px;
                    }
                    h1 {
                        text-align: center;
                        color: #333;
                    }
                    .container {
                        max-width: 600px; /* Máximo ancho del formulario */
                        margin: 0 auto; /* Centrar el formulario */
                        background: white; /* Fondo blanco */
                        padding: 20px; /* Espaciado interno */
                        border-radius: 8px; /* Bordes redondeados */
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra del formulario */
                    }
                    .form-group {
                        margin-bottom: 15px;
                    }
                    label {
                        display: block;
                        margin-bottom: 5px;
                        font-weight: bold;
                    }
                    input[type="text"],
                    input[type="email"],
                    input[type="password"],
                    input[type="date"],
                    select {
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
                    }
                    input[type="checkbox"] {
                        margin-top: 10px;
                    }
                    input[type="submit"] {
                        background-color: #5cb85c;
                        color: white;
                        padding: 10px 15px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        width: 100%; /* Botón ocupa el ancho completo */
                    }
                    input[type="submit"]:hover {
                        background-color: #4cae4c; /* Color del botón al pasar el mouse */
                    }
                    a {
                        display: block;
                        margin-top: 20px;
                        text-align: center;
                        color: #337ab7;
                        text-decoration: none; /* Sin subrayado */
                    }
                    a:hover {
                        text-decoration: underline; /* Subrayar al pasar el mouse */
                    }
                    img {
                        margin-top: 10px; /* Espaciado superior para la imagen */
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Editar Usuario</h1>
                    <form action="update.php?id=<?= htmlspecialchars($usuario['ID']) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="ID" value="<?= htmlspecialchars($usuario['ID']) ?>">

                        <div class="form-group">
                            <label for="NombreUsuario">Nombre de Usuario:</label>
                            <input type="text" name="NombreUsuario" value="<?= htmlspecialchars($usuario['NombreUsuario']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="Email">Email:</label>
                            <input type="email" name="Email" value="<?= htmlspecialchars($usuario['Email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="Contrasenia">Contraseña:</label>
                            <input type="password" name="Contrasenia"><br>
                            <small>Deja vacío si no deseas cambiar la contraseña.</small>
                        </div>

                        <div class="form-group">
                            <label for="ConfirmarContrasenia">Confirmar contraseña:</label>
                            <input type="password" name="ConfirmarContrasenia">
                        </div>

                        <div class="form-group">
                            <label for="RolID">Rol:</label>
                            <select name="RolID" required>
                                <?php
                                // Obtener roles desde la base de datos
                                $query = "SELECT ID, RolName FROM Roles";
                                $stmt = $connection->prepare($query);
                                $stmt->execute();
                                $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($roles as $rol) {
                                    echo '<option value="' . htmlspecialchars($rol['ID']) . '"' . ($rol['ID'] == $usuario['RolID'] ? ' selected' : '') . '>' . htmlspecialchars($rol['RolName']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="IsActive">Activo:</label>
                            <input type="checkbox" name="IsActive" <?= $usuario['IsActive'] ? 'checked' : '' ?>>
                        </div>

                        <div class="form-group">
                            <label for="FechaCreacion">Fecha de Creación:</label>
                            <input type="date" name="FechaCreacion" value="<?= htmlspecialchars($usuario['FechaCreacion']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="Imagen">Imagen:</label>
                            <input type="file" name="Imagen" accept="image/*"><br>
                            <img src="<?= htmlspecialchars('imagenes milogar/Usuarios/' . $usuario['Imagen']) ?>" alt="Imagen de Usuario" width="50">
                        </div>

                        <input type="submit" value="Actualizar" onclick="return confirm('¿Estás seguro de que deseas actualizar este usuario?');">
                    </form>
                    <a href="index.php"><input type="button" value="Regresar a la consulta"></a>
                </div>
            </body>
        </html>
        <?php
    } else {
        echo "Usuario no encontrado."; // Mensaje si el usuario no existe
    }
} else {
    echo "ID no proporcionado."; // Mensaje si no hay ID
}
?>

