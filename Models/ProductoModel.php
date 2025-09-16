<?php

class ProductoModel
{

    private $conn;
    private $table = 'productos';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertarProducto($nombre, $descripcion, $precio, $precio1, $precio2, $precio3, $precio4, $stock, $subcategoria_id, $codigo_barras, $imagen, $isActive, $is_promocion, $descuento, $categoria_id, $puntos_otorgados, $cantidad_minima_para_puntos)
    {
        try {
            // Consulta SQL para insertar el producto
            $query = "
        INSERT INTO Productos (
            nombreProducto, descripcionProducto, precio, precio_1, precio_2, 
            precio_3, precio_4, stock, subcategoria_id, codigo_barras, imagen, 
            isActive, fechaCreacion, is_promocion, descuento, categoria_id,
            puntos_otorgados, cantidad_minima_para_puntos
        ) 
        VALUES (
            :nombre, :descripcion, :precio, :precio_1, :precio_2, :precio_3, 
            :precio_4, :stock, :subcategoria_id, :codigo_barras, :imagen, 
            :isActive, now(), :is_promocion, :descuento, :categoria_id,
            :puntos_otorgados, :cantidad_minima_para_puntos
        )";

            $stmt = $this->conn->prepare($query);

            // Vincular parámetros
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':precio_1', $precio1, PDO::PARAM_STR);
            $stmt->bindParam(':precio_2', $precio2, PDO::PARAM_STR);
            $stmt->bindParam(':precio_3', $precio3, PDO::PARAM_STR);
            $stmt->bindParam(':precio_4', $precio4, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_STR);
            $stmt->bindParam(':subcategoria_id', $subcategoria_id, PDO::PARAM_INT);
            $stmt->bindParam(':codigo_barras', $codigo_barras, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            $stmt->bindParam(':isActive', $isActive, PDO::PARAM_BOOL);
            $stmt->bindParam(':is_promocion', $is_promocion, PDO::PARAM_BOOL);
            $stmt->bindParam(':descuento', $descuento, PDO::PARAM_STR);
            $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $stmt->bindParam(':puntos_otorgados', $puntos_otorgados, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad_minima_para_puntos', $cantidad_minima_para_puntos, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return $this->conn->lastInsertId(); // Retornar el ID del producto insertado
            } else {
                return false; // Retornar falso si falla la inserción
            }
        } catch (PDOException $e) {
            error_log("Error al insertar producto: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener todos los productos con categorías y subcategorías
    public function getAllProductosConCategorias()
    {
        $query = "
            SELECT p.id, p.nombreProducto, p.descripcionProducto, p.precio, p.precio_1, 
                   p.precio_2, p.precio_3, p.precio_4, p.stock, 
                   s.id AS subcategoria_id, s.nombre AS subcategoria_nombre, 
                   c.id AS categoria_id, c.nombre AS categoria_nombre, 
                   p.codigo_barras, p.is_talla
            FROM Productos p
            JOIN Subcategorias s ON p.subcategoria_id = s.id
            JOIN Categorias c ON s.categoria_id = c.id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $sql = "
        SELECT p.id, 
            p.nombreProducto, 
            p.descripcionProducto, 
            p.precio, 
            p.precio_1, 
            p.precio_2, 
            p.precio_3, 
            p.precio_4, 
            p.stock, 
            p.codigo_barras, 
            p.imagen, 
            p.isActive, 
            p.fechaCreacion, 
            p.is_promocion, 
            p.descuento,
            p.puntos_otorgados,
            p.cantidad_minima_para_puntos,
                    p.is_talla,

            c.nombreCategoria AS nombreCategoria, 
            s.nombrSubcategoria AS nombreSubcategoria
        FROM productos p
        INNER JOIN subcategorias s ON p.subcategoria_id = s.id
        INNER JOIN categorias c ON s.categoria_id = c.id
        WHERE p.nombreProducto LIKE :search OR p.descripcionProducto LIKE :search
        ORDER BY p.nombreProducto ASC
        LIMIT :limit OFFSET :offset
        ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAll(): " . $e->getMessage());
            return false;
        }
    }

    public function getTotalProductos($search = '')
    {
        try {
            $sql = "
            SELECT COUNT(*) as total
            FROM productos p
            INNER JOIN subcategorias s ON p.subcategoria_id = s.id
            INNER JOIN categorias c ON s.categoria_id = c.id
            WHERE p.nombreProducto LIKE :search OR p.descripcionProducto LIKE :search
        ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            error_log("Error en getTotalProductos(): " . $e->getMessage());
            return 0;
        }
    }


    // MÉTODO PARA OBTENER LOS PRODUCTOS EN PROMOCIÓN CON PAGINACIÓN
    public function read($limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT p.id, 
                       p.nombreProducto, 
                       p.descripcionProducto, 
                       p.precio, 
                       p.precio_1, 
                       p.precio_2, 
                       p.precio_3, 
                       p.precio_4, 
                       p.stock, 
                       p.codigo_barras, 
                       p.imagen, 
                       p.isActive, 
                       p.fechaCreacion, 
                       p.is_promocion, 
                       p.descuento, 
                       p.cantidad_minima_para_puntos,
                       p.is_talla,  
                       p.puntos_otorgados,
                       c.nombreCategoria AS nombreCategoria, 
                       s.nombrSubcategoria AS nombreSubcategoria
                FROM productos p
                INNER JOIN subcategorias s ON p.subcategoria_id = s.id
                INNER JOIN categorias c ON s.categoria_id = c.id
                WHERE p.is_promocion = 1
                ORDER BY p.fechaCreacion DESC
                LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        } catch (Exception $e) {
            return false;
        }
    }
    public function obtenerComentariosActivosPorProducto($producto_id)
    {
        $sql = "SELECT 
                c.id,
                c.usuario_id AS usuario_id,  -- 👈 asegúrate que sea 'usuario_id'
                u.nombreUsuario,
                c.comentario,
                c.calificacion,
                c.fecha,
                c.producto_id
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.estado = 1 
              AND c.producto_id = :producto_id
            ORDER BY c.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->execute();

        $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 💡 Agrega aquí un log temporal
        error_log("SQL ejecutado con usuario_id:");
        error_log(print_r($comentarios, true));

        return $comentarios;
    }

    public function getProductosConTallas($search = '', $limit = 10, $offset = 0)
    {
        try {
            $query = "
            SELECT 
                pt.id AS ID, 
                p.nombreProducto AS nombreProducto, 
                p.descripcionProducto AS descripcion, 
                t.talla AS talla, 
                pt.stock AS stock, 
                p.precio_1 AS precio, 
                p.imagen AS imagen 
            FROM producto_talla pt 
            JOIN productos p ON pt.producto_id = p.id 
            JOIN tallas t ON pt.talla_id = t.id
            WHERE p.nombreProducto LIKE :search 
            OR p.descripcionProducto LIKE :search 
            OR p.codigo_barras LIKE :search  -- Aquí se añadió el filtro para el código de barras
            ORDER BY p.nombreProducto ASC
            LIMIT :limit OFFSET :offset
        ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Resultados obtenidos con paginación: " . print_r($resultados, true));

            return $resultados;
        } catch (Exception $e) {
            error_log("Error en getProductosConTallas(): " . $e->getMessage());
            return false;
        }
    }

    public function countProductosConTallas($search = '')
    {
        try {
            $query = "
                SELECT COUNT(*) AS total
                FROM producto_talla pt
                JOIN productos p ON pt.producto_id = p.id
                JOIN tallas t ON pt.talla_id = t.id
                WHERE p.nombreProducto LIKE :search OR p.descripcionProducto LIKE :search
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error en countProductosConTallas(): " . $e->getMessage());
            return 0;
        }
    }

    public function contarTotalPromociones()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM productos WHERE is_promocion = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (Exception $e) {
            error_log("Error al contar productos en promoción: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerProductosPaginados($pagina = 1, $productosPorPagina = 5)
    {
        // Calculamos el desplazamiento (OFFSET)
        $offset = ($pagina - 1) * $productosPorPagina;

        // Consulta SQL para obtener los productos paginados
        $sql = "SELECT * FROM productos ORDER BY nombreProducto ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $productosPorPagina, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener todos los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modelo ProductoModel.php
    public function validarExistenciaProductoTalla($producto_id, $talla_id)
    {
        $query = "SELECT COUNT(*) FROM producto_talla WHERE producto_id = :producto_id AND talla_id = :talla_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':talla_id', $talla_id, PDO::PARAM_INT);

        $stmt->execute();

        // Verificamos si ya existe la asignación
        $count = $stmt->fetchColumn();

        return $count > 0; // Devuelve true si ya existe, de lo contrario, false
    }
    //para asignar tallas a un producto
    public function asignarTalla($producto_id, $talla_id, $stock)
    {
        $query = "INSERT INTO producto_talla (producto_id, talla_id, stock) VALUES (:producto_id, :talla_id, :stock)";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmt->bindParam(':talla_id', $talla_id, PDO::PARAM_INT);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error en asignarTalla: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProductoIdPorProductoTallaId($producto_talla_id)
    {
        try {
            $query = "SELECT producto_id FROM producto_talla WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $producto_talla_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['producto_id'] : null;
        } catch (Exception $e) {
            error_log("Error en obtenerProductoIdPorProductoTallaId: " . $e->getMessage());
            return null;
        }
    }

    // Verificar si ya existe la combinación producto + talla (excluyendo el mismo ID)
    public function verificarDuplicadoTalla($producto_talla_id, $producto_id, $talla_id)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM producto_talla 
                  WHERE producto_id = :producto_id 
                  AND talla_id = :talla_id 
                  AND id != :producto_talla_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':producto_id', $producto_id);
            $stmt->bindParam(':talla_id', $talla_id);
            $stmt->bindParam(':producto_talla_id', $producto_talla_id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] > 0; // true si hay duplicado
        } catch (Exception $e) {
            error_log("Error en verificarDuplicadoTalla: " . $e->getMessage());
            return true; // Para prevenir que se actualice en caso de error
        }
    }

    // Método para actualizar talla y stock de un producto
    public function actualizarTallaYStock($producto_id, $talla_id, $stock)
    {
        try {
            $query = "UPDATE producto_talla SET talla_id = :talla_id, stock = :stock WHERE id = :producto_talla_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':producto_talla_id', $producto_id); // que en realidad es el id de producto_talla
            $stmt->bindParam(':talla_id', $talla_id);
            $stmt->bindParam(':stock', $stock);

            if ($stmt->execute()) {
                $affectedRows = $stmt->rowCount();  // Verifica cuántas filas fueron afectadas
                error_log("Filas afectadas: " . $affectedRows);  // Log para saber cuántas filas se actualizaron

                if ($affectedRows > 0) {
                    return ['status' => 'success'];
                } else {
                    return ['status' => 'warning', 'message' => 'No se encontraron cambios en el producto'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Error al actualizar el producto'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Método para eliminar una talla de producto
    public function eliminarTalla($productoTallaId)
    {
        try {
            $query = "DELETE FROM producto_talla WHERE id = :producto_talla_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':producto_talla_id', $productoTallaId);

            // Ejecutar la eliminación
            if ($stmt->execute()) {
                return true;  // Si se eliminó correctamente
            }
            return false;  // Si hubo un error en la eliminación
        } catch (Exception $e) {
            // Si hay un error, lo registramos y retornamos false
            error_log('Error al eliminar la talla: ' . $e->getMessage());
            return false;
        }
    }

        public function searchProductoTalla($query)
    {
        // Asegúrate de que $query no esté vacío o sea nulo
        if (empty($query)) {
            return [];  // Retorna un arreglo vacío si no se proporciona una consulta
        }

        // Agregar los comodines de búsqueda al término de búsqueda
        $searchTerm = '%' . $query . '%';

        // Consulta optimizada, solo buscando en los productos y las categorías/subcategorías
        $sql = "
    SELECT p.id, p.nombreProducto, p.descripcionProducto, p.codigo_barras, 
           p.imagen, p.subcategoria_id, c.nombreCategoria, s.nombrSubcategoria
    FROM productos p
    LEFT JOIN subcategorias s ON p.subcategoria_id = s.id
    LEFT JOIN categorias c ON s.categoria_id = c.id
    WHERE (
        p.nombreProducto COLLATE utf8mb4_general_ci LIKE :queryProducto
        OR c.nombreCategoria COLLATE utf8mb4_general_ci LIKE :queryCategoria
        OR s.nombrSubcategoria COLLATE utf8mb4_general_ci LIKE :querySubcategoria
        OR p.codigo_barras COLLATE utf8mb4_general_ci LIKE :queryCodigoBarras
    )
    ";

        try {
            // Preparamos la sentencia
            $stmt = $this->conn->prepare($sql);

            // Preparamos los parámetros de búsqueda
            $params = [
                ':queryProducto' => $searchTerm,
                ':queryCategoria' => $searchTerm,
                ':querySubcategoria' => $searchTerm,
                ':queryCodigoBarras' => $searchTerm // Agregado el parámetro para buscar por código de barras
            ];

            // Ejecutamos la consulta pasando los parámetros
            $stmt->execute($params);

            // Obtenemos los resultados
            $resultados = $stmt->fetchAll();

            // Si no hay resultados, retornamos un arreglo vacío
            if (empty($resultados)) {
                return [];
            }

            return $resultados;  // Devolvemos los productos encontrados
        } catch (PDOException $e) {
            // Loguea el error si ocurre algún problema con la consulta
            error_log("Error en la consulta: " . $e->getMessage());
            return [];  // En caso de error, devolvemos un arreglo vacío
        }
    }


    public function obtenerTotalProductos()
    {
        $sql = "SELECT COUNT(*) FROM Productos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Retornar el total de productos
        return $stmt->fetchColumn();
    }

    //para traer las tallas asociadas a cada producto
        public function getProductoPorIdConTalla($productoTallaId)
    {
        $query = "SELECT pt.id AS ID, 
                         p.nombreProducto AS nombreProducto, 
                         p.descripcionProducto AS descripcion, 
                         t.talla AS talla, 
                         pt.stock AS stock, 
                         p.precio_1 AS precio, 
                         p.imagen AS imagen 
                  FROM producto_talla pt 
                  JOIN productos p ON pt.producto_id = p.id 
                  JOIN tallas t ON pt.talla_id = t.id 
                  WHERE pt.id = :productoTallaId";  // <- Cambiar aquí
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':productoTallaId', $productoTallaId, PDO::PARAM_INT);
    
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Resultados obtenidos: " . print_r($resultados, true));
    
        return $resultados;
    }
    public function obtenerTallasPorProducto($productoId)
    {
        $sql = "SELECT t.talla AS talla, pt.stock 
                FROM producto_talla pt 
                INNER JOIN tallas t ON pt.talla_id = t.id 
                WHERE pt.producto_id = :producto_id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':producto_id', $productoId, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por su ID
    public function getById($id)
    {
        $query = "SELECT 
        p.id, p.nombreProducto, p.descripcionProducto, p.codigo_barras, p.precio,
        p.precio_1, p.precio_2, p.precio_3, p.precio_4, p.imagen, 
        p.descuento, p.stock, p.categoria_id, p.subcategoria_id, 
        p.isActive, p.is_promocion, p.puntos_otorgados, p.cantidad_minima_para_puntos,p.is_talla,
        c.nombreCategoria AS categoria_nombre, s.nombrSubcategoria AS subcategoria_nombre
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    LEFT JOIN subcategorias s ON p.subcategoria_id = s.id
    WHERE p.id = :id"; // Corregido: Usar :id para el parámetro

        $stmt = $this->conn->prepare($query);

        // Usamos bindParam para vincular el parámetro de la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);  // Correcto: Vinculamos el valor de id

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;  // Devolver los detalles del producto
        } else {
            return null;  // No se encontró el producto
        }
    }

    public function update($id, $data)
    {
        $query = "UPDATE productos SET 
        nombreProducto = :nombreProducto, 
        descripcionProducto = :descripcionProducto, 
        precio = :precio, 
        precio_1 = :precio_1, 
        precio_2 = :precio_2, 
        precio_3 = :precio_3, 
        precio_4 = :precio_4, 
        stock = :stock, 
        subcategoria_id = :subcategoria_id, 
        codigo_barras = :codigo_barras, 
        imagen = :imagen, 
        isActive = :isActive, 
        is_promocion = :isPromocion, 
        descuento = :descuento, 
        categoria_id = :categoria_id,
        puntos_otorgados = :puntos_otorgados,
        cantidad_minima_para_puntos = :cantidad_minima_para_puntos
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombreProducto', $data['nombreProducto']);
        $stmt->bindParam(':descripcionProducto', $data['descripcionProducto']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':precio_1', $data['precio_1']);
        $stmt->bindParam(':precio_2', $data['precio_2']);
        $stmt->bindParam(':precio_3', $data['precio_3']);
        $stmt->bindParam(':precio_4', $data['precio_4']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':subcategoria_id', $data['subcategoria_id']);
        $stmt->bindParam(':codigo_barras', $data['codBarras']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':isActive', $data['isActive']);
        $stmt->bindParam(':isPromocion', $data['isPromocion']);
        $stmt->bindParam(':descuento', $data['descuento']);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':puntos_otorgados', $data['puntos_otorgados']);
        $stmt->bindParam(':cantidad_minima_para_puntos', $data['cantidad_minima_para_puntos']);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false;
        }
    }


    // Eliminar un producto
    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para obtener todas las categorías
    public function getCategorias()
    {
        $query = "SELECT * FROM Categorias ORDER BY nombreCategoria ASC"; // Consultar categorías ordenadas alfabéticamente
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array asociativo
    }

    // Método para obtener subcategorías por categoría específica
    public function getSubcategoriasPorCategoria($categoriaId)
    {
        $query = "SELECT * FROM Subcategorias WHERE categoria_id = :categoria_id ORDER BY nombrSubcategoria ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT); // Asociamos el parámetro de forma segura
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolvemos un array con los resultados
    }

    public function ObtenerProductosPopulares($limit = 12, $offset = 0)
    {
        $sql = "
   SELECT p.id, 
           p.nombreProducto, 
           p.descripcionProducto, 
           p.precio, 
           p.precio_1, 
           p.precio_2, 
           p.precio_3, 
           p.precio_4, 
           p.stock, 
           p.codigo_barras, 
           p.imagen, 
           p.isActive, 
           p.fechaCreacion, 
           p.is_promocion, 
           p.descuento,
           p.cantidad_minima_para_puntos,
           p.puntos_otorgados,
           c.nombreCategoria AS nombreCategoria, 
           s.nombrSubcategoria AS nombreSubcategoria
    FROM productos p
    INNER JOIN subcategorias s ON p.subcategoria_id = s.id
    INNER JOIN categorias c ON s.categoria_id = c.id
    WHERE p.isActive = 1
    LIMIT :limit OFFSET :offset;

    ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ObtenerProductosPopulares(): " . $e->getMessage());
            return false;
        }
    }

    public function ObtenerMasvendidos($limit = 12, $offset = 0)
{
    $sql = "
        SELECT p.id, 
               p.nombreProducto, 
               p.descripcionProducto, 
               p.precio, 
               p.precio_1, 
               p.precio_2, 
               p.precio_3, 
               p.precio_4, 
               p.stock, 
               p.codigo_barras, 
               p.imagen, 
               p.isActive, 
               p.fechaCreacion, 
               p.is_promocion, 
               p.descuento,
               p.is_talla,
               p.cantidad_minima_para_puntos,
               p.puntos_otorgados,
               c.nombreCategoria AS nombreCategoria, 
               s.nombrSubcategoria AS nombreSubcategoria,
               SUM(dp.cantidad) AS total_vendidos
        FROM productos p
        INNER JOIN subcategorias s ON p.subcategoria_id = s.id
        INNER JOIN categorias c ON s.categoria_id = c.id
        INNER JOIN detallesPedidos dp ON dp.producto_id = p.id
        WHERE p.isActive = 1
        GROUP BY p.id
        ORDER BY total_vendidos DESC
        LIMIT :limit OFFSET :offset
    ";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error en ObtenerProductosPopulares(): " . $e->getMessage());
        return false;
    }
}
public function contarProductosActivos()
{
    $sql = "SELECT COUNT(*) as total FROM productos WHERE isActive = 1";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (Exception $e) {
        error_log("Error en contarProductosActivos(): " . $e->getMessage());
        return 0;
    }
}



    // Método para obtener productos por categoría
    // Método para obtener productos por categoría
    public function getProductsByCategory($categoria_id)
    {
        // Consulta SQL para obtener los productos que pertenecen a la categoría especificada
        $sql = "
        SELECT 
            p.id,
            p.nombreProducto, 
            p.precio_1, 
            p.imagen, 
            c.nombreCategoria
        FROM 
            Productos p
        JOIN 
            Subcategorias s ON p.subcategoria_id = s.id
        JOIN 
            Categorias c ON s.categoria_id = c.id
        WHERE 
            c.id = ?
    ";
        // Preparar y ejecutar la consulta
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoria_id]);

        // Retornar los productos encontrados
        return $stmt->fetchAll();
    }



    // Método para obtener productos por subcategoría
    public function getProductsBySubcategory($subcategoria_id)
    {
        $sql = "SELECT * FROM Productos WHERE subcategoria_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$subcategoria_id]);
        return $stmt->fetchAll();
    }

    // 1️⃣ Método para buscar productos con límite y offset (paginación)
public function searchProductsPaginated($query, $limit = 12, $offset = 0)
{
    if (empty($query)) return [];

    $searchTerm = '%' . $query . '%';

    $sql = "
        SELECT p.*, c.nombreCategoria, s.nombrSubcategoria
        FROM Productos p
        JOIN Subcategorias s ON p.subcategoria_id = s.id
        JOIN Categorias c ON s.categoria_id = c.id
        WHERE p.isActive = 1
        AND (
            p.nombreProducto COLLATE utf8mb4_general_ci LIKE :queryProducto
            OR c.nombreCategoria COLLATE utf8mb4_general_ci LIKE :queryCategoria
            OR s.nombrSubcategoria COLLATE utf8mb4_general_ci LIKE :querySubcategoria
        )
        ORDER BY p.id ASC
        LIMIT :limit OFFSET :offset
    ";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':queryProducto', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':queryCategoria', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':querySubcategoria', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en searchProductsPaginated: " . $e->getMessage());
        return [];
    }
}

// 2️⃣ Método para obtener el total de productos encontrados (sin límite)
public function countProducts($query)
{
    if (empty($query)) return 0;

    $searchTerm = '%' . $query . '%';

    $sql = "
        SELECT COUNT(*) as total
        FROM Productos p
        JOIN Subcategorias s ON p.subcategoria_id = s.id
        JOIN Categorias c ON s.categoria_id = c.id
        WHERE p.isActive = 1
        AND (
            p.nombreProducto COLLATE utf8mb4_general_ci LIKE :queryProducto
            OR c.nombreCategoria COLLATE utf8mb4_general_ci LIKE :queryCategoria
            OR s.nombrSubcategoria COLLATE utf8mb4_general_ci LIKE :querySubcategoria
        )
    ";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':queryProducto' => $searchTerm,
            ':queryCategoria' => $searchTerm,
            ':querySubcategoria' => $searchTerm
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error en countProducts: " . $e->getMessage());
        return 0;
    }
}


    public function searchAdminProducts($query)
    {
        // Asegúrate de que $query no esté vacío o sea nulo
        if (empty($query)) {
            return [];  // Retorna un arreglo vacío si no se proporciona una consulta
        }

        // Agregar los comodines de búsqueda al término de búsqueda
        $searchTerm = '%' . $query . '%';

        $sql = "
    SELECT p.*, c.nombreCategoria, s.nombrSubcategoria
    FROM productos p
    JOIN subcategorias s ON p.subcategoria_id = s.id
    JOIN categorias c ON s.categoria_id = c.id
  WHERE (
        p.nombreProducto COLLATE utf8mb4_general_ci LIKE :queryProducto
        OR c.nombreCategoria COLLATE utf8mb4_general_ci LIKE :queryCategoria
        OR s.nombrSubcategoria COLLATE utf8mb4_general_ci LIKE :querySubcategoria
        OR p.codigo_barras COLLATE utf8mb4_general_ci LIKE :queryCodigoBarras
    )
    ";


        try {
            // Preparamos la sentencia
            $stmt = $this->conn->prepare($sql);

            // Preparamos los parámetros de búsqueda
            $params = [
                ':queryProducto' => $searchTerm,
                ':queryCategoria' => $searchTerm,
                ':querySubcategoria' => $searchTerm,
                ':queryCodigoBarras' => $searchTerm // Agregado el parámetro para buscar por código de barras
            ];

            // Ejecutamos la consulta pasando los parámetros
            $stmt->execute($params);

            // Obtenemos los resultados
            $resultados = $stmt->fetchAll();

            // Si no hay resultados, retornamos un arreglo vacío
            if (empty($resultados)) {
                return [];
            }

            return $resultados;  // Devolvemos los productos encontrados
        } catch (PDOException $e) {
            // Loguea el error si ocurre algún problema con la consulta
            error_log("Error en la consulta: " . $e->getMessage());
            return [];  // En caso de error, devolvemos un arreglo vacío
        }
    }

public function obtenerProductosConPuntos($pagina = 1, $porPagina = 12)
{
    $offset = ($pagina - 1) * $porPagina;

    $query = "SELECT * 
              FROM productos 
              WHERE puntos_otorgados > 0 AND isActive = 1
              ORDER BY id DESC
              LIMIT :porPagina OFFSET :offset";

    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':porPagina', (int)$porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function contarProductosConPuntos()
{
    $query = "SELECT COUNT(*) as total FROM productos WHERE puntos_otorgados > 0 AND isActive = 1";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}
}
