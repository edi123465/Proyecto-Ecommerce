<?php

require_once __DIR__ . '/../Config/db.php';

require_once __DIR__ . '/../Models/ReporteModel.php';



$action = $_GET['action'] ?? '';



$db = new Database1();

$conn = $db->getConnection();

$reporteModel = new ReporteModel($conn);



if ($action === 'obtenerVentas') {

    $periodo = $_GET['periodo'] ?? 'mes';

    $filtroMes = $_GET['mes'] ?? null;



    switch ($periodo) {

        case 'dia':

            $data = $reporteModel->getVentasPorDia($filtroMes);

            break;

        case 'semana':

            $data = $reporteModel->getVentasPorSemana($filtroMes);

            break;

        case 'anio':

            $data = $reporteModel->getVentasPorAnio($filtroMes);

            break;

        default:

            $data = $reporteModel->getVentasPorMes();

            break;

    }



    header('Content-Type: application/json');

    echo json_encode($data);

    exit;

}



if ($action === 'productosMasVendidos') {

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

    $data = $reporteModel->getProductosMasVendidos($limit);



    header('Content-Type: application/json');

    echo json_encode($data);

    exit;

}



http_response_code(400);

echo json_encode(['error' => 'Acción no válida']);

