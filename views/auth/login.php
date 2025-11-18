<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Policlínico San Gabriel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e9ecef; display: flex; align-items: center; height: 100vh; }
        .card-login { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .brand-logo { width: 80px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-login p-4">
                    <div class="text-center">
                        <h3 class="mb-4 text-primary">San Gabriel</h3>
                        <h5 class="text-muted mb-4">Acceso al Sistema</h5>
                    </div>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success text-center">Registro exitoso. Inicie sesión.</div>
                    <?php endif; ?>

                    <form action="index.php?action=login" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required placeholder="ejemplo@correo.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required placeholder="******">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <small>¿No tienes cuenta? <a href="index.php?action=register">Regístrate aquí</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>