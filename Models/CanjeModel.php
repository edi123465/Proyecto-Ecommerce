<?php
require_once __DIR__ . "/../Config/db.php";

class CanjeModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //metodo para obtener los canjeables en la parte administrativa
    public function getCanjeables()
    {
        $sql = "SELECT * FROM canjeables ORDER BY fecha_creacion ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener los canjeables activos en la parte administrativa
    public function getCanjeablesTienda()
    {
        // Consulta para obtener solo los canjeables cuyo estado sea 'activo'
        $sql = "SELECT * FROM canjeables WHERE estado = 'activo' ORDER BY fecha_creacion ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //metodo para obtener los canjeables en la tienda virtual 
    public function getAllCanjeables()
    {
        $sql = "SELECT * FROM canjeables ORDER BY fecha_creacion ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCanjeableById($id)
    {
        $sql = "SELECT * FROM canjeables WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener los productos y asociarlos al select de los canjeables
    public function obtenerProductos()
    {
        // Crear una consulta para obtener los productos
        $query = "SELECT id, nombreProducto FROM productos ORDER BY nombreProducto ASC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener todos los productos
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $productos;
    }
    // Método para obtener los detalles del producto por ID
    public function obtenerProductoPorId($productoId)
    {
        // Consulta para obtener el producto
        $query = "SELECT nombreProducto, descripcionProducto, imagen FROM productos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $productoId]);

        // Obtener el resultado
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para verificar si ya existe un canjeable con el mismo nombre
    public function verificarCanjeableExistente($nombre)
    {
        $query = "SELECT * FROM canjeables WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        // Verificar si ya existe un canjeable con el mismo nombre
        if ($stmt->rowCount() > 0) {
            return true;  // Ya existe un canjeable con ese nombre
        }

        return false;  // No existe un canjeable con ese nombre
    }


    public function createCanjeable($data)
    {
        $sql = "INSERT INTO canjeables (
                    nombre, descripcion, tipo, valor_descuento, fecha_creacion, puntos_necesarios, producto_id, estado, imagen
                ) VALUES (
                    :nombre, :descripcion, :tipo, :valor_descuento, NOW(), :puntos_necesarios, :producto_id, :estado, :imagen
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $data['tipo'], PDO::PARAM_STR);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento'], PDO::PARAM_STR);
        $stmt->bindParam(':puntos_necesarios', $data['puntos_necesarios'], PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_STR);

        return $stmt->execute();
    }


    public function updateCanjeable($id, $data)
    {
        // Comprobar si hay una nueva imagen
        if (!empty($data['imagen'])) {
            $imagen = $data['imagen'];  // Usar la nueva imagen
        } else {
            // Si no hay nueva imagen, mantener la imagen anterior
            // Suponemos que la imagen anterior está en $data['imagen_anterior']
            $imagen = $data['imagen_anterior'];
        }

        // Crear la consulta SQL para actualizar el canjeable
        $sql = "UPDATE canjeables 
                SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, 
                    valor_descuento = :valor_descuento, puntos_necesarios = :puntos_necesarios, 
                    producto_id = :producto_id, estado = :estado, imagen = :imagen 
                WHERE id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);

        // Vincular los parámetros con los valores
        $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $data['tipo'], PDO::PARAM_STR);
        $stmt->bindParam(':valor_descuento', $data['valor_descuento'], PDO::PARAM_STR);
        $stmt->bindParam(':puntos_necesarios', $data['puntos_necesarios'], PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);  // Vincular el ID para la actualización

        // Ejecutar la consulta y devolver el resultado
        return $stmt->execute();
    }


    public function deleteCanjeable($id)
    {
        // Consulta para eliminar el canjeable por ID
        $query = "DELETE FROM canjeables WHERE id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el parámetro ID
        $stmt->bindParam(':id', $id);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmt->execute()) {
            return true;  // El canjeable se eliminó correctamente
        } else {
            return false;  // Hubo un error al eliminar el canjeable
        }
    }


}
