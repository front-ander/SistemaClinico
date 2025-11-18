-- ============================================
-- Script para insertar Usuarios Administradores y Doctores
-- Sistema Clínica San Gabriel
-- ============================================

USE san_gabriel_db;

-- ============================================
-- 1. INSERTAR USUARIOS ADMINISTRADORES
-- ============================================
-- Nota: Las contraseñas están hasheadas con password_hash() de PHP
-- Contraseña por defecto para todos: "admin123" (puedes cambiarla después)

INSERT INTO usuarios (nombre_completo, email, password, rol, telefono) VALUES
('Administrador Principal', 'admin@sangabriel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '999-888-7777'),
('María González', 'maria.admin@sangabriel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '999-888-7778');

-- ============================================
-- 2. INSERTAR USUARIOS DOCTORES/MÉDICOS
-- ============================================
-- Primero insertamos los médicos en la tabla usuarios
-- Contraseña por defecto: "doctor123" (puedes cambiarla después)

INSERT INTO usuarios (nombre_completo, email, password, rol, telefono) VALUES
-- Médicos de Medicina General
('Dr. Carlos Ramírez', 'carlos.ramirez@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0001'),
('Dra. Ana Martínez', 'ana.martinez@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0002'),

-- Médicos de Pediatría
('Dr. Luis Fernández', 'luis.fernandez@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0003'),
('Dra. Sofía Herrera', 'sofia.herrera@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0004'),

-- Médicos de Odontología
('Dr. Roberto Sánchez', 'roberto.sanchez@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0005'),
('Dra. Carmen López', 'carmen.lopez@sangabriel.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'medico', '999-111-0006');

-- ============================================
-- 3. ASIGNAR ESPECIALIDADES A LOS MÉDICOS
-- ============================================
-- Primero verificamos los IDs de las especialidades
-- (Asumiendo que ya tienes las especialidades insertadas: Medicina General=1, Pediatría=2, Odontología=3)

-- Obtener los IDs de los médicos recién insertados y asignarles especialidades
-- Medicina General (especialidad_id = 1)
INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 1 FROM usuarios WHERE email = 'carlos.ramirez@sangabriel.com' AND rol = 'medico';

INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 1 FROM usuarios WHERE email = 'ana.martinez@sangabriel.com' AND rol = 'medico';

-- Pediatría (especialidad_id = 2)
INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 2 FROM usuarios WHERE email = 'luis.fernandez@sangabriel.com' AND rol = 'medico';

INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 2 FROM usuarios WHERE email = 'sofia.herrera@sangabriel.com' AND rol = 'medico';

-- Odontología (especialidad_id = 3)
INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 3 FROM usuarios WHERE email = 'roberto.sanchez@sangabriel.com' AND rol = 'medico';

INSERT INTO medicos_info (usuario_id, especialidad_id) 
SELECT id, 3 FROM usuarios WHERE email = 'carmen.lopez@sangabriel.com' AND rol = 'medico';

-- ============================================
-- VERIFICACIÓN
-- ============================================
-- Ejecuta estas consultas para verificar que todo se insertó correctamente:

-- Ver todos los administradores
SELECT id, nombre_completo, email, rol FROM usuarios WHERE rol = 'admin';

-- Ver todos los médicos con sus especialidades
SELECT 
    u.id,
    u.nombre_completo,
    u.email,
    u.telefono,
    e.nombre as especialidad
FROM usuarios u
JOIN medicos_info m ON u.id = m.usuario_id
JOIN especialidades e ON m.especialidad_id = e.id
WHERE u.rol = 'medico'
ORDER BY e.nombre, u.nombre_completo;

-- ============================================
-- NOTAS IMPORTANTES:
-- ============================================
-- 1. Las contraseñas hasheadas son de ejemplo:
--    - Admin: "admin123" 
--    - Doctores: "doctor123"
-- 
-- 2. Para generar nuevas contraseñas hasheadas, puedes usar este código PHP:
--    <?php echo password_hash('tu_contraseña', PASSWORD_BCRYPT); ?>
--
-- 3. Si las especialidades tienen IDs diferentes, ajusta los números en la sección 3
--
-- 4. Puedes agregar más médicos siguiendo el mismo patrón:
--    a) Insertar en usuarios con rol='medico'
--    b) Insertar en medicos_info con el usuario_id y especialidad_id correspondiente

