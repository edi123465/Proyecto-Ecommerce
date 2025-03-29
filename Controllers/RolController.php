<?php

require_once __DIR__ . '/../Models/RolModel.php';
require_once __DIR__ . '/../Config/db.php';

class RolController
{

    private $conn;
    private $table = 'Roles';
    private $rolModel; // Modelo de Productos

    public function __construct($db)
    {
        $database = new Database1();
        $this->conn = $database->getConnection(); // Asigna la conexión a $this->conn
        $this->rolModel = new RolModel($this->conn); // Usa $this->conn para el modelo
    }

    public function read()
    {
        try {
            // Log para verificar la acción recibida
            error_log("Action: " . $_GET['action']);

            // Llamar al método del modelo para obtener los roles
            $roles = $this->rolModel->obtenerRoles();

            // Verifica los datos recibidos
            error_log("Roles obtenidos: " . json_encode($roles));

            // Devuelve la respuesta como JSON
            if (!empty($roles)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $roles
                ]);
            } else {
                // Caso sin datos (no hay roles)
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'empty',
                    'message' => 'No hay roles disponibles.'
                ]);
            }
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al obtener roles: " . $e->getMessage());

            // Responder con un error en formato JSON
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Ocurrió un error al procesar la solicitud.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getRoles()
    {
        header('Content-Type: application/json');
        try {
            // Obtener los roles
            $roles = $this->rolModel->obtenerRol(); // Método del modelo que obtiene los roles
    
            // Verificar y registrar los datos obtenidos
            if (empty($roles)) {
                error_log("No se encontraron roles en la base de datos.");
            } else {
                error_log("Roles obtenidos: " . print_r($roles, true));
            }
    
            // Limpiar el búfer de salida antes de enviar JSON
            ob_clean();
            
            // Enviar la respuesta JSON con los roles
            echo json_encode(['roles' => $roles]);
            exit;
        } catch (Exception $e) {
            // Registrar el error en el log
            error_log("Error al obtener roles: " . $e->getMessage());
    
            // Limpiar el búfer antes de enviar error
            ob_clean();
    
            // Devolver un mensaje de error en formato JSON
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    


    public function create($data)
    {
        header('Content-Type: application/json');

        try {
            // Verificar si se reciben datos
            error_log("Iniciando creación de rol");

            // Obtener los datos JSON enviados
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data) {
                error_log("No se recibieron datos válidos");
                throw new Exception("No se recibieron datos válidos.");
            } else {
                error_log("Datos recibidos: " . print_r($data, true)); // Esto te mostrará los datos en el log
            }

            // Validar que los datos existan en el formulario
            if (!isset($data['RolName'], $data['RolDescription'])) {
                error_log("Faltan datos requeridos");
                throw new Exception("Faltan datos requeridos para crear el rol.");
            }

            // Verificar y asignar valor a IsActive si no está presente
            $isActive = isset($data['IsActive']) ? ($data['IsActive'] === 'on' ? 1 : 0) : 0; // Valor predeterminado 0

            // Obtener la fecha y hora actual
            $currentDate = date('Y-m-d H:i:s'); // Fecha actual en formato adecuado para SQL Server
            error_log("Fecha actual: " . $currentDate); // Verificar la fecha

            // Preparar los datos para el modelo
            $dataToInsert = [
                'RolName' => $data['RolName'],
                'RolDescription' => $data['RolDescription'],
                'IsActive' => $isActive, // Usar el valor asignado para IsActive
                'CreatedAt' => $currentDate, // Agregar la fecha actual
            ];

            // Verificar los datos antes de insertarlos
            error_log("Datos a insertar: " . print_r($dataToInsert, true));

            // Llamar al modelo para crear el rol
            $result = $this->rolModel->crearRol($dataToInsert);

            if ($result) {
                // Si la creación fue exitosa
                return json_encode(['success' => true, 'message' => 'Rol creado con éxito']);
            } else {
                // Si la creación no fue exitosa
                throw new Exception("No se pudo crear el rol en la base de datos.");
            }
        } catch (Exception $e) {
            // Capturar cualquier excepción y loguear el error
            error_log("Error en la creación del rol: " . $e->getMessage());

            // Retornar una respuesta JSON con el error
            return json_encode(['success' => false, 'message' => 'Error al crear el rol: ' . $e->getMessage()]);
        }
    }

    // Función para manejar la edición del rol
    public function edit($data)
    {
        ob_clean();

        $data = json_decode(file_get_contents('php://input'), true); // Obtener los datos recibidos en formato JSON

        if ($data && isset($data['roleId'])) {
            $id = $data['roleId']; // Obtener el ID desde los datos
            $roleName = $data['roleName'];
            $roleDescription = $data['roleDescription'];
            $isActive = $data['isActive'];

            // Llamar al método para actualizar los datos del rol
            $result = $this->rolModel->actualizarRol($id, $roleName, $roleDescription, $isActive);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol actualizado con éxito.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el rol.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Datos inválidos o faltantes.'
            ]);
        }
        exit();  // Asegúrate de salir después de enviar la respuesta
    }

    // Función para manejar la eliminación de un rol
    public function delete($id)
    {
        // Llamar al modelo para eliminar el rol
        $result = $this->rolModel->eliminarRol($id);

        if ($result) {
            // Si la eliminación fue exitosa, devolver un mensaje de éxito
            echo json_encode([
                'success' => true,
                'message' => 'Rol eliminado con éxito.'
            ]);
            exit(); // Asegúrate de terminar la ejecución después de enviar el JSON
        } else {
            // Si hubo un error en la eliminación
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar el rol.'
            ]);
        }
    }
}

