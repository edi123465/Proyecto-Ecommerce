<?php
require_once "../../Controllers/RolController.php";
require_once "../../Models/RolModel.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MILOGAR - CREATE</title>
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
            textarea,
            input[type="date"] {
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

        <!-- <form action="index.php?action=store" method="POST">
            <h1>Crear nuevo Rol</h1>
            <label for="RolName">Nombre del Rol:</label>
            <input type="text" name="RolName" required>

            <label for="RolDescription">Descripción:</label>
            <textarea name="RolDescription" required></textarea>

            <label for="IsActive">Activo:</label>
            <input type="checkbox" name="IsActive" value="1">

            <button type="submit">Guardar</button>
            <a href="index.php">Regresar a la consulta</a>

        </form> -->
        <script src="../../assets/js/validaciones.js"></script>
    </body>
</html>

