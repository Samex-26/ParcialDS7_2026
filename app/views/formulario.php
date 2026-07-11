<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Colaboradores - Perfil Laboral</title>
<base href="/ParcialDSF7/public/">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php if (!isset($errors)) { $errors = []; } ?>

    <div class="container">
        <div class="header">
            <h1>Gestión de <span>Colaboradores</span></h1>
            <p>Registro de colaborador y perfil laboral</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-circle-exclamation"></i>
                <div>
                    <strong>Por favor, corrige los siguientes errores:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>• <?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

<form action="/ParcialDSF7/public/guardar" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Identidad <span class="required">*</span></label>
                    <input type="text" name="identidad" placeholder="00-0000-0000" maxlength="12" pattern="[0-9]{2}-[0-9]{4}-[0-9]{4}"
                           value="<?= htmlspecialchars($_POST['identidad'] ?? '') ?>"
                           class="<?= isset($errors['identidad']) ? 'error' : '' ?>">
                    <?php if (isset($errors['identidad'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['identidad']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" placeholder="Nombres"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                           class="<?= isset($errors['nombre']) ? 'error' : '' ?>">
                    <?php if (isset($errors['nombre'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['nombre']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Apellido <span class="required">*</span></label>
                    <input type="text" name="apellido" placeholder="Apellidos"
                           value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>"
                           class="<?= isset($errors['apellido']) ? 'error' : '' ?>">
                    <?php if (isset($errors['apellido'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['apellido']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Edad <span class="required">*</span></label>
                    <input type="number" name="edad" min="18" max="120"
                           value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>"
                           class="<?= isset($errors['edad']) ? 'error' : '' ?>">
                    <?php if (isset($errors['edad'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['edad']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Sexo <span class="required">*</span></label>
                    <select name="sexo" class="<?= isset($errors['sexo']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <option value="Masculino" <?= ($_POST['sexo'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option value="Femenino" <?= ($_POST['sexo'] ?? '') === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                        <option value="Otro" <?= ($_POST['sexo'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                    <?php if (isset($errors['sexo'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['sexo']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Tipo de sangre <span class="required">*</span></label>
                    <select name="tipo_sangre" class="<?= isset($errors['tipo_sangre']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($tiposSangre) && is_array($tiposSangre)): ?>
                            <?php foreach ($tiposSangre as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo['nombre']) ?>" <?= ($_POST['tipo_sangre'] ?? '') === $tipo['nombre'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['tipo_sangre'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['tipo_sangre']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Ruta <span class="required">*</span></label>
                    <select name="ruta_id" class="<?= isset($errors['ruta_id']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($rutas) && is_array($rutas)): ?>
                            <?php foreach ($rutas as $ruta): ?>
                                <option value="<?= $ruta['id'] ?>" <?= ($_POST['ruta_id'] ?? '') == $ruta['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ruta['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['ruta_id'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['ruta_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>País de residencia <span class="required">*</span></label>
                    <select name="pais_id" class="<?= isset($errors['pais_id']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($paises) && is_array($paises)): ?>
                            <?php foreach ($paises as $pais): ?>
                                <option value="<?= $pais['id'] ?>" <?= ($_POST['pais_id'] ?? '') == $pais['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pais['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['pais_id'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['pais_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group full-width">
                    <label>Nacionalidad <span class="required">*</span></label>
                    <input type="text" name="nacionalidad" placeholder="Ej: Panameña, Colombiana, Mexicana..."
                           value="<?= htmlspecialchars($_POST['nacionalidad'] ?? '') ?>"
                           class="<?= isset($errors['nacionalidad']) ? 'error' : '' ?>">
                    <?php if (isset($errors['nacionalidad'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['nacionalidad']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Correo electrónico <span class="required">*</span></label>
                    <input type="email" name="correo" placeholder="correo@ejemplo.com"
                           value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                           class="<?= isset($errors['correo']) ? 'error' : '' ?>">
                    <?php if (isset($errors['correo'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['correo']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Celular <span class="required">*</span></label>
                    <input type="text" name="celular" placeholder="8 dígitos"
                           value="<?= htmlspecialchars($_POST['celular'] ?? '') ?>"
                           class="<?= isset($errors['celular']) ? 'error' : '' ?>">
                    <?php if (isset($errors['celular'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['celular']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Puesto (Ocupación) <span class="required">*</span></label>
                    <select name="ocupacion_id" class="<?= isset($errors['ocupacion_id']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($ocupaciones) && is_array($ocupaciones)): ?>
                            <?php foreach ($ocupaciones as $ocupacion): ?>
                                <option value="<?= $ocupacion['id'] ?>" <?= ($_POST['ocupacion_id'] ?? '') == $ocupacion['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ocupacion['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['ocupacion_id'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['ocupacion_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Tipo de empleado <span class="required">*</span></label>
                    <select name="tipo_empleado_id" class="<?= isset($errors['tipo_empleado_id']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($tiposEmpleado) && is_array($tiposEmpleado)): ?>
                            <?php foreach ($tiposEmpleado as $tipoEmpleado): ?>
                                <option value="<?= $tipoEmpleado['id'] ?>" <?= ($_POST['tipo_empleado_id'] ?? '') == $tipoEmpleado['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipoEmpleado['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['tipo_empleado_id'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['tipo_empleado_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Planilla <span class="required">*</span></label>
                    <select name="planilla_id" class="<?= isset($errors['planilla_id']) ? 'error' : '' ?>">
                        <option value="">Selecciona</option>
                        <?php if (isset($planillas) && is_array($planillas)): ?>
                            <?php foreach ($planillas as $planilla): ?>
                                <option value="<?= $planilla['id'] ?>" <?= ($_POST['planilla_id'] ?? '') == $planilla['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($planilla['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['planilla_id'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['planilla_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Salario <span class="required">*</span></label>
                    <input type="text" name="salario" placeholder="Ej: 1200.00"
                           value="<?= htmlspecialchars($_POST['salario'] ?? '') ?>"
                           class="<?= isset($errors['salario']) ? 'error' : '' ?>">
                    <?php if (isset($errors['salario'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['salario']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Fecha de inicio <span class="required">*</span></label>
                    <input type="date" name="fecha_inicio"
                           value="<?= htmlspecialchars($_POST['fecha_inicio'] ?? '') ?>"
                           class="<?= isset($errors['fecha_inicio']) ? 'error' : '' ?>">
                    <?php if (isset($errors['fecha_inicio'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['fecha_inicio']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($_POST['fecha_fin'] ?? '') ?>"
                           class="<?= isset($errors['fecha_fin']) ? 'error' : '' ?>">
                    <?php if (isset($errors['fecha_fin'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['fecha_fin']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group full-width">
                    <label>Motivo (si hay fecha fin)</label>
                    <textarea name="motivo" rows="3" placeholder="Explique el motivo si termina el contrato"><?= htmlspecialchars($_POST['motivo'] ?? '') ?></textarea>
                    <?php if (isset($errors['motivo'])): ?>
                        <div class="error-text"><?= htmlspecialchars($errors['motivo']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group full-width">
                    <label>Observaciones</label>
                    <textarea name="perfil_observaciones" rows="3" placeholder="Comentarios adicionales del perfil laboral"><?= htmlspecialchars($_POST['perfil_observaciones'] ?? '') ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Guardar colaborador
            </button>
        </form>
        <br>
        <div class="header-actions">
            <a href="/ParcialDSF7/public/reporte" class="btn btn-reporte">
               <i class="fas fa-table"></i> Ver reporte
            </a>
        </div>

        
    </div>
    <div class="footer">
            <p>
                <i class="fas fa-copyright"></i> 2026 iTECH Contrataciones. Todos los derechos reservados.
            </p>
            <p class="small muted">
                <i class="fas fa-envelope"></i> info@itech.com &nbsp;|&nbsp;
                <i class="fas fa-phone"></i> +507 1234-5678
            </p>
        </div>
</body>
</html>
