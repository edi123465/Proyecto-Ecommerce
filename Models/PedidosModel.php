<?php
// PedidoModel.php
class PedidoModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crearPedido($usuario_id, $fecha, $subtotal, $iva, $total, $estado, $numeroPedido, $totalProductos, $tipoPago)
    {
        try {
            // Insertar en la tabla 'pedidos'
            $sql = "INSERT INTO Pedidos (usuario_id, fechaCreacion, subtotal, iva, total, estado, numeroPedido, items,tipoPago) 
                    VALUES (:usuario_id, GETDATE(), :subtotal, :iva, :total, :estado, :numeroPedido, :items, :tipoPago)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            //$stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->bindParam(':subtotal', $subtotal, PDO::PARAM_STR);
            $stmt->bindParam(':iva', $iva, PDO::PARAM_STR);
            $stmt->bindParam(':total', $total, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':numeroPedido', $numeroPedido, PDO::PARAM_STR);
            $stmt->bindParam(':items', $totalProductos, PDO::PARAM_INT);  // Insertar el campo 'items'
            $stmt->bindParam(':tipoPago', $tipoPago, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $pedido_id = $this->conn->lastInsertId();

                return $pedido_id;
            } else {
                error_log("Error al ejecutar la consulta: " . implode(", ", $stmt->errorInfo()));
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el pedido: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodosLosPedidos()
    {
        $query = "
                    SELECT 
                        p.id AS PedidoID, -- ID del pedido
                        p.numeroPedido AS NumeroPedido, -- Número de pedido
                        p.usuario_id AS UsuarioID, -- ID del usuario
                        u.NombreUsuario AS NombreUsuario, -- Nombre del usuario
                        p.fechaCreacion AS FechaCreacion, -- Fecha de creación del pedido
                        SUM(dp.subtotal) AS SubtotalPedido, -- Subtotal del pedido, agrupado por pedido
                        (SUM(dp.subtotal) * 0.15) AS IVAPedido, -- IVA del pedido
                        (SUM(dp.subtotal) * 1.15) AS TotalPedido, -- Total del pedido con IVA
                        p.estado AS EstadoPedido, -- Estado del pedido
                        SUM(dp.cantidad) AS ItemsPedido -- Cantidad total de productos solicitados
                    FROM 
                        pedidos p
                    JOIN 
                        DetallesPedidos dp ON p.id = dp.pedido_id
                    JOIN 
                        usuarios u ON p.usuario_id = u.id
                    JOIN 
                        productos pr ON dp.producto_id = pr.id
                    GROUP BY 
                        p.id, p.numeroPedido, p.usuario_id, u.NombreUsuario, p.fechaCreacion, p.estado
                    ORDER BY 
                        p.id ASC;
    ";

        $stmt = $this->conn->query($query);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica el resultado de la consulta
        error_log("Pedidos obtenidos: " . json_encode($resultados));

        return $resultados;
    }


    public function obtenerPedidosPorUsuario($userId)
    {
        try {
            // Verificar si $userId está definido correctamente
            if (empty($userId)) {
                error_log("Error: userId está vacío o no definido.");
                return false;
            }

            // Consulta SQL para obtener los pedidos del usuario
            $query = "SELECT * FROM Pedidos WHERE usuario_id = :userId";

            // Preparar la consulta con un cursor que permita recorrer los resultados
            $stmt = $this->conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

            // Verificar si la preparación de la consulta fue exitosa
            if (!$stmt) {
                error_log("Error al preparar la consulta: " . implode(", ", $this->conn->errorInfo()));
                return false;
            }

            // Asignar el parámetro y ejecutar la consulta
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Verificar si la consulta tuvo éxito
            if ($stmt->errorCode() !== '00000') {
                error_log("Error en la ejecución de la consulta: " . implode(", ", $stmt->errorInfo()));
                return false;
            }

            // Devolver el objeto PDOStatement para que se maneje el fetch en el controlador
            return $stmt;
        } catch (PDOException $e) {
            // Capturar y registrar cualquier excepción de PDO
            error_log("Error en obtenerPedidosPorUsuario: " . $e->getMessage());
            return false;
        }
    }



    public function getDetallePedidoById($pedido_id)
    {
        try {
            error_log("Ejecutando consulta para el pedido_id: " . $pedido_id);

            $sql = "SELECT     p.id, 
                    p.nombreProducto, 
                    dp.cantidad, 
                    dp.precio_unitario, 
                    (dp.cantidad * dp.precio_unitario) AS subtotal, 
                    ped.numeroPedido,  -- Número de pedido
                    ped.fechaCreacion,  -- Fecha de creación del pedido
                    ped.estado  -- Estado del pedido
                    FROM 
                    DetallesPedidos dp
                    JOIN 
                    Productos p ON dp.producto_id = p.id
                    JOIN 
		            Pedidos ped ON dp.pedido_id = ped.id  -- Unir con la tabla Pedidos
                    WHERE dp.pedido_id = :pedido_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Verifica si los resultados están vacíos o no
                if (!empty($resultados)) {
                    error_log("Detalles encontrados: " . print_r($resultados, true));
                    return $resultados;
                } else {
                    error_log("No se encontraron detalles para el pedido con ID: " . $pedido_id);
                    return null;
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Error al ejecutar la consulta: " . $errorInfo[2]);
                return null;
            }
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            return null;
        }
    }



    public function insertarDetallePedido($pedido_id, $producto_id, $cantidad, $precio, $subtotal, $imagen)
    {
        try {
            // Insertar en la tabla 'detallesPedidos'
            $sql = "INSERT INTO DetallesPedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal, imagen) 
                    VALUES (:pedido_id, :producto_id, :cantidad, :precio, :subtotal, :imagen)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
            $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':subtotal', $subtotal, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

            return $stmt->execute();  // Devuelve true si se ejecutó correctamente
        } catch (PDOException $e) {
            error_log("Error al insertar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }
}
