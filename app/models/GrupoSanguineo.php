<?php
namespace App\Models;

use App\Config\BaseDatos;

class GrupoSanguineo {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::obtenerInstancia();
    }

    public function listarTodos() {
        return $this->db->obtenerFilas("SELECT id, nombre FROM cat_tipos_sangre ORDER BY nombre");
    }
}
?>
