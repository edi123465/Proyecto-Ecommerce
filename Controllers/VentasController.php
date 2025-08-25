<?php

require_once __DIR__ . '/../Models/VentaModel.php';
require_once __DIR__ . '/../Config/db.php';

class VentaController
{
    private $conn;
    private $ventaModel;

    public function __construct()
    {
        // Crear conexión
        $database = new Database1(); // Suponiendo tu clase de conexión
        $this->conn = $database->getConnection();

        // Instanciar modelo de ventas
        $this->ventaModel = new VentasModel($this->conn);
    }

    // Guardar venta
    public function guardarVenta($data)
    {
        try {
            // Si es cliente con datos
            if ($data['tipoCliente'] === 'datos') {
                $clienteID = $this->ventaModel->guardarCliente($data['cliente']);
            } else {
                $clienteID = null;
            }

            // Preparar la venta
            $this->ventaModel->Fecha = $data['fecha'];
            $this->ventaModel->MetodoPago = $data['metodoPago'];
            $this->ventaModel->Total = $data['total'];
            $this->ventaModel->PagoCliente = $data['pagoCliente'];
            $this->ventaModel->TipoCliente = $data['tipoCliente'];
            $this->ventaModel->ClienteID = $clienteID;

            // Guardar venta
            $ventaID = $this->ventaModel->guardarVenta();

            // Guardar detalle de venta
            $this->ventaModel->guardarDetalleVenta($ventaID, $data['productos']);

            return ['success' => true, 'ventaID' => $ventaID];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
