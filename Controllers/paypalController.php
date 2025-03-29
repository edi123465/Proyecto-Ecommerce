<?php
// PaypalController.php
header("Access-Control-Allow-Origin: http://localhost:8088");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

class PaypalController
{
    private $clientId;
    private $clientSecret;
    private $apiUrl;

    public function __construct()
    {
        $this->clientId = 'AW40lggBQSXXOmb33Y_UIF6aMGOoE14FdkfSrnfCJ_zh3EUeieBq7CjS1QA0JTMKDeISC-dGpfrcb3Df'; // Client ID
        $this->clientSecret = 'EIdOmwIpaJDL4zQkxfynAl-ssnHIbfc1GbSoWsYVc8VfJDa-VB0IUwlOALaRDqWfTqizEdWkMWuACkae'; // Secret Key
        $this->apiUrl = 'https://api-m.sandbox.paypal.com';
    }

    private function getAccessToken()
    {
        $ch = curl_init("{$this->apiUrl}/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->clientId}:{$this->clientSecret}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Accept-Language: en_US",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("Error al obtener token: " . curl_error($ch));
            curl_close($ch);
            return null;
        }
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }

    public function createOrder()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            echo json_encode(["error" => "No se pudo obtener el token de acceso"]);
            exit;
        }

        $data = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => "0.75"
                ]
            ]],
            "application_context" => [
                "return_url" => "http://localhost:8088/Milogar/success.php",
                "cancel_url" => "http://localhost:8088/Milogar/cancel.php"
            ]
        ];

        $ch = curl_init("{$this->apiUrl}/v2/checkout/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("Error al crear orden: " . curl_error($ch));
            echo json_encode(["error" => "Error al crear la orden"]);
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['id'])) {
            echo json_encode(["orderID" => $result['id'], "approve" => $result['links'][1]['href']]); 
        } else {
            echo json_encode(["error" => "No se pudo crear la orden"]);
        }
    }

    public function captureOrder()
    {
        $orderID = filter_input(INPUT_GET, 'orderID', FILTER_SANITIZE_STRING);
        if (!$orderID) {
            echo json_encode(["error" => "No se proporcionó un Order ID válido"]);
            exit;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            echo json_encode(["error" => "No se pudo obtener el token de acceso"]);
            exit;
        }

        $ch = curl_init("{$this->apiUrl}/v2/checkout/orders/{$orderID}/capture");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("Error al capturar orden: " . curl_error($ch));
            echo json_encode(["error" => "Error al capturar la orden"]);
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        echo $response;
    }
}

// Manejo de acciones
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$paypal = new PaypalController();

switch ($action) {
    case 'createOrder':
        $paypal->createOrder();
        break;
    case 'captureOrder':
        $paypal->captureOrder();
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
}
