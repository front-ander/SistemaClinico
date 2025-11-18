<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
        <a href="index.php?action=dashboard" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="index.php?action=medicos" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-md me-2"></i> Médicos</a>
        <a href="index.php?action=pacientes" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-users me-2"></i> Pacientes</a>
        <a href="index.php?action=citas" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-check me-2"></i> Citas</a>
        <a href="index.php?action=profile" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-user-cog me-2"></i> Reporte-PDF</a>
        <hr>
        <a href="index.php?action=settings" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-cogs me-2"></i> Configuración</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel de Administración</h2>
            <span class="badge bg-primary p-2">Admin: <?php echo $_SESSION['user_name']; ?></span>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Pacientes Totales</h6>
                                <h3 class="fw-bold"><?php echo $stats['pacientes']; ?></h3>
                            </div>
                            <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Médicos Activos</h6>
                                <h3 class="fw-bold"><?php echo $stats['medicos']; ?></h3>
                            </div>
                            <i class="fas fa-user-md fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Citas Hoy</h6>
                                <h3 class="fw-bold"><?php echo $stats['citas_hoy']; ?></h3>
                            </div>
                            <i class="fas fa-calendar-day fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<?php include '../views/layouts/footer.php'; ?>