<?php
// Permitir CORS desde cualquier origen (si quieres limitarlo a tu dominio, reemplaza '*' por 'http://milogar.wuaze.com')
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos

// Si la solicitud es un preflight (OPTIONS), responder con los encabezados adecuados y salir
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
session_start();

// PedidosController.php
require_once(__DIR__ . '/../Models/PedidosModel.php');
require_once(__DIR__ . '/../Config/db.php');

require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PedidosController
{
    private $modeloPedidos;
    private $conn;

    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $database = new Database1();
        $this->conn = $database->getConnection(); // Asigna la conexión a $this->conn
        $this->modeloPedidos = new PedidoModel($this->conn); // Usa $this->conn para el modelo
    }
        // Método para verificar si el usuario tiene un pedido pendiente
    public function verificarPedidoPendiente()
    {
        // Obtener el ID del usuario (asumimos que ya está en la sesión)
        $usuarioID = $_SESSION['user_id'];  // El ID del usuario registrado
        require_once __DIR__ . '/../Models/PedidosModel.php';
        require_once __DIR__ . '/../Config/db.php';
        // Crear instancia del modelo de pedidos
        $database = new Database1();
        $db = $database->getConnection();
        $pedidoModel = new PedidoModel($db);

        // Verificar si el último pedido está pendiente
        $pedidoPendiente = $pedidoModel->ultimoPedidoPendiente($usuarioID);

        if ($pedidoPendiente) {
            // Si el último pedido está pendiente, no se puede realizar otro
            echo json_encode([
                'status' => 'error',
                'message' => 'Tienes un pedido pendiente. Cancélalo o contacta al administrador para validarlo antes de realizar uno nuevo.'
            ]);
            
            exit;  // Salir del controlador
        }
    }
    public function ordenPedido()
{
    $jsonInput = file_get_contents('php://input');
    error_log("JSON recibido: " . $jsonInput);
    $data = json_decode($jsonInput, true);
    error_log("Datos recibidos: " . print_r($data, true));
         // Verificar si el usuario tiene un pedido pendiente
         $usuarioID = $_SESSION['user_id'];  // Obtener ID de usuario de la sesión
         $this->verificarPedidoPendiente($usuarioID);  // Verificar si hay un pedido pendiente
 
    if (
        is_null($data) ||
        !isset($data['usuario_id'], $data['fecha'], $data['subtotal'], $data['iva'], $data['total'], $data['estado'], $data['numeroPedido'], $data['totalProductos'], $data['metodoPago'], $data['productos'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o mal formateados.']);
        return;
    }

    // Extraer datos del pedido
    $usuario_id = $data['usuario_id'];
    $fecha = $data['fecha'];
    $subtotal = $data['subtotal'];
    $iva = $data['iva'];
    $descuento = isset($data['descuento']) ? $data['descuento'] : 0;
    $total = ($subtotal - $descuento) + $iva;
    $estado = $data['estado'];
    $numeroPedido = $data['numeroPedido'];
    $totalProductos = $data['totalProductos'];
    $productos = $data['productos'];
    $metodoPago = $data['metodoPago'];
    $direccion = $data['direccion'];
    $puntos = isset($data['puntos']) ? floatval($data['puntos']) : 0;

    error_log("Fecha recibida: " . $fecha);

    // Validar la fecha
    $fecha_valida = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
    if ($fecha_valida === false) {
        echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido.']);
        return;
    }

    // Crear pedido con descuento incluido
    $pedido_id = $this->modeloPedidos->crearPedido($usuario_id, $subtotal, $iva, $total, $estado, $numeroPedido, $totalProductos, $metodoPago, $direccion, $descuento);
    error_log("Pedido creado con ID: " . $pedido_id);

    if ($pedido_id) {
        
        // Insertar detalles del pedido
        foreach ($productos as $producto) {
            if (isset($producto['id'], $producto['cantidad'], $producto['precio'], $producto['subtotal'], $producto['imagen'])) {
                $resultado = $this->modeloPedidos->insertarDetallePedido(
                    $pedido_id,
                    $producto['id'],
                    $producto['cantidad'],
                    $producto['precio'],
                    $producto['subtotal'],
                    $producto['imagen']
                );

                if (!$resultado) {
                    echo json_encode(['success' => false, 'message' => 'Error al insertar detalle para producto ID: ' . $producto['id']]);
                    return;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Faltan datos en uno o más productos.']);
                return;
            }
        }

        // Sumar puntos si corresponde
        if ($usuario_id && $puntos > 0) {
            $resultadoPuntos = $this->modeloPedidos->sumarPuntosUsuario($usuario_id, $puntos);
            if (!$resultadoPuntos) {
                error_log("No se pudieron sumar los puntos al usuario con ID: $usuario_id");
            }
        }

        echo json_encode(['success' => true, 'pedido_id' => $pedido_id, 'message' => 'Pedido y detalles creados exitosamente.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el pedido.']);
        exit;
    }
}

    public function obtenerTodo()
{
    try {
        // Obtener parámetros de la solicitud
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        // Filtros opcionales
        $numeroPedido = isset($_GET['numeroPedido']) ? $_GET['numeroPedido'] : null;
        $nombreUsuario = isset($_GET['nombreUsuario']) ? $_GET['nombreUsuario'] : null;
        $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
        $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;

        // Obtener pedidos con filtros y total
        $pedidos = $this->modeloPedidos->obtenerPedidosConFiltros($limit, $offset, $numeroPedido, $nombreUsuario, $fechaInicio, $fechaFin);
        $totalPedidos = $this->modeloPedidos->contarTotalPedidos($numeroPedido, $nombreUsuario, $fechaInicio, $fechaFin);  // Si tienes un método para contar con filtros.
        
        // Depurar valores (esto es opcional)
        error_log("Total de pedidos: $totalPedidos");
        error_log("Pedidos en la página $page: " . count($pedidos));
        error_log("Limit: $limit, Offset: $offset");

        // Calcular total de páginas
        $totalPaginas = ceil($totalPedidos / $limit);

        // Responder en formato JSON
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $pedidos,
            'paginaActual' => $page,
            'totalPaginas' => $totalPaginas,
            'totalPedidos' => $totalPedidos,
            'limitePorPagina' => $limit
        ]);
    } catch (Exception $e) {
        // Manejo de errores
        error_log("Error al obtener pedidos: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Ocurrió un error al procesar la solicitud.',
            'error' => $e->getMessage()
        ]);
    }
}

