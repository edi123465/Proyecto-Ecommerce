<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../Config/db.php";
require_once __DIR__ . '/../vendor/autoload.php';

class UsuarioModel
{

    private $conn;
    private $table = 'usuarios';
    public function __construct($db)
    {
        $db = new Database1();
        $this->conn = $db->getConnection(); // AQUÍ debe devolverse el objeto PDO

    }
    public function obtenerUsuario($limit, $offset)
    {
        $query = "SELECT u.ID, u.NombreUsuario, u.Email, u.RolID, u.IsActive, u.FechaCreacion, u.Imagen, u.total_puntos, r.RolName  
                FROM " . $this->table . " u 
                JOIN roles r ON u.RolID = r.ID 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            error_log("Error al ejecutar la consulta: " . implode(" | ", $stmt->errorInfo()));
            return null;
        }
    }

    // Método para obtener el total de usuarios
    public function contarUsuarios()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function crearUsuario($nombreUsuario, $email, $rol_id, $imagen, $isActiveChecked, $contraseñaEncriptada)
    {
        $isActive = $isActiveChecked ? 1 : 0; // Convertir a 1 o 0

        $sql = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen) 
            VALUES (:nombreUsuario, :email, :contrasenia, :rol_id, :isActive, NOW(), :imagen)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contrasenia', $contraseñaEncriptada, PDO::PARAM_STR);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                // Capturar errores específicos de MySQL
                error_log("Error en la consulta SQL: " . implode(" - ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al ejecutar consulta: " . $e->getMessage());
            return false;
        }
    }

    // Método para actualizar un usuario
    public function update($id, $data)
    {
        // Prepara la consulta de actualización
        $sql = "UPDATE usuarios SET 
            NombreUsuario = :NombreUsuario,
            Email = :Email,
            RolID = :RolID,
            IsActive = :IsActive,
            FechaCreacion = :FechaCreacion" .
            (isset($data['Contrasenia']) ? ", Contrasenia = :Contrasenia" : "") .
            " WHERE ID = :id";  // Eliminado el fragmento relacionado con Imagen

        try {
            // Preparar la consulta
            $stmt = $this->conn->prepare($sql);

            // Enlazar parámetros
            $stmt->bindParam(':NombreUsuario', $data['NombreUsuario']);
            $stmt->bindParam(':Email', $data['Email']);
            $stmt->bindParam(':RolID', $data['RolID']);
            $stmt->bindParam(':IsActive', $data['IsActive']);
            $stmt->bindParam(':FechaCreacion', $data['FechaCreacion']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Si hay una nueva contraseña, encriptarla y enlazar
            if (isset($data['Contrasenia'])) {
                $hashedPassword = password_hash($data['Contrasenia'], PASSWORD_DEFAULT);
                $stmt->bindParam(':Contrasenia', $hashedPassword);
            }

            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al actualizar el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false;
        }
    }

    public function getByUserName($username)
    {
        try {
            $sql = "SELECT ID, NombreUsuario, Email, Contrasenia, RolID FROM " . $this->table . " WHERE NombreUsuario = :nombreUsuario";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':nombreUsuario', $username);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el usuario: " . $e->getMessage());
            return false; // O lanzar una excepción dependiendo del manejo que desees
        }
    }

    //Metodos para actualizar la contraseña
    public function getById($id)
    {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $nuevaContrasenia)
    {
        $query = "UPDATE usuarios SET Contrasenia = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nuevaContrasenia, $id]);
    }


    //metodo para obtener el correo por el usuario registrado
    public function getEmailByUserId($userId)
    {
        try {
            $sql = "SELECT Email FROM usuarios" . " WHERE ID = :userId";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['Email'] : null;
        } catch (PDOException $e) {
            error_log("Error al obtener el correo del usuario: " . $e->getMessage());
            return null;
        }
    }
    public function getRoleById($roleId)
    {
        $sql = "SELECT RolName FROM Roles WHERE ID = :roleId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el nombre del rol como array asociativo
    }

    // Método para crear un nuevo cliente desde la tienda virtual en MySQL
    public function createClient($nombreUsuario, $email, $rol_id, $imagen, $isActiveChecked, $contraseñaEncriptada)
    {
        // Verificar si el checkbox de "activo" fue marcado
        $isActive = $isActiveChecked ? 1 : 0;

        // Query ajustado para MySQL
        $sql = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen) 
            VALUES (:nombreUsuario, :email, :contrasenia, :rol_id, :isActive, NOW(), :imagen)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contrasenia', $contraseñaEncriptada, PDO::PARAM_STR);
        $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);

        // Si la imagen es NULL, usa PDO::PARAM_NULL
        if ($imagen === null) {
            $stmt->bindValue(':imagen', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Obtener el ID del usuario recién insertado
            return $this->conn->lastInsertId(); // Este método devuelve el último ID insertado
        }

        return false; // Si no se insertó correctamente
    }

    //crear la cuenta y verifica duplicados
    //metodo que servira para detectar si hay nombre de usuario o correos repetidos
    public function checkIfExist($nombreUsuario, $email)
    {
        $query = "SELECT COUNT(*) FROM usuarios WHERE nombreUsuario = :nombreUsuario OR Email = :Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->bindParam(':Email', $email);
        $stmt->execute();

        // Verificar el valor que retorna la consulta
        $result = $stmt->fetchColumn();
        return $result > 0;  // Retorna true si existe algún duplicado
    }
    //actualizar datros del usuario
    public function checkIfExists($nombreUsuario, $email, $userID = null)
    {
        // Si userID es proporcionado, verificamos los duplicados ignorando ese usuario.
        $query = "SELECT COUNT(*) FROM usuarios WHERE (NombreUsuario = :nombreUsuario OR Email = :Email) AND ID != :userID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':userID', $userID);

        $stmt->execute();

        // Verificar el valor que retorna la consulta
        $result = $stmt->fetchColumn();
        return $result > 0;  // Retorna true si existe algún duplicado
    }

    public function login($username, $password)
    {
        // Consultar si el usuario existe en la base de datos
        $query = "SELECT * FROM Usuarios WHERE nombreUsuario = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el usuario y si la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            // Si la contraseña es correcta, devolver true (login exitoso)
            return true;
        }
        // Si el usuario no existe o la contraseña es incorrecta
        return false;
    }

    // Método para actualizar la información del usuario en MySQL
    public function updateDataClient($userID, $nombreUsuario, $email)
    {
        // Construcción de la consulta SQL
        $sql = "UPDATE usuarios SET NombreUsuario = :nombreUsuario, Email = :email WHERE ID = :userID";

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);

        // Enlazar parámetros
        $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT); // Falta este bindParam

        // Ejecutar la consulta
        return $stmt->execute();
    }
    public function deleteUser($id)
    {
        try {
            // Eliminar los detalles de pedidos asociados a los pedidos del usuario
            $sqlDetalles = "DELETE FROM detallesPedidos WHERE pedido_id IN (SELECT id FROM pedidos WHERE usuario_id = :id)";
            $queryDetalles = $this->conn->prepare($sqlDetalles);
            $queryDetalles->bindParam(":id", $id, PDO::PARAM_INT);
            $queryDetalles->execute();

            // Eliminar los pedidos del usuario
            $sqlPedidos = "DELETE FROM pedidos WHERE usuario_id = :id";
            $queryPedidos = $this->conn->prepare($sqlPedidos);
            $queryPedidos->bindParam(":id", $id, PDO::PARAM_INT);
            $queryPedidos->execute();

            // Finalmente, eliminar el usuario
            $sqlUsuario = "DELETE FROM usuarios WHERE id = :id";
            $queryUsuario = $this->conn->prepare($sqlUsuario);
            $queryUsuario->bindParam(":id", $id, PDO::PARAM_INT);

            return $queryUsuario->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function existeCorreo($email)
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function guardarTokenRecuperacion($email, $token)
    {
        $sql = "UPDATE usuarios SET token_recuperacion = :token WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function verificarToken($token)
    {
        $sql = "SELECT email FROM usuarios WHERE token_recuperacion = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPassword($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET Contrasenia = :contrasenia, token_recuperacion = NULL WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":contrasenia", $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function getPuntos($usuario_id)
    {
        $query = "SELECT total_puntos FROM usuarios WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['total_puntos'] : 0;
    }

    // Método para actualizar los puntos del usuario
    public function sumarPuntosUsuario($usuario_id, $puntos)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET total_puntos = ISNULL(total_puntos, 0) + ? WHERE id = ?");
            return $stmt->execute([$puntos, $usuario_id]);
        } catch (PDOException $e) {
            error_log("Error al sumar puntos al usuario: " . $e->getMessage());
            return false;
        }
    }

    public function descontarPuntos($usuarioId, $puntosUsar) {
        $stmt = $this->conn->prepare("SELECT total_puntos FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
        $puntosActuales = $stmt->fetchColumn();
    
        if ($puntosActuales >= $puntosUsar) {
            $nuevoTotal = $puntosActuales - $puntosUsar;
            $stmtUpdate = $this->conn->prepare("UPDATE usuarios SET total_puntos = ? WHERE id = ?");
            $stmtUpdate->execute([$nuevoTotal, $usuarioId]);
    
            return [
                'status' => 'ok',
                'puntos' => $nuevoTotal
            ];
        } else {
            return [
                'status' => 'error',
                'mensaje' => 'No tienes suficientes puntos para canjear este producto.'
            ];
        }
    }

    
 // UsuarioModel.php
public function actualizarPuntosPorCantidad($usuarioId, $puntosUnitarios, $cantidad) {
    $stmt = $this->conn->prepare("SELECT total_puntos FROM usuarios WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $puntosActuales = $stmt->fetchColumn();

    $totalPuntosUsar = $puntosUnitarios * $cantidad;

    if ($puntosActuales >= $totalPuntosUsar) {
        $nuevoTotal = $puntosActuales - $totalPuntosUsar;
        return [
            'status' => 'ok',
            'nuevoTotal' => $nuevoTotal,
            'puedeCanjear' => true,
            'mensaje' => 'Puntos suficientes'
        ];
    } else {
        return [
            'status' => 'error',
            'nuevoTotal' => $puntosActuales,
            'puedeCanjear' => false,
            'mensaje' => 'No tienes suficientes puntos para esta cantidad'
        ];
    }
}

public function actualizarPuntosDinamico($usuarioId, $puntosUnitarios, $cantidad)
{
    $stmt = $this->conn->prepare("SELECT total_puntos FROM usuarios WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $puntosActuales = $stmt->fetchColumn();

    $puntosPorUsar = $puntosUnitarios * $cantidad;

    if ($puntosActuales >= $puntosPorUsar) {
        $nuevoTotal = $puntosActuales - $puntosPorUsar;

        $stmtUpdate = $this->conn->prepare("UPDATE usuarios SET total_puntos = ? WHERE id = ?");
        $stmtUpdate->execute([$nuevoTotal, $usuarioId]);

        return [
            'status' => 'ok',
            'nuevoTotal' => $nuevoTotal
        ];
    } else {
        return [
            'status' => 'error',
            'mensaje' => 'No tienes suficientes puntos disponibles.'
        ];
    }
}

}
