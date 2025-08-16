<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Asegúrate de incluir el archivo de conexión y el modelo
require_once __DIR__ . '/../Models/ComentariosModel.php';
require_once __DIR__ . '/../Config/db.php';
class ComentariosController
{
    private $conn;
    private $comentariosModel;

    public function __construct()
    {
        // Instanciar la conexión a la base de datos
        $database = new Database1();
        $this->conn = $database->getConnection();
        // Instanciar el modelo de comentarios usando la conexión
        $this->comentariosModel = new ComentariosModel($this->conn);
    }

    public function processRequest()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getAllComentarios':
                $this->obtenerTodo();
                break;

            case 'getAllComentariosActive':
                $this->obtenerComentariosActivos();
                break;
            case 'getAllComentariosActiveSearch':
                $this->obtenerComentariosActivos();
                break;
            case 'create':
                $this->create();
                break;
            case 'update':
                $this->updateComentario();
                break;
            //accion para eliminar un comentario de la tienda por parte del usuario cliente
            case 'delete':
                $this->deleteComentario();
                break;

            case 'checkComentario':   // Nueva acción para verificar si ya hay comentario
                $this->checkComentario();
                break;

            case 'updateEstadoComentario':
                $this->updateEstadoComentario();
                break;

            case 'deleteComentario':
                $this->deleteComentario();
                break;
            case 'listarComentariosActivos':
                $this->listarComentariosActivos();
                break;
            case 'guardar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->guardar();
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            break;
            case 'editarComentarioUsuario':  // <== Aquí agregas el case para editar comentario
                $this->editarComentarioUsuario();
            break;
            case 'eliminarComentarioUsuario':
                $this->eliminarComentarioUsuario();
            break;
            case 'listar':
                $this->listar();
            break;
            case 'eliminar':  // <== Nueva acción para eliminar comentario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->eliminarComentario();
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }
            break;
            case 'eliminarComentarioAdmin':
                $this->eliminarComentarioAdmin();
                    break;

            
                default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                break;
            }
    }

        public function eliminarComentario()
    {
        if (!isset($_POST['comentario_id'])) {
            echo json_encode(['success' => false, 'message' => 'ID de comentario no recibido']);
            return;
        }

        $comentario_id = intval($_POST['comentario_id']);

        if ($this->comentariosModel->eliminar($comentario_id)) {
            echo json_encode(['success' => true, 'message' => 'Comentario eliminado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar comentario']);
        }
    }

        public function eliminarComentarioAdmin()
{
    session_start();


    // Validar método POST y recibir id comentario
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['comentario_id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        return;
    }

    $comentario_id = intval($_POST['comentario_id']);

    $resultado = $this->comentariosModel->eliminarComentarioAdmin($comentario_id);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Comentario eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el comentario']);
    }
}

public function guardar()
    {
        session_start();

        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
                exit;
        }
        $usuario_id = $_SESSION['user_id'];
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        $valoracion = isset($_POST['valoracion']) ? intval($_POST['valoracion']) : 0;
        if (empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'El comentario no puede estar vacío']);
            return;
        }
        if ($valoracion < 1 || $valoracion > 5) {
            echo json_encode(['success' => false, 'message' => 'Valoración inválida']);
            return;
        }

        // Validar si usuario ya tiene comentario
        if ($this->comentariosModel->tieneComentario($usuario_id)) {
            echo json_encode(['success' => false, 'message' => 'Solo puedes dejar un comentario']);
            return;
        }

        $resultado = $this->comentariosModel->insertarComentario($usuario_id, $descripcion, $valoracion);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Comentario guardado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el comentario']);
        }
    }

public function eliminarComentarioUsuario()
    {
        session_start();

        // Validar sesión
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
            return;
        }

        // Validar que sea POST y que llegue el id del comentario
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['comentario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        $usuario_id = $_SESSION['user_id'];
        $comentario_id = intval($_POST['comentario_id']);

        // Llamar al método del modelo para eliminar
        $resultado = $this->comentariosModel->eliminarComentario($comentario_id, $usuario_id);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Comentario eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el comentario']);
        }
    }

    public function editarComentarioUsuario()
    {
        session_start();

        // Validar sesión
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
            return;
        }

        // Validar que sea POST y que lleguen los datos necesarios
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['comentario_id'], $_POST['descripcion'], $_POST['valoracion'])
        ) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        $usuario_id = $_SESSION['user_id'];
        $comentario_id = intval($_POST['comentario_id']);
        $descripcion = trim($_POST['descripcion']);
        $valoracion = intval($_POST['valoracion']);

        // Validaciones básicas
        if (empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'El comentario no puede estar vacío']);
            return;
        }

        if ($valoracion < 1 || $valoracion > 5) {
            echo json_encode(['success' => false, 'message' => 'Valoración inválida']);
            return;
        }

        // Actualizar comentario en la base de datos
        $resultado = $this->comentariosModel->editarComentario($comentario_id, $descripcion, $valoracion);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Comentario editado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al editar el comentario']);
        }
    }

