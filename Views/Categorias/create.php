<?php
require_once "../../Controllers/CategoriaController.php";
require_once "../../Models/CategoriaModel.php";
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MILOGAR - Crear Categoría</title>
        <link rel="icon" href="../../assets/imagenesMilogar/logomilo.jpg" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }

            h1 {
                color: #333;
                text-align: center; /* Centra el título */
            }

            .form-container {
                max-width: 500px;
                margin: auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
                font-size: 14px;
            }

            textarea {
                resize: vertical; /* Permite cambiar el tamaño verticalmente */
            }

            input[type="checkbox"] {
                margin-bottom: 15px; /* Espacio debajo del checkbox */
            }

            .button-container {
                display: flex; /* Usar flexbox para alinear los botones */
                justify-content: space-between; /* Espacio entre los botones */
                margin-top: 20px; /* Espacio arriba de los botones */
            }

            button {
                padding: 10px 15px;
                background-color: #28a745; /* Color verde para el botón */
                border: none;
                border-radius: 5px;
                color: white;
                font-size: 14px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #218838; /* Color más oscuro en hover */
            }

            .btn-secondary {
                background-color: #007bff; /* Color azul para el botón secundario */
            }

            .btn-secondary:hover {
                background-color: #0056b3; /* Color más oscuro en hover */
            }

            .link {
                text-decoration: none;
                color: white; /* Color del texto */
            }
        </style>
    </head>
    <body>

        <div class="form-container">
            <h1>Crear Categoría</h1>
            <form action="index.php?action=store" method="POST">
                <label for="categoriaName">Nombre:</label>
                <input type="text" name="categoriaName" required>

                <label for="categoriaDescription">Descripción:</label>
                <textarea name="categoriaDescription" required></textarea>

                <label for="IsActive">Activo:</label>
                <input type="checkbox" name="IsActive" value="1"> 

                <div class="button-container">
                    <button type="submit">Guardar</button>
                    <button class="btn-secondary">
                        <a href="index.php" class="link">Regresar a la consulta</a>
                    </button>
                </div>
            </form>
        </div>

        <script src="../../assets/js/validaciones.js"></script>
    </body>
</html>
