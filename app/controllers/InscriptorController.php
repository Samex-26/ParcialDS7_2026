<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\Inscriptor;
use App\Utils\Validator;
use App\Utils\Sanitizer;

class InscriptorController {
    private function consultar($sql, $params = []) {
        return Database::getInstance()->fetchAll($sql, $params);
    }

    private function cargarPaises() {
        return $this->consultar("SELECT id, nombre FROM paises ORDER BY nombre");
    }

    private function cargarRutas() {
        return $this->consultar("SELECT id, nombre FROM cat_rutas ORDER BY nombre");
    }

    private function cargarTiposSangre() {
        return $this->consultar("SELECT nombre FROM cat_tipos_sangre ORDER BY nombre");
    }

    private function cargarPlanillas() {
        return $this->consultar("SELECT id, nombre FROM cat_tipos_planilla ORDER BY nombre");
    }

    private function cargarTiposEmpleado() {
        return $this->consultar("SELECT id, nombre FROM cat_tipos_empleado ORDER BY nombre");
    }

    private function cargarOcupaciones() {
        return $this->consultar("SELECT id, nombre FROM cat_ocupaciones ORDER BY nombre");
    }

    // MÉTODO INDEX

    public function index() {
        $paises = $this->cargarPaises();
        $rutas = $this->cargarRutas();
        $tiposSangre = $this->cargarTiposSangre();
        $planillas = $this->cargarPlanillas();
        $tiposEmpleado = $this->cargarTiposEmpleado();
        $ocupaciones = $this->cargarOcupaciones();

        include __DIR__ . '/../views/formulario.php';
    }

    // MÉTODO GUARDAR
    
