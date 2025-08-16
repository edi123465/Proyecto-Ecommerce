<?php
class ComentariosModel
{
    private $conn;
    private $table = 'comentarios';

    public $id;
    public $usuario_id;
    public $comentario;
    public $fecha;
    public $estado;
    public $producto_id;
    public $calificacion;

    public function __construct($db)
    {
        $this->conn = $db;
    }



        // Método para verificar si ya existe un comentario del usuario para ese producto
    public function existeComentario($userId, $productoId)
    {
        $sql = "SELECT COUNT(*) as count FROM comentarios WHERE usuario_id = :user_id AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $productoId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    // Mostrar solo comentarios activos (estado=1) para la tienda
    public function getActiveComments()
    {
        $query = "SELECT c.*, u.nombreUsuario, u.email 
                  FROM $this->table c
                  JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.estado = 1
                  ORDER BY c.fecha DESC";

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Obtener comentarios por usuario
    public function getByUsuario($usuario_id)
    {
        $query = "SELECT * FROM $this->table WHERE usuario_id = :usuario_id ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return null;
    }

    // Insertar un nuevo comentario
    public function create()
    {
        $query = "INSERT INTO $this->table 
                  (usuario_id, producto_id, comentario, calificacion, fecha, estado) 
                  VALUES (:usuario_id, :producto_id, :comentario, :calificacion, NOW(), :estado)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':usuario_id', $this->usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
        $stmt->bindParam(':calificacion', $this->calificacion, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $this->estado, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Actualizar un comentario
    public function updateEstado($id, $estado) {
        $sql = "UPDATE comentarios SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();return $stmt->execute();
    }

public function deleteById($id) {
    try {
        $stmt = $this->conn->prepare("DELETE FROM comentarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al eliminar comentario: " . $e->getMessage());
        return false;
    }
}

public function deleteByUser($comentarioId, $usuarioId)
{
    $query = "DELETE FROM $this->table WHERE id = :comentarioId AND usuario_id = :usuarioId";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':comentarioId', $comentarioId, PDO::PARAM_INT);
    $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);

    return $stmt->execute();
}

// Actualizar un comentario existente
public function update()
{
    $query = "UPDATE $this->table 
              SET comentario = :comentario, 
                  calificacion = :calificacion, 
                  fecha = NOW() 
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
    $stmt->bindParam(':calificacion', $this->calificacion, PDO::PARAM_INT);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
}

    public function obtenerComentariosActivos()
    {
        $sql = "SELECT 
                c.id, 
                c.usuario_id, 
                u.nombreUsuario,    -- nombre desde tabla usuarios
                c.fecha_creacion,  
                c.descripcion, 
                c.valoracion
                FROM comentarios_tienda c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.estado = 1
                ORDER BY c.fecha_creacion DESC; ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function getAll()
{
    $sql = "SELECT 
            p.id AS producto_id,
            p.nombreProducto,
            p.imagen,
            c.id AS comentario_id,
            c.comentario,
            c.fecha,
            c.estado,
            u.id AS usuario_id,
            u.nombreUsuario
        FROM comentarios c
        INNER JOIN productos p ON c.producto_id = p.id
        INNER JOIN usuarios u ON c.usuario_id = u.id
        ORDER BY p.nombreProducto, c.fecha DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    // Obtener todas las filas como array asociativo y retornarlo
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function eliminar($comentario_id)
    {
        $query = "DELETE FROM comentarios WHERE id = :comentario_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Opcional: loguear error $e->getMessage()
            return false;
        }
    }

        public function eliminarComentarioAdmin($comentario_id)
{
    $sql = "DELETE FROM comentarios_tienda WHERE id = :comentario_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);

    return $stmt->execute();
}

    public function eliminarComentario($comentario_id, $usuario_id)
    {
        $sql = "DELETE FROM comentarios_tienda WHERE id = :comentario_id AND usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Opcional: loguear error $e->getMessage()
            return false;
        }
    }

        public function insertarComentario($usuario_id, $descripcion, $valoracion)
    {
        $sql = "INSERT INTO comentarios_tienda (usuario_id, descripcion, valoracion, estado, fecha_creacion)
            VALUES (:usuario_id, :descripcion, :valoracion, 1, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':valoracion', $valoracion, PDO::PARAM_INT);

        return $stmt->execute();
    }

        public function tieneComentario($usuario_id)
    {
        $sql = "SELECT COUNT(*) FROM comentarios_tienda WHERE usuario_id = :usuario_id AND estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }

     public function editarComentario($comentario_id, $descripcion, $valoracion)
{
    $sql = "UPDATE comentarios_tienda
            SET descripcion = :descripcion,
                valoracion = :valoracion
            WHERE id = :comentario_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':valoracion', $valoracion, PDO::PARAM_INT);
    $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);

    return $stmt->execute();
}

}
