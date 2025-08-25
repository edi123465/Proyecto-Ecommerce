<?php

require_once __DIR__ . "/../Config/db.php";

class TallasModel
{

    private $conn;
    private $table = 'tallas';
    public $RolID;
    public $RolName;
    public $RolDescription;
    public $IsActive;
    public $CreatedAt;

    public function __construct($db)
    {

        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT id, talla FROM " . $this->table;
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute();  // Ejecutamos la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtenemos los resultados

            return $result;  // Devuelve el resultado
        } catch (Exception $e) {
            // En caso de error, loguear el error y devolver null
            error_log($e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        // Prepara la consulta SQL para insertar en la tabla tallas
        $query = "INSERT INTO tallas (talla) VALUES (:talla)";

        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula el parámetro
        $stmt->bindParam(':talla', $data['talla']);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Inserción exitosa
        }

        return false; // Inserción fallida
    }



    public function getById($id)
    {
        $query = "SELECT * FROM tallas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $talla = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log para verificar qué datos se están trayendo
        error_log("Consulta de talla: " . print_r($talla, true));

        return $talla; // Retorna los datos de la talla
    }



    public function update($id, $data)
    {
        // Consulta SQL para actualizar la talla
        $query = "UPDATE tallas 
              SET talla = :talla 
              WHERE id = :id";

        // Prepara la consulta
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':talla', $data['talla']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            // Si hay un error, obtener información sobre el mismo
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false;
        }
    }



    // Eliminar una talla
    public function delete($id)
    {
        $query = "DELETE FROM tallas WHERE id = :ID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ID', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
