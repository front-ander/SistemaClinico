<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold"><?php echo $_SESSION['user_role'] === 'admin' ? 'SG Admin' : 'San Gabriel'; ?></h4>
        <a href="index.php?action=dashboard" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-home me-2"></i> Inicio</a>
        <?php if($_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
            <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
            <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
            <a href="index.php?action=reportes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-file-alt me-2"></i> Reporte-PDF</a>
        <?php endif; ?>
        <hr>
        <a href="index.php?action=settings" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detalles de la Cita</h2>
            <a href="index.php?action=dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Información de la Cita</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">Paciente:</th>
                                <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                            </tr>
                            <tr>
                                <th>Médico:</th>
                                <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                            </tr>
                            <?php if(isset($cita['especialidad'])): ?>
                            <tr>
                                <th>Especialidad:</th>
                                <td><?php echo htmlspecialchars($cita['especialidad']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Fecha:</th>
                                <td><?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?></td>
                            </tr>
                            <tr>
                                <th>Hora:</th>
                                <td><?php echo date('H:i', strtotime($cita['hora_cita'])); ?></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <?php
                                    $estadoClass = [
                                        'pendiente' => 'warning',
                                        'confirmada' => 'success',
                                        'cancelada' => 'danger',
                                        'completada' => 'info'
                                    ];
                                    $class = $estadoClass[$cita['estado']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $class; ?> fs-6">
                                        <?php echo ucfirst($cita['estado']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php if($cita['motivo']): ?>
                            <tr>
                                <th>Motivo:</th>
                                <td><?php echo nl2br(htmlspecialchars($cita['motivo'])); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <?php if($cita['resultados']): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold text-success">
                        <i class="fas fa-file-medical me-2"></i>Resultados de la Consulta
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($cita['resultados'])); ?>
                        </div>
                    </div>
                </div>
                <?php elseif($_SESSION['user_role'] === 'medico' && $cita['medico_id'] == $_SESSION['user_id'] && $cita['estado'] != 'cancelada'): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Agregar Resultados de la Consulta</div>
                    <div class="card-body">
                        <form action="index.php?action=citas_agregar_resultados" method="POST">
                            <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Resultados / Notas de la Consulta</label>
                                <textarea name="resultados" class="form-control" rows="8" required placeholder="Ingrese los resultados, diagnóstico, tratamiento, recomendaciones, etc."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Resultados
                            </button>
                        </form>
                    </div>
                </div>
                <?php elseif($_SESSION['user_role'] === 'paciente'): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Los resultados de la consulta estarán disponibles una vez que el médico complete la atención.
                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <?php if($_SESSION['user_role'] === 'paciente' && $cita['estado'] == 'pendiente'): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-3">Confirmar Cita</h6>
                        <a href="index.php?action=citas_confirmar&id=<?php echo $cita['id']; ?>" 
                           class="btn btn-success w-100"
                           onclick="return confirm('¿Confirmar esta cita?')">
                            <i class="fas fa-check me-2"></i>Confirmar Cita
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($cita['paciente_email']) || isset($cita['paciente_telefono'])): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Información de Contacto</div>
                    <div class="card-body">
                        <?php if(isset($cita['paciente_email'])): ?>
                        <p><strong>Email:</strong><br><?php echo htmlspecialchars($cita['paciente_email']); ?></p>
                        <?php endif; ?>
                        <?php if(isset($cita['paciente_telefono'])): ?>
                        <p><strong>Teléfono:</strong><br><?php echo htmlspecialchars($cita['paciente_telefono']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>

