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
        header('Content-Type: application/json');

        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $usuarios = $this->model->obtenerUsuario($limit, $offset);
        $totalUsuarios = $this->model->contarUsuarios();

        if ($usuarios === null) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener usuarios.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'usuarios' => $usuarios,
            'total' => $totalUsuarios,
            'page' => $page,
            'limit' => $limit
        ]);
    }



    public function create()
    {
        header('Content-Type: application/json');

        try {
            error_log(date('Y-m-d H:i:s') . " - Inicio del método create.");

            // Registrar los datos recibidos para depuración
            error_log('Datos $_POST recibidos: ' . print_r($_POST, true));

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

            // Registrar los valores procesados
            error_log('Nombre de usuario: ' . $nombreUsuario);
            error_log('Email: ' . $email);
            error_log('Rol ID: ' . $rol_id);

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
            $result = $this->model->crearUsuario($nombreUsuario, $email, $rol_id, null, $isActiveChecked, $contraseñaEncriptada);

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
            $mail->Password = 'fjju ugeu xrrq vrrd'; // Tu contraseña de Gmail o contraseña de aplicación
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




    // Método para actualizar un usuario
    public function actualizarUsuario($id)
    {
        // Verificar si la solicitud es un POST (usualmente será así con fetch)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos de la solicitud JSON
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar que los datos estén presentes
            if (!isset($data['NombreUsuario']) || !isset($data['Email']) || !isset($data['RolID']) || !isset($data['IsActive']) || !isset($data['FechaCreacion'])) {
                echo json_encode(['error' => 'Faltan datos necesarios']);
                return;
            }

            // Llamar al método del modelo para actualizar
            $result = $this->model->update($id, $data);

            // Verificar el resultado de la actualización y devolver una respuesta JSON
            if ($result) {
                echo json_encode(['success' => 'Usuario actualizado correctamente.']);
            } else {
                echo json_encode(['error' => 'Hubo un problema al actualizar el usuario.']);
            }
        } else {
            echo json_encode(['error' => 'Método de solicitud no permitido']);
        }
    }

    // Método para eliminar usuario en el controlador
    public function eliminarUsuario($id)
    {
        // Llamar al modelo que tiene el método delete
        if ($this->model->delete($id)) {
            echo json_encode(["success" => true, "message" => "Usuario eliminado exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Hubo un error al eliminar el usuario."]);
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
                $_SESSION['user_id'] = $result;

                $_SESSION['user_name'] = $nombreUsuario;
                $_SESSION['user_email'] = $email;
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
    public function checkDuplicates($input)
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
            if ($this->model->checkIfExist($nombreUsuario, $email)) {
                echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el correo electrónico ya están registrados.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'El nombre de usuario y el correo electrónico están disponibles.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al verificar la disponibilidad.']);
        }
    }
    public function checkDuplicate($input)
    {
        header('Content-Type: application/json');

        try {
            // Decodificar el cuerpo de la solicitud JSON
            $input = json_decode(file_get_contents("php://input"), true);

            $nombreUsuario = htmlspecialchars(trim($input['name']));
            $email = filter_var(trim($input['email']), FILTER_VALIDATE_EMAIL);
            $userID = isset($input['userID']) ? $input['userID'] : null; // Obtener el ID del usuario a actualizar

            // Validar email
            if (!$email) {
                echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
                return;
            }

            // Verificar si el nombre de usuario o correo electrónico ya existen, ignorando al usuario que está actualizando
            if ($this->model->checkIfExists($nombreUsuario, $email, $userID)) {
                echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el correo electrónico ya están registrados.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'El nombre de usuario y el correo electrónico están disponibles.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al verificar la disponibilidad.']);
        }
    }

    public function actualizarDatosCliente()
    {
        header('Content-Type: application/json');

        // Asegúrate de registrar cuando se llama al método
        error_log("Método actualizarDatosCliente iniciado");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            // Obtener los datos enviados desde el frontend
            $data = json_decode(file_get_contents('php://input'), true);

            // Registra los datos recibidos
            error_log("Datos recibidos del frontend: " . print_r($data, true));

            if (isset($data['userID'], $data['nombreUsuario'], $data['email'])) {
                // Instancia de la base de datos
                $database = new Database1();
                $db = $database->getConnection();

                // Instancia del modelo de usuario
                $userModel = new UsuarioModel($db);

                // Verificar si el nombre de usuario o el correo ya están en uso por otro usuario
                $existe = $userModel->checkIfExists($data['nombreUsuario'], $data['email'], $data['userID']);

                // Log para verificar la respuesta de la verificación de duplicados
                error_log("Resultado de checkIfExists para usuarioID " . $data['userID'] . ": " . ($existe ? 'Duplicado encontrado' : 'No duplicado'));

                if ($existe) {
                    echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el correo electrónico ya están en uso.']);
                    return; // Detener la ejecución si hay duplicados
                }

                // Llamar al método updateDataClient del modelo
                $resultado = $userModel->updateDataClient($data['userID'], $data['nombreUsuario'], $data['email']);

                // Registra el resultado de la actualización
                error_log("Resultado de la actualización de datos para el usuarioID " . $data['userID'] . ": " . ($resultado ? 'Actualización exitosa' : 'Error en la actualización'));

                // Responder al frontend
                if ($resultado) {
                    // Actualizar el nombre de usuario en la sesión
                    $_SESSION['user_name'] = $data['nombreUsuario']; // Reemplazar con el nuevo nombre de usuario

                    // Responder al frontend indicando que la actualización fue exitosa
                    echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
    }

    public function cambiarContrasenia()
    {
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!$datos) {
            echo json_encode(["mensaje" => "No se recibieron datos."]);
            return;
        }

        $id = $datos['id'];
        $contrasenia_actual = $datos['contrasenia_actual'];
        $nueva_contrasenia = $datos['nueva_contrasenia'];

        // Obtener usuario por ID
        $usuario = $this->model->getById($id);

        if (!$usuario) {
            echo json_encode(["mensaje" => "Usuario no encontrado."]);
            return;
        }

        // Verificar si la contraseña actual es correcta
        if (!password_verify($contrasenia_actual, $usuario['Contrasenia'])) {
            echo json_encode(["mensaje" => "La contraseña actual es incorrecta."]);
            return;
        }

        // Hashear la nueva contraseña antes de actualizarla
        $nueva_contrasenia_hashed = password_hash($nueva_contrasenia, PASSWORD_DEFAULT);

        // Actualizar contraseña
        if ($this->model->updatePassword($id, $nueva_contrasenia_hashed)) {
            echo json_encode(["mensaje" => "Contraseña actualizada correctamente."]);
        } else {
            echo json_encode(["mensaje" => "Error al actualizar la contraseña."]);
        }
    }
    //metodo para eliminar una cuenta de usuario 
    public function eliminarCuenta()
    {
        session_start();  // Iniciar sesión para poder destruirla
        header('Content-Type: application/json');

        try {
            // Obtener el ID del usuario desde la sesión
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(["success" => false, "mensaje" => "No has iniciado sesión."]);
                return;
            }

            $id = $_SESSION['user_id'];

            // Eliminar el usuario de la base de datos
            $resultado = $this->model->deleteUser($id);

            if ($resultado) {
                // Destruir la sesión para que el usuario quede como invitado
                session_unset();
                session_destroy();

                echo json_encode(["success" => true, "mensaje" => "Tu cuenta ha sido eliminada correctamente."]);
            } else {
                echo json_encode(["success" => false, "mensaje" => "No se pudo eliminar la cuenta."]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "mensaje" => "Error: " . $e->getMessage()]);
        }
    }
    // Método para solicitar enlace de recuperación de contraseña
    public function solicitarEnlace()
    {
        error_log("Iniciando método solicitarEnlace");

        $datos = json_decode(file_get_contents("php://input"), true);
        error_log("Datos recibidos: " . json_encode($datos));

        if (isset($datos["email"])) {
            $email = trim($datos["email"]);
            error_log("Email recibido: " . $email);

            if ($this->model->existeCorreo($email)) {
                error_log("El correo existe en la base de datos");

                $token = bin2hex(random_bytes(50));
                error_log("Token generado: " . $token);

                if ($this->model->guardarTokenRecuperacion($email, $token)) {
                    error_log("Token guardado en la base de datos");

                    $enlace = "http://localhost:8088/Milogar/reset-password.php?token=" . $token;
                    error_log("Enlace generado: " . $enlace);

                    if ($this->enviarEnlacePorCorreo($email, $enlace)) {
                        error_log("Correo enviado correctamente");
                        echo json_encode(["success" => true, "mensaje" => "Revisa tu correo, te enviamos un enlace para restablecer tu contraseña."]);
                    } else {
                        error_log("Error al enviar el correo");
                        echo json_encode(["success" => false, "mensaje" => "Error al enviar el correo."]);
                    }
                } else {
                    error_log("Error al guardar el token en la base de datos");
                    echo json_encode(["success" => false, "mensaje" => "Error al procesar la solicitud."]);
                }
            } else {
                error_log("Correo no encontrado en la base de datos");
                echo json_encode(["success" => false, "mensaje" => "Correo no encontrado."]);
            }
        } else {
            error_log("No se recibió el parámetro 'email'");
            echo json_encode(["success" => false, "mensaje" => "Correo no proporcionado."]);
        }

        error_log("Finalizando método solicitarEnlace");
    }

    private function enviarEnlacePorCorreo($email, $enlace)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Cambia esto si usas otro proveedor
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ddonmilo100@gmail.com';
            $mail->Password   = 'fjju ugeu xrrq vrrd';  // O mejor, usa una contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Configuración del correo
            $mail->setFrom('ddonmilo100@gmail.com', 'MILOGAR');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña';
            $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$enlace'>$enlace</a>";

            // Enviar correo
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function restablecerPassword()
    {
        $datos = json_decode(file_get_contents("php://input"), true);
        if (isset($datos["token"]) && isset($datos["password"])) {
            $token = trim($datos["token"]);
            $password = trim($datos["password"]);

            $usuario = $this->model->verificarToken($token);
            if ($usuario) {
                $email = $usuario["email"];
                if ($this->model->actualizarPassword($email, $password)) {
                    echo json_encode(["success" => true, "mensaje" => "Contraseña restablecida con éxito."]);
                } else {
                    echo json_encode(["success" => false, "mensaje" => "Error al actualizar la contraseña."]);
                }
            } else {
                echo json_encode(["success" => false, "mensaje" => "Token no válido o expirado."]);
            }
        }
    }

    public function obtenerPuntos()
    {
        $db = new Database1(); // O como sea que estés instanciando tu conexión
        $usuario_id = $_GET['usuario_id'] ?? 0;
        $usuarioModel = new UsuarioModel($db);
        $puntos = $usuarioModel->getPuntos((int)$usuario_id);

        echo json_encode(['puntos' => $puntos]);
    }


    public function descontarPuntos()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $usuarioId = $data['usuario_id'];
        $puntosDescontar = $data['puntosDescontar'];

        // Obtener puntos actuales
        $puntosActuales = $this->model->getPuntos($usuarioId);

        if ($puntosActuales !== null) {
            // Restar puntos usando el método del modelo
            $this->model->descontarPuntos($usuarioId, $puntosDescontar);

            // Calcular el nuevo total
            $nuevoTotal = $puntosActuales - $puntosDescontar;

            echo json_encode([
                'success' => true,
                'nuevoTotal' => $nuevoTotal
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        }
    }

    // UsuarioController.php
    public function actualizarPuntosModal()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $usuarioId = $data['usuarioId'];
            $puntosUnitarios = $data['puntosUnitarios'];
            $cantidad = $data['cantidad'];

            require_once '../Models/UsuarioModel.php';
            $modelo = new UsuarioModel($db);

            $respuesta = $modelo->actualizarPuntosPorCantidad($usuarioId, $puntosUnitarios, $cantidad);
            echo json_encode($respuesta);
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
                    $controller->checkDuplicates($input);
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

                // Llamar al método para eliminar el usuario y devolver la respuesta JSON directamente
                $controller->eliminarUsuario($userId);
            } else {
                // Respuesta si el ID no es válido o no se proporciona
                echo json_encode([
                    'success' => false,
                    'message' => 'ID del usuario no proporcionado o no válido.'
                ]);
            }
            break;
        case 'actualizarUsuario':
            // Asegúrate de tener el ID del usuario en la URL
            if (isset($_GET['id'])) {
                $id = $_GET['id']; // Obtener el ID de la URL

                // Crear la instancia de la base de datos y el controlador
                $db = new Database1();
                $controller = new UsuarioController($db);

                // Llamar al método en el controlador para actualizar el usuario
                $controller->actualizarUsuario($id); // Pasa el ID al método del controlador
            } else {
                // Si el ID no está presente en la URL
                echo json_encode(['error' => 'ID de usuario no proporcionado.']);
            }
            break;
        case 'updateClient': // Nueva acción para actualizar los datos del cliente
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->actualizarDatosCliente(); // Llama al método en el controlador
            break;
        case 'cambiarContrasenia':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->cambiarContrasenia(); // Llama al método en el controlador
            break;
        case 'eliminarCuenta':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->eliminarCuenta(); // Llama al método en el controlador
            break;
        case 'solicitarEnlace':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->solicitarEnlace(); // Llama al método en el controlador
            break;
        case 'restablecerPassword':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->restablecerPassword(); // Llama al método en el controlador
            break;
        case 'obtenerPuntos':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->obtenerPuntos();
            break;
        case 'descontarPuntos':
            $db = new Database1();
            $controller = new UsuarioController($db);
            $controller->descontarPuntos();
            break;
        case 'actualizarPuntosModal':
                $data = json_decode(file_get_contents("php://input"), true);
            
                $usuarioId = $data['usuarioId'];
                $puntosUnitarios = $data['puntosUnitarios'];
                $cantidad = $data['cantidad'];
            
                $usuarioModel = new UsuarioModel($db);
            
                $resultado = $usuarioModel->actualizarPuntosDinamico($usuarioId, $puntosUnitarios, $cantidad);
            
                echo json_encode($resultado);
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
