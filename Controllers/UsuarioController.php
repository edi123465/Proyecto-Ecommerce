<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../Config/db.php'; // Asegúrate de requerir el modelo
require_once __DIR__ . '/../Models/UsuarioModel.php'; // Asegúrate de requerir el modelo
require_once __DIR__ . '/../vendor/autoload.php';

class UsuarioController
{

    private $usuariomodel;
    private $conn;
    private $model;

    public function __construct($db)
    {
        // Crear una instancia de la base de datos
        $db = new Database1();
        $conn = $db->getConnection();  // Obtener la conexión PDO
        $this->model = new UsuarioModel($conn);  // Pasar la conexión PDO al modelo
    }

    public function read()
    {
        // Establecer el encabezado para que la respuesta sea en formato JSON
        header('Content-Type: application/json');

        // Obtener los usuarios desde el modelo
        $usuarios = $this->model->obtenerUsuario();

        if ($usuarios === null) {
            // Si no se obtienen usuarios, devolver un mensaje de error en formato JSON
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener usuarios.'
            ]);
            return;
        }

        // Si la consulta tiene resultados, mostrar cuántos usuarios se obtuvieron
        error_log("Usuarios obtenidos: " . count($usuarios));

        // Devolver los usuarios en formato JSON
        echo json_encode([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }


    public function create()
    {
        header('Content-Type: application/json');

        try {
            error_log(date('Y-m-d H:i:s') . " - Inicio del método create.");

            // Registrar los datos recibidos para depuración
            error_log('Datos $_POST recibidos: ' . print_r($_POST, true));
            error_log('Datos $_FILES recibidos: ' . print_r($_FILES, true));

            // Validar si los datos necesarios fueron enviados
            if (!isset($_POST['NombreUsuario'], $_POST['Email'], $_POST['RolID'])) {
                error_log(date('Y-m-d H:i:s') . " - Error: Datos incompletos en la solicitud.");
                echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
                return;
            }

            error_log(date('Y-m-d H:i:s') . " - Datos recibidos correctamente.");

            // Obtener datos de la solicitud
            $nombreUsuario = trim($_POST['NombreUsuario']);
            $email = trim($_POST['Email']);
            $rol_id = intval($_POST['RolID']);
            $isActiveChecked = isset($_POST['IsActive']) && $_POST['IsActive'] === 'on'; // Checkbox "activo"

            // Manejo de la imagen: si no se sube, asignar null
            $imagen = null;
            if (!empty($_FILES['Imagen']['name'])) {
                $imagen = $this->handleImageUpload();  // Llama al método para manejar la imagen
            }

            // Registrar los valores procesados
            error_log('Nombre de usuario: ' . $nombreUsuario);
            error_log('Email: ' . $email);
            error_log('Rol ID: ' . $rol_id);
            error_log('Imagen: ' . print_r($imagen, true));

            // Validar que los datos esenciales no estén vacíos
            if (empty($nombreUsuario) || empty($email) || $rol_id <= 0) {
                error_log(date('Y-m-d H:i:s') . " - Error: Datos inválidos o incompletos.");
                echo json_encode(['success' => false, 'message' => 'Datos inválidos o incompletos.']);
                return;
            }

            error_log(date('Y-m-d H:i:s') . " - Validación de datos completada.");

            // Verificar si la contraseña fue enviada, de no ser así, generar una temporal
            $contraseña = isset($_POST['contrasenia']) && !empty($_POST['contrasenia']) ? $_POST['contrasenia'] : $this->generarContraseñaTemporal();

            // Encriptar la contraseña
            $contraseñaEncriptada = password_hash($contraseña, PASSWORD_BCRYPT);

            error_log(date('Y-m-d H:i:s') . " - Contraseña encriptada correctamente.");

            // Llamar al método del modelo para crear el usuario
            $result = $this->model->crearUsuario($nombreUsuario, $email, $rol_id, $imagen, $isActiveChecked, $contraseñaEncriptada);

            if ($result) {
                error_log(date('Y-m-d H:i:s') . " - Usuario creado correctamente: $nombreUsuario");

                // Si se generó una contraseña temporal, enviarla por correo utilizando el método enviarCorreo
                if (empty($_POST['contrasenia'])) {
                    try {
                        $this->enviarCorreo($email, $nombreUsuario, $contraseña);
                        error_log(date('Y-m-d H:i:s') . " - Correo de bienvenida enviado a: $email");
                    } catch (Exception $e) {
                        error_log(date('Y-m-d H:i:s') . " - Error al enviar el correo: " . $e->getMessage());
                    }
                }

                echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
            } else {
                error_log(date('Y-m-d H:i:s') . " - Error al crear usuario en la base de datos: $nombreUsuario");
                echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
                return;
            }
        } catch (Exception $e) {
            error_log(date('Y-m-d H:i:s') . " - Excepción capturada: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            return; // Asegúrate de detener el flujo
        }
    }



    public function generarContraseñaTemporal($longitud = 8)
    {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $contraseña = '';
        $longitudCaracteres = strlen($caracteres);

        for ($i = 0; $i < $longitud; $i++) {
            $contraseña .= $caracteres[rand(0, $longitudCaracteres - 1)];
        }

        return $contraseña;
    }

    public function enviarCorreo($email, $nombreUsuario, $contraseñaTemporal)
    {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'ddonmilo100@gmail.com'; // Tu correo de Gmail
            $mail->Password = 'nkwm cmzc ezlp dwvl'; // Tu contraseña de Gmail o contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente
            $mail->setFrom('ddonmilo100@gmail.com', 'Soporte'); // Correo del administrador o el correo desde el que se envía
            $mail->addAddress($email); // Correo del destinatario (el que ingresa el usuario)

            // Si deseas permitir que el usuario responda al correo, puedes agregar esto:
            $mail->addReplyTo($email, $nombreUsuario); // Responder al correo del usuario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "Cuenta creada en MILOGAR: Bienvenido $nombreUsuario"; // Asunto actualizado
            $mail->Body    = "
            <html>
            <head>
                <title>Cuenta creada en MILOGAR</title>
            </head>
            <body>
                <p>Hola $nombreUsuario,</p>
                <p>Tu cuenta en MILOGAR ha sido creada exitosamente. A continuación te proporcionamos tus credenciales de acceso:</p>
                <p><strong>Nombre de usuario:</strong> $nombreUsuario</p>
                <p><strong>Contraseña temporal:</strong> $contraseñaTemporal</p>
                <p>Por razones de seguridad, te pedimos que cambies tu contraseña en tu primera visita a la tienda virtual, en el apartado <strong>MI CUENTA</strong>.</p>
                <p>Te damos la bienvenida a MILOGAR, ¡esperamos que disfrutes de la experiencia de compra!</p>
                <p>Saludos,</p>
                <p>El equipo de soporte de MILOGAR</p>
            </body>
            </html>
        ";

            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
            throw new Exception('No se pudo enviar el correo de bienvenida: ' . $mail->ErrorInfo);
        }
    }


    // Función para manejar la subida de la imagen
    private function handleImageUpload()
    {
        // Verifica si se subió una imagen
        if (!empty($_FILES['Imagen']['name'])) {
            // Configuración del directorio y formatos permitidos
            $directorio = $_SERVER['DOCUMENT_ROOT'] . "/assets/imagenesMilogar/Usuarios";
            $nombreOriginal = basename($_FILES['Imagen']['name']);
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $rutaRelativa = $directorio . '/' . $nombreOriginal;

            $extensionesPermitidas = ['jpg', 'jpeg', 'png'];

            // Validar formato de imagen
            if (!in_array($extension, $extensionesPermitidas)) {
                throw new Exception('Extensión de imagen no permitida. Solo se aceptan PNG, JPG y JPEG.');
            }

            // Validar tamaño de imagen (máximo 5MB)
            if ($_FILES['Imagen']['size'] > 5 * 1024 * 1024) {
                throw new Exception('El tamaño de la imagen no debe superar los 5MB.');
            }

            // Crear el directorio solo si no existe
            if (!file_exists($directorio)) {
                if (!mkdir($directorio, 0777, true)) {
                    throw new Exception('Error al crear el directorio para almacenar la imagen.');
                }
            }

            // Mover la imagen al directorio especificado
            if (!move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaRelativa)) {
                throw new Exception('Error al subir la imagen.');
            }

            // Devolver el nombre original del archivo
            return $nombreOriginal;
        }

        // Si no se subió una imagen, devuelve null
        return null;
    }


    // Método para actualizar un usuario
    public function update($id, $data)
    {
        // Llamar al método del modelo para actualizar
        $result = $this->usuariomodel->update($id, $data);

        // Verificar el resultado de la actualización
        if ($result) {
            echo "Usuario actualizado correctamente.";
        } else {
            echo "Hubo un problema al actualizar el usuario.";
        }
    }
    public function delete($id)
    {
        // Verificar que el ID sea un número entero
        if (!is_numeric($id)) {
            echo "ID no válido.";
            return false;
        }

        // Llamar al método delete del modelo
        $usuarioModel = new UsuarioModel($this->conn);
        if ($usuarioModel->delete($id)) {
            // Redirigir a la lista de usuarios después de la eliminación exitosa
            header("Location: index.php?success=1"); // Cambia la ruta si es necesario
            exit; // Asegúrate de detener la ejecución después de redirigir
        } else {
            echo "Error al eliminar el usuario.";
        }
    }
    public function suscribirse($input)
{
    session_start();  // Asegúrate de iniciar la sesión aquí
    header('Content-Type: application/json');

    try {
        // Verificar si el usuario ya está logueado
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            error_log("El usuario ya está logueado, no puede crear una nueva cuenta.");

            // Enviar respuesta indicando que el usuario ya está logueado
            echo json_encode([
                'success' => false,
                'message' => 'Ya has iniciado sesión, no puedes crear una nueva cuenta.',
                'redirect' => 'index.php'  // Redirigir al index o página principal
            ]);
            return;
        }

        // Decodificar el cuerpo de la solicitud JSON
        $input = json_decode(file_get_contents("php://input"), true); // Decodificar el JSON

        error_log("Datos recibidos en suscribirse: " . print_r($input, true));

        // Sanitizar y preparar los valores
        $nombreUsuario = htmlspecialchars(trim($input['name']));
        $email = filter_var(trim($input['email']), FILTER_VALIDATE_EMAIL);
        $password = password_hash(trim($input['password']), PASSWORD_BCRYPT); // Cifrar contraseña
        $rolId = 20; // Asumimos que '20' es el RolID para cliente
        $status = 1; // Usuario activo
        $imagen = "sin_imagen"; // Imagen por defecto

        // Validar el email
        if (!$email) {
            throw new Exception("El correo electrónico no es válido.");
        }

        // Llamar al método del modelo para crear el usuario
        $result = $this->model->createClient($nombreUsuario, $email, $rolId, $imagen, $status, $password);

        if ($result) {
            // Guardar el nombre del usuario en la sesión
            $_SESSION['user_name'] = $nombreUsuario;
            $_SESSION['is_logged_in'] = true;  // Marcar al usuario como logueado

            // Redirigir con el mensaje de éxito
            error_log("Usuario registrado exitosamente: $email");
            error_log("Usuario registrado exitosamente con nombre de usuario: " . $_SESSION['user_name']);

            echo json_encode([
                'success' => true,
                'message' => 'Suscripción realizada exitosamente.',
                'redirect' => 'nombre_vista_destino.php'  // Aquí debes poner la vista de destino
            ]);
        } else {
            error_log("Error al registrar usuario. Posiblemente el correo ya está registrado: $email");
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado.']);
        }
    } catch (Exception $e) {
        error_log("Error en suscribirse: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

    // metodo que servira para detectar si hay duplicados tanto en el nombre del usuario como su email.
    public function checkDuplicate($input)
    {
        header('Content-Type: application/json');

        try {
            // Decodificar el cuerpo de la solicitud JSON
            $input = json_decode(file_get_contents("php://input"), true);

            $nombreUsuario = htmlspecialchars(trim($input['name']));
            $email = filter_var(trim($input['email']), FILTER_VALIDATE_EMAIL);

            // Validar email
            if (!$email) {
                echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
                return;
            }

            // Verificar si el nombre de usuario o correo electrónico ya existen
            if ($this->model->checkIfExists($nombreUsuario, $email)) {
                echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el correo electrónico ya están registrados.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'El nombre de usuario y el correo electrónico están disponibles.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al verificar la disponibilidad.']);
        }
    }
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];  // Obtener la acción de la URL

    // Dependiendo de la acción, ejecutamos diferentes métodos para manejar los usuarios
    switch ($action) {
        case 'read':
            // Conectar a la base de datos
            $db = new Database1(); // O como sea que estés instanciando tu conexión

            // Crear instancia del controlador de usuarios
            $controller = new UsuarioController($db);
            // Si la acción es 'read', llamar al método que obtiene todos los usuarios
            $controller->read();  // Método para obtener todos los usuarios
            break;
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller = new UsuarioController(new Database1());
                $controller->create();
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Método no permitido.'
                ]);
            }
            break;
        case 'edit':
            // Obtener los datos recibidos en formato JSON
            $data = json_decode(file_get_contents('php://input'), true);  // Leer datos JSON

            // Verificar que los datos están presentes
            if ($data && isset($data['userId'])) {
                $db = new Database1();  // Instanciación de la base de datos
                $controller = new UsuarioController($db);  // Crear instancia del controlador
                //$controller->edit($data);  // Llamar al método para actualizar el usuario

                // Respuesta exitosa
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario actualizado con éxito.'
                ]);
            } else {
                // Si los datos no están disponibles o son inválidos
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos inválidos o faltantes.'
                ]);
            }
            break;
        case 'suscribirse':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');

                // Leer el cuerpo de la solicitud en formato JSON
                $input = json_decode(file_get_contents('php://input'), true);

                try {
                    // Crear instancia de la base de datos y el controlador
                    $db = new Database1();
                    $controller = new UsuarioController($db);

                    // Ejecutar el método 'suscribirse' en el controlador
                    $controller->suscribirse($input);
                } catch (Exception $e) {
                    // Capturar errores y devolver respuesta JSON
                    error_log("Error al ejecutar suscripción: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Error al realizar la suscripción.']);
                }
            } else {
                error_log("Método no permitido, solo se permite POST.");
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            }
            break;
        case 'checkDuplicate':  // Este es el nuevo case que agregas
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');

                // Leer el cuerpo de la solicitud en formato JSON
                $input = json_decode(file_get_contents('php://input'), true);

                try {
                    // Crear instancia de la base de datos y el controlador
                    $db = new Database1();
                    $controller = new UsuarioController($db);

                    // Ejecutar el método 'checkDuplicate' en el controlador
                    $controller->checkDuplicate($input);
                } catch (Exception $e) {
                    // Capturar errores y devolver respuesta JSON
                    error_log("Error al ejecutar la validación de duplicados: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Error al validar duplicados.']);
                }
            } else {
                error_log("Método no permitido, solo se permite POST.");
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            }
            break;

        case 'delete':
            // Verificar que se pasa el ID del usuario a eliminar
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $userId = $_GET['id'];

                // Conectar a la base de datos
                $db = new Database1();
                $controller = new UsuarioController($db); // Instancia del controlador

                // Llamar al método para eliminar el usuario
                $controller->delete($userId);

                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado con éxito.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID del usuario no proporcionado o no válido.'
                ]);
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
