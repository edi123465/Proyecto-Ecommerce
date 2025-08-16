<?php
// Incluir modelo y archivo de configuración de base de datos
require_once __DIR__ . "../../Models/EntregasModel.php";
require_once __DIR__ . "../../Config/db.php";

class EntregasController
{
    private $conn;
    private $entregasModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->entregasModel = new EntregasModel($this->conn);
    }

    public function guardarDireccion()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        error_log("Datos recibidos del frontend: " . print_r($input, true));
        
        if (!$input) {
            error_log("Error: Datos JSON inválidos o mal formateados.");
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o mal formateados']);
            return;
        }

        $usuario_id = $input['usuarioId'] ?? null;
        $pais = $input['pais'] ?? null;
        $estado = $input['estado'] ?? null;
        $direccion = $input['direccion'] ?? null;
        $referencia = $input['referencia'] ?? null;
        $telefono_contacto = $input['telefono'] ?? null;
        $es_predeterminada = $input['direccionPredeterminada'] ?? 0;
        error_log("Datos a insertar: " . print_r([
            'usuarioId' => $usuario_id,
            'pais' => $pais,
            'estado' => $estado,
            'direccion' => $direccion,
            'referencia' => $referencia,
            'telefono' => $telefono_contacto,
            'direccionPredeterminada' => $es_predeterminada
        ], true));
        if ($usuario_id && $pais && $estado && $direccion && $referencia && $telefono_contacto && $es_predeterminada) {
            // Verificar si el usuario ya tiene 2 direcciones
            $totalDirecciones = $this->entregasModel->contarDireccionesPorUsuario($usuario_id);
            if ($totalDirecciones >= 2) {
                echo json_encode(['status' => 'error', 'message' => 'Límite de direcciones alcanzado']);
                return;
            }

            // Insertar la nueva dirección
            $resultado = $this->entregasModel->insertarDireccion(
                $usuario_id,
                $pais,
                $estado,
                $direccion,
                $referencia,
                $telefono_contacto,
                $es_predeterminada
            );

            if ($resultado) {
                echo json_encode(['status' => 'success', 'message' => 'Dirección guardada con éxito']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Hubo un error al guardar la dirección']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos']);
        }
    }

    public function obtenerDirecciones()
    {
        session_start();
        // Establecer el tipo de contenido como JSON
        header('Content-Type: application/json');

        // Verificar que el usuario esté autenticado
        $usuario_id = $_SESSION['user_id'] ?? null;

        // Log del usuario_id para ver qué valor tiene
        error_log("usuario_id desde la sesión: " . $usuario_id);

        if (!$usuario_id) {
            error_log("Usuario no autenticado");
            echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
            return;
        }

        try {
            // Obtener las direcciones del modelo
            $direcciones = $this->entregasModel->obtenerDireccionesPorUsuario($usuario_id);

            // Verificar si se encontraron direcciones
            if (!empty($direcciones)) {
                // Log para ver qué devuelve el modelo
                error_log("Direcciones obtenidas: " . var_export($direcciones, true));

                // Formatear las direcciones en el formato adecuado para el frontend
                $direccionesFormateadas = [];
                foreach ($direcciones as $direccion) {
                    $direccionesFormateadas[] = [
                        'direccion' => $direccion['direccion'],
                        'nombre' => $direccion['referencia'] ?? 'Nombre no disponible',
                        'pais' => $direccion['pais'],
                        'estado' => $direccion['estado'],
                        'predeterminada' => $direccion['es_predeterminada'] == 1
                    ];
                }
                echo json_encode(['status' => 'success', 'direcciones' => $direccionesFormateadas]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontraron direcciones']);
            }
        } catch (Exception $e) {
            // Error al obtener las direcciones
            error_log("Error al obtener las direcciones: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener las direcciones']);
        }
    }
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $database = new Database1();
    $db = $database->getConnection();
    $controller = new EntregasController($db);

    // Usamos un switch para manejar las diferentes acciones    
    switch ($action) {
        case 'obtenerDirecciones':
            // Acción para obtener las direcciones
            if (isset($_GET['userId'])) {
                $userId = $_GET['userId']; // Obtener el userId de la URL
                $controller->obtenerDirecciones($userId); // Llamamos a la función obtenerDirecciones
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User ID no proporcionado']);
            }
            break;

            case 'insertarDireccion':
                // Acción para insertar una nueva dirección
                $input = json_decode(file_get_contents("php://input"), true); // Obtener los datos JSON del cuerpo de la solicitud
            
                if ($input) {
                    // Asignamos los datos recibidos al contexto global o a las propiedades del controlador según sea necesario
                    // Suponiendo que el controlador tiene acceso a estos valores o los establece internamente
                    $controller->guardarDireccion();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o mal formateados']);
                }
                break;
    }
}
