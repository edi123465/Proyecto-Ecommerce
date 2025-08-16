<?php
require_once('../assets/vendor/tecnickcom/tcpdf/tcpdf.php');

// Obtener los productos del POST
if (isset($_POST['productos'])) {
    $productos = json_decode($_POST['productos'], true);  // Convertir el JSON a array PHP
} else {
    die('No se recibieron productos.');
}

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();

// Configuraciones del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Tienda');
$pdf->SetTitle('Reporte del Carrito de Compras');
$pdf->SetSubject('Productos Seleccionados');
$pdf->SetKeywords('Carrito, Reporte, PDF, TCPDF');
// Remover la cabecera automática
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false); // Si no necesitas pie de página

// Añadir una página
$pdf->AddPage();

// Logo de la empresa
$logo = '../assets/imagenesMilogar/logomilo.jpg'; // Cambia la ruta al logo de tu empresa
$pdf->Image($logo, 10, 10, 40, 0, '', '', '', '', false, 300, '', false, false, 0, false, false, false);

// Mover a la derecha para la información de la empresa
$pdf->SetXY(75, 0); // Ajusta la posición (60 es el margen desde la izquierda)

$pdf->SetFont('helvetica', 'B', 50);
$pdf->Cell(0, 10, 'MILOGAR', 0, 1, 'L'); // Cambia 'Nombre de la Empresa' por el nombre real
//FRASE DE LA EMPRESA
$pdf->SetXY(85, 16); // Ajusta la posición (60 es el margen desde la izquierda)
$pdf->SetFont('helvetica', '', 15);
$pdf->Cell(0, 10, 'Variedad para tu hogar', 0, 1, 'L'); // Cambia 'Nombre de la Empresa' por el nombre real
/// Nombre del cliente
$nombreCliente = 'Juan Pérez';
$pdf->SetX(60);
$pdf->Cell(0, 10, 'Cliente: ' . $nombreCliente, 0, 1, 'L');
// Fecha actual
$pdf->SetXY(130,26);
$pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L');

// Datos del contacto
$pdf->SetFont('helvetica', '', 12);
// Mover cada celda a la derecha usando SetX(60)
$pdf->SetX(60);
$pdf->Cell(0, 10, 'RUC: 123456789', 0, 1, 'L');

$pdf->SetXY(130,36);
$pdf->Cell(0, 10, 'Teléfono: (+593) 98 908 2073', 0, 1, 'L');
// Número de pedido
$numeroPedido = '382480';
$pdf->SetX(60);
$pdf->Cell(0, 10, 'Número de Pedido: ' . $numeroPedido, 0, 1, 'L');

// Espacio entre el encabezado y el contenido
$pdf->Ln(10);

// Título del documento
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte del Carrito de Compras', 0, 1, 'C');

// Espacio entre título y contenido
$pdf->Ln(10);

// Detalles del carrito
$pdf->SetFont('helvetica', '', 12);

// Encabezado de la tabla
$html = '
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th><b>Producto</b></th>
            <th><b>Cantidad</b></th>
            <th><b>Precio Unitario</b></th>
            <th><b>Subtotal</b></th>
            <th><b>IVA (15%)</b></th> <!-- Nueva columna para el IVA -->
            <th><b>Subtotal + IVA</b></th> <!-- Nueva columna para subtotal con IVA -->
        </tr>
    </thead>
    <tbody>
';

// Agregar los productos a la tabla
foreach ($productos as $producto) {
    $subtotal = $producto['subtotal']; // Asume que 'subtotal' ya está calculado antes
    $iva = $subtotal * 0.15; // Calcular IVA del 15%
    $subtotalConIVA = $subtotal + $iva; // Sumar el IVA al subtotal

    $html .= '<tr>';
    $html .= '<td>' . $producto['nombre'] . '</td>';
    $html .= '<td>' . $producto['cantidad'] . '</td>';
    $html .= '<td>$' . number_format($producto['precio'], 2) . '</td>';
    $html .= '<td>$' . number_format($subtotal, 2) . '</td>'; // Subtotal original
    $html .= '<td>$' . number_format($iva, 2) . '</td>'; // IVA calculado
    $html .= '<td>$' . number_format($subtotalConIVA, 2) . '</td>'; // Subtotal + IVA
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Agregar la tabla al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Calcular total del carrito
$total = 0;
$totalIVA = 0; // Inicializa total de IVA
$totalConIVA = 0; // Inicializa total con IVA
foreach ($productos as $producto) {
    $subtotal = $producto['subtotal'];
    $iva = $subtotal * 0.15; // Calcular IVA del 15%
    $total += $subtotal; // Acumular subtotal
    $totalIVA += $iva; // Acumular IVA
    $totalConIVA += ($subtotal + $iva); // Acumular total con IVA
}

// Mostrar totales
$pdf->Ln(10); // Salto de línea
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Subtotal Total: $' . number_format($total, 2), 0, 1, 'R');
$pdf->Cell(0, 10, 'IVA Total (15%): $' . number_format($totalIVA, 2), 0, 1, 'R');
$pdf->Cell(0, 10, 'Total a Pagar: $' . number_format($totalConIVA, 2), 0, 1, 'R');

// Salida del PDF (F para descarga directa, I para abrir en navegador)
$pdf->Output('reporte_carrito.pdf', 'D'); // 'D' para forzar la descarga
?>
