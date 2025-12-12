-- Flyway Migration: V5__Make_Fabian_Admin
-- Asignar rol de administrador a fabian@gmail.com

-- Primero eliminar cualquier rol existente de este usuario
DELETE FROM model_has_roles 
WHERE model_id IN (SELECT id FROM users WHERE email = 'fabian@gmail.com');

-- Asignar rol de admin a fabian@gmail.com
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND u.email = 'fabian@gmail.com'
ON DUPLICATE KEY UPDATE role_id = r.id;

