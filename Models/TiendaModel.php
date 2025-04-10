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
        $query = "SELECT id, nombreCategoria, imagen FROM Categorias"; // Seleccionamos solo el ID y el nombre
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Logueamos los resultados obtenidos
            error_log(print_r($result, true)); // Esto imprimirá los resultados obtenidos de la base de datos en el log

            return $result;  // Devuelve los resultados, solo id y nombre
        } else {
            error_log("Error en la consulta: " . print_r($stmt->errorInfo(), true)); // Log de errores de la consulta
            return null;  // Si falla, devuelve null
        }
    }

    public function getProductosPorCategoriaOSubcategoria($categoriaId = null, $subcategoriaId = null)
    {
        $query = "
        SELECT p.id, p.nombreProducto, p.descripcionProducto, p.precio, p.precio_1, 
               p.precio_2, p.precio_3, p.precio_4, p.stock, p.imagen, 
               s.id AS subcategoria_id, s.nombrSubcategoria AS subcategoria_nombre, 
               c.id AS categoria_id, c.nombreCategoria AS categoria_nombre, 
               p.codigo_barras, p.descuento  -- Agregar descuento aquí
        FROM Productos p
        JOIN Subcategorias s ON p.subcategoria_id = s.id
        JOIN Categorias c ON s.categoria_id = c.id
    ";

        $conditions = [];
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
}
