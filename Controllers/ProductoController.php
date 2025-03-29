<?php
require_once __DIR__ . '/../Models/ProductoModel.php';
require_once __DIR__ . '/../Models/CategoriaModel.php'; // Asegúrate de que la ruta es correcta
require_once __DIR__ . '/../Config/db.php'; // Incluye la configuración de la base de datos

// Asegúrate de que la ruta es correcta
ini_set('display_errors', 1);
error_reporting(E_ALL);
class ProductoController
{

    private $model;
    private $conn;
    // Constructor para inicializar el modelo
    public function __construct($db)
    {
        $database = new Database1();
        $this->conn = $database->getConnection(); // Asigna la conexión a $this->conn
        $this->model = new ProductoModel($db); // Pasar la conexión al modelo
    }

    public function insertarProducto()
    {
        // Verificar si todos los campos están presentes en la solicitud
        if (
            isset(
                $_POST['nombreProducto'],
                $_POST['descripcionProducto'],
                $_POST['categoria'],
                $_POST['subcategoria'],
                $_POST['precio'],
                $_POST['precio_1'],
                $_POST['precio_2'],
                $_POST['precio_3'],
                $_POST['precio_4'],
                $_POST['stock'],
                $_POST['codigo_barras'],
                $_POST['isActive'],
                $_POST['is_promocion'],
                $_POST['descuento']
            )
        ) {
            // Procesar la imagen si existe
            // Procesar la imagen si existe
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                // Configuración para el almacenamiento de la imagen
                $imagen = $_FILES['imagen'];
                $imagenNombre = time() . '_' . $imagen['name'];  // Nombre único para evitar sobrescribir archivos
                // Ruta completa donde se guardará la imagen en el servidor
                $imagenRutaCompleta = '../assets/imagenesMilogar/productos/' . $imagenNombre;

                // Guardar la imagen en el servidor
                move_uploaded_file($imagen['tmp_name'], $imagenRutaCompleta);

                // Solo guardar el nombre del archivo (sin la ruta completa) en la base de datos
                $imagenNombreEnBD = $imagenNombre;
            } else {
                $imagenNombreEnBD = null; // Si no hay imagen, dejar en null
            }


            // Log de los datos que se intentan insertar
            error_log("Datos a insertar: ");
            error_log("nombreProducto: " . $_POST['nombreProducto']);
            error_log("descripcionProducto: " . $_POST['descripcionProducto']);
            error_log("categoria: " . $_POST['categoria']);
            error_log("subcategoria: " . $_POST['subcategoria']);
            error_log("precio: " . $_POST['precio']);
            error_log("precio_1: " . $_POST['precio_1']);
            error_log("precio_2: " . $_POST['precio_2']);
            error_log("precio_3: " . $_POST['precio_3']);
            error_log("precio_4: " . $_POST['precio_4']);
            error_log("stock: " . $_POST['stock']);
            error_log("codigo_barras: " . $_POST['codigo_barras']);
            error_log("isActive: " . $_POST['isActive']);
            error_log("is_promocion: " . $_POST['is_promocion']);
            error_log("descuento: " . $_POST['descuento']);
            error_log("imagenRuta: " . $imagenNombreEnBD);

            // Crear una instancia del modelo y usar la conexión que ya está pasada
            $productoModel = new ProductoModel($this->conn);
            $resultado = $productoModel->insertarProducto(
                $_POST['nombreProducto'],
                $_POST['descripcionProducto'],
                $_POST['precio'],
                $_POST['precio_1'],
                $_POST['precio_2'],
                $_POST['precio_3'],
                $_POST['precio_4'],
                $_POST['stock'],
                $_POST['subcategoria'],
                $_POST['codigo_barras'],
                $imagenNombreEnBD,
                $_POST['isActive'],
                $_POST['is_promocion'],
                $_POST['descuento'],
                $_POST['categoria'],

            );

            // Responder al cliente
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado correctamente. XDXD']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el producto.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
        }
    }



    // Método para obtener un producto por su ID
    public function obtenerProductoPorId()
    {
        if (isset($_GET['id'])) {
            $idProducto = $_GET['id'];

            // Llamamos al método getById del modelo para obtener el producto
            $producto = $this->model->getById($idProducto);

            // Verificamos si el producto fue encontrado
            if ($producto) {
                // Si el producto existe, respondemos con los datos en formato JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $producto
                ]);
            } else {
                // Si no se encuentra el producto, respondemos con un mensaje de error
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Producto no encontrado'
                ]);
            }
        } else {
            // Si no se proporciona un ID, respondemos con un error
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'ID del producto no proporcionado'
            ]);
        }
    }

    public function productoporid()
    {
        $producto = $this->model->getById($this->conn);
        return $producto;
    }

    public function updateProducto($id)
    {
        // Verifica si se ha enviado una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura los datos enviados desde el formulario
            $data = [
                'nombreProducto' => $_POST['nombreProducto'] ?? '',
                'descripcionProducto' => $_POST['descripcionProducto'] ?? '',
                'precio' => floatval($_POST['precio'] ?? 0),
                'precio_1' => floatval($_POST['precio1'] ?? 0),
                'precio_2' => floatval($_POST['precio2'] ?? 0),
                'precio_3' => floatval($_POST['precio3'] ?? 0),
                'precio_4' => floatval($_POST['precio4'] ?? 0),
                'stock' => intval($_POST['stock'] ?? 0),
                'codBarras' => $_POST['codBarras'] ?? '',
                'categoria_id' => intval($_POST['categoria_id'] ?? 0),  // Captura el ID de la categoría
                'subcategoria_id' => intval($_POST['subcategoria_id'] ?? 0), // Captura el ID de la subcategoría
                'isActive' => ($_POST['isActive'] ?? '0') == '1' ? 1 : 0, // Asegura que es 1 o 0
                'isPromocion' => ($_POST['isPromocion'] ?? '0') == '1' ? 1 : 0, // Asegura que es 1 o 0

                'descuento' => floatval($_POST['descuento'] ?? 0),
            ];
            error_log("Valor de isPromocion: " . $_POST['isPromocion']);

            // Registro de depuración para verificar los datos recibidos
            error_log("Datos recibidos para actualizar el producto: " . print_r($data, true));

            // Recuperar la imagen actual desde la base de datos
            $productoActual = $this->model->getById($id);
            $imagenActual = $productoActual['imagen'] ?? null; // Si no hay imagen, será null

            // Manejo de la nueva imagen
            if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($_FILES['nueva_imagen']['name'], PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    // Renombrar la imagen para evitar conflictos
                    $nombreImagen = uniqid() . '-' . basename($_FILES['nueva_imagen']['name']);
                    $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . "/Milogar/assets/imagenesMilogar/productos/" . $nombreImagen;

                    if (move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $rutaDestino)) {
                        $data['imagen'] = $nombreImagen; // Asignar la nueva imagen
                    } else {
                        // Log de error si no se pudo mover la imagen
                        error_log("Error al mover la imagen: " . print_r($_FILES['nueva_imagen'], true));
                        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
                        exit;
                    }
                } else {
                    // Log de error si la imagen no es del tipo permitido
                    error_log("Tipo de imagen no permitido: " . $_FILES['nueva_imagen']['name']);
                    echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes JPG o PNG.']);
                    exit;
                }
            } else {
                // Si no hay nueva imagen, mantener la imagen anterior
                if ($imagenActual) {
                    $data['imagen'] = $imagenActual;
                }
            }

            // Intentar actualizar el producto
            if ($this->model->update($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
            } else {
                // Log de error si la actualización falla
                error_log("Error al actualizar el producto con ID: " . $id);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto.']);
            }
        }
    }

    // Método para eliminar un producto
    public function deleteProducto($id)
    {
        if ($this->model->delete($id)) {
            // Respuesta en formato JSON si la eliminación fue exitosa
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente.']);
        } else {
            // Respuesta en formato JSON si ocurrió un error
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto.']);
        }
        exit; // Asegurarse de que no se ejecute más código después de enviar la respuesta
    }


    public function obtenerTodosLosProductos()
    {
        // Establece el encabezado para indicar que la respuesta es JSON
        header('Content-Type: application/json');

        try {
            // Llama al modelo para obtener los productos en promoción
            $productos = $this->model->getAll();

            // Agregar un log para ver los datos que se están trayendo
            error_log("Todos los productos: " . print_r($productos, true)); // Imprime los datos en el log

            // Verifica si los productos fueron encontrados
            if (!empty($productos)) {
                // Envía los productos como JSON
                echo json_encode([
                    'status' => 'success',
                    'data' => $productos
                ]);
            } else {
                // Si no hay productos en promoción, envía un mensaje de error
                echo json_encode([
                    'status' => 'error',
                    'mensaje' => 'No se encontraron productos en promoción'
                ]);
            }
        } catch (Exception $e) {
            // Si ocurre un error, captura la excepción y muestra el mensaje de error
            error_log("Error al obtener productos en promoción: " . $e->getMessage()); // Loguea el error de la excepción
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Error al obtener los productos en promoción',
                'detalle' => $e->getMessage() // Muestra el mensaje de error de la excepción
            ]);
        }
    }

    //metodo para obtener los productos con promocion(Tienda virtual)
    public function obtenerProductosPromocion()
    {
        // Establece el encabezado para indicar que la respuesta es JSON
        header('Content-Type: application/json');

        try {
            // Llama al modelo para obtener los productos en promoción
            $productosPromocion = $this->model->read();

            // Agregar un log para ver los datos que se están trayendo
            error_log("Productos en promoción: " . print_r($productosPromocion, true)); // Imprime los datos en el log

            // Verifica si los productos fueron encontrados
            if (!empty($productosPromocion)) {
                // Envía los productos como JSON
                echo json_encode([
                    'status' => 'success',
                    'data' => $productosPromocion
                ]);
            } else {
                // Si no hay productos en promoción, envía un mensaje de error
                echo json_encode([
                    'status' => 'error',
                    'mensaje' => 'No se encontraron productos en promoción'
                ]);
            }
        } catch (Exception $e) {
            // Si ocurre un error, captura la excepción y muestra el mensaje de error
            error_log("Error al obtener productos en promoción: " . $e->getMessage()); // Loguea el error de la excepción
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Error al obtener los productos en promoción',
                'detalle' => $e->getMessage() // Muestra el mensaje de error de la excepción
            ]);
        }
    }


    // Método para obtener todos los productos con categorías y subcategorías
    public function getAllProductosConCategorias()
    {
        return $this->model->getAllProductosConCategorias();
    }


    //obtener los productos populares
    public function ProductosPopulares()
    {
        return $this->model->obtenerProductosPopulares();
    }

    //metodo para obtener los productos populares(Tienda virtual)
    public function obtenerProductosPopulares()
    {
        // Establece el encabezado para indicar que la respuesta es JSON
        header('Content-Type: application/json');

        try {
            // Llama al modelo para obtener los productos en promoción
            $productosPromocion = $this->model->obtenerProductosPopulares();

            // Agregar un log para ver los datos que se están trayendo
            error_log("Productos Populares: " . print_r($productosPromocion, true)); // Imprime los datos en el log

            // Verifica si los productos fueron encontrados
            if (!empty($productosPromocion)) {
                // Envía los productos como JSON
                echo json_encode([
                    'status' => 'success',
                    'data' => $productosPromocion
                ]);
            } else {
                // Si no hay productos en promoción, envía un mensaje de error
                echo json_encode([
                    'status' => 'error',
                    'mensaje' => 'No se encontraron productos en promoción'
                ]);
            }
        } catch (Exception $e) {
            // Si ocurre un error, captura la excepción y muestra el mensaje de error
            error_log("Error al obtener productos en promoción: " . $e->getMessage()); // Loguea el error de la excepción
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Error al obtener los productos en promoción',
                'detalle' => $e->getMessage() // Muestra el mensaje de error de la excepción
            ]);
        }
    }


    // Método para obtener todas las categorías
    public function obtenerCategorias()
    {
        $categorias = $this->model->getCategorias(); // Llama al modelo para obtener las categorías
        if ($categorias) {
            echo json_encode([
                'status' => 'success',
                'data' => $categorias
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron categorías.'
            ]);
        }
    }


    // Método para obtener subcategorías por categoría seleccionada
    public function obtenerSubcategoriasPorCategoria()
    {
        if (isset($_GET['categoria_id'])) {
            $categoriaId = intval($_GET['categoria_id']); // Convertimos a entero para mayor seguridad
            $subcategorias = $this->model->getSubcategoriasPorCategoria($categoriaId); // Llamamos al modelo

            if (!empty($subcategorias)) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $subcategorias
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron subcategorías para esta categoría.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Falta el ID de la categoría.'
            ]);
        }
    }

    public function generarNumeroPedido($longitud = 15)
    {
        //$codigoInicial = "1";
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numeroPedido = '';
        for ($i = 0; $i < $longitud; $i++) {
            $numeroPedido .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $numeroPedido;
    }

    
    public function obtenerFechaActual()
    {
        date_default_timezone_set('America/Bogota'); // Quito y Bogotá tienen la misma zona horaria
        return date('Y-m-d H:i:s');
    }   
    public function busquedaDinamica()
    {
        header('Content-Type: application/json'); // Asegurar respuesta JSON

        try {
            // Verificar si se proporcionó el parámetro de búsqueda
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (empty($query)) {
                echo json_encode(['error' => 'No se proporcionó un término de búsqueda.']);
                return;
            }

            // Llamar al modelo para obtener los productos
            $resultados = $this->model->searchProducts($query);

            // Verificar si hay resultados
            if (empty($resultados)) {
                echo json_encode(['message' => 'No se encontraron productos.']);
                return;
            }

            // Devolver productos en formato JSON
            echo json_encode([
                'success' => true,
                'query' => $query,
                'productos' => $resultados
            ]);
        } catch (Exception $e) {
            error_log("Error en la búsqueda: " . $e->getMessage());
            echo json_encode(['error' => 'Ocurrió un error al realizar la búsqueda.']);
        }
    }
}


