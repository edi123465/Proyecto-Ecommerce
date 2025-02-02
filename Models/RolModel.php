<?php

require_once __DIR__ . "/../Config/db.php";

class RolModel
{

    private $conn;
    private $table = 'Roles';
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
        $query = "SELECT * FROM Roles";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;  // Devuelve el resultado
        } else {
            return null;  // Si falla, devuelve null
        }
    }

    public function crearRol($data)
    {
        // Consulta SQL
        $query = "INSERT INTO " . $this->table . " (RolName, RolDescription, IsActive, CreatedAt) 
                  VALUES (:rolName, :rolDescription, :isActive, GETDATE())"; // Utiliza GETDATE() para la fecha actual

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            echo "Error al preparar la consulta.";
            return false;
        }

        // Vincular los parámetros
        $stmt->bindParam(':rolName', $data['RolName']);
        $stmt->bindParam(':rolDescription', $data['RolDescription']);
        $stmt->bindParam(':isActive', $data['IsActive']);

        // Intentar ejecutar la consulta
        try {
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error al ejecutar la consulta.";
                return false;
            }
        } catch (Exception $e) {
            // Capturar cualquier excepción y mostrar el error
            echo "Error en la ejecución: " . $e->getMessage();
            return false;
        }
    }

    // Método para obtener los roles desde la base de datos
    public function obtenerRoles()
    {
        // Consulta SQL para obtener los roles
        $query = "SELECT ID, RolName, RolDescription, IsActive, CreatedAt FROM " . $this->table;

        try {
            // Preparar la consulta
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Retornar los resultados como un arreglo asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Si ocurre un error, capturamos la excepción
            error_log("Error en la consulta para obtener roles: " . $e->getMessage());
            return null; // Retornamos null si hay un error
        }
    }
    //obtener el id y nombre del Rol para actualizar 
    public function obtenerRol(){
        try {
            $query = "SELECT ID, RolName FROM Roles";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los roles: " . $e->getMessage());
        }
    }
    // Función para obtener los datos de un rol por su ID
    public function obtenerRolesPorId($id) {
        // Consultar el rol por ID
        $query = "SELECT * FROM Roles WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Retornar los datos del rol
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function actualizarRol($id, $roleName, $roleDescription, $isActive)
    {
        $query = "UPDATE roles SET RolName = :roleName, RolDescription = :roleDescription, IsActive = :isActive WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
    
        // Vincular los parámetros
        $stmt->bindParam(':roleName', $roleName);
        $stmt->bindParam(':roleDescription', $roleDescription);
        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    // Función para eliminar un rol
    public function eliminarRol($id) {
        $query = "DELETE FROM Roles WHERE id = :id"; // Asumiendo que el nombre de la tabla es 'roles'
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Método del modelo para realizar la paginación
    public function getRoles($pageNumber = 1, $pageSize = 5)
    {
        $offset = ($pageNumber - 1) * $pageSize;
        $sql = "SELECT * FROM Roles ORDER BY ID OFFSET $offset ROWS FETCH NEXT $pageSize ROWS ONLY";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener el total de roles
    public function getTotalRoles()
    {
        $sql = "SELECT COUNT(*) AS TotalCount FROM Roles";
        $stmt = $this->conn->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['TotalCount'];
    }
}
