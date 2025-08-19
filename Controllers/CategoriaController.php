<?php
require_once "../Config/db.php";
require_once "../Models/CategoriaModel.php";
class CategoriaController
{

    private $conn;
    private $table = 'categorias';
    private $categoriaModel;
    private $subcategoriaModel;

    public function __construct($db)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $this->conn = $db;
        $this->categoriaModel = new CategoriaModel($this->conn);
    }

public function obtenerCategorias()
    {
        header('Content-Type: application/json');
    
        $categoriasModel = new CategoriaModel($this->conn);
    
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
    
        // LOG para depuración
        error_log("Parámetro 'search': " . $search);
        error_log("Parámetro 'limit': " . $limit);
        error_log("Parámetro 'page': " . $page);
        error_log("Parámetro 'offset': " . $offset);
    
        try {
            $result = $categoriasModel->getAll($search, $limit, $offset);
            $totalCategorias = $categoriasModel->countTotal($search);
            $totalPages = ceil($totalCategorias / $limit);
    
            error_log("Total de categorías: " . $totalCategorias);
            error_log("Total de páginas: " . $totalPages);
    
            if ($result && count($result) > 0) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $result,
                    'totalPages' => $totalPages,
                    'currentPage' => $page
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron categorías.'
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al obtener las categorías: ' . $e->getMessage()
            ]);
        }
    }
    
    // Acción para obtener una categoría por ID
    public function obtenerCategoriaPorId()
    {
        // Verificamos si el parámetro 'id' está presente en la solicitud
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            // Llamamos al método 'getById' del modelo
            $categoria = $this->categoriaModel->getById($id);

            // Agregamos error_log para depurar
            error_log("Datos obtenidos de la categoría: " . print_r($categoria, true));

            if ($categoria) {
                // Si encontramos la categoría, retornamos el resultado en formato JSON
                echo json_encode([
                    'status' => 'success',
                    'data' => $categoria
                ]);
            } else {
                // Si no se encuentra la categoría, retornamos un error
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Categoría no encontrada'
                ]);
            }
        } else {
            // Si el parámetro 'id' no está presente
            echo json_encode([
                'status' => 'error',
                'message' => 'El parámetro id es necesario'
            ]);
        }
    }

    public function insertarCategoria()
    {
        // Verificar si todos los campos están presentes en la solicitud
        if (
            isset(
                $_POST['categoryName'],
                $_POST['categoryDescription'],
                $_POST['categoryStatus']
            )
        ) {
            // Procesar la imagen si existe
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                // Configuración para el almacenamiento de la imagen
                $imagen = $_FILES['imagen'];
                $imagenNombre = time() . '_' . $imagen['name'];  // Nombre único para evitar sobrescribir archivos
                // Ruta completa donde se guardará la imagen en el servidor
                $imagenRutaCompleta = $_SERVER['DOCUMENT_ROOT'] . '/Milogar/assets/imagenesMilogar/Categorias/' . $imagenNombre;

                // Guardar la imagen en el servidor
                move_uploaded_file($imagen['tmp_name'], $imagenRutaCompleta);

                // Solo guardar el nombre del archivo (sin la ruta completa) en la base de datos
                $imagenNombreEnBD = $imagenNombre;
            } else {
                $imagenNombreEnBD = null; // Si no hay imagen, dejar en null
            }

            // Log de los datos que se intentan insertar
            error_log("Datos a insertar: ");
            error_log("categoryName: " . $_POST['categoryName']);
            error_log("categoryDescription: " . $_POST['categoryDescription']);
            error_log("categoryStatus: " . $_POST['categoryStatus']);
            error_log("imagenRuta: " . $imagenNombreEnBD);

            // Crear una instancia del modelo y usar la conexión que ya está pasada
            $categoriaModel = new CategoriaModel($this->conn);
            $resultado = $categoriaModel->create([
                'nombre' => $_POST['categoryName'],
                'descripcion' => $_POST['categoryDescription'],
                'isActive' => $_POST['categoryStatus'],
                'imagen' => $imagenNombreEnBD
            ]);

            // Responder al cliente
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Categoría agregada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar la categoría.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
        }
    }

    // Acción para actualizar la categoría
    public function actualizarCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capturamos los datos del formulario
            $id = $_POST['id'];
            $nombre = $_POST['nombreCategoria'];
            $descripcion = $_POST['descripcionCategoria'];
            $isActive = $_POST['isActive'];

            // Obtener la imagen actual de la categoría si no se sube una nueva
            $imagen = null;
            if (isset($_FILES['imagenCategoria']) && $_FILES['imagenCategoria']['error'] == 0) {
                // Si se sube una nueva imagen, procesarla
                $imagen = $_FILES['imagenCategoria']['name'];
                $targetPath = '../assets/imagenesMilogar/Categorias/' . $imagen;
                move_uploaded_file($_FILES['imagenCategoria']['tmp_name'], $targetPath);
            } else {
                // Si no se sube una nueva imagen, obtener la imagen actual (esto debe provenir de la base de datos)
                // Puedes buscar la imagen actual en la base de datos si tienes el ID de la categoría
                $categoria = $this->categoriaModel->getById($id); // Asegúrate de tener este método en el modelo
                $imagen = $categoria['imagen']; // Se conserva la imagen actual en la base de datos
            }

            // Crear un arreglo con los datos
            $data = [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'isActive' => $isActive,
                'imagen' => $imagen
            ];

            // Usamos el modelo para actualizar la categoría
            if ($this->categoriaModel->update($id, $data)) {
                // Si la actualización fue exitosa, enviamos una respuesta de éxito
                echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada correctamente.']);
            } else {
                // Si hubo un error, enviamos una respuesta de error
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la categoría.']);
            }
        } else {
            // Si no es una solicitud POST, mostramos un error
            echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida.']);
        }
    }



    // Acción para eliminar la categoría
    public function eliminarCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificamos si se recibió el ID a eliminar
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];

                // Llamamos al modelo para eliminar la categoría
                if ($this->categoriaModel->delete($id)) {
                    // Si la eliminación fue exitosa, enviamos una respuesta de éxito
                    echo json_encode(['status' => 'success', 'message' => 'Categoría eliminada correctamente.']);
                } else {
                    // Si hubo un error, enviamos una respuesta de error
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la categoría.']);
                }
            } else {
                // Si no se recibió un ID válido, enviamos una respuesta de error
                echo json_encode(['status' => 'error', 'message' => 'ID no válido o no proporcionado.']);
            }
        } else {
            // Si no es una solicitud POST, respondemos con un error
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
        }
    }
}

// Depuración: Imprimir los datos recibidos
error_log("Parámetros GET recibidos: " . print_r($_GET, true));

if (isset($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'default'; // Valor por defecto si no se pasa acción

    $database = new Database1();
    $db = $database->getConnection();

    $CategoriaModel = new CategoriaModel($db);

    switch ($action) {
            //casos para obtener informacion
        case 'obtenerCategorias':
            $CategoriaController = new CategoriaController($db);
            $CategoriaController->obtenerCategorias();
            break;
        case 'createCategory':
            $CategoriaController = new CategoriaController($db);
            $CategoriaController->insertarCategoria();
            break;
        case 'obtenerCategoriaPorId':
            $controller = new CategoriaController($db);
            $controller->obtenerCategoriaPorId();
            break;
        case 'editarCategoria':
            $controller = new CategoriaController($db);
            $controller->actualizarCategoria();
            break;
        case 'eliminarCategoria':
            $controller = new CategoriaController($db);
            $controller->eliminarCategoria();
            break;
    }
} else {
    echo json_encode(['error' => 'Parámetro action faltante']);
}
