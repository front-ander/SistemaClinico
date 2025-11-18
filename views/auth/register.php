<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Policlínico San Gabriel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e9ecef; height: 100vh; display: flex; align-items: center; }
        .card-register { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-register p-4">
                    <h3 class="text-center text-primary mb-3">Crear Cuenta</h3>
                    <p class="text-center text-muted mb-4">Únete al sistema de salud digital</p>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="index.php?action=register" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-lg">Registrarse</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <small>¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>