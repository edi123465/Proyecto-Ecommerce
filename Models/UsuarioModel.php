<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../Config/db.php";
require_once __DIR__ . '/../vendor/autoload.php';

class UsuarioModel
{

    private $conn;
    private $table = 'Usuarios';
    private $model;
    public function __construct($db)
    {
        $this->conn = $db;
        
    }

    public function obtenerUsuario()
    {
        // Consulta SQL que une la tabla 'usuarios' con la tabla 'roles'
        $query = "SELECT u.ID, u.NombreUsuario, u.Email, u.RolID, u.IsActive, u.FechaCreacion, u.Imagen, r.RolName  
                  FROM " . $this->table . " u 
                  JOIN Roles r ON u.RolID = r.ID";  // Relación entre usuarios y roles

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la consulta es exitosa, devolver los resultados como un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si hay un error, obtener la información del error
            $errorInfo = $stmt->errorInfo();
            // Es importante registrar los errores para depuración
            error_log("Error al ejecutar la consulta: " . $errorInfo[2]);

            // Retornar null en caso de error
            return null;
        }
    }

    public function crearUsuario($nombreUsuario, $email, $rol_id, $imagen, $isActiveChecked, $contraseñaEncriptada)
    {
        // Verificar si el checkbox de "activo" fue marcado
        $isActive = $isActiveChecked ? 1 : 0;

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen) 
                VALUES (:nombreUsuario, :email, :contrasenia, :rol_id, :isActive, GETDATE(), :imagen)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contrasenia', $contraseñaEncriptada); // Ahora se pasa la contraseña encriptada correctamente
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->bindParam(':isActive', $isActive);  // Pasamos el valor de isActive dinámicamente
        $stmt->bindParam(':imagen', $imagen);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    // Método para actualizar un usuario
    public function update($id, $data)
    {
        // Prepara la consulta de actualización
        $sql = "UPDATE Usuarios SET 
                NombreUsuario = :NombreUsuario,
                Email = :Email,
                RolID = :RolID,
                IsActive = :IsActive,
                FechaCreacion = :FechaCreacion" .
            (isset($data['Contrasenia']) ? ", Contrasenia = :Contrasenia" : "") .
            (isset($data['Imagen']) && !empty($data['Imagen']) ? ", Imagen = :Imagen" : "") .
            " WHERE ID = :id";

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

            // Si hay una nueva imagen, enlazarla
            if (isset($data['Imagen']) && !empty($data['Imagen'])) {
                $stmt->bindParam(':Imagen', $data['Imagen']);
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
            $sql = "SELECT * FROM " . $this->table . " WHERE NombreUsuario = :nombreUsuario";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':nombreUsuario', $username);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el usuario: " . $e->getMessage());
            return false; // O lanzar una excepción dependiendo del manejo que desees
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

    //metodo para crear un nuevo cliente desde la tienda virtual
    public function createClient($nombreUsuario, $email, $rol_id, $imagen, $isActiveChecked, $contraseñaEncriptada)
    {
        // Verificar si el checkbox de "activo" fue marcado
        $isActive = $isActiveChecked ? 1 : 0;

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen) 
                VALUES (:nombreUsuario, :email, :contrasenia, :rol_id, :isActive, GETDATE(), :imagen)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contrasenia', $contraseñaEncriptada, PDO::PARAM_STR);
        $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    //metodo que servira para detectar si hay nombre de usuario o correos repetidos
    public function checkIfExists($nombreUsuario, $email)
    {
        $query = "SELECT COUNT(*) FROM Usuarios WHERE nombreUsuario = :nombreUsuario OR Email = :Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->bindParam(':Email', $email);
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
}
