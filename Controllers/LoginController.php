<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Milogar/Models/UsuarioModel.php';

class LoginController
{

    private $usuariomodel;
    private $conn;
    private $model;

    public function __construct($connection)
    {
        // Crear una instancia de la base de datos
        $db = new Database1();
        $conn = $db->getConnection();  // Obtener la conexión PDO
        $this->model = new UsuarioModel($conn);  // Pasar la conexión PDO al modelo
    }

    public function login()
    {
        session_start();
        header('Content-Type: application/json'); // Asegurar encabezado JSON
    
        // Registrar los datos recibidos en el archivo de logs
        error_log("Datos POST recibidos: " . print_r($_POST, true));
    
        // Obtener los datos enviados desde el formulario
        $username = $_POST['txt_nombreUsuario'] ?? '';
        $password = $_POST['txt_password'] ?? '';
    
        // Registrar los datos después de decodificar
        error_log("Username: $username, Password: $password");
    
        // Verificar si es un invitado (sin nombre de usuario ni contraseña)
        if (empty($username) && empty($password)) {
            $_SESSION['user_id'] = null;
            $_SESSION['user_name'] = 'Invitado';
            $_SESSION['is_logged_in'] = true;
            $_SESSION['show_welcome_alert'] = true;
    
            error_log("Inicio de sesión como invitado.");
    
            echo json_encode([
                'success' => true,
                'message' => 'Bienvenido como invitado.',
                'redirect' => 'index.php',
            ]);
            return;
        }        
        // Obtener el usuario por nombre de usuario
        $usuario = $this->model->getByUserName($username);
    
        // Registrar el resultado de la búsqueda del usuario
        error_log("Resultado de búsqueda de usuario: " . print_r($usuario, true));
    
        if ($usuario) {
            if (password_verify($password, $usuario['Contrasenia'])) {
                $email = $usuario['Email'];  // Ahora tenemos el correo electrónico del usuario

                $role = $this->model->getRoleById($usuario['RolID']);
    
                $_SESSION['user_id'] = $usuario['ID'];
                $_SESSION['user_name'] = $usuario['NombreUsuario'];
                $_SESSION['user_email'] = $email;  // Guardar el correo electrónico en la sesión

                $_SESSION['user_role'] = $role['RolName'];
                $_SESSION['is_logged_in'] = true;
                $_SESSION['show_welcome_alert'] = true;
    
                $redirect = '';

                if ($_SESSION['user_role'] === 'Administrador') {
                    $redirect = '/Milogar/menu';  // Redirigir a la vista del panel administrativo
                } elseif ($_SESSION['user_role'] === 'Colaborador') {
                    $redirect = '/Milogar/index.php';  // Redirigir a la tienda
                } else {
                    $redirect = '/Milogar/index.php';  // O redirigir a la página de inicio u otra vista por defecto
                }    
                error_log("Inicio de sesión exitoso. Redirigiendo a: $redirect");
    
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso.',
                    'redirect' => $redirect,
                ]);
                return;
            } else {
                error_log("Contraseña incorrecta para el usuario: $username");
    
                echo json_encode([
                    'success' => false,
                    'message' => 'Contraseña incorrecta.',
                ]);
                return;
            }
        } else {
            error_log("Usuario no encontrado: $username");
    
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ]);
            return;
        }
    }
    
    

    public function logout()
    {
        session_destroy(); // Destruir la sesión
        header('Location: ../login.php'); // Redirigir a la página de inicio
        exit;
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];  // Obtener la acción de la URL

    // Dependiendo de la acción, ejecutamos diferentes métodos para manejar los usuarios
    switch ($action) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');
                // Leer el cuerpo de la solicitud en formato JSON
                $input = json_decode(file_get_contents('php://input'), true);
                try {
                    // Crear instancia de la base de datos y el controlador
                    $db = new Database1();
                    $controller = new LoginController($db);

                    // Ejecutar el método 'suscribirse' en el controlador
                    $controller->login($input);
                } catch (Exception $e) {
                    // Capturar errores y devolver respuesta JSON
                    error_log("Error al ejecutar suscripción: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Error al realizar la suscripción.']);
                }
            }else {
                error_log("Método no permitido, solo se permite POST.");
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            }
            break;
        default:
            // Si la acción no es válida, retornar un error
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida.'
            ]);
            break;
    }
} else {
    // Si no se pasa la acción, retornar un error
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida o no especificada.'
    ]);
}