// Depuración: Imprimir los datos recibidos
error_log("Parámetros GET recibidos: " . print_r($_GET, true));

if (isset($_GET['action'])) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'default'; // Valor por defecto si no se pasa acción

    $database = new Database1();
    $db = $database->getConnection();

    $productoModel = new ProductoModel($db);

    switch ($action) {
            //casos para obtener informacion
        case 'obtenerCategorias':
            $productoController = new ProductoController($db);
            $productoController->obtenerCategorias();
            break;

        case 'obtenerSubcategoriasPorCategoria':
            $productoController = new ProductoController($db);
            $productoController->obtenerSubcategoriasPorCategoria();
            break;
        case 'obtenerProductosPromocion':
            $productoController = new ProductoController($db);
            // Llamar al método que obtiene los productos en promoción
            $productoController->obtenerProductosPromocion();
            break;
        case 'obtenerProductosPopulares':
            $productoController = new ProductoController($db);
            $productoController->obtenerProductosPopulares();
            break;
        case 'obtenerTodo':
            $productoController = new ProductoController($db);
            //llamamos al metodo que obtiene todos los productos
            $productoController->obtenerTodosLosProductos();
            break;
            // Caso para insertar un nuevo producto
        case 'insertarProducto':
            $productoController = new ProductoController($db);
            // Llamamos al método insertarProducto para manejar la inserción
            $productoController->insertarProducto();
            break;
        case 'obtenerProductoPorId':
            // Crear una instancia del controlador de Producto
            $productoController = new ProductoController($db);

            // Llamamos al método obtenerProductoPorId para manejar la solicitud
            $productoController->obtenerProductoPorId();
            break;
        case 'actualizarProducto':
            // Verificar que se haya proporcionado un ID para el producto
            if (isset($_GET['idProducto']) && is_numeric($_GET['idProducto'])) {
                // Crear una instancia del controlador de Producto
                $productoController = new ProductoController($db);

                // Llamamos al método updateProducto pasando el ID del producto
                $productoController->updateProducto($_GET['idProducto']);
            } else {
                // En caso de que no se proporcione un ID válido
                echo json_encode(['success' => false, 'message' => 'ID de producto no válido.']);
            }
            break;
        case 'deleteProducto':
            // Verificar que se haya proporcionado un ID para el producto
            if (isset($_GET['idProducto']) && is_numeric($_GET['idProducto'])) {
                // Crear una instancia del controlador de Producto
                $productoController = new ProductoController($db);

                // Llamamos al método deleteProducto pasando el ID del producto
                $resultado = $productoController->deleteProducto($_GET['idProducto']);
            } else {
                // En caso de que no se proporcione un ID válido
                echo json_encode(['success' => false, 'message' => 'ID de producto no válido.']);
            }
            break;

        case 'verDetalle':
            $productoController = new ProductoController($db);
            $productoController->obtenerProductoPorId();
            break;
        case 'search':
            
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (empty($query)) {
                echo json_encode(['error' => 'No se proporcionó un término de búsqueda.']);
                exit;
            }

            // Llamamos al modelo para obtener los resultados de la búsqueda
            $productos = $productoModel->searchProducts($query);

            if (!empty($productos)) {
                echo json_encode(['productos' => $productos]); // Devuelve los productos en JSON
            } else {
                echo json_encode(['error' => 'No se encontraron productos.']);
            }
            exit;
    }
} else {
    //echo json_encode(['error' => 'Parámetro action faltante']);
}
