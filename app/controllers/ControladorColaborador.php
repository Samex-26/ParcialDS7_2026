<?php
namespace App\Controllers;

use App\Config\BaseDatos;
use App\Models\Colaborador;
use App\Utils\Validador;
use App\Utils\Limpiador;

class ControladorColaborador {
    private function buscar($sql, $params = []) {
        return BaseDatos::obtenerInstancia()->obtenerFilas($sql, $params);
    }

    private function obtenerPaises() {
        return $this->buscar("SELECT id, nombre FROM paises ORDER BY nombre");
    }

    private function obtenerRutas() {
        return $this->buscar("SELECT id, nombre FROM cat_rutas ORDER BY nombre");
    }

    private function obtenerTiposSangre() {
        return $this->buscar("SELECT nombre FROM cat_tipos_sangre ORDER BY nombre");
    }

    private function obtenerPlanillas() {
        return $this->buscar("SELECT id, nombre FROM cat_tipos_planilla ORDER BY nombre");
    }

    private function obtenerTiposEmpleado() {
        return $this->buscar("SELECT id, nombre FROM cat_tipos_empleado ORDER BY nombre");
    }

    private function obtenerOcupaciones() {
        return $this->buscar("SELECT id, nombre FROM cat_ocupaciones ORDER BY nombre");
    }

    // ============================================================
    // MÉTODO MOSTRAR FORMULARIO
    // ============================================================
    public function mostrarFormulario() {
        $paises = $this->obtenerPaises();
        $rutas = $this->obtenerRutas();
        $tiposSangre = $this->obtenerTiposSangre();
        $planillas = $this->obtenerPlanillas();
        $tiposEmpleado = $this->obtenerTiposEmpleado();
        $ocupaciones = $this->obtenerOcupaciones();

        include __DIR__ . '/../views/formulario.php';
    }

