<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Colaboradores</title>
<base href="/ParcialDSF7/public/">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>Reporte de <span>Colaboradores</span></h1>
                <p>Resumen de perfiles laborales registrados</p>
            </div>
            <div class="header-actions">
<a href="/ParcialDSF7/public/exportar-excel" class="btn btn-excel">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="/ParcialDSF7/public/" class="btn btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if (empty($inscriptores)): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No hay colaboradores registrados</h3>
                <p>Registra un colaborador desde el formulario principal.</p>
            </div>
        <?php else: ?>
            <?php
                $total = count($inscriptores);
                $activos = 0;
                foreach ($inscriptores as $row) {
                    if (!empty($row['es_activo']) && $row['es_activo'] == 1) {
                        $activos++;
                    }
                }
            ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-label">Total colaboradores</div>
                    <div class="stat-value"><?= $total ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Perfiles activos</div>
                    <div class="stat-value green"><?= $activos ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Perfiles inactivos</div>
                    <div class="stat-value red"><?= $total - $activos ?></div>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Identidad</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                            <th>Tipo sangre</th>
                            <th>Ruta</th>
                            <th>País</th>
                            <th>Ocupación</th>
                            <th>Tipo empleado</th>
                            <th>Planilla</th>
                            <th>Salario</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 0; ?>
                        <?php foreach ($inscriptores as $row): ?>
                            <?php $contador++; ?>
                            <?php $activo = !empty($row['es_activo']) && $row['es_activo'] == 1 ? 'Sí' : 'No'; ?>
                            <tr class="<?= $activo === 'Sí' ? 'integro' : 'corrompido' ?>">
                                <td><?= $contador ?></td>
                                <td><?= htmlspecialchars($row['codigo_empleado'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['identidad'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nombre'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['apellido'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['edad'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['sexo'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['tipo_sangre'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['ruta'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['pais_nombre'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['ocupacion'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['tipo_empleado'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['planilla'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['salario'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['fecha_inicio'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['fecha_fin'] ?? '-') ?></td>
                                <td><?= $activo ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        
    </div>
    <div class="footer">
            <p>
                <i class="fas fa-copyright"></i> 2026 iTECH Contrataciones. Todos los derechos reservados.
                &nbsp;|&nbsp; <i class="fas fa-envelope"></i> info@itech.com
                &nbsp;|&nbsp; <i class="fas fa-phone"></i> +507 1234-5678
            </p>
            <p class="small muted">
                Reporte generado el <?= date('d/m/Y \a \l\a\s H:i:s') ?>
            </p>
        </div>
</body>
</html>