public function actualizarEstado()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inputRaw = file_get_contents('php://input');
        $input = json_decode($inputRaw, true);

        error_log("=== DEBUG actualizarEstado ===");
        error_log("Raw input: " . $inputRaw);
        error_log("Decoded input: " . print_r($input, true));

        $pedidoID = $input['pedido_id'] ?? null;
        $nuevoEstado = $input['estado'] ?? null;

        error_log("Pedido ID: " . ($pedidoID ?? 'NULL'));
        error_log("Nuevo Estado: " . ($nuevoEstado ?? 'NULL'));

        if ($pedidoID && $nuevoEstado) {
            require_once __DIR__ . '/../Config/db.php'; // Asegúrate que esta ruta es correcta
            require_once __DIR__ . '/../Models/PedidosModel.php';
            error_log("Actualizando estado del pedido con ID: " . $pedidoID . " al estado: " . $nuevoEstado);

            $database = new Database1();
            $db = $database->getConnection();

            $modeloPedidos = new PedidoModel($db);
            $resultado = $modeloPedidos->actualizarEstadoPedido($pedidoID, $nuevoEstado);

            if ($resultado) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Estado del pedido actualizado correctamente'
                ]);
            } else {
                error_log("Error al actualizar estado en la base de datos.");
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al actualizar el estado del pedido'
                ]);
            }
        } else {
            error_log("ID del pedido o estado no proporcionado.");
            echo json_encode([
                'status' => 'error',
                'message' => 'ID del pedido o estado no proporcionado'
            ]);
        }
    } else {
        error_log("Método no permitido. Se esperaba POST.");
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
    }
}

    public function obtenerPedidosUsuario()
    {
        try {

            $userId = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
            error_log("ID de usuario obtenido desde la sesión: " . $userId);

            // Llamar al método del modelo para obtener los pedidos
            $stmt = $this->modeloPedidos->obtenerPedidosPorUsuario($userId);

            // Verificar si la consulta fue exitosa
            if ($stmt === false) {
                header('Content-Type: application/json');
                error_log("Error al ejecutar la consulta para el usuario ID: " . $userId);
                echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
                exit();
            }

            // Obtener los resultados
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si se obtuvieron pedidos
            if (empty($pedidos)) {
                header('Content-Type: application/json');
                error_log("No se encontraron pedidos para el usuario ID: " . $userId);
                echo json_encode(['success' => false, 'message' => 'No se encontraron pedidos']);
                exit();
            }

            // Devolver los pedidos en formato JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'pedidos' => $pedidos]);
            exit();
        } catch (\Throwable $th) {
            // Registrar el error con más detalles
            error_log("Error al obtener los pedidos para el usuario: " . $th->getMessage());
            echo json_encode(['success' => false, 'message' => 'Hubo un error al procesar la solicitud', 'error' => $th->getMessage()]);
            exit();
        }
    }
    // Función para obtener los detalles del pedido
    public function obtenerDetallePedidosPorId($pedido_id)
    {
        error_log("Pedido ID recibido en el controlador: " . $pedido_id);
        // Llamar al modelo para obtener los detalles del pedido
        $pedidoModel = new PedidoModel($this->conn);
        $detallePedido = $pedidoModel->getDetallePedidoById($pedido_id);

        // Log para verificar los detalles obtenidos
        error_log("Detalles del pedido obtenidos: " . print_r($detallePedido, true));
        header('Content-Type: application/json');
        // Verificar si el detalle fue encontrado
        if ($detallePedido) {
            // Responder con los detalles del pedido en formato JSON
            echo json_encode([
                'success' => true,
                'pedido' => $detallePedido
            ]);
        } else {
            // Log para cuando no se encuentran detalles
            error_log("No se encontraron detalles para el pedido con ID: $pedido_id");

            // Si no se encuentran detalles, devolver un mensaje de error
            echo json_encode([
                'success' => false,
                'message' => 'No se encontraron detalles para este pedido.'
            ]);
        }
    }

    // PedidosController.php

    public function generarPDF()
    {
        header('Content-Type: application/json'); // Asegurar JSON
        require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

        $data = json_decode(file_get_contents('php://input'), true);

        // Validación solo para productos y número de pedido
        if (!isset($data['productos']) || !isset($data['numeroPedido'])) {
            //die('Faltan datos de productos o número de pedido.');
        }

        $numeroPedido = $data['numeroPedido'];  // Obtener el número de pedido
        $productos = $data['productos'];
        $cliente = ($data['usuario_id'] === 'invitado') ? 'Invitado' : $data['usuario_nombre'];
        $emailCliente = $data['email'];  // Correo del cliente
        $emailAdmin = 'edi.borja1310@gmail.com';  // Cambia esto por el correo del administrador

        // Crear el objeto TCPDF y configurar el documento
        $pdf = new TCPDF();

        // Configuración del PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Milogar');
        $pdf->SetTitle('Reporte del Pedido');
        $pdf->SetSubject('Detalle del Pedido');
        $pdf->setPrintHeader(false);  // Desactivar cabecera predeterminada
        $pdf->setPrintFooter(false);  // Desactivar pie de página predeterminado
        $pdf->AddPage();

        // Logo de la empresa (ajustar la ruta al logo correcto)
        $logo = '../assets/imagenesMilogar/logomilo.jpg';  // Cambia esta ruta por la correcta
        $pdf->Image($logo, 10, 10, 40, 0, '', '', '', '', false, 300, '', false, false, 0, false, false, false);

        // Cuadro con los datos de la empresa a la derecha del logo
        $pdf->SetXY(60, 10); // Ajusta la posición para el cuadro de los datos de la empresa (a la derecha de la imagen)
        $pdf->SetFillColor(240, 240, 240); // Color de fondo del cuadro
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Datos de la Empresa', 1, 1, 'C', 1); // Encabezado del cuadro
        $pdf->SetFont('helvetica', '', 12);

        // Datos de la empresa (nombre, dirección, etc.)
        $pdf->SetXY(60, 20); // Ajusta la posición Y para los datos de la empresa (mueve a la derecha desde el logo)
        $pdf->Cell(0, 10, 'Nombre: MILOGAR', 0, 1, 'L'); // El nombre de la empresa

        $pdf->SetXY(60, 30); // Ajusta la posición Y para la siguiente línea (si deseas más separación entre líneas)
        $pdf->Cell(0, 10, 'Dirección: El Quinche, Calle Quito y Tulcán', 0, 1, 'L'); // Dirección

        $pdf->SetXY(60, 40); // Ajusta la posición Y para la siguiente línea
        $pdf->Cell(0, 10, 'Teléfono: 0989082073', 0, 1, 'L'); // Teléfono

        $pdf->SetXY(60, 50); // Ajusta la posición Y para la siguiente línea
        $pdf->Cell(0, 10, 'Correo: ddonmilo100@gmail.com', 0, 1, 'L'); // Correo

        $pdf->Ln(10); // Espaciado entre los datos de la empresa y los datos del cliente

        // Cuadro con los datos del cliente
        $pdf->SetXY(10, 60); // Ajusta la posición para el cuadro de datos del cliente
        $pdf->SetFillColor(240, 240, 240); // Color de fondo del cuadro
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Datos del Cliente', 1, 1, 'C', 1); // Encabezado del cuadro
        $pdf->SetFont('helvetica', '', 12);

        // Datos del cliente (nombre, dirección, email, etc.)
        $pdf->Cell(0, 10, 'Cliente: ' . $cliente, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Correo: ' . $data['email'], 0, 1, 'L');


        $pdf->SetX(150); // Mantén la posición X para alinearlo a la derecha
        $pdf->Cell(0, 10, 'Teléfono: ' . $data['telefono'], 0, 1, 'L');
        $pdf->Cell(0, 10, 'Opción de Entrega: ' . $data['deliveryOption'], 0, 1, 'L'); // Mostrar la opción de entrega
        // Verifica la opción seleccionada y muestra la dirección si es "agregarDireccionEnvio"
        if ($data['deliveryOption'] === 'agregarDireccionEnvio') {
            $provincia = $data['provinciaEnvio'] ?? '';
            $ciudad = $data['ciudad'] ?? '';
            $direccion = $data['direccion'] ?? 'No proporcionada';

            // Concatenar la dirección en formato profesional
            $direccionCompleta = $provincia . ', ' . $ciudad . ', ' . $direccion;

            $pdf->Cell(0, 10, 'Dirección de Envío: ' . $direccionCompleta, 0, 1, 'L');
            $pdf->Ln(); // Salto de línea opcional
        }
        // Mover la fecha un poco hacia arriba
        $pdf->SetY(70); // Ajusta la posición Y para mover la fecha hacia arriba
        $pdf->SetX(150); // Mantén la posición X para alinearlo a la derecha
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L');

        $pdf->Ln(5); // Ajusta el espaciado después de la fecha

        // Mover el tipo de pago hacia arriba
        $pdf->SetY(90); // Ajusta la posición Y para mover el tipo de pago hacia arriba
        $pdf->SetX(10); // Mantén la posición X para el tipo de pago
        $pdf->Cell(0, 10, 'Tipo de Pago: ' . (isset($data['metodoPago']) ? $data['metodoPago'] : 'No especificado'), 0, 1, 'L');

        // Espaciado entre el tipo de pago y los detalles del pedido
        $pdf->Ln(10); // Espaciado entre el tipo de pago y los detalles del pedido
        $pdf->Ln(); // Salto de línea
        // Datos del pedido
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte de Pedido #' . $numeroPedido, 0, 1, 'C');
        $pdf->Ln(5);

        // Encabezado de la tabla de productos
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(80, 10, 'Producto', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Precio Unitario', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');

        // Estilo para el contenido de la tabla
        $pdf->SetFont('helvetica', '', 12);

        // Agregar los productos a la tabla
        $total = 0;
        foreach ($productos as $producto) {
            $subtotal = $producto['cantidad'] * $producto['precio']; // Calcular subtotal por producto

            $pdf->Cell(80, 10, $producto['nombre'], 1, 0, 'L');
            $pdf->Cell(30, 10, $producto['cantidad'], 1, 0, 'C');
            $pdf->Cell(40, 10, '$' . number_format($producto['precio'], 2), 1, 0, 'C');
            $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');

            $total += $subtotal;
        }

        // Espaciado entre la tabla y el total
        $pdf->Ln(10);

              // Mostrar el total
        $pdf->SetFont('helvetica', 'B', 12);
        // Mostrar Subtotal
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(150, 10, 'Subtotal', 1, 0, 'R');
        $pdf->Cell(40, 10, '$' . number_format($data['subtotal'], 2), 1, 1, 'C');

        // Mostrar Descuento
        $pdf->Cell(150, 10, 'Descuento aplicado por cupones', 1, 0, 'R');
        $pdf->Cell(40, 10, '-$' . number_format($data['descuento'], 2), 1, 1, 'C');

        // 🔸 Mostrar Costo de Envío si es mayor a 0
        if (isset($data['costoEnvio']) && floatval($data['costoEnvio']) > 0) {
            $pdf->Cell(150, 10, 'Costo de Envío por sector', 1, 0, 'R');
            $pdf->Cell(40, 10, '$' . number_format($data['costoEnvio'], 2), 1, 1, 'C');
        }

        // Mostrar Total
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(150, 10, 'Total a Pagar', 1, 0, 'R');
        $pdf->Cell(40, 10, '$' . number_format($data['total'], 2), 1, 1, 'C');

        // Agregar el texto de "Precios ya incluyen IVA" en letra pequeña
        $pdf->SetFont('helvetica', '', 10); // Cambia el tamaño de la fuente para hacerlo más pequeño
        $pdf->Cell(0, 10, 'Los precios ya incluyen IVA (15%)', 0, 1, 'C'); // Centrado y debajo del total
        // Guardar posición actual
$x = $pdf->GetX();
$y = $pdf->GetY();

// Establecer ancho del cuadro (ajustado, no toda la hoja)
$width = 120;
$padding = 4;

// Establecer fuente y tamaño
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Información Adicional', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 11);

// Contenido del bloque
$infoText = 
    'Empresa de Envío: ' . $data['empresaEnvio'] . "\n" .
    'Tipo de Envío: ' . $data['tipoEnvio'] . "\n" .
    'Referencias: ' . ($data['referencias'] ?? 'No proporcionadas');

// Medir altura del texto para ajustar el alto del cuadro
$nbLines = $pdf->getNumLines($infoText, $width - 2 * $padding);
$lineHeight = 6;
$height = $nbLines * $lineHeight + 2 * $padding;

// Centrar el cuadro horizontalmente
$x = ($pdf->GetPageWidth() - $width) / 2;
$y = $pdf->GetY(); // posición actual Y

// Dibujar rectángulo
$pdf->Rect($x, $y, $width, $height);

// Establecer posición para el texto dentro del cuadro
$pdf->SetXY($x + $padding, $y + $padding);

// Imprimir el texto
$pdf->MultiCell(
    $width - 2 * $padding,
    $lineHeight,
    $infoText,
    0,
    'L',
    false
);

// Mover cursor debajo del cuadro
$pdf->SetY($y + $height + 5);


        // Salida del PDF
$pdfPath = $_SERVER['DOCUMENT_ROOT'] . '/Milogar/assets/pedidos/pedido_' . $numeroPedido . '.pdf';
        // Guardar el PDF temporalmente en el servidor
        $pdf->Output($pdfPath, 'F'); // 'F' guarda el archivo en el servidor, pero no lo envía ni descarga

        // Enviar el PDF por correo
        $this->enviarCorreo($emailCliente, $emailAdmin, $pdfPath, $numeroPedido, $cliente);

        // Eliminar el archivo después de enviarlo
        unlink($pdfPath); // Elimina el archivo PDF temporal después de que se haya enviado

        // Responder al cliente que el PDF se envió correctamente
        echo json_encode(['success' => true]);
    }
    public function enviarCorreo($emailCliente, $emailAdmin, $pdfPath, $numeroPedido, $cliente)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'edi.borja1310@gmail.com'; 
            $mail->Password = 'udzm podh mvvb hbve'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configurar remitente
            $mail->setFrom('edi.borja1310@gmail.com', 'MILOGAR');

            // Enviar al cliente
            $mail->addAddress($emailCliente);
            // Contenido del correo para el cliente
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de tu pedido #' . $numeroPedido . ' - MILOGAR';
            $mail->Body = '
        <h3>Hola, '.$cliente.'</h3>
        <p>Gracias por tu compra en MILOGAR. Tu pedido #' . $numeroPedido . ' está en proceso.</p>
        <p>Adjunto encontrarás el detalle de tu pedido.<br>
        
        Para la validación de tu pedido, por favor envia el comprobante de pago junto al pdf de tu pedido al número: 0989082073</p>
        <b>Numero de cuenta: </b> 2100272933 Cuenta corriente<br>
        <b>Titular de la cuenta: </b> Deisy Pamela Remache Yanchaguano

    ';

            // Adjuntar el archivo PDF
            $mail->addAttachment($pdfPath, 'Pedido_' . $numeroPedido . '.pdf');
            // Enviar correo al cliente
            $mail->send();
            // Limpiar las direcciones de destinatarios para el siguiente correo
            $mail->clearAddresses();

            // Verificar si $emailAdmin es correcto antes de enviarlo
            if (empty($emailAdmin)) {
                throw new Exception('La dirección de correo del administrador está vacía o incorrecta.');
            }

            // Enviar al administrador
            $mail->addAddress($emailAdmin);
            // Contenido del correo para el administrador
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo pedido realizado #' . $numeroPedido . ' - MILOGAR';
            $mail->Body = '
        <h3>Hola Administrador,</h3>
        <p>Un usuario ha realizado un nuevo pedido con número <strong>' . $numeroPedido . '</strong>.</p>
        <p>Adjunto encontrarás el detalle completo del pedido.</p>
        <p>Correo del cliente: ' . $emailCliente . '</p>
    ';

            // Adjuntar el archivo PDF
            $mail->addAttachment($pdfPath, 'Pedido_' . $numeroPedido . '.pdf');
            // Enviar correo al administrador
            $mail->send();

            // Si llegamos aquí, los correos se enviaron correctamente
            // Puedes registrar en un log que los correos fueron enviados
            error_log("Correos enviados con éxito para el pedido #$numeroPedido.");
        } catch (Exception $e) {
            // En caso de error, loguea el error sin usar echo
            error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
}




