 <?php

require_once __DIR__ . "/../Config/db.php";

class SubcategoriaModel {

    private $conn;
    private $table = 'Subcategorias';
    public $id;
    public $nombre;
    public $descripcion;
    public $categoria_id;
    public $isActive;
    public $fechaCreacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT 
            s.id,
            s.nombrSubcategoria,
            s.descripcionSubcategoria,
            s.categoria_id,
            c.nombreCategoria,  -- Traemos el nombre de la categoría
            s.isActive,
            s.fechaCreacion
        FROM 
            Subcategorias s
        JOIN 
            Categorias c ON s.categoria_id = c.id";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;  // Devuelve los resultados
        } else {
            error_log("Error en la consulta: " . print_r($stmt->errorInfo(), true)); // Log de errores de la consulta
            return null;  // Si falla, devuelve null
        }
    }

    public function getCategorias() {
        $query = "SELECT id, nombreCategoria FROM Categorias"; // Seleccionamos solo el ID y el nombre
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
    
    

    // Crear una nueva subcategoría
    public function insert($data) {
        $query = "INSERT INTO " . $this->table . " (nombrSubcategoria, descripcionSubcategoria, categoria_id, isActive, fechaCreacion) 
              VALUES (:nombre, :descripcion, :categoria_id, :isActive, now())";
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            // Captura el error y muéstralo para la depuración
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2]; // Mostrar el mensaje de error
            return false;
        }
    }

    // Obtener subcategoría por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET nombrSubcategoria = :nombrSubcategoria,
                      descripcionSubcategoria = :descripcionSubcategoria,
                      categoria_id = :categoria_id,
                      isActive = :isActive 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        // Vincular los parámetros
        $stmt->bindParam(':nombrSubcategoria', $data['nombrSubcategoria']);
        $stmt->bindParam(':descripcionSubcategoria', $data['descripcionSubcategoria']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Retornar verdadero si la actualización fue exitosa
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
            return false; // Retornar falso si ocurrió un error
        }
    }
    

    // Eliminar una subcategoría
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //metodo para obtener las subcategorias y traerlas a la lista de la tienda virtual
    public function getSubcategoriasByCategoriaId($categoriaId) {
        $query = "SELECT id, nombrSubcategoria FROM Subcategorias WHERE categoria_id = :categoriaId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Devuelve las subcategorías
        } else {
            return null;  // Si falla, devuelve null
        }
    }
    
        // Método para obtener el nombre de la subcategoría por su ID
    public function getNombreSubcategoria($subcategoria_id) {
        $query = "SELECT nombrSubcategoria FROM Subcategorias WHERE id = :subcategoria_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subcategoria_id', $subcategoria_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchColumn(); // Devuelve solo el nombre de la subcategoría
        } else {
            return null; // Si falla, devuelve null
        }
    }

}

?>
