<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <a href="index.php?action=reportes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-file-alt me-2"></i> Reporte-PDF</a>
        <hr>
        <a href="index.php?action=settings" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Cita</h2>
            <a href="index.php?action=citas" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="index.php?action=citas_update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Paciente</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($cita['paciente_nombre']); ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Médico *</label>
                        <select name="medico_id" class="form-select" required>
                            <option value="">Seleccione un médico</option>
                            <?php foreach($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>" 
                                    <?php echo ($medico['id'] == $cita['medico_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($medico['nombre_completo'] . ' - ' . $medico['especialidad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" value="<?php echo $cita['fecha_cita']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora *</label>
                            <input type="time" name="hora" class="form-control" value="<?php echo date('H:i', strtotime($cita['hora_cita'])); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <textarea name="motivo" class="form-control" rows="3"><?php echo htmlspecialchars($cita['motivo'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-select" required>
                            <option value="pendiente" <?php echo ($cita['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="confirmada" <?php echo ($cita['estado'] == 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                            <option value="cancelada" <?php echo ($cita['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                            <option value="completada" <?php echo ($cita['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?action=citas" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Cita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>