public function listar()
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $comentarios = $this->comentariosModel->getAll();  // Devuelve array ya?
        echo json_encode(['success' => true, 'data' => $comentarios]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener comentarios']);
    }

    exit;
}

    public function listarComentariosActivos()
    {
        header('Content-Type: application/json');
        try {
            $comentarios = $this->comentariosModel->obtenerComentariosActivos();
            echo json_encode(['success' => true, 'data' => $comentarios]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener comentarios']);
        }
    }

    public function updateComentario()
    {
        // Leer datos JSON enviados por POST
        $input = json_decode(file_get_contents("php://input"), true);

        // Validar que venga la información necesaria
        if (!isset($input['id'], $input['comentario'], $input['calificacion'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Datos incompletos para actualizar comentario.'
            ]);
            return;
        }

        // Asignar datos al modelo
        $this->comentariosModel->id = $input['id'];
        $this->comentariosModel->comentario = $input['comentario'];
        $this->comentariosModel->calificacion = $input['calificacion'];

        // Ejecutar actualización
        if ($this->comentariosModel->update()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el comentario.'
            ]);
        }
    }

       public function deleteComentario()
    {
        session_start();
        header('Content-Type: application/json'); // ✅ Asegura siempre el header JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $comentarioId = $input['id'] ?? null;
        $usuarioId = $_SESSION['user_id'] ?? null;

        if (!$comentarioId || !$usuarioId) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos para eliminar el comentario']);
        exit; // ✅ Termina aquí
        }

        $comentarioModel = new ComentariosModel($this->conn);

        $exito = $comentarioModel->deleteByUser($comentarioId, $usuarioId);

        echo json_encode([
            'success' => $exito,
            'message' => $exito ? 'Comentario eliminado correctamente' : 'No se pudo eliminar el comentario'
        ]);
        
    }

    public function checkComentario()
    {
        header('Content-Type: application/json');

        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        $productoId = isset($_GET['producto_id']) ? $_GET['producto_id'] : null;

        if (!$userId || !$productoId) {
            echo json_encode([
                'success' => false,
                'message' => 'Parámetros inválidos.'
            ]);
            return;
        }

        $existe = $this->comentariosModel->existeComentario($userId, $productoId);

        echo json_encode([
            'success' => true,
            'exists' => $existe
        ]);
    }

    public function obtenerComentariosActivos()
    {
        try {
            error_log("Iniciando obtención de comentarios activos."); // Log al inicio

            $comentarios = $this->comentariosModel->getActiveComments();

            error_log("Comentarios obtenidos: " . print_r($comentarios, true)); // Log con el contenido

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $comentarios
            ]);

            error_log("Respuesta JSON enviada correctamente.");
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');

            error_log("Error al obtener comentarios activos: " . $e->getMessage());

            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener los comentarios activos',
                'error' => $e->getMessage()
            ]);
        }
    }




    //para la parte del admin
    public function obtenerTodo()
    {
        try {
            $comentarios = $this->comentariosModel->getAll();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $comentarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener los comentarios',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function create()
    {
            header('Content-Type: application/json');  // <-- aquí, al inicio

        try {
            error_log("Acción: create comentario");

            // Obtener el JSON enviado desde el cliente
            $data = json_decode(file_get_contents("php://input"), true);

            // Validar que los datos obligatorios estén presentes
            if (
                empty($data['user_id']) ||
                empty($data['producto_id']) ||
                empty($data['comentario']) ||
                !isset($data['calificacion'])
            ) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos incompletos para crear el comentario.'
                ]);
                return;
            }

            // Verificar si el usuario ya comentó este producto
            if ($this->comentariosModel->existeComentario($data['user_id'], $data['producto_id'])) {
                http_response_code(409); // Conflict
                echo json_encode([
                    'success' => false,
                    'message' => 'Solo puedes hacer un comentario por producto.'
                ]);
                return;
            }

            // Asignar los valores al modelo
            $this->comentariosModel->usuario_id = $data['user_id'];
            $this->comentariosModel->producto_id = $data['producto_id'];
            $this->comentariosModel->comentario = $data['comentario'];
            $this->comentariosModel->calificacion = $data['calificacion'];
            $this->comentariosModel->estado = 1; // activo por defecto

            if ($this->comentariosModel->create()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Comentario creado exitosamente.'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'No se pudo crear el comentario.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al crear comentario: " . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Ocurrió un error al crear el comentario.',
                'error' => $e->getMessage()
            ]);
        }
    }




    public function updateEstadoComentario()
    {
        // Leemos el JSON que envía el fetch
        $input = json_decode(file_get_contents('php://input'), true);

        // Log para depuración (temporal)
        error_log("Datos recibidos: " . print_r($input, true));

        $id = $input['id'] ?? null;
        $estado = $input['estado'] ?? null;

        header('Content-Type: application/json');

        if (!$id || !isset($estado)) {
            error_log("Faltan datos: id=$id estado=$estado");
            echo json_encode(['success' => false, 'message' => 'Faltan datos']);
            return;
        }

        // Actualizamos el estado
        $result = $this->comentariosModel->updateEstado($id, $estado);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            error_log("Error al actualizar estado en el modelo para id=$id estado=$estado");
            echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
        }
    }
}

// Instanciar el controlador y procesar la solicitud
$controller = new ComentariosController();
$controller->processRequest();
