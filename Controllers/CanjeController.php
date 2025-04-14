<?php
require_once __DIR__ . '/../Config/db.php'; // Asegúrate de requerir el modelo
require_once __DIR__ . '/../Models/CanjeModel.php';

class CanjeController
{
    private $conn;
    private $model;

    public function __construct($db)
    {
        // Crear una instancia de la base de datos
        $db = new Database1();
        $conn = $db->getConnection();  // Obtener la conexión PDO
        $this->model = new CanjeModel($conn);  // Pasar la conexión PDO al modelo
    }

    // Listar opciones canjeables
    public function listar()
    {
        // Obtener los canjeables desde el modelo
        $opciones = $this->model->getCanjeables();

        // Agregar log para verificar los datos obtenidos
        error_log("Datos obtenidos del modelo: " . print_r($opciones, true));  // Escribir en los logs

        // Ver los datos antes de enviarlos como JSON
        echo json_encode($opciones);
    }
    // Método para obtener los canjeables activos (para la parte del cliente)
    public function obtenerCanjeablesTienda()
    {
        // Obtener los canjeables activos desde el modelo
        $canjeables = $this->model->getCanjeablesTienda();

        // Verificar si se obtuvieron resultados
        if ($canjeables) {
            // Responder con los datos de los canjeables en formato JSON
            echo json_encode(['success' => true, 'canjeables' => $canjeables]);
        } else {
            // Responder con mensaje de error si no hay canjeables activos
            echo json_encode(['success' => false, 'message' => 'No hay canjeables activos disponibles.']);
        }
    }
    public function obtenerCanjePorId()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Llamamos al método getCanjeableById
            $canje = $this->model->getCanjeableById($id);

