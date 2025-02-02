<?php

class ProductoModel
{

    private $conn;
    private $table = 'Productos';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertarProducto($nombre, $descripcion, $precio, $precio1, $precio2, $precio3, $precio4, $stock, $subcategoria_id, $codigo_barras, $imagen, $isActive, $is_promocion, $descuento, $categoria_id)
    {
        try {
            // Consulta SQL para insertar el producto
            $query = "
        INSERT INTO Productos (
            nombreProducto, descripcionProducto, precio, precio_1, precio_2, 
            precio_3, precio_4, stock, subcategoria_id, codigo_barras, imagen, 
            isActive, fechaCreacion, is_promocion, descuento, categoria_id
        ) 
        VALUES (
            :nombre, :descripcion, :precio, :precio_1, :precio_2, :precio_3, 
            :precio_4, :stock, :subcategoria_id, :codigo_barras, :imagen, 
            :isActive, GETDATE(), :is_promocion, :descuento, :categoria_id
        )";

            $stmt = $this->conn->prepare($query);

            // Vincular parámetros a la consulta con tipo de dato STR (cadena de texto)
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);  // Aunque es un número decimal, puedes usar STR si es necesario
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


    //metodo para obtener todos los productos (parte administrativa y tienda virtual)
    public function getAll()
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
                        c.nombreCategoria AS nombreCategoria, 
                        s.nombrSubcategoria AS nombreSubcategoria
                    FROM Productos p
                    INNER JOIN subcategorias s ON p.subcategoria_id = s.id
                    INNER JOIN categorias c ON s.categoria_id = c.id
                    ORDER BY p.nombreProducto ASC;
                 ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $productos;
        } catch (Exception $e) {
            // Retorna false si hay error
            return false;
        }
    }


    //METODO PARA OBTENER LOS PRODUCTOS CON PROMOCION
    public function read()
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
                     c.nombreCategoria AS nombreCategoria, 
                     s.nombrSubcategoria AS nombreSubcategoria
                 FROM Productos p
                 INNER JOIN subcategorias s ON p.subcategoria_id = s.id
                 INNER JOIN categorias c ON s.categoria_id = c.id
                 WHERE p.is_promocion = 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $productos;
        } catch (Exception $e) {
            // Retorna false si hay error
            return false;
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
        p.id, p.nombreProducto, p.descripcionProducto, p.codigo_barras,p.precio,
        p.precio_1, p.precio_2, p.precio_3, p.precio_4, p.imagen, 
        p.descuento, p.stock, p.categoria_id, p.subcategoria_id, 
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
        // La consulta SQL para actualizar los datos de un producto, sin modificar la fecha de creación
        $query = "UPDATE Productos SET 
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
            is_promocion = :is_promocion, 
            descuento = :descuento, 
            categoria_id = :categoria_id 
            WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // Vincular los parámetros con los datos
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
        $stmt->bindParam(':imagen', $data['imagen']); // Asegúrate de que la imagen esté aquí
        $stmt->bindParam(':isActive', $data['isActive']); // Esto debe ser correcto
        $stmt->bindParam(':is_promocion', $data['isPromocion']); // Nueva bandera de promoción
        $stmt->bindParam(':descuento', $data['descuento']); // Nuevo campo de descuento
        $stmt->bindParam(':categoria_id', $data['categoria_id']); // Nueva categoría asociada
        $stmt->bindParam(':id', $id); // Aquí pasas el ID directamente
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            // Si hay un error, obtener información sobre el mismo
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

    // Leer (obtener) todos los productos
    public function ObtenerProductosPopulares()
    {
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
           c.nombreCategoria AS nombreCategoria, 
           s.nombrSubcategoria AS nombreSubcategoria
            FROM productos p
            INNER JOIN subcategorias s ON p.subcategoria_id = s.id  -- Relacionar productos con subcategorías
            INNER JOIN categorias c ON s.categoria_id = c.id;  -- Relacionar subcategorías con categorías";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    //FILTROS DE INFORMACION
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
            WHERE p.nombreProducto COLLATE SQL_Latin1_General_CP1_CI_AS LIKE :queryProducto
            OR c.nombreCategoria COLLATE SQL_Latin1_General_CP1_CI_AS LIKE :queryCategoria
            OR s.nombrSubcategoria COLLATE SQL_Latin1_General_CP1_CI_AS LIKE :querySubcategoria
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
}
