<?php

include 'database.php'; // Conexión a la base de datos

$categoriaId = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : 0;

// Crear la conexión
$conn = new PDO($dsn, $username, $password);

// Consulta para obtener las subcategorías según la categoría seleccionada
$sql = "SELECT id, nombrSubcategoria FROM subcategorias WHERE categoria_id = :categoria_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT);
$stmt->execute();

$subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos en formato JSON
header('Content-Type: application/json'); // Establecer el tipo de contenido
echo json_encode($subcategorias);