    public function guardar() {
        $errors = [];

        $colaboradorData = [
            'identidad' => Sanitizer::sanitizeIdentidad($_POST['identidad'] ?? ''),
            'nombre' => Sanitizer::sanitizeNombre($_POST['nombre'] ?? ''),
            'apellido' => Sanitizer::sanitizeNombre($_POST['apellido'] ?? ''),
            'edad' => Sanitizer::sanitizeEdad($_POST['edad'] ?? 0),
            'tipo_sangre' => Sanitizer::sanitizeString($_POST['tipo_sangre'] ?? ''),
            'sexo' => Sanitizer::sanitizeString($_POST['sexo'] ?? ''),
            'ruta_id' => Sanitizer::sanitizeSelectId($_POST['ruta_id'] ?? 0),
            'pais_residencia_id' => Sanitizer::sanitizeSelectId($_POST['pais_id'] ?? 0),
            'nacionalidad' => Sanitizer::sanitizeString($_POST['nacionalidad'] ?? ''),
            'correo' => Sanitizer::sanitizeEmail($_POST['correo'] ?? ''),
            'celular' => Sanitizer::sanitizeCelular($_POST['celular'] ?? ''),
            'observaciones' => Sanitizer::sanitizeObservaciones($_POST['observaciones'] ?? ''),
        ];

        $perfilData = [
            'tipo_empleado_id' => Sanitizer::sanitizeSelectId($_POST['tipo_empleado_id'] ?? 0),
            'planilla_id' => Sanitizer::sanitizeSelectId($_POST['planilla_id'] ?? 0),
            'ocupacion_id' => Sanitizer::sanitizeSelectId($_POST['ocupacion_id'] ?? 0),
            'puesto' => '', // Se llena dinámicamente más abajo
            'salario' => Sanitizer::sanitizeSalario($_POST['salario'] ?? '0'),
            'fecha_inicio' => Sanitizer::sanitizeFecha($_POST['fecha_inicio'] ?? ''),
            'fecha_fin' => Sanitizer::sanitizeFecha($_POST['fecha_fin'] ?? ''),
            'motivo' => Sanitizer::sanitizeObservaciones($_POST['motivo'] ?? ''),
            'observaciones' => Sanitizer::sanitizeObservaciones($_POST['perfil_observaciones'] ?? ''),
        ];

        // Validaciones del Colaborador
        if (!Validator::validateRequired($colaboradorData['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio';
        }
        if (!Validator::validateRequired($colaboradorData['apellido'])) {
            $errors['apellido'] = 'El apellido es obligatorio';
        }
        if (!Validator::validateIdentidad($colaboradorData['identidad'])) {
            $errors['identidad'] = 'Identidad inválida (formato: 1234-5678)';
        }
        if (!Validator::validateEmail($colaboradorData['correo'])) {
            $errors['correo'] = 'Correo electrónico inválido';
        }
        if (!Validator::validateCelular($colaboradorData['celular'])) {
            $errors['celular'] = 'Celular inválido (8 dígitos)';
        }
        if (!Validator::validateEdad($colaboradorData['edad'])) {
            $errors['edad'] = 'Edad debe ser entre 18 y 120 años';
        }
        if (!Validator::validateSexo($colaboradorData['sexo'])) {
            $errors['sexo'] = 'Seleccione un sexo válido';
        }
        if (!Validator::validateSelectId($colaboradorData['pais_residencia_id'])) {
            $errors['pais_id'] = 'Seleccione un país válido';
        }
        if (!Validator::validateSelectId($colaboradorData['ruta_id'])) {
            $errors['ruta_id'] = 'Seleccione una ruta válida';
        }
        if (!Validator::validateRequired($colaboradorData['tipo_sangre'])) {
            $errors['tipo_sangre'] = 'Seleccione un tipo de sangre válido';
        }

        // Validaciones del Perfil Laboral
        if (!Validator::validateSelectId($perfilData['tipo_empleado_id'])) {
            $errors['tipo_empleado_id'] = 'Seleccione un tipo de empleado válido';
        }
        if (!Validator::validateSelectId($perfilData['planilla_id'])) {
            $errors['planilla_id'] = 'Seleccione una planilla válida';
        }
        if (!Validator::validateSelectId($perfilData['ocupacion_id'])) {
            $errors['ocupacion_id'] = 'Seleccione una ocupación válida';
        }
        if (!Validator::validateSalario($perfilData['salario'])) {
            $errors['salario'] = 'Salario inválido';
        }
        if (!Validator::validateDate($perfilData['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'Fecha de inicio inválida';
        }
        if (!empty($perfilData['fecha_fin']) && !Validator::validateDate($perfilData['fecha_fin'])) {
            $errors['fecha_fin'] = 'Fecha fin inválida';
        }
        if (!empty($perfilData['fecha_fin']) && !Validator::validateRequired($perfilData['motivo'])) {
            $errors['motivo'] = 'Motivo es obligatorio cuando hay fecha fin';
        }

        // Si existen errores, se recargan los catálogos y se vuelve a mostrar la vista
        if (!empty($errors)) {
            $paises = $this->cargarPaises();
            $rutas = $this->cargarRutas();
            $tiposSangre = $this->cargarTiposSangre();
            $planillas = $this->cargarPlanillas();
            $tiposEmpleado = $this->cargarTiposEmpleado();
            $ocupaciones = $this->cargarOcupaciones();
            include __DIR__ . '/../views/formulario.php';
            return;
        }

        // Obtener el nombre de la ocupación para rellenar el campo descriptivo 'puesto'
        $ocupaciones = $this->cargarOcupaciones();
        foreach ($ocupaciones as $ocupacion) {
            if ($ocupacion['id'] == $perfilData['ocupacion_id']) {
                $perfilData['puesto'] = $ocupacion['nombre'];
                break;
            }
        }

        $result = (new Inscriptor())->guardar($colaboradorData, $perfilData);
if ($result['success']) {
            header('Location: /ParcialDSF7/reporte');
        } else {
            echo "Error al guardar: " . $result['error'];
        }
        exit();
    }

    // ============================================================
    // MÉTODO REPORTE
    // ============================================================
    public function reporte() {
        try {
            $inscriptores = (new Inscriptor())->obtenerTodos();
            if (!is_array($inscriptores)) $inscriptores = [];
        } catch (\Exception $e) {
            $inscriptores = [];
            error_log("Error en reporte: " . $e->getMessage());
        }
        include __DIR__ . '/../views/reporte.php';
    }

    // ============================================================
    // MÉTODO EXPORTAR EXCEL
    // ============================================================
    public function exportarExcel() {
        try {
            $inscriptores = (new Inscriptor())->obtenerTodos();
            if (!is_array($inscriptores)) $inscriptores = [];
        } catch (\Exception $e) {
            $inscriptores = [];
            error_log("Error en exportarExcel: " . $e->getMessage());
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="reporte_empresarial_colaboradores.xls"');
        echo '<table border="1"><tr><th>ID</th><th>Código</th><th>Identidad</th><th>Nombre</th><th>Apellido</th><th>Edad</th><th>Tipo Sangre</th><th>Sexo</th><th>Ruta</th><th>País</th><th>Nacionalidad</th><th>Correo</th><th>Celular</th><th>Puesto</th><th>Ocupación</th><th>Tipo Empleado</th><th>Planilla</th><th>Salario</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Activo</th><th>Motivo</th><th>Fecha Registro</th></tr>';
        foreach ($inscriptores as $row) {
            $activo = ($row['es_activo'] ?? 0) == 1 ? 'Sí' : 'No';
            echo "<tr><td>{$row['id']}</td><td>{$row['codigo_empleado']}</td><td>{$row['identidad']}</td><td>{$row['nombre']}</td><td>{$row['apellido']}</td><td>{$row['edad']}</td><td>{$row['tipo_sangre']}</td><td>{$row['sexo']}</td><td>{$row['ruta']}</td><td>{$row['pais_nombre']}</td><td>{$row['nacionalidad']}</td><td>{$row['correo']}</td><td>{$row['celular']}</td><td>{$row['puesto']}</td><td>{$row['ocupacion']}</td><td>{$row['tipo_empleado']}</td><td>{$row['planilla']}</td><td>{$row['salario']}</td><td>{$row['fecha_inicio']}</td><td>{$row['fecha_fin']}</td><td>{$activo}</td><td>{$row['motivo']}</td><td>{$row['fecha_registro']}</td></tr>";
        }
        echo '</table>';
        exit();
    }

    // ============================================================
    // FIRMA DIGITAL
    // ============================================================
    public function firmarReporte($datos) {
        $privateKeyPath = __DIR__ . '/../../private_key.pem';
        $publicKeyPath = __DIR__ . '/../../public_key.pem';

        if (!file_exists($privateKeyPath) || !file_exists($publicKeyPath)) {
            return [
                'success' => false,
                'error' => 'Las claves OpenSSL no existen. Ejecuta: php generar_claves.php'
            ];
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        if (!$privateKey) {
            return ['success' => false, 'error' => 'No se pudo cargar la clave privada'];
        }

        $dataString = json_encode($datos);
        $hash = hash('sha256', $dataString);

        $signature = '';
        openssl_sign($hash, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $signatureBase64 = base64_encode($signature);

        $publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));
        $verification = openssl_verify($hash, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return [
            'success' => true,
            'hash' => $hash,
            'firma' => $signatureBase64,
            'verificada' => $verification === 1,
            'fecha' => date('Y-m-d H:i:s')
        ];
    }
}
?>
