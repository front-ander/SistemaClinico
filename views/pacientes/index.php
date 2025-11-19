<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <a href="index.php?action=reportes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-file-alt me-2"></i> Reporte-PDF</a>
        <hr>
        <a href="index.php?action=settings" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestión de Pacientes</h2>
            <a href="index.php?action=pacientes_create" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nuevo Paciente</a>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pacientes)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay pacientes registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($pacientes as $paciente): ?>
                                <tr>
                                    <td><?php echo $paciente['id']; ?></td>
                                    <td><?php echo htmlspecialchars($paciente['nombre_completo']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['email']); ?></td>
                                    <td><?php echo htmlspecialchars($paciente['telefono'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($paciente['fecha_registro'])); ?></td>
                                    <td>
                                        <a href="index.php?action=pacientes_edit&id=<?php echo $paciente['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=pacientes_delete&id=<?php echo $paciente['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar este paciente?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>

