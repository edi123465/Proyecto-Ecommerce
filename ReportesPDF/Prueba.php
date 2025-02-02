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

// Obtener los datos del carrito desde el POST
$carrito = json_decode($_POST['carrito'], true); // Asumiendo que envías el carrito como JSON

// Crear el contenido HTML para el PDF
$html = '<h1>Detalle de Venta del Carrito de Compras</h1>';
$html .= '<table border="1" cellpadding="4">';
$html .= '<tr><th>Imagen</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>';

$total = 0;
foreach ($carrito as $item) {
    $subtotal = $item['cantidad'] * $item['precio'];
    $html .= '<tr>';
    $html .= '<td><img src="' . $item['imagen'] . '" width="50" height="50" /></td>'; // Añadir imagen
    $html .= '<td>' . $item['nombre'] . '</td>'; // Cambia 'nombre' según tu estructura
    $html .= '<td>' . $item['cantidad'] . '</td>';
    $html .= '<td>$' . number_format($item['precio'], 2) . '</td>';
    $html .= '<td>$' . number_format($subtotal, 2) . '</td>';
    $html .= '</tr>';
    $total += $subtotal;
}

$html .= '<tr><td colspan="4" style="text-align:right;"><strong>Total</strong></td>';
$html .= '<td>$' . number_format($total, 2) . '</td></tr>';
$html .= '</table>';

// Escribir el contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->Output('detalle_venta.pdf', 'I'); // 'I' para mostrar en el navegador; usa 'F' para guardar en el servidor
?>