if (isset($_GET['action'])) {
    
    $action = $_GET['action'];  // Obtener la acción de la URL
    // Dependiendo de la acción, ejecutamos diferentes métodos para manejar los roles
    switch ($action) {
        case 'read':
            $db = new Database1(); // O como sea que estés instanciando tu conexión
            // Si la acción es 'read', llama al método que obtiene todos los roles
            $controller = new RolController($db); // Suponiendo que $db es la conexión a la base de datos
            $controller->read();  // Método para obtener todos los roles
            break;
        case 'getRoles':
            $db = new Database1(); // Instancia de la base de datos
            $controller = new RolController($db);

            // Llamar al método que obtiene los roles
            $roles = $controller->getRoles(); // Este método debe devolver un array con los roles
            // Devolver los roles como JSON
            echo json_encode([
                'success' => true,
                'data' => $roles
            ]);
            break;

        case 'create':
            $db = new Database1();  // Instanciación de la base de datos

            // Si la acción es 'create', verificar si los datos están disponibles
            $data = json_decode(file_get_contents('php://input'), true);  // Obtener los datos recibidos en formato JSON
            if ($data) {
                $controller = new RolController($db);  // Crear instancia del controlador
                $controller->create($data);  // Llamar al método para crear un rol
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol creado con éxito.'
                ]);
            } else {
                // Si no se reciben datos válidos, retornar un error
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos inválidos o faltantes.'
                ]);
            }
            break;

        case 'edit':
            // Obtener los datos recibidos en formato JSON
            $data = json_decode(file_get_contents('php://input'), true);  // Leer datos JSON

            // Verificar que los datos están presentes
            if ($data && isset($data['roleId'])) {
                $db = new Database1();  // Instanciación de la base de datos
                $controller = new RolController($db);  // Crear instancia del controlador
                $controller->edit($data);  // Llamar al método para actualizar el rol

                // Respuesta exitosa
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol actualizado con éxito.'
                ]);
            } else {
                // Si los datos no están disponibles o son inválidos
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos inválidos o faltantes.'
                ]);
            }
            break;

        case 'delete':
            // Verificar que se pasa el ID del rol a eliminar
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $rolId = $_GET['id'];

                // Conectar a la base de datos
                $db = new Database1();
                $controller = new RolController($db); // Instancia del controlador

                // Llamar al método para eliminar el rol
                $controller->delete($rolId);

                echo json_encode([
                    'success' => true,
                    'message' => 'Rol eliminado con éxito.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID del rol no proporcionado o no válido.'
                ]);
            }
            break;

        default:

            // Si la acción no es válida, retornar un error
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida.'
            ]);
            break;
    }
} else {
    // Si no se pasa la acción, retornar un error
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida o no especificada.'
    ]);
}
