<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <!-- Sidebar (Simplificado para reutilizar, idealmente sería un include separado) -->
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <a href="index.php?action=reportes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-file-alt me-2"></i> Reporte-PDF</a>
        <hr>
        <a href="index.php?action=settings" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4">
        <h2 class="mb-4">Configuración del Sistema</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Configuración actualizada correctamente.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="index.php?action=settings_update" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Clínica</label>
                        <input type="text" name="nombre_clinica" class="form-control" value="<?php echo htmlspecialchars($settings['nombre_clinica'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($settings['direccion'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($settings['telefono'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email de Contacto</label>
                        <input type="email" name="email_contacto" class="form-control" value="<?php echo htmlspecialchars($settings['email_contacto'] ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>
