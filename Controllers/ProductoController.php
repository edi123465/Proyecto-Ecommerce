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

    public function obtenerProductosConTallas()
    {
        header('Content-Type: application/json');

        // Obtener parámetros de búsqueda y paginación
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        // Obtener los productos con tallas
        $resultado = $this->model->getProductosConTallas($search, $limit, $offset);

        // Contar total de resultados para la paginación
        $total = $this->model->countProductosConTallas($search);

        // Calcular el total de páginas
        $totalPaginas = $limit > 0 ? ceil($total / $limit) : 1;

        // Registrar en log
        error_log("Total de registros encontrados: $total");
        error_log("Límite por página: $limit");
        error_log("Total de páginas: $totalPaginas");

        if ($resultado && !empty($resultado)) {
            echo json_encode([
                'status' => 'success',
                'data' => $resultado,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'totalPages' => $totalPaginas
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron productos con tallas',
                'total' => 0,
                'totalPages' => 0
            ]);
        }

        exit;
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

    public function asignarTalla()
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        error_log("JSON recibido: " . $json); // 👈 Log del JSON crudo

        $data = json_decode($json, true);
        error_log("Datos decodificados: " . print_r($data, true)); // 👈 Log del array asociativo

        if (!isset($data['producto_id'], $data['talla_id'], $data['stock'])) {
            error_log("❌ Datos incompletos.");
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            return;
        }

        $producto_id = intval($data['producto_id']);
        $talla_id = intval($data['talla_id']);
        $stock = intval($data['stock']);

        error_log("✅ producto_id: $producto_id, talla_id: $talla_id, stock: $stock"); // 👈 Log valores individuales

        // Verificamos si ya existe esta combinación producto-talla
        $existe = $this->model->validarExistenciaProductoTalla($producto_id, $talla_id);

        if ($existe) {
            error_log("❌ La talla ya está asignada a este producto.");
            echo json_encode(['status' => 'error', 'message' => 'El producto ya tiene asignada esta talla']);
            return;
        }

        // Si no existe, asignamos la talla
        $resultado = $this->model->asignarTalla($producto_id, $talla_id, $stock);

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Talla asignada correctamente']);
        } else {
            error_log("❌ Error al insertar en la base de datos");
            echo json_encode(['status' => 'error', 'message' => 'Error al asignar la talla']);
        }
        exit;
    }

    public function editarTallaYStock()
    {
        // Leer JSON del cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar los datos recibidos
        error_log("Datos recibidos en editarTallaYStock: " . print_r($data, true));

        // Validar si los datos son correctos
        if (isset($data['producto_id'], $data['talla_id'], $data['stock'])) {
            $producto_talla_id = $data['producto_id']; // OJO: este es el ID de la tabla producto_talla
            $talla_id = $data['talla_id'];
            $stock = $data['stock'];

            // Necesitamos también el producto_id real (relacional)
            // Si lo tienes por separado, extraelo del JSON. Si no, debes obtenerlo desde la DB.
            $producto_id = $this->model->obtenerProductoIdPorProductoTallaId($producto_talla_id);

            // Verificar duplicados
            if ($this->model->verificarDuplicadoTalla($producto_talla_id, $producto_id, $talla_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Este producto ya tiene esa talla registrada.']);
                return;
            }

            // Llamar al modelo para actualizar la talla y stock
            $resultado = $this->model->actualizarTallaYStock($producto_talla_id, $talla_id, $stock);

            // Devolver la respuesta
            header('Content-Type: application/json');
            echo json_encode($resultado);
        } else {
            error_log("Datos inválidos para editar talla y stock");
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o incompletos']);
        }
    }

    public function eliminarTalla()
    {
        if (isset($_POST['producto_talla_id'])) {
            $producto_talla_id = $_POST['producto_talla_id'];

            // Puedes hacer un log por si quieres asegurarte
            error_log("ID de talla recibido (POST): " . $producto_talla_id);

            $resultado = $this->model->eliminarTalla($producto_talla_id);

            if ($resultado) {
                echo json_encode(['status' => 'success', 'message' => 'Talla eliminada correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la talla']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de talla no proporcionado']);
        }
    }

    public function obtenerProductoPorIdConTalla()
    {
        if (isset($_GET['id'])) {
            $productoId = intval($_GET['id']);
            $database = new Database1();
            $db = $database->getConnection();
            require_once '../Models/ProductoModel.php';
            $productoModel = new ProductoModel($db);

            $producto = $productoModel->getProductoPorIdConTalla($productoId);

            if ($producto) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $producto
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Producto no encontrado'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de producto no especificado'
            ]);
        }
    }
}


