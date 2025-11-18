<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">SG Admin</h4>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <hr>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Médico</h2>
            <a href="index.php?action=medicos" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="index.php?action=medicos_update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $medico['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($medico['nombre_completo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($medico['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($medico['telefono'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad *</label>
                        <select name="especialidad_id" class="form-select" required>
                            <option value="">Seleccione una especialidad</option>
                            <?php foreach($especialidades as $especialidad): ?>
                                <option value="<?php echo $especialidad['id']; ?>" 
                                    <?php echo ($especialidad['id'] == $medico['especialidad_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($especialidad['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?action=medicos" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Médico</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>

