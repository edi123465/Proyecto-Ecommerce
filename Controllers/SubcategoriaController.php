<?php
class SubcategoriaController
{

    private $conn;
    private $table = 'Subcategorias';
    private $subcategoriaModel;
    private $categoriaModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->subcategoriaModel = new SubcategoriaModel($this->conn);
    }

    public function obtenerSubCategorias()
    {
        error_log("Llamando a obtenerSubCategorias");  // Esto debe aparecer en el log

        header('Content-Type: application/json');

        $SubcategoriasModel = new SubcategoriaModel($this->conn);

        try {
            $result = $SubcategoriasModel->getAll();

            // Logueamos el resultado
            error_log("Subcategorías obtenidas: " . print_r($result, true));

            if ($result && count($result) > 0) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron categorías.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en obtenerSubCategorias: " . $e->getMessage());

            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener las categorías: ' . $e->getMessage()
            ]);
        }
    }

    // Función para obtener las categorías y devolverlas como JSON
    function obtenerCategorias()
    {
        // Crear una instancia del modelo de Subcategorías
        $subcategoriaModel = new SubcategoriaModel($this->conn);

        // Llamar al método que obtiene las categorías
        $categorias = $subcategoriaModel->getCategorias();

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



    public function insertarSubcategoria()
    {
        // Verificar que el método de solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del cuerpo de la solicitud (suponiendo que los datos se envían como JSON)
            $data = json_decode(file_get_contents("php://input"), true);

            // Verificar si los datos necesarios están presentes
            if (
                isset($data['nombre']) &&
                isset($data['descripcion']) &&
                isset($data['categoria_id']) &&
                isset($data['isActive'])
            ) {

                // Crear una nueva instancia del modelo Subcategoría
                $subcategoriaModel = new SubcategoriaModel($this->conn);

                // Llamar al método insert del modelo para insertar la nueva subcategoría
                $result = $subcategoriaModel->insert($data);

                // Verificar si la inserción fue exitosa
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Subcategoría creada exitosamente.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al crear la subcategoría.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Faltan datos necesarios para crear la subcategoría.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método de solicitud no permitido. Utilice POST.'
            ]);
        }
    }

    function obtenerSubcategoriasPorId()
    {
        // Verificar si se pasó el parámetro 'id' a través de la URL o de la solicitud
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            // Crear una instancia del modelo Subcategoría
            $subcategoriaModel = new SubcategoriaModel($this->conn);

            // Obtener los datos de la subcategoría por ID
            $subcategoria = $subcategoriaModel->getById($id);

            if ($subcategoria) {
                // Retornar los datos en formato JSON
                echo json_encode([
                    'status' => 'success',
                    'subcategoria' => $subcategoria
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Subcategoría no encontrada.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de subcategoría no proporcionado.'
            ]);
        }
    }

    public function editarSubcategoria()
    {
        // Verificar si los datos han sido enviados a través de POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);

            // Verificar que todos los campos necesarios estén presentes
            if (
                isset($data['id']) && isset($data['nombre']) &&
                isset($data['descripcion']) && isset($data['categoriaId']) &&
                isset($data['isActive'])
            ) {
                // Crear una instancia del modelo Subcategoria
                $subcategoriaModel = new SubcategoriaModel($this->conn);

                // Crear un array con los nombres de campo que espera el modelo
                $data['nombrSubcategoria'] = $data['nombre'];
                $data['descripcionSubcategoria'] = $data['descripcion'];
                $data['categoria_id'] = $data['categoriaId'];
                $data['isActive'] = (bool)$data['isActive']; // Asegurarse de que 'isActive' sea booleano

                // Llamar al método de actualización del modelo y pasarle los datos
                $result = $subcategoriaModel->update($data);

                // Enviar una respuesta al frontend
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Subcategoría actualizada exitosamente']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al actualizar la subcategoría']);
                }
            } else {
                // Si los datos no son completos, enviar error
                echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            }
        } else {
            // Si no se recibió una solicitud POST
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
        }
    }

    // Función para manejar la eliminación de subcategoría
    public function eliminarSubcategoria()
    {
        // Verificar si se pasó el ID de la subcategoría
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Llamar al método delete del modelo
            if ($this->subcategoriaModel->delete($id)) {
                // Si la eliminación es exitosa, responder con un estado de éxito
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Subcategoría eliminada exitosamente.'
                ]);
            } else {
                // Si hubo un error al eliminar, responder con un estado de error
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo eliminar la subcategoría.'
                ]);
            }
        } else {
            // Si no se pasa el ID, responder con un error
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de subcategoría no proporcionado.'
            ]);
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
        case 'obtenerSubcategorias':
            $SubCategoriaController = new SubcategoriaController($db);
            $SubCategoriaController->obtenerSubcategorias();
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
    }
} else {
    echo json_encode(['error' => 'Parámetro action faltante']);
}