// Depuración: Imprimir los datos recibidos
error_log("Parámetros GET recibidos: " . print_r($_GET, true));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer JSON del cuerpo
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    error_log("JSON recibido en router: " . $json);
    error_log("Datos decodificados en router: " . print_r($data, true));

    // Caso: Asignar talla
    if (isset($_GET['action']) && $_GET['action'] === 'asignarTalla') {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $controller = new ProductoController($db);
        $controller->asignarTalla(); // ✅ este ya lee su propio JSON
        exit;
    }

    // Caso: Editar talla y stock de producto
    if (isset($_GET['action']) && $_GET['action'] === 'editarTallaYStock') {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $controller = new ProductoController($db);
        $controller->editarTallaYStock(); // Método para editar talla y stock
        exit;
    }
    // Caso: Eliminar talla de producto
    if (isset($_GET['action']) && $_GET['action'] === 'eliminarTalla') {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $controller = new ProductoController($db);
        $controller->eliminarTalla(); // Método para eliminar talla
        exit;
    }

    // Caso: Buscar productos (sigue usando $_POST tradicional)
    if (isset($_POST['action']) && $_POST['action'] === 'searchProducts') {
        $database = new Database1();
        $db = $database->getConnection();
        $productoModel = new ProductoModel($db);

        $termino = trim($_POST['termino'] ?? '');
        $resultados = $productoModel->searchProductoTalla($termino);
        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Caso: Obtener productos con tallas
    if (isset($_GET['action']) && $_GET['action'] === 'obtenerProductosConTallas') {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $controller = new ProductoController($db);
        $controller->obtenerProductosConTallas();
        exit;
    }

    // ✅ Nuevo caso: Obtener producto por ID con talla
    if (isset($_GET['action']) && $_GET['action'] === 'obtenerProductoPorIdConTalla') {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $controller = new ProductoController($db);
        $controller->obtenerProductoPorIdConTalla();
        exit;
    }

    // ✅ Mueve aquí esta parte:
    if (isset($_GET['action']) && $_GET['action'] === 'obtenerTallasPorProducto' && isset($_GET['producto_id'])) {
        require_once '../Models/ProductoModel.php';
        $database = new Database1();
        $db = $database->getConnection();
        $productoModel = new ProductoModel($db); // 👈 esto faltaba también
        $productoId = $_GET['producto_id'];
        $tallas = $productoModel->obtenerTallasPorProducto($productoId);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $tallas]);
        exit;
    }
}

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
            $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $itemsPerPage = 12;
            $offset = ($page - 1) * $itemsPerPage;

            if (empty($query)) {
                echo json_encode(['error' => 'No se proporcionó un término de búsqueda.']);
                exit;
            }

            // Total de productos que coinciden
            $totalProductos = $productoModel->countProducts($query);

            // Productos de la página actual
            $productos = $productoModel->searchProductsPaginated($query, $itemsPerPage, $offset);

            echo json_encode([
                'productos' => $productos,
                'total' => $totalProductos,
                'itemsPerPage' => $itemsPerPage,
                'currentPage' => $page,
                'totalPages' => ceil($totalProductos / $itemsPerPage)
            ]);
            exit;

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
