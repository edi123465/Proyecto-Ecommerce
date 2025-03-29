<?php
// api/pagar.php

require_once '../Config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos necesarios desde el frontend
    $data = json_decode(file_get_contents('php://input'), true);

    $amount = $data['amount']; // El monto del pago
    $reference = $data['reference']; // Referencia del pedido
    $userId = $data['userId']; // Id del usuario (si es necesario)
    
    // Crear los datos para la solicitud a la API de PayPhone
    $postData = [
        'amount' => $amount,
        'reference' => $reference,
        'userId' => $userId, // Si es necesario incluirlo
        'callbackUrl' => 'https://tuservidor.com/callback', // URL de callback (si es necesario)
    ];

    // Inicializar cURL para hacer la solicitud
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, PAYPHONE_API_URL);  // URL de la API de PayPhone
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData)); // Datos de la solicitud en JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYPHONE_API_TOKEN, // Token de autenticación
        'Content-Type: application/json', // Tipo de contenido
    ]);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    curl_close($ch);

    // Verificar si la respuesta es exitosa
    if ($response) {
        $responseData = json_decode($response, true);
        
        if (isset($responseData['paymentUrl'])) {
            // Si la respuesta tiene la URL de pago, redirigir al frontend
            echo json_encode(['success' => true, 'paymentUrl' => $responseData['paymentUrl']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al obtener la URL de pago']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la comunicación con la API']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}


?>