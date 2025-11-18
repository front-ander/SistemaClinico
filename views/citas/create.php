<?php include '../views/layouts/header.php'; ?>

<div class="d-flex">
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-center mb-4">San Gabriel</h4>
        <a href="index.php?action=dashboard"><i class="fas fa-home me-2"></i> Inicio</a>
        <a href="#" class="bg-secondary text-white rounded"><i class="fas fa-calendar-plus me-2"></i> Nueva Cita</a>
        <a href="index.php?action=logout" class="text-danger mt-5"><i class="fas fa-sign-out-alt me-2"></i> Salir</a>
    </div>

    <div class="flex-grow-1 p-4">
        <h2>Agendar Nueva Cita</h2>
        <div class="card card-custom bg-white p-4 mt-4 col-md-8">
            <form action="index.php?action=guardar_cita" method="POST">
                
                <div class="mb-3">
                    <label class="form-label">Seleccionar Médico / Especialidad</label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php foreach($medicos as $medico): ?>
                            <option value="<?php echo $medico['id']; ?>">
                                <?php echo $medico['nombre_completo'] . " - " . $medico['especialidad']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha Preferida</label>
                        <input type="date" name="fecha" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hora Preferida</label>
                        <input type="time" name="hora" class="form-control" min="08:00" max="18:00" required>
                        <small class="text-muted">Horario de atención: 8:00 AM - 6:00 PM</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Motivo de consulta</label>
                    <textarea name="motivo" class="form-control" rows="3" placeholder="Describa brevemente sus síntomas..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Confirmar Cita</button>
            </form>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>