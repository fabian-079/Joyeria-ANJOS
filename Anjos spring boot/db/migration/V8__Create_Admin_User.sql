-- Flyway Migration: V8__Create_Admin_User
-- Crear un nuevo usuario administrador funcional

-- Crear usuario admin (email: admin@anjos.com, password: admin123)
INSERT IGNORE INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Administrador', 'admin@anjos.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW());

-- Obtener el ID del rol 'admin'
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'admin' LIMIT 1);

-- Obtener el ID del usuario 'admin@anjos.com'
SET @admin_user_id = (SELECT id FROM users WHERE email = 'admin@anjos.com' LIMIT 1);

-- Eliminar cualquier rol existente para admin@anjos.com antes de asignar el rol de admin
DELETE FROM model_has_roles
WHERE model_id = @admin_user_id AND model_type = 'com.empresa.miproyecto.model.User';

-- Asignar el rol de admin a admin@anjos.com
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT @admin_role_id, 'com.empresa.miproyecto.model.User', @admin_user_id
WHERE @admin_role_id IS NOT NULL AND @admin_user_id IS NOT NULL
ON DUPLICATE KEY UPDATE role_id = @admin_role_id;

