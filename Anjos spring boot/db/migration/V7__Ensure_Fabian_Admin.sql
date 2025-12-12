-- Flyway Migration: V7__Ensure_Fabian_Admin
-- Asegurar que fabian@gmail.com tenga rol de administrador

-- Primero, verificar si el usuario existe, si no, crearlo
INSERT IGNORE INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Fabian', 'fabian@gmail.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW());

-- Eliminar cualquier rol existente de este usuario
DELETE FROM model_has_roles 
WHERE model_id IN (SELECT id FROM users WHERE email = 'fabian@gmail.com');

-- Asignar rol de admin a fabian@gmail.com
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND u.email = 'fabian@gmail.com'
ON DUPLICATE KEY UPDATE role_id = r.id;

