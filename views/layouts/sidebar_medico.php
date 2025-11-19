<div class="sidebar p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <h4 class="text-center mb-4 fw-bold">San Gabriel-Admin</h4>
    <a href="index.php?action=dashboard" class="text-white text-decoration-none d-block p-2 <?php echo (!isset($_GET['action']) || $_GET['action'] == 'dashboard') ? 'bg-secondary rounded' : ''; ?> mb-2">
        <i class="fas fa-home me-2"></i> Inicio
    </a>
    <a href="index.php?action=horarios" class="text-white text-decoration-none d-block p-2 <?php echo (isset($_GET['action']) && $_GET['action'] == 'horarios') ? 'bg-secondary rounded' : ''; ?> mb-2">
        <i class="fas fa-clock me-2"></i> Gestionar Horarios
    </a>
    <a href="index.php?action=logout" class="text-danger text-decoration-none d-block p-2 mt-5">
        <i class="fas fa-sign-out-alt me-2"></i> Salir
    </a>
</div>