            // Devolvemos la respuesta como JSON
            echo json_encode($canje);
        } else {
            // Si no se pasa un ID, devolvemos un error
            echo json_encode(['message' => 'ID no proporcionado']);
        }
    }

    // Método para obtener los productos como JSON
    public function obtenerProductos()
    {
        header('Content-Type: application/json; charset=UTF-8');

        // Obtener los productos desde el modelo
        $productos = $this->model->obtenerProductos();

        // Log para ver los datos que traemos
        error_log('Productos obtenidos: ' . print_r($productos, true));  // Registra los productos en el log

        // Devolver los productos como JSON
        echo json_encode($productos);
    }

    // Acción para obtener los detalles del producto
    public function obtenerProductoDetalles()
    {
        if (isset($_GET['id'])) {
            $productoId = $_GET['id'];

            // Obtener los detalles del producto desde el modelo
            $producto = $this->model->obtenerProductoPorId($productoId);

            // Verificar si el producto fue encontrado
            if ($producto) {
                // Devolver los detalles del producto en formato JSON
                echo json_encode($producto);
            } else {
                // Si no se encuentra el producto, devolver un error
                echo json_encode(['error' => 'Producto no encontrado']);
            }
        } else {
            echo json_encode(['error' => 'ID de producto no proporcionado']);
        }
    }
    public function crearCanjeable()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger los datos del formulario
            $data = [
                'nombre' => $_POST['nombre'] ?? null,
                'descripcion' => $_POST['descripcion'] ?? null,
                'tipo' => $_POST['tipo'] ?? null,
                'valor_descuento' => $_POST['valor_descuento'] ?? null,
                'puntos_necesarios' => $_POST['puntos_necesarios'] ?? null,
                'producto_id' => $_POST['producto_id'] ?? null,
                'estado' => $_POST['estado'] ?? null,
            ];

            // 📝 Log para depurar
            error_log("Datos recibidos para crear canjeable: " . print_r($_POST, true));

            // Verificar si el canjeable ya existe
            if ($this->model->verificarCanjeableExistente($data['nombre'])) {
                echo json_encode(["success" => false, "message" => "Ya existe un canjeable con ese nombre."]);
                return;
            }

            // 📦 Manejo de la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = uniqid() . '_' . $_FILES['imagen']['name'];
                $rutaDestino = 'assets/imagenesMilogar/canjeables/' . $nombreArchivo;

                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array(strtolower($extension), $permitidas)) {
                    error_log("Formato de imagen no permitido. Extensión: " . strtolower($extension));
                    echo json_encode(["success" => false, "message" => "Formato de imagen no permitido."]);
                    return;
                }

                error_log("Intentando mover el archivo a la ruta: " . $rutaDestino);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    // Guardamos solo el nombre del archivo, no la URL completa
                    $data['imagen'] = $nombreArchivo;  // Guardamos solo el nombre de la imagen
                    error_log("Imagen guardada exitosamente: " . $nombreArchivo);
                } else {
                    error_log("Error al mover el archivo.");
                    echo json_encode(["success" => false, "message" => "Error al guardar la imagen."]);
                    return;
                }
            } elseif (!empty($_POST['imagen'])) {
                // Usar la imagen ya existente (URL) y guardar solo el nombre del archivo
                $nombreImagen = basename($_POST['imagen']);  // Extraemos solo el nombre del archivo
                $data['imagen'] = $nombreImagen;
                error_log("Usando imagen proporcionada: " . $_POST['imagen']);
            } else {
                // No se proporcionó imagen
                $data['imagen'] = null;
                error_log("No se proporcionó imagen.");
            }

            // Log antes de guardar en base de datos
            error_log("Guardando canjeable con los siguientes datos: " . print_r($data, true));

            // Guardar en base de datos
            $resultado = $this->model->createCanjeable($data);

            // Enviar respuesta en formato JSON con éxito o error
            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Canjeable creado exitosamente."]);
            } else {
                error_log("Error al guardar el canjeable en la base de datos.");
                echo json_encode(["success" => false, "message" => "Error al crear el canjeable."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Método de solicitud no permitido."]);
        }
    }

    public function editarCanjeable() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['producto_id'])) {
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
        
                // Obtener el canjeable actual
                $canjeableActual = $this->model->getCanjeableById($id);
        
                // Verificar si cambiaron nombre o descripción antes de comprobar duplicado
                if (
                    ($nombre !== $canjeableActual['nombre']) ||
                    ($descripcion !== $canjeableActual['descripcion'])
                ) {
                    // Solo si cambiaron, se hace la verificación
                    if ($this->model->verificarCanjeableExistente($nombre, $descripcion, $id)) {
                        echo json_encode(['success' => false, 'message' => 'Ya existe un canjeable con el mismo nombre y descripción']);
                        return;
                    }
                }
        
                $imagenAnterior = basename($_POST['imagen_anterior']); 
        
                $data = [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'tipo' => $_POST['tipo'],
                    'valor_descuento' => $_POST['valor_descuento'],
                    'puntos_necesarios' => $_POST['puntos_necesarios'],
                    'producto_id' => $_POST['producto_id'],
                    'estado' => $_POST['estado'],
                    'imagen' => isset($_FILES['imagen']) ? $this->uploadImage($_FILES['imagen']) : $imagenAnterior,
                ];
        
                $result = $this->model->updateCanjeable($id, $data);
        
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Canjeable actualizado exitosamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el canjeable']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Faltan datos del producto']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Solicitud no válida']);
        }
        
    }
    
    // Función para manejar la carga de imágenes
    private function uploadImage($image) {
        // Definir el directorio donde se guardarán las imágenes
        $uploadDir = 'assets/imagenesMilogar/Canjes/';
        $uploadFile = $uploadDir . basename($image['name']);

        // Verificar si el archivo es una imagen válida
        if (getimagesize($image['tmp_name'])) {
            // Mover el archivo cargado al directorio de destino
            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                return basename($image['name']); // Retorna el nombre del archivo subido
            } else {
                // Si hubo un error en la carga de la imagen
                return false;
            }
        } else {
            return false; // Si no es una imagen válida
        }
    }
    
    public function eliminarCanjeable()
{
    // Verificar si la solicitud es GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Obtener el ID del canjeable desde la solicitud
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Llamar al método del modelo para eliminar el canjeable
            $result = $this->model->deleteCanjeable($id);
    
            if ($result) {
                // Responder con un mensaje de éxito
                echo json_encode(['success' => true, 'message' => 'Canjeable eliminado exitosamente']);
            } else {
                // Responder con un mensaje de error
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el canjeable']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos del canjeable']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Solicitud no válida']);
    }
}

}

// Obtener la acción que se pasa en la URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Crear la instancia de la base de datos
require_once __DIR__ . '/../Config/db.php'; // Asegúrate de requerir el modelo
$db = new Database1();
$conn = $db->getConnection();  // Obtener la conexión PDO

// Crear la instancia del controlador de canjeables
$canjeController = new CanjeController($conn);

// Acción para manejar la obtención de los canjeables
switch ($action) {
    case 'obtenerCanjes':
        // Llamar al método listar del controlador
        $canjeController->listar();
        break;
    case 'obtenerCanjeablesTienda':
        $canjeController->obtenerCanjeablesTienda();
        break;
    case 'getProductos':
        $canjeController->obtenerProductos();
        break;
    case 'crearCanjeable':
        $canjeController->crearCanjeable();
        break;
    case 'obtenerProductoDetalles':
        $canjeController->obtenerProductoDetalles();
        break;
    case 'obtenerCanjeporId':
        $canjeController->obtenerCanjePorId();
        break;
    case 'editarCanjeable':
        $canjeController->editarCanjeable();  
        break;
    case 'eliminarCanjeable':
        $canjeController->eliminarCanjeable();
        break;
    // Puedes agregar más casos según sea necesario, por ejemplo, para crear, actualizar o eliminar canjes
    default:
        echo json_encode(["message" => "Acción no válida"]);
        break;
}
