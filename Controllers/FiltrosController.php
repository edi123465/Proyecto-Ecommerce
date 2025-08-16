<?php

// Conexión a la base de datos
require_once "../Config/db.php"; // Incluye tu archivo de conexión
// Crear una instancia de la clase Database1
$db = new Database1();
$conn = $db->getConnection(); // Obtiene la conexión
// Comprobar si la conexión se realizó correctamente
if (!$conn) {
    die("No se pudo conectar a la base de datos.");
}

if (isset($_GET['q'])) {
    $busqueda = $_REQUEST['q'] . '%'; // Obtener el valor ingresado por el usuario y usar '%' para LIKE
    // Consulta ajustada para incluir el valor de búsqueda
    $sql = "SELECT p.*, s.nombrSubcategoria, c.nombreCategoria 
            FROM Productos p
            JOIN Subcategorias s ON p.subcategoria_id = s.id 
            JOIN Categorias c ON s.categoria_id = c.id
            WHERE p.nombreProducto LIKE :busqueda 
            OR s.nombrSubcategoria LIKE :busqueda 
            OR c.nombreCategoria LIKE :busqueda;";

    $stmt = $conn->prepare($sql);
    echo "Valor de búsqueda antes de vincular: " . htmlspecialchars($busqueda) . "<br>";

    $stmt->bindParam(':busqueda', $busqueda, PDO::PARAM_STR); // Vincular el parámetro
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Traer todos los resultados

    if ($result) {
        foreach ($result as $row) {
            echo '<div>';
            echo '<h2>' . htmlspecialchars($row['nombreProducto']) . '</h2>';
            echo '<p>' . htmlspecialchars($row['nombreSubcategoria']) . '</p>';
            echo '<p>' . htmlspecialchars($row['nombreCategoria']) . '</p>'; // Mostrar también la categoría
            echo '</div>';
        }
    } else {
        echo 'No se encontraron productos.';
    }
}
?>
