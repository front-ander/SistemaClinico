<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <a href="index.php?action=reportes" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-file-alt me-2"></i> Reporte-PDF</a>
        <hr>
        <a href="index.php?action=settings" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <h2 class="mb-4">Generador de Reportes</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                        <h4>Reporte de Citas</h4>
                        <p class="text-muted">Listado completo de todas las citas registradas.</p>
                        <a href="index.php?action=reportes_ver&type=citas" target="_blank" class="btn btn-outline-primary w-100">Generar PDF</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h4>Reporte de Pacientes</h4>
                        <p class="text-muted">Listado de pacientes registrados en el sistema.</p>
                        <a href="index.php?action=reportes_ver&type=pacientes" target="_blank" class="btn btn-outline-success w-100">Generar PDF</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-3x text-info mb-3"></i>
                        <h4>Reporte de Médicos</h4>
                        <p class="text-muted">Listado del personal médico activo.</p>
                        <a href="index.php?action=reportes_ver&type=medicos" target="_blank" class="btn btn-outline-info w-100">Generar PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>
