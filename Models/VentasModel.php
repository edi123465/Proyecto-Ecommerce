<?php

class VentasModel
{
    private $conn;

    // Propiedades para la venta
    public $VentaID;
    public $Fecha;
    public $MetodoPago;
    public $Total;
    public $PagoCliente;
    public $TipoCliente;
    public $ClienteID;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Insertar cliente
    public function guardarCliente($cliente)
    {
        $query = "INSERT INTO Clientes (Nombre, Cedula, Direccion, Telefono, Correo)
                  VALUES (:nombre, :cedula, :direccion, :telefono, :correo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $cliente['nombre']);
        $stmt->bindParam(':cedula', $cliente['cedula']);
        $stmt->bindParam(':direccion', $cliente['direccion']);
        $stmt->bindParam(':telefono', $cliente['telefono']);
        $stmt->bindParam(':correo', $cliente['correo']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Devuelve el ID del cliente insertado
        } else {
            return false;
        }
    }

    // Insertar venta
    public function guardarVenta()
    {
        $query = "INSERT INTO Ventas (Fecha, MetodoPago, Total, PagoCliente, TipoCliente, ClienteID)
                  VALUES (:fecha, :metodoPago, :total, :pagoCliente, :tipoCliente, :clienteID)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $this->Fecha);
        $stmt->bindParam(':metodoPago', $this->MetodoPago);
        $stmt->bindParam(':total', $this->Total);
        $stmt->bindParam(':pagoCliente', $this->PagoCliente);
        $stmt->bindParam(':tipoCliente', $this->TipoCliente);
        $stmt->bindParam(':clienteID', $this->ClienteID);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Devuelve el ID de la venta
        } else {
            return false;
        }
    }

    // Insertar detalle de venta
    public function guardarDetalleVenta($ventaID, $productos)
    {
        $query = "INSERT INTO DetalleVentas (VentaID, NombreProducto, Cantidad, PrecioUnitario, Subtotal)
                  VALUES (:ventaID, :nombreProducto, :cantidad, :precioUnitario, :subtotal)";
        $stmt = $this->conn->prepare($query);

        foreach ($productos as $p) {
            $stmt->bindParam(':ventaID', $ventaID);
            $stmt->bindParam(':nombreProducto', $p['nombre']);
            $stmt->bindParam(':cantidad', $p['cantidad']);
            $stmt->bindParam(':precioUnitario', $p['precio']);
            $stmt->bindParam(':subtotal', $p['subtotal']);
            $stmt->execute();
        }

        return true;
    }
}
