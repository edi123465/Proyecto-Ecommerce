<?php

class TiendaModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

public function getCategorias()
{
    $query = "SELECT id, nombreCategoria, imagen, descripcionCategoria 
              FROM Categorias 
              WHERE isActive = 1"; // Solo categorías activas
    $stmt = $this->conn->prepare($query);

    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Logueamos los resultados obtenidos
        error_log("✅ Categorías obtenidas:");
        error_log(print_r($result, true)); // Esto imprimirá los resultados en el log

        return $result;  // Devuelve los resultados, ahora con descripcionCategoria también
    } else {
        error_log("❌ Error en la consulta: " . print_r($stmt->errorInfo(), true)); // Log de errores
        return null;  // Si falla, devuelve null
    }
}



    public function getProductosPorCategoriaOSubcategoria($categoriaId = null, $subcategoriaId = null)
{
    $query = "
    SELECT 
        p.id, 
        p.nombreProducto, 
        p.descripcionProducto, 
        p.precio, 
        p.precio_1, 
        p.precio_2, 
        p.precio_3, 
        p.precio_4, 
        p.stock, 
        p.imagen, 
        p.codigo_barras, 
        p.descuento,
        p.cantidad_minima_para_puntos,     
        p.puntos_otorgados,   
        p.is_talla,
        s.id AS subcategoria_id, 
        s.nombrSubcategoria AS subcategoria_nombre, 
        c.id AS categoria_id, 
        c.nombreCategoria AS categoria_nombre
    FROM productos p
    JOIN subcategorias s ON p.subcategoria_id = s.id
    JOIN categorias c ON s.categoria_id = c.id
    ";

    $conditions = ["p.isActive = 1"]; // Mostrar solo productos activos
    $params = [];

    if ($categoriaId !== null) {
        $conditions[] = "c.id = :categoriaId";
        $params[':categoriaId'] = $categoriaId;
    }
    if ($subcategoriaId !== null) {
        $conditions[] = "s.id = :subcategoriaId";
        $params[':subcategoriaId'] = $subcategoriaId;
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerComentariosActivosPorProducto($producto_id)
    {
        $sql = "SELECT c.producto_id, u.nombreUsuario, c.comentario, c.calificacion, c.fecha 
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.estado = 1 AND c.producto_id = :producto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
