<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Policlínico San Gabriel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            /* Mismo fondo médico con capa oscura */
            background: linear-gradient(rgba(0, 40, 70, 0.7), rgba(0, 40, 70, 0.7)), 
                        url('https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .card-register { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); 
            background: rgba(255, 255, 255, 0.95); /* Blanco con transparencia */
        }
        .brand-logo { 
            width: 100px; 
            height: 100px; 
            object-fit: contain; 
            margin-bottom: 15px;
            border-radius: 50%; 
            background: white;
            padding: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card card-register p-4">
                    <div class="text-center">
                        <img src="../public/img/poli.jpg" class="brand-logo" alt="Logo Clínica">
                        
                        <h3 class="mb-2 text-primary fw-bold">Crear Cuenta</h3>
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">Únete al sistema de salud digital</p>
                    </div>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger text-center py-2" style="font-size: 0.9rem;"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="index.php?action=register" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-lg" required placeholder="Ej. Juan Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="ejemplo@correo.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="******">
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">Registrarse</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <small class="text-muted">¿Ya tienes cuenta? <a href="index.php?action=login" class="text-primary fw-bold text-decoration-none">Inicia sesión</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>