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
                   p.codigo_barras
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
public function obtenerComentariosActivosPorProducto($producto_id) {
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

    public function obtenerTotalProductos()
    {
        $sql = "SELECT COUNT(*) FROM Productos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Retornar el total de productos
        return $stmt->fetchColumn();
    }




    // Obtener un producto por su ID
    public function getById($id)
    {
        $query = "SELECT 
        p.id, p.nombreProducto, p.descripcionProducto, p.codigo_barras, p.precio,
        p.precio_1, p.precio_2, p.precio_3, p.precio_4, p.imagen, 
        p.descuento, p.stock, p.categoria_id, p.subcategoria_id, 
        p.isActive, p.is_promocion, p.puntos_otorgados, p.cantidad_minima_para_puntos,
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

    public function searchProducts($query)
    {
        // Asegúrate de que $query no esté vacío o sea nulo
        if (empty($query)) {
            return [];  // Retorna un arreglo vacío si no se proporciona una consulta
        }

        // Agregar los comodines de búsqueda al término de búsqueda
        $searchTerm = '%' . $query . '%';

        // Consulta SQL para buscar productos, categorías y subcategorías
        $sql = "
        SELECT p.*, c.nombreCategoria, s.nombrSubcategoria
        FROM Productos p
        JOIN Subcategorias s ON p.subcategoria_id = s.id
        JOIN Categorias c ON s.categoria_id = c.id
        WHERE p.nombreProducto COLLATE utf8mb4_general_ci LIKE :queryProducto
        OR c.nombreCategoria COLLATE utf8mb4_general_ci LIKE :queryCategoria
        OR s.nombrSubcategoria COLLATE utf8mb4_general_ci LIKE :querySubcategoria
    ";

        try {
            // Preparamos la sentencia
            $stmt = $this->conn->prepare($sql);

            // Ejecutamos la consulta pasando los parámetros de búsqueda con los comodines
            $stmt->execute([
                ':queryProducto' => $searchTerm,
                ':queryCategoria' => $searchTerm,
                ':querySubcategoria' => $searchTerm
            ]);

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

    //para el sistema de canje de puntos
    public function obtenerProductosConPuntos()
    {
        $query = "SELECT * FROM productos WHERE puntos_otorgados > 0 AND isActive = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
