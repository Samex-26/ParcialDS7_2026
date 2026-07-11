<?php
namespace App\Models;
use App\Config\BaseDatos;

class Nacion {
    private $db;
    
    public function __construct() {
        $this->db = BaseDatos::obtenerInstancia();
    }
    
    public function listarTodos() {
        return $this->db->obtenerFilas("SELECT id, nombre FROM paises ORDER BY nombre");
    }
}
?>
