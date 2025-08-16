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
                $_POST['descuento'],
                $_POST['total_puntos'],
                $_POST['cantidad_minima']
            )
        ) {
            // Procesar la imagen si existe
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $imagen = $_FILES['imagen'];
                $imagenNombre = time() . '_' . $imagen['name'];
                $imagenRutaCompleta = '../assets/imagenesMilogar/productos/' . $imagenNombre;

                move_uploaded_file($imagen['tmp_name'], $imagenRutaCompleta);

                $imagenNombreEnBD = $imagenNombre;
            } else {
                $imagenNombreEnBD = null;
            }

            // Log de los datos
            error_log("Datos a insertar:");
            foreach ($_POST as $key => $value) {
                error_log("$key: $value");
            }
            error_log("imagenRuta: " . $imagenNombreEnBD);

            // Crear instancia del modelo
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
                $_POST['total_puntos'],
                $_POST['cantidad_minima']
            );

            // Respuesta
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el producto.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
        }
    }



    public function obtenerProductoPorId()
    {
        // Verificamos si el parámetro 'id' está presente en la URL
        if (isset($_GET['id'])) {
            $idProducto = $_GET['id'];

            // Log para ver qué ID se ha recibido
            error_log("ID del producto recibido: " . $idProducto);

            // Llamamos al método getById del modelo para obtener el producto
            $producto = $this->model->getById($idProducto);

            // Verificamos si el producto fue encontrado
            if ($producto) {
                // Log para ver el producto obtenido
                error_log("Producto encontrado: " . print_r($producto, true));

                // Si el producto existe, respondemos con los datos en formato JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $producto
                ]);
            } else {
                // Si no se encuentra el producto, respondemos con un mensaje de error
                error_log("Producto no encontrado para el ID: " . $idProducto);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Producto no encontrado'
                ]);
            }
        } else {
            // Si no se proporciona un ID, respondemos con un error
            error_log("ID del producto no proporcionado en la solicitud.");
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                'categoria_id' => intval($_POST['categoria_id'] ?? 0),
                'subcategoria_id' => intval($_POST['subcategoria_id'] ?? 0),
                'isActive' => ($_POST['isActive'] ?? '0') == '1' ? 1 : 0,
                'isPromocion' => ($_POST['isPromocion'] ?? '0') == '1' ? 1 : 0,
                'descuento' => floatval($_POST['descuento'] ?? 0),
                'cantidad_minima_para_puntos' => intval($_POST['cantidad_minima_para_puntos'] ?? 0),
                'puntos_otorgados' => intval($_POST['puntos_otorgados'] ?? 0),

            ];

            // Obtener el producto actual
            $productoActual = $this->model->getById($id);
            $imagenActual = $productoActual['imagen'] ?? null;

            // Verificar si hay una nueva imagen
            if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($_FILES['nueva_imagen']['name'], PATHINFO_EXTENSION));
                $nombreImagen = uniqid() . '.webp';
                $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . "/Milogar/assets/imagenesMilogar/productos/" . $nombreImagen;

                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                    // Eliminar imagen anterior solo si hay una nueva
                    if ($imagenActual) {
                        $rutaImagenAnterior = $_SERVER['DOCUMENT_ROOT'] . "/Milogar/assets/imagenesMilogar/productos/" . $imagenActual;
                        if (file_exists($rutaImagenAnterior)) {
                            unlink($rutaImagenAnterior);
                        }
                    }

                    $imagenTemporal = $_FILES['nueva_imagen']['tmp_name'];

                    if ($extension === 'webp') {
                        move_uploaded_file($imagenTemporal, $rutaDestino);
                    } else {
                        $img = null;
                        if ($extension === 'jpg' || $extension === 'jpeg') {
                            $img = imagecreatefromjpeg($imagenTemporal);
                        } elseif ($extension === 'png') {
                            $img = imagecreatefrompng($imagenTemporal);
                            imagepalettetotruecolor($img);
                            imagealphablending($img, true);
                            imagesavealpha($img, true);
                        }
                        if ($img) {
                            imagewebp($img, $rutaDestino, 80);
                            imagedestroy($img);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Error al convertir la imagen a WebP.']);
                            exit;
                        }
                    }
                    $data['imagen'] = $nombreImagen;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes JPG, PNG y WebP.']);
                    exit;
                }
            } else {
                // Si no se subió una nueva imagen, conservar la imagen actual
                $data['imagen'] = $imagenActual;
            }

            // Intentar actualizar el producto
            if ($this->model->update($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
            } else {
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
        header('Content-Type: application/json');

        try {
            // Captura los parámetros de búsqueda y paginación
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $offset = ($page - 1) * $limit;

            // Obtener productos paginados
            $productos = $this->model->getAll($search, $limit, $offset);
            // Obtener el total de productos que coinciden con la búsqueda
            $total = $this->model->getTotalProductos($search);

            if (!empty($productos)) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $productos,
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'mensaje' => 'No se encontraron productos',
                    'total' => 0
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public function obtenerProductosPromocion()
    {
        header('Content-Type: application/json');

        try {
            // Parámetros de paginación desde la URL, por ejemplo: ?page=2&limit=8
            $pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limite = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;
            $offset = ($pagina - 1) * $limite;

            // Llama al modelo con paginación
            $productosPromocion = $this->model->read($limite, $offset);

            // Para saber cuántos productos hay en total (opcional)
            $total = $this->model->contarTotalPromociones(); // este método debes tenerlo en tu modelo

            if (!empty($productosPromocion)) {
                echo json_encode([
                    'status' => 'success',
                    'pagina_actual' => $pagina,
                    'total' => $total,
                    'total_paginas' => ceil($total / $limite),
                    'data' => $productosPromocion
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'mensaje' => 'No se encontraron productos en promoción'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error al obtener productos en promoción: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Error al obtener los productos en promoción',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Método para obtener todos los productos con categorías y subcategorías
    public function getAllProductosConCategorias()
    {
        return $this->model->getAllProductosConCategorias();
    }




    public function obtenerProductosPopulares()
    {
        // Obtener los parámetros de paginación desde la URL (GET)
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        // Llamada al modelo para obtener productos
        $productosPopulares = $this->model->obtenerProductosPopulares($limit, $offset);

        // Contar el total de productos
        $totalProductos = $this->model->getTotalProductos(); // Este método debe contar el total de productos

        // Calcular el número total de páginas
        $totalPaginas = ceil($totalProductos / $limit);

        // Enviar la respuesta
        echo json_encode([
            'status' => 'success',
            'data' => $productosPopulares,
            'totalPaginas' => $totalPaginas,
            'paginaActual' => ($offset / $limit) + 1
        ]);
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

    public function searchProducts()
    {
        header('Content-Type: application/json');

        // Recibir el valor de búsqueda enviado por el cliente (AJAX/fetch)
        $query = isset($_GET['query']) ? $_GET['query'] : '';  // Puedes usar $_POST si prefieres otro método

        // Agregar un log para verificar el valor de la consulta
        error_log("Valor de búsqueda recibido: " . $query);

        // Llamar al método de búsqueda
        $productos = $this->model->searchAdminProducts($query);

        // Agregar un log para verificar el resultado de la búsqueda
        error_log("Productos encontrados: " . print_r($productos, true));  // print_r convierte el array a una cadena legible

        // Devolver los productos encontrados como JSON
        echo json_encode($productos);
    }

    public function productosConPuntos()
    {
        $db = new Database1();
        $productoModel = new ProductoModel($db->getConnection());
        $productos = $productoModel->obtenerProductosConPuntos();

        echo json_encode($productos); // Devuelve en formato JSON
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
            $productoController = new ProductoController($db);
            $productoController->searchProducts();
        case 'searchProducts':
            // Lógica para 'searchProducts'
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (empty($query)) {
                echo json_encode(['error' => 'No se proporcionó un término de búsqueda.']);
                exit;
            }

            // Llamar al modelo para obtener los productos que coincidan con la búsqueda
            $productos = $productoModel->searchAdminProducts($query);

            if (!empty($productos)) {
                echo json_encode($productos); // Devuelve los productos como un array JSON
            } else {
                echo json_encode(['error' => 'No se encontraron productos.']);
            }
            exit;
        case 'productosConPuntos':
            $controller = new ProductoController($db);
            $controller->productosConPuntos();
            exit;
        case 'getComentariosPorProducto':
            header('Content-Type: application/json');
            $producto_id = $_GET['producto_id'] ?? null;
            if (!$producto_id) {
                echo json_encode(['success' => false, 'message' => 'Producto no especificado']);
                return;
            }

            $TiendaModel = new ProductoModel($db);
            $comentarios = $TiendaModel->obtenerComentariosActivosPorProducto($producto_id);

            echo json_encode(['success' => true, 'data' => $comentarios]);
            return;
    }
} else {
    //echo json_encode(['error' => 'Parámetro action faltante']);
}
