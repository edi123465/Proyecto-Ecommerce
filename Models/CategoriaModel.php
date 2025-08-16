<?php

require_once __DIR__ . "/../Config/db.php";

class CategoriaModel
{

    private $conn;
    private $table = 'Categorias';
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
        $query = "SELECT id, nombreCategoria, descripcionCategoria, isActive, fechaCreacion, imagen FROM " . $this->table;
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
        // Prepara la consulta SQL para la tabla Categorías
        $query = "INSERT INTO Categorias (nombreCategoria, descripcionCategoria, isActive, fechaCreacion, imagen) 
                  VALUES (:nombre, :descripcion, :isActive, now(), :imagen)";  // Se agrega el campo 'imagen'

        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':isActive', $data['isActive']);
        $stmt->bindParam(':imagen', $data['imagen']); // Aquí vinculamos la imagen


        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Inserción exitosa
        }

        return false; // Inserción fallida
    }


    public function getById($id)
    {
        $query = "SELECT * FROM categorias WHERE id = :id"; // Ajusta según el nombre de tu tabla
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Agregar error_log para ver qué datos se están trayendo
        error_log("Consulta de categoría: " . print_r($categoria, true));
    
        return $categoria; // Retorna los datos de la categoría
    }
    
    
    // Actualizar una categoria con un arreglo de datos
public function update($id, $data)
{
    // Consulta SQL para actualizar la categoría
    $query = "UPDATE " . $this->table . " 
              SET nombreCategoria = :nombreCategoria, descripcionCategoria = :descripcionCategoria,
                  isActive = :isActive, imagen = :imagen 
              WHERE id = :id";

    // Prepara la consulta
    $stmt = $this->conn->prepare($query);

    // Enlace de parámetros
    $stmt->bindParam(':nombreCategoria', $data['nombre']);  // Cambiado de ':nombre' a ':nombreCategoria'
    $stmt->bindParam(':descripcionCategoria', $data['descripcion']);  // Cambiado de ':descripcion' a ':descripcionCategoria'
    $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_INT);  // Correcto
    $stmt->bindParam(':imagen', $data['imagen']);  // Correcto
    $stmt->bindParam(':id', $id);  // Correcto

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



    // Eliminar unA CATEGORIA
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :ID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ID', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    //metodo para obtener los nombres de las categorias, esto es para traer los noombres
    // y aplicarlos a la lista de la 
    public function getCategoryByName()
    {
        $query = "SELECT id, nombreCategoria FROM Categorias";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;  // Devuelve los nombres de las categorías
        } else {
            return null;  // Si falla, devuelve null
        }
    }
}
