<?php
class EntregasModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertarDireccion($usuario_id, $pais, $estado, $direccion, $referencia, $telefono_contacto, $es_predeterminada)
    {
        try {
            // Modificar la consulta SQL para incluir la fecha actual
            $sql = "INSERT INTO DireccionesEntrega (
                        usuario_id, pais, estado, direccion, referencia, telefono_contacto,fecha_creacion, es_predeterminada
                    ) VALUES (
                        :usuario_id, :pais, :estado, :direccion, :referencia, :telefono_contacto,GETDATE(), :es_predeterminada
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':pais', $pais, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':referencia', $referencia, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_contacto', $telefono_contacto, PDO::PARAM_STR);
            $stmt->bindParam(':es_predeterminada', $es_predeterminada, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al insertar dirección: " . $e->getMessage();
            return false;
        }
    }
    //metodo para obtener las direcciones por ususario
    public function obtenerDireccionesPorUsuario($usuario_id)
    {
        try {
            $query = "SELECT 
                    id, 
                    pais, 
                    estado, 
                    direccion, 
                    referencia,  
                    es_predeterminada 
                  FROM DireccionesEntrega 
                  WHERE usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Log para ver los resultados obtenidos
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Direcciones obtenidas del modelo: " . var_export($result, true));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error al obtener direcciones: " . $e->getMessage());
            return [];
        }
    }

    public function contarDireccionesPorUsuario($usuario_id)
{
    $query = "SELECT COUNT(*) as total FROM DireccionesEntrega WHERE usuario_id = ?";
    $stmt = $this->conn->prepare($query); // Usar tu instancia de conexión a la base de datos
    $stmt->execute([$usuario_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?? 0;
}

    
}
