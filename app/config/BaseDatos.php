<?php
namespace App\Config;

class BaseDatos {
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $dbname = 'itech_DBP';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $this->conn = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function obtenerInstancia() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function obtenerConexion() {
        return $this->conn;
    }
    
    public function ejecutarConsulta($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function obtenerFilas($sql, $params = []) {
        return $this->ejecutarConsulta($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function obtenerFila($sql, $params = []) {
        return $this->ejecutarConsulta($sql, $params)->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function insertar($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->ejecutarConsulta($sql, $data);
        return $this->conn->lastInsertId();
    }
    
    public function ultimoIdInsertado() {
        return $this->conn->lastInsertId();
    }
}
?>
