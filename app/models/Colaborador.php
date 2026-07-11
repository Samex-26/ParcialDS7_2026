<?php
namespace App\Models;

use App\Config\BaseDatos;

class Colaborador {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::obtenerInstancia();
    }

    public function registrar($colaboradorData, $perfilData) {
        try {
            $conn = $this->db->obtenerConexion();
            $conn->beginTransaction();

            // Verificar si el colaborador ya existe por su identidad
            $existing = $this->db->obtenerFila(
                "SELECT id FROM colaboradores WHERE identidad = :identidad",
                ['identidad' => $colaboradorData['identidad']]
            );

            if ($existing && isset($existing['id'])) {
                $colaboradorId = $existing['id'];
                
                // Preparar los datos eliminando 'identidad' para que coincida exactamente con los tokens del UPDATE
                $updateData = array_merge($colaboradorData, ['id' => $colaboradorId]);
                unset($updateData['identidad']);

                $this->db->ejecutarConsulta(
                    "UPDATE colaboradores SET nombre = :nombre, apellido = :apellido, edad = :edad, tipo_sangre = :tipo_sangre, sexo = :sexo, ruta_id = :ruta_id, pais_residencia_id = :pais_residencia_id, nacionalidad = :nacionalidad, correo = :correo, celular = :celular, observaciones = :observaciones WHERE id = :id",
                    $updateData
                );
            } else {
                // Si no existe, se inserta normalmente (el método insertar mapea las columnas automáticamente)
                $colaboradorId = $this->db->insertar('colaboradores', $colaboradorData);
                $this->db->ejecutarConsulta(
                    "UPDATE colaboradores SET codigo_empleado = :codigo_empleado WHERE id = :id",
                    ['codigo_empleado' => $colaboradorId, 'id' => $colaboradorId]
                );
            }

            // Desactivar el perfil laboral activo anterior
            $this->db->ejecutarConsulta(
                "UPDATE perfiles_laborales SET es_activo = 0 WHERE colaborador_id = :colaborador_id AND es_activo = 1",
                ['colaborador_id' => $colaboradorId]
            );

            if (empty($perfilData['fecha_fin'])) {
                $perfilData['fecha_fin'] = null;
            }

            $perfilData['es_activo'] = empty($perfilData['fecha_fin']) ? 1 : 0;
            $perfilData['colaborador_id'] = $colaboradorId;

            // Construcción dinámica del INSERT para el perfil laboral
            $columns = implode(', ', array_keys($perfilData));
            $placeholders = ':' . implode(', :', array_keys($perfilData));
            $sql = "INSERT INTO perfiles_laborales ($columns) VALUES ($placeholders)";
            $this->db->ejecutarConsulta($sql, $perfilData);

            $conn->commit();
            return ['success' => true, 'id' => $colaboradorId];
        } catch (\Exception $e) {
            if (isset($conn) && $conn->inTransaction()) {
                $conn->rollBack();
            }
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT 
                        c.id,
                        c.codigo_empleado,
                        c.identidad,
                        c.nombre,
                        c.apellido,
                        c.edad,
                        c.tipo_sangre,
                        c.sexo,
                        r.nombre AS ruta,
                        p.nombre AS pais_nombre,
                        c.nacionalidad,
                        c.correo,
                        c.celular,
                        c.observaciones AS colaborador_observaciones,
                        c.fecha_registro AS fecha_registro,
                        pl.puesto,
                        pl.salario,
                        pl.fecha_inicio,
                        pl.fecha_fin,
                        pl.es_activo,
                        pl.motivo,
                        ct.nombre AS tipo_empleado,
                        pla.nombre AS planilla,
                        oc.nombre AS ocupacion
                    FROM colaboradores c
                    LEFT JOIN cat_rutas r ON c.ruta_id = r.id
                    LEFT JOIN paises p ON c.pais_residencia_id = p.id
                    LEFT JOIN perfiles_laborales pl ON pl.colaborador_id = c.id AND pl.es_activo = 1
                    LEFT JOIN cat_tipos_empleado ct ON pl.tipo_empleado_id = ct.id
                    LEFT JOIN cat_tipos_planilla pla ON pl.planilla_id = pla.id
                    LEFT JOIN cat_ocupaciones oc ON pl.ocupacion_id = oc.id
                    ORDER BY c.id ASC";

            return $this->db->obtenerFilas($sql);
        } catch (\Exception $e) {
            error_log("Error en Colaborador::listarTodos: " . $e->getMessage());
            return [];
        }
    }
}
?>
