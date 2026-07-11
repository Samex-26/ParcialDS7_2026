<?php
require __DIR__ . '/app/config/BaseDatos.php';
require __DIR__ . '/app/models/GrupoSanguineo.php';

use App\Models\GrupoSanguineo;

try {
    $grupoSanguineo = new GrupoSanguineo();
    $tiposSangre = $grupoSanguineo->listarTodos();
    echo 'OK\n';
    echo 'TIPOS_SANGRE_COUNT=' . count($tiposSangre) . "\n";
    foreach ($tiposSangre as $row) {
        echo $row['id'] . ' - ' . $row['nombre'] . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
