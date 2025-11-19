<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h4 class="text-center mb-4 fw-bold">San Gabriel</h4>
        <a href="index.php?action=dashboard" class="text-white text-decoration-none d-block p-2 bg-secondary rounded mb-2"><i class="fas fa-home me-2"></i> Inicio</a>
        <a href="index.php?action=agendar" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-calendar-plus me-2"></i> Nueva Cita</a>
        <a href="index.php?action=dashboard" class="text-secondary text-decoration-none d-block p-2"><i class="fas fa-history me-2"></i> Historial</a>
        <hr>
        <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2"><i class="fas fa-sign-out-alt me-2"></i> Salir</a>
    </div>

    <div class="flex-grow-1 p-4">
        <h2>Bienvenido, <?php echo $_SESSION['user_name']; ?></h2>
        <p class="text-muted">Panel del Paciente</p>

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

        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="card card-custom bg-primary text-white p-3 h-100">
                    <h5>Próxima Cita</h5>
                    <?php if($proximaCita): ?>
                        <p class="display-6"><?php echo date('d M', strtotime($proximaCita['fecha_cita'])); ?></p>
                        <small><?php echo date('H:i A', strtotime($proximaCita['hora_cita'])); ?> - <?php echo htmlspecialchars($proximaCita['especialidad'] ?? 'N/A'); ?></small>
                        <br><small class="mt-2">Dr. <?php echo htmlspecialchars($proximaCita['medico_nombre']); ?></small>
                    <?php else: ?>
                        <p class="display-6">-</p>
                        <small>No hay citas programadas</small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom bg-white p-3 h-100">
                    <h5>Mis Citas</h5>
                    <p class="display-6 text-dark"><?php echo $citasPendientes; ?></p>
                    <small class="text-muted">Citas pendientes</small>
                </div>
            </div>
            <div class="col-md-4">
                <a href="index.php?action=agendar" class="text-decoration-none">
                    <div class="card card-custom bg-success text-white p-3 h-100" style="cursor:pointer;">
                        <h5><i class="fas fa-plus-circle"></i> Agendar</h5>
                        <p class="mt-2">Solicitar nueva atención</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="card card-custom bg-white mt-4 p-4">
            <h5>Citas Recientes</h5>
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Médico</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($citas)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay citas registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($citas as $cita): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($cita['fecha_cita'])); ?></td>
                                <td><?php echo date('H:i', strtotime($cita['hora_cita'])); ?></td>
                                <td><?php echo htmlspecialchars($cita['medico_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['especialidad'] ?? 'N/A'); ?></td>
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
                                    <?php if($cita['estado'] == 'pendiente'): ?>
                                        <a href="index.php?action=citas_confirmar&id=<?php echo $cita['id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('¿Confirmar esta cita?')">
                                            <i class="fas fa-check"></i> Confirmar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../views/layouts/footer.php'; ?>