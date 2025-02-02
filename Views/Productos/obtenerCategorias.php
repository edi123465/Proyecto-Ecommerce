<?php

include 'database.php'; // Conexión a la base de datos
// Crear la conexión
$conn = new PDO($dsn, $username, $password);

// Consulta para obtener las categorías
$sql = "SELECT id, nombreCategoria FROM categorias";
$stmt = $conn->prepare($sql);
$stmt->execute();

$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos en formato JSON
header('Content-Type: application/json'); // Establecer el tipo de contenido
echo json_encode($categorias);
