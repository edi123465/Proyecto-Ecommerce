<?php
class ReporteModel
{
    private $db;
    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

public function getVentasPorMes($mes = null)
{
    $sql = "SELECT DATE_FORMAT(fechaCreacion, '%Y-%m') AS periodo, SUM(total) AS total FROM pedidos";
    $params = [];
    if ($mes) {
        $sql .= " WHERE DATE_FORMAT(fechaCreacion, '%Y-%m') = ?";
        $params[] = $mes;
    }
    $sql .= " GROUP BY DATE_FORMAT(fechaCreacion, '%Y-%m') ORDER BY periodo ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getVentasPorSemana($mes = null)
    {
        $sql = "SELECT YEAR(fechaCreacion) AS anio, WEEK(fechaCreacion, 1) AS semana, SUM(total) AS total
                FROM pedidos";
        $params = [];
        if ($mes) {
            $sql .= " WHERE DATE_FORMAT(fechaCreacion, '%Y-%m') = ?";
            $params[] = $mes;
        }
        $sql .= " GROUP BY anio, semana ORDER BY anio ASC, semana ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasPorDia($mes = null)
    {
        $sql = "SELECT DATE(fechaCreacion) AS periodo, SUM(total) AS total
                FROM pedidos";
        $params = [];
        if ($mes) {
            $sql .= " WHERE DATE_FORMAT(fechaCreacion, '%Y-%m') = ?";
            $params[] = $mes;
        }
        $sql .= " GROUP BY DATE(fechaCreacion) ORDER BY periodo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function getVentasPorAnio($anio = null)
{
    $sql = "SELECT YEAR(fechaCreacion) AS periodo, SUM(total) AS total FROM pedidos";
    $params = [];

    if ($anio) {
        $sql .= " WHERE YEAR(fechaCreacion) = ?";
        $params[] = $anio;
    }

    $sql .= " GROUP BY YEAR(fechaCreacion) ORDER BY periodo ASC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getProductosMasVendidos($limit = 10)
{
    $sql = "SELECT 
                dp.producto_id,
                p.nombreProducto,
                SUM(dp.cantidad) AS total_vendido
            FROM detallespedidos dp
            JOIN pedidos pe ON dp.pedido_id = pe.id
            JOIN productos p ON dp.producto_id = p.id
            WHERE pe.estado = 'Autorizado'
            GROUP BY dp.producto_id, p.nombreProducto
            ORDER BY total_vendido DESC
            LIMIT ?";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    
}