// Manejar la solicitud dependiendo de la acción
$action = $_GET['action'] ?? '';
$controller = new PedidosController();

if ($action === 'ordenPedido') {
    $controller->ordenPedido();
} elseif ($action === 'obtenerPedidosUsuario') {
    $controller->obtenerPedidosUsuario();
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];  // Obtener la acción de la URL

    // Dependiendo de la acción, ejecutamos diferentes métodos
    switch ($action) {
        case 'obtenerTodo':
            // Si la acción es 'obtenerTodo', llama al método que obtiene todos los pedidos
            $controller = new PedidosController();
            $controller->obtenerTodo();
            break;

        case 'obtenerDetallePedidosPorId':
            // Si la acción es 'obtenerDetallePedidosPorId', verifica que el 'id' esté presente
            if (isset($_GET['id'])) {
                $pedido_id = $_GET['id'];  // Obtener el ID del pedido
                $controller = new PedidosController();
                $controller->obtenerDetallePedidosPorId($pedido_id);  // Llamar a la función para obtener el detalle
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de pedido no proporcionado.'
                ]);
            }
            break;
        case 'generarPDF':
            // Si la acción es 'generarPDF', obtiene los datos para generar el PDF
            $data = json_decode(file_get_contents('php://input'), true);

            // Verificar si los datos necesarios están presentes (productos y numeroPedido)
            if (isset($data['productos']) && isset($data['numeroPedido'])) {
                $numeroPedido = $data['numeroPedido'];  // Obtener el número de pedido
                $productos = $data['productos'];        // Obtener los productos

                // Crear el reporte PDF
                $controller = new PedidosController();
                $controller->generarPDF($numeroPedido, $productos);  // Pasar numeroPedido en lugar de pedido_id
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Faltan datos para generar el reporte PDF.'
                ]);
            }
            break;
        case 'actualizarEstado':
            $controller->actualizarEstado();
            break;


        default:
        
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida o no especificada.sss'
    ]);
}
