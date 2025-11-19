<?php include '../views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../views/layouts/sidebar_medico.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Horarios</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    Configurar Disponibilidad Semanal
                </div>
                <div class="card-body">
                    <form action="index.php?action=guardar_horarios" method="POST">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Disponible</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                    foreach ($dias as $dia): 
                                        $h = $horarios_map[$dia] ?? ['activo' => 0, 'hora_inicio' => '09:00', 'hora_fin' => '17:00'];
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $dia; ?></strong></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="activo[<?php echo $dia; ?>]" 
                                                       id="activo_<?php echo $dia; ?>"
                                                       <?php echo $h['activo'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="activo_<?php echo $dia; ?>">
                                                    <?php echo $h['activo'] ? 'Activo' : 'Inactivo'; ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" 
                                                   name="inicio[<?php echo $dia; ?>]" 
                                                   value="<?php echo date('H:i', strtotime($h['hora_inicio'])); ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" 
                                                   name="fin[<?php echo $dia; ?>]" 
                                                   value="<?php echo date('H:i', strtotime($h['hora_fin'])); ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Horarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Script simple para actualizar el texto del switch
document.querySelectorAll('.form-check-input').forEach(input => {
    input.addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.checked ? 'Activo' : 'Inactivo';
    });
});
</script>

<?php include '../views/layouts/footer.php'; ?>