    // ============================================================
    // MÉTODO REGISTRAR
    // ============================================================
    public function registrar() {
        $errors = [];

        $colaboradorData = [
            'identidad' => Limpiador::limpiarIdentidad($_POST['identidad'] ?? ''),
            'nombre' => Limpiador::limpiarNombre($_POST['nombre'] ?? ''),
            'apellido' => Limpiador::limpiarNombre($_POST['apellido'] ?? ''),
            'edad' => Limpiador::limpiarEdad($_POST['edad'] ?? 0),
            'tipo_sangre' => Limpiador::limpiarCadena($_POST['tipo_sangre'] ?? ''),
            'sexo' => Limpiador::limpiarCadena($_POST['sexo'] ?? ''),
            'ruta_id' => Limpiador::limpiarIdSeleccion($_POST['ruta_id'] ?? 0),
            'pais_residencia_id' => Limpiador::limpiarIdSeleccion($_POST['pais_id'] ?? 0),
            'nacionalidad' => Limpiador::limpiarCadena($_POST['nacionalidad'] ?? ''),
            'correo' => Limpiador::limpiarCorreo($_POST['correo'] ?? ''),
            'celular' => Limpiador::limpiarCelular($_POST['celular'] ?? ''),
            'observaciones' => Limpiador::limpiarObservaciones($_POST['observaciones'] ?? ''),
        ];

        $perfilData = [
            'tipo_empleado_id' => Limpiador::limpiarIdSeleccion($_POST['tipo_empleado_id'] ?? 0),
            'planilla_id' => Limpiador::limpiarIdSeleccion($_POST['planilla_id'] ?? 0),
            'ocupacion_id' => Limpiador::limpiarIdSeleccion($_POST['ocupacion_id'] ?? 0),
            'puesto' => '', // Se llena dinámicamente más abajo
            'salario' => Limpiador::limpiarSalario($_POST['salario'] ?? '0'),
            'fecha_inicio' => Limpiador::limpiarFecha($_POST['fecha_inicio'] ?? ''),
            'fecha_fin' => Limpiador::limpiarFecha($_POST['fecha_fin'] ?? ''),
            'motivo' => Limpiador::limpiarObservaciones($_POST['motivo'] ?? ''),
            'observaciones' => Limpiador::limpiarObservaciones($_POST['perfil_observaciones'] ?? ''),
        ];

        // Validaciones del Colaborador
        if (!Validador::validarRequerido($colaboradorData['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio';
        }
        if (!Validador::validarRequerido($colaboradorData['apellido'])) {
            $errors['apellido'] = 'El apellido es obligatorio';
        }
        if (!Validador::validarIdentidad($colaboradorData['identidad'])) {
            $errors['identidad'] = 'Identidad inválida (formato: 00-0000-0000)';
        }
        if (!Validador::validarCorreo($colaboradorData['correo'])) {
            $errors['correo'] = 'Correo electrónico inválido';
        }
        if (!Validador::validarCelular($colaboradorData['celular'])) {
            $errors['celular'] = 'Celular inválido (8 dígitos)';
        }
        if (!Validador::validarEdad($colaboradorData['edad'])) {
            $errors['edad'] = 'Edad debe ser entre 18 y 120 años';
        }
        if (!Validador::validarSexo($colaboradorData['sexo'])) {
            $errors['sexo'] = 'Seleccione un sexo válido';
        }
        if (!Validador::validarIdSeleccion($colaboradorData['pais_residencia_id'])) {
            $errors['pais_id'] = 'Seleccione un país válido';
        }
        if (!Validador::validarIdSeleccion($colaboradorData['ruta_id'])) {
            $errors['ruta_id'] = 'Seleccione una ruta válida';
        }
        if (!Validador::validarRequerido($colaboradorData['tipo_sangre'])) {
            $errors['tipo_sangre'] = 'Seleccione un tipo de sangre válido';
        }

        // Validaciones del Perfil Laboral
        if (!Validador::validarIdSeleccion($perfilData['tipo_empleado_id'])) {
            $errors['tipo_empleado_id'] = 'Seleccione un tipo de empleado válido';
        }
        if (!Validador::validarIdSeleccion($perfilData['planilla_id'])) {
            $errors['planilla_id'] = 'Seleccione una planilla válida';
        }
        if (!Validador::validarIdSeleccion($perfilData['ocupacion_id'])) {
            $errors['ocupacion_id'] = 'Seleccione una ocupación válida';
        }
        if (!Validador::validarSalario($perfilData['salario'])) {
            $errors['salario'] = 'Salario inválido';
        }
        if (!Validador::validarFecha($perfilData['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'Fecha de inicio inválida';
        }
        if (!empty($perfilData['fecha_fin']) && !Validador::validarFecha($perfilData['fecha_fin'])) {
            $errors['fecha_fin'] = 'Fecha fin inválida';
        }
        if (!empty($perfilData['fecha_fin']) && !Validador::validarRequerido($perfilData['motivo'])) {
            $errors['motivo'] = 'Motivo es obligatorio cuando hay fecha fin';
        }

        // Si existen errores, se recargan los catálogos y se vuelve a mostrar la vista
        if (!empty($errors)) {
            $paises = $this->obtenerPaises();
            $rutas = $this->obtenerRutas();
            $tiposSangre = $this->obtenerTiposSangre();
            $planillas = $this->obtenerPlanillas();
            $tiposEmpleado = $this->obtenerTiposEmpleado();
            $ocupaciones = $this->obtenerOcupaciones();
            include __DIR__ . '/../views/formulario.php';
            return;
        }

        // Obtener el nombre de la ocupación para rellenar el campo descriptivo 'puesto'
        $ocupaciones = $this->obtenerOcupaciones();
        foreach ($ocupaciones as $ocupacion) {
            if ($ocupacion['id'] == $perfilData['ocupacion_id']) {
                $perfilData['puesto'] = $ocupacion['nombre'];
                break;
            }
        }

        $result = (new Colaborador())->registrar($colaboradorData, $perfilData);
if ($result['success']) {
            header('Location: /ParcialDSF7/reporte');
        } else {
            echo "Error al guardar: " . $result['error'];
        }
        exit();
    }

    // ============================================================
    // MÉTODO MOSTRAR REPORTE
    // ============================================================
    public function mostrarReporte() {
        try {
            $inscriptores = (new Colaborador())->listarTodos();
            if (!is_array($inscriptores)) $inscriptores = [];
        } catch (\Exception $e) {
            $inscriptores = [];
            error_log("Error en reporte: " . $e->getMessage());
        }
        include __DIR__ . '/../views/reporte.php';
    }

    // ============================================================
    // MÉTODO DESCARGAR EXCEL
    // ============================================================
    public function descargarExcel() {
        try {
            $inscriptores = (new Colaborador())->listarTodos();
            if (!is_array($inscriptores)) $inscriptores = [];
        } catch (\Exception $e) {
            $inscriptores = [];
            error_log("Error en descargarExcel: " . $e->getMessage());
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
    public function firmarInforme($datos) {
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
