<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel</h4>
        <a href="index.php?action=dashboard" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-home me-2"></i> Inicio</a>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2 mt-5"><i class="fas fa-sign-out-alt me-2"></i> Salir</a>
    </div>

    <div class="flex-grow-1 p-4 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Bienvenido, <?php echo $_SESSION['user_name']; ?></h2>
                <p class="text-muted">Panel del Médico</p>
            </div>
            <?php if($citasPendientes > 0): ?>
                <span class="badge bg-danger fs-6 p-3">
                    <i class="fas fa-bell me-2"></i><?php echo $citasPendientes; ?> Citas Pendientes
                </span>
            <?php endif; ?>
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

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Citas Pendientes</h6>
                                <h3 class="fw-bold"><?php echo $citasPendientes; ?></h3>
                            </div>
                            <i class="fas fa-calendar-check fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Citas</h6>
                                <h3 class="fw-bold"><?php echo count($citas); ?></h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Mis Citas</div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($citas)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay citas programadas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($citas as $cita): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($cita['hora_cita'])); ?></td>
                                    <td><?php echo htmlspecialchars($cita['paciente_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['paciente_email']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['paciente_telefono'] ?? 'N/A'); ?></td>
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
                                        <span class="badge bg-<?php echo $class; ?>">
                                            <?php echo ucfirst($cita['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=citas_ver&id=<?php echo $cita['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
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

