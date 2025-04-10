<?php

require_once __DIR__ . '/../Models/CategoriaModel.php';
require_once __DIR__ . '/../Models/SubcategoriaModel.php';
require_once __DIR__ . '/../Models/ProductoModel.php';
require_once __DIR__ . '/../Models/TiendaModel.php';




class TiendaController
{

    private $conn;
    private $table = 'Categorias';
    private $categoriaModel;
    private $subcategoriaModel;
    private $productoModel;
    private $tiendaModel;
    public function __construct($db)
    {
        $this->conn = $db;
        $this->categoriaModel = new CategoriaModel($this->conn);
        $this->subcategoriaModel = new SubcategoriaModel($this->conn);
        $this->productoModel = new ProductoModel($this->conn);
        $this->tiendaModel = new TiendaModel($this->conn);
    }

    // Función para obtener las categorías y devolverlas como JSON
    function obtenerCategorias()
    {
        // Crear una instancia del modelo de Subcategorías
        $tiendaModel = new TiendaModel($this->conn);

        // Llamar al método que obtiene las categorías
        $categorias = $tiendaModel->getCategorias();

        // Registrar los datos obtenidos en el log
        error_log("Datos obtenidos de las categorías: " . print_r($categorias, true)); // Esto imprimirá los resultados en el log

        // Comprobar si se obtuvieron resultados
        if ($categorias) {
            // Si se obtuvieron categorías, se devuelve un JSON con el resultado
            echo json_encode([
                'status' => 'success',
                'data' => $categorias
            ]);
        } else {
            // Si no se obtuvieron categorías, se devuelve un mensaje de error
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron categorías.'
            ]);
        }
    }

    public function getCategoriasConSubcategorias()
    {
        $categorias = $this->categoriaModel->getCategoryByName();

        foreach ($categorias as &$categoria) {
            $categoriaId = $categoria['id'];
            $subcategorias = $this->subcategoriaModel->getSubcategoriasByCategoriaId($categoriaId);
            $categoria['subcategorias'] = $subcategorias;
        }

        echo json_encode($categorias);
    }

    public function obtenerProductos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categoriaId = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;
            $subcategoriaId = isset($_GET['subcategoria_id']) ? $_GET['subcategoria_id'] : null;
    
            require_once "../models/ProductoModel.php";
            $tiendaModel = new TiendaModel($this->conn);
            $productos = $tiendaModel->getProductosPorCategoriaOSubcategoria($categoriaId, $subcategoriaId);
    
            // Registrar en el log los datos recibidos y los productos obtenidos
            error_log("Categoría ID: " . ($categoriaId ?? 'NULL') . " | Subcategoría ID: " . ($subcategoriaId ?? 'NULL'));
            error_log("Productos obtenidos: " . json_encode($productos));
    
            echo json_encode($productos);
        }
    }
    


    //metodo del controlador para mostrar los productos por la categoria seleccionada
    public function mostrarProductosPorSubcategoria()
    {
        try {
            $subcategoria_id = isset($_GET['subcategoria_id']) ? $_GET['subcategoria_id'] : null;

            if ($subcategoria_id) {
                $nombreSubcategoria = $this->subcategoriaModel->getNombreSubcategoria($subcategoria_id);
                $productos = $this->productoModel->getAllProductosConCategorias($subcategoria_id);
                header('Content-Type: application/json');

                // Depuración: Log de salida
                error_log("Nombre de Subcategoría: " . json_encode($nombreSubcategoria));
                error_log("Productos: " . json_encode($productos));

                echo json_encode([
                    'nombreSubcategoria' => $nombreSubcategoria,
                    'productos' => $productos
                ]);
            } else {
                echo json_encode([
                    'error' => 'Subcategoría no encontrada.'
                ]);
            }
        } catch (Exception $e) {
            error_log('Error al procesar la solicitud: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
    }
    //filtros de datos
    public function searchAction()
    {
        header('Content-Type: application/json'); // Asegura que la respuesta esté en formato JSON

        // Depuración: Log de parámetros recibidos
        error_log("Parámetros GET recibidos: " . print_r($_GET, true));

        // Validar que se proporcionó un término de búsqueda
        if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
            echo json_encode(['error' => 'No se proporcionó un término de búsqueda.']);
            return;
        }

        // Validar el parámetro 'action'
        if (!isset($_GET['action']) || $_GET['action'] !== 'search') {
            echo json_encode(['error' => 'No se proporcionó una acción válida.']);
            return;
        }

        $query = trim($_GET['q']); // Sanitizar el término de búsqueda

        // Llamar al modelo para buscar los productos
        try {
            $resultados = $this->productoModel->searchProducts($query);

            if (empty($resultados)) {
                echo json_encode(['message' => 'No se encontraron productos para el término proporcionado.']);
            } else {
                echo json_encode([
                    'success' => true,
                    'query' => $query,
                    'productos' => $resultados
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al buscar productos: " . $e->getMessage());
            echo json_encode(['error' => 'Ocurrió un error al realizar la búsqueda.']);
        }
    }

    
}


// Depuración: Imprimir los datos recibidos
error_log("Parámetros GET recibidos: " . print_r($_GET, true));

if (isset($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'default'; // Valor por defecto si no se pasa acción
    require_once "../Config/db.php";
    require_once "../Models/SubcategoriaModel.php";
    $database = new Database1();
    $db = $database->getConnection();
    $SubcategoriaModel = new SubcategoriaModel($db);

    switch ($action) {
            //casos para obtener informacion
        case 'obtenerCategorias':
            $TiendaController = new TiendaController($db);
            $TiendaController->obtenerCategorias();
            break;
        case 'obtenerCategorias':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->obtenerCategorias();
            break;
        case 'insertarSubcategoria':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->insertarSubcategoria();
            break;
        case 'obtenerSubcategoriaId':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->obtenerSubcategoriasPorId();
            break;
        case 'editarSubcategoria':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->editarSubcategoria();
            break;
        case 'eliminarSubcategoria':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->eliminarSubcategoria();
            break;
        case 'getCategoriasConSubcategorias':
            $TiendaController = new TiendaController($db);
            $TiendaController->getCategoriasConSubcategorias();
            break;
        case 'obtenerProductos';
            $TiendaController = new TiendaController($db);
            $TiendaController->obtenerProductos();
        break;
    }
} else {
    //echo json_encode(['error' => 'Parámetro action faltante']);
}
