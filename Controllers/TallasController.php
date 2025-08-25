<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos

require_once "../Config/db.php";
require_once "../Models/TallasModel.php";
class TallasController
{

    private $conn;
    private $tallasModel;

    public function __construct($db)
    {
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $this->conn = $db;
        $this->tallasModel = new TallasModel($this->conn);
    }

    // Método para obtener todas las tallas
    public function obtenerTallas()
    {
        header('Content-Type: application/json');

        try {
            // Llamamos al método getAll del modelo de tallas
            $result = $this->tallasModel->getAll();

            // Si hay resultados, los retornamos en formato JSON
            if ($result && count($result) > 0) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $result
                ]);
            } else {
                // Si no se encuentran tallas, enviamos un mensaje de error
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron tallas.'
                ]);
            }
        } catch (Exception $e) {
            // Si ocurre un error, lo registramos y mostramos un mensaje de error
            error_log($e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener las tallas: ' . $e->getMessage()
            ]);
        }
    }



    public function insertarTalla()
    {
        // Verificar si el campo talla está presente
        if (isset($_POST['talla']) && !empty($_POST['talla'])) {
            $talla = $_POST['talla'];  // Obtener el valor de la talla
        
            // Log de los datos que se intentan insertar
            error_log("Datos a insertar: ");
            error_log("talla: " . $talla); // Confirmar que el valor está llegando
        
            // Crear una instancia del modelo de tallas y usar la conexión
            $tallaModel = new TallasModel($this->conn);
        
            // Llamar al método de inserción en el modelo
            $resultado = $tallaModel->create([
                'talla' => $talla // Aquí estamos insertando el nombre de la talla
            ]);
        
            // Responder al cliente
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Talla agregada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar la talla.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
        }
    }
    
    // Método para obtener una talla por su ID
    public function obtenerTallaPorId($id)
    {
        error_log("Obteniendo talla por ID: " . $id); // <- Esto
    
        $tallasModel = new TallasModel($this->conn);
        $talla = $tallasModel->getById($id);
    
        if ($talla) {
            echo json_encode([
                'status' => 'success',
                'data' => $talla
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Talla no encontrada.'
            ]);
        }
    }

    public function editarTalla()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos como JSON
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'] ?? null;
        $talla = $data['talla'] ?? '';

        if (!$id || trim($talla) === '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Datos incompletos para actualizar.'
            ]);
            return;
        }

        $tallasModel = new TallasModel($this->conn);
        $resultado = $tallasModel->update($id, ['talla' => $talla]);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Talla actualizada correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar la talla.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido.'
        ]);
    }
}

// En TallasController.php
public function eliminarTalla()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'ID no proporcionado.'
            ]);
            return;
        }

        $tallasModel = new TallasModel($this->conn);
        $resultado = $tallasModel->delete($id);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Talla eliminada correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar la talla.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido.'
        ]);
    }
}

    
    
}


if (isset($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'default'; // Valor por defecto si no se pasa acción

    $database = new Database1();
    $db = $database->getConnection();

    $tallasModel = new TallasModel($db);

    switch ($action) {
        //casos para obtener informacion
        case 'obtenerTallas':
            $TallasController = new TallasController($db);
            $TallasController->obtenerTallas();
            break;
        case 'insertarTalla':
            $TallasController = new TallasController($db);
            $TallasController->insertarTalla();
            break;
            case 'obtenerTallaPorId':
                $TallasController = new TallasController($db);
                if (isset($_GET['id'])) {
                    $TallasController->obtenerTallaPorId($_GET['id']);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'ID no proporcionado.'
                    ]);
                }
                break;
        case 'actualizarTalla':
            $TallasController = new TallasController($db);
            $TallasController->editarTalla();
            break;
        case 'eliminarTalla':
            $TallasController = new TallasController($db);
            $TallasController->eliminarTalla();
            break;
    }
} else {
    echo json_encode(['error' => 'Parámetro action faltante']);
}
