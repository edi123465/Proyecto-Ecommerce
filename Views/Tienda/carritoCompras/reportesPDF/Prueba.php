<?php
// Incluir TCPDF desde la carpeta assets
require_once "../../../../assets/vendor/tecnickcom/tcpdf/tcpdf.php";
// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Detalle de Venta');
$pdf->SetSubject('Carrito de Compras');
$pdf->SetKeywords('TCPDF, PDF, carrito, compras');

// Configuración de la fuente
$pdf->SetFont('helvetica', '', 12);

// Agregar una página
$pdf->AddPage();

// Datos del carrito (ejemplo)
$carrito = [
    ['producto' => 'Producto 1', 'cantidad' => 2, 'precio' => 10],
    ['producto' => 'Producto 2', 'cantidad' => 1, 'precio' => 20],
    ['producto' => 'Producto 3', 'cantidad' => 3, 'precio' => 15],
];

// Crear el contenido HTML para el PDF
$html = '<h1>Detalle de Venta del Carrito de Compras jjjjjjj</h1>';
$html .= '<table border="1" cellpadding="4">';
$html .= '<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>';

$total = 0;
foreach ($carrito as $item) {
    $subtotal = $item['cantidad'] * $item['precio'];
    $html .= '<tr>';
    $html .= '<td>' . $item['producto'] . '</td>';
    $html .= '<td>' . $item['cantidad'] . '</td>';
    $html .= '<td>$' . number_format($item['precio'], 2) . '</td>';
    $html .= '<td>$' . number_format($subtotal, 2) . '</td>';
    $html .= '</tr>';
    $total += $subtotal;
}

$html .= '<tr><td colspan="3" style="text-align:right;"><strong>Total</strong></td>';
$html .= '<td>$' . number_format($total, 2) . '</td></tr>';
$html .= '</table>';

// Escribir el contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->Output('detalle_venta.pdf', 'I'); // 'I' para mostrar en el navegador; usa 'F' para guardar en el servidor
?>
