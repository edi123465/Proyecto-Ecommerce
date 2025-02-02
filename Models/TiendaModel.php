<?php

class TiendaModel{
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategorias() {
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
}
