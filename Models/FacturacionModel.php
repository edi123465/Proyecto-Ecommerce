<?php

require_once __DIR__ . "/../Config/db.php";

class FacturacionModel
{
    private $conn;
    private $table = 'productos';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Buscar producto por código de barras exacto
    public function getProductoByCodigoBarras($codigo)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE codigo_barras = :codigo AND isActive = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Un solo producto
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    // Buscar productos por caracteres (relacionados)
    public function buscarProductos($termino)
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE (nombreProducto LIKE :termino OR descripcionProducto LIKE :termino)
                    AND isActive = 1";
            $stmt = $this->conn->prepare($sql);
            $like = "%" . $termino . "%";
            $stmt->bindParam(":termino", $like, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Varios productos
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
