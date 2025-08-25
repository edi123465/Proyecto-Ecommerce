<?php
require_once "../Config/db.php";
require_once "../Models/FacturacionModel.php";

class FacturacionController
{
    private $conn;
    private $facturacionModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->facturacionModel = new FacturacionModel($this->conn);
    }

    // Buscar por código de barras exacto
    public function obtenerProductoPorCodigo()
    {
        $codigo = $_GET['codigo'] ?? '';
        if (empty($codigo)) {
            echo json_encode(['error' => 'Código de barras no proporcionado']);
            return;
        }

        $producto = $this->facturacionModel->getProductoByCodigoBarras($codigo);
        echo json_encode($producto);
    }

    // Buscar productos relacionados por nombre/código
    public function buscarProductosRelacionados()
    {
        $termino = $_GET['q'] ?? '';
        if (empty($termino)) {
            echo json_encode(['error' => 'Término de búsqueda no proporcionado']);
            return;
        }

        $productos = $this->facturacionModel->buscarProductos($termino);
        echo json_encode($productos);
    }

    // Método para procesar acción desde GET
    public function procesarAccion($action)
    {
        switch ($action) {
            case 'obtenerProductoPorCodigo':
                $this->obtenerProductoPorCodigo();
                break;
            case 'buscarProductosRelacionados':
                $this->buscarProductosRelacionados();
                break;
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }
    }
}

// -------------------------------
// Manejo de acciones vía GET
// -------------------------------
error_log("Parámetros GET recibidos: " . print_r($_GET, true));

if (isset($_GET['action'])) {
    $database = new Database1();
    $db = $database->getConnection();

    $controller = new FacturacionController($db);
    $controller->procesarAccion($_GET['action']);
} else {
    echo json_encode(['error' => 'Parámetro action faltante']);
}
