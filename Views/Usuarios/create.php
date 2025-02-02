<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MILOGAR - Crear Usuario</title>
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
            button {
                background-color: #5cb85c;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 100%; /* Botón ocupa el ancho completo */
            }
            button:hover {
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
            .container {
                max-width: 600px; /* Máximo ancho del formulario */
                margin: 0 auto; /* Centrar el formulario */
                background: white; /* Fondo blanco */
                padding: 20px; /* Espaciado interno */
                border-radius: 8px; /* Bordes redondeados */
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra del formulario */
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Crear Usuario</h1>
            <form action="index.php?action=store" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="NombreUsuario">Nombre de Usuario:</label>
                    <input type="text" id="NombreUsuario" name="NombreUsuario">
                </div>
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" id="Email" name="Email">
                </div>
                <div class="form-group">
                    <label for="Contrasenia">Contraseña:</label>
                    <input type="password" id="Contrasenia" name="Contrasenia">
                </div>
                <div class="form-group">
                    <label for="ConfirmarContrasenia">Confirmar contraseña:</label>
                    <input type="password" id="ConfirmarContrasenia" name="ConfirmarContrasenia">
                </div>
                <div class="form-group">
                    <label for="RolID">Rol:</label>
                    <select id="RolID" name="RolID">
                        <!-- Aquí se llenarán los roles desde la base de datos -->
                        <?php
                        // Conectar a la base de datos y obtener roles
                        require_once __DIR__ . '/../../Config/db.php';
                        $db = new Database1();
                        $connection = $db->getConnection();

                        $query = "SELECT ID, RolName FROM Roles"; // Ajusta la consulta según tu esquema
                        $stmt = $connection->prepare($query);
                        $stmt->execute();
                        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($roles as $rol) {
                            echo '<option value="' . htmlspecialchars($rol['ID']) . '">' . htmlspecialchars($rol['RolName']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="IsActive">Activo:</label>
                    <input type="checkbox" id="IsActive" name="IsActive" value="1">
                </div>
                <div class="form-group">
                    <label for="FechaCreacion">Fecha de Creación:</label>
                    <input type="date" id="FechaCreacion" name="FechaCreacion" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="Imagen">Imagen:</label>
                    <input type="file" id="Imagen" name="Imagen" accept="image/*">
                </div><br>
                <button type="submit">Crear Usuario</button>
            </form>
            <a href="index.php">Regresar al menú</a>
        </div>
    </body>
</html>
