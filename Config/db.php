<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class Database1 {

    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Obtener variables de entorno
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db_name = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASS'];

            // Crear conexión PDO usando las variables de entorno
            $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión exitosa a la base de datos $db_name en MySQL";

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
