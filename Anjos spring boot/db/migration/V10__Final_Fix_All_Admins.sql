-- Flyway Migration: V10__Final_Fix_All_Admins
-- Migración final y definitiva para asegurar que todos los admins funcionen

-- 1. Asegurar que el rol 'admin' existe
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) 
VALUES ('admin', 'web', NOW(), NOW());

-- 2. Crear/actualizar usuario fabian@gmail.com con todos los campos necesarios
INSERT INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Fabian Admin', 'fabian@gmail.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = 'Fabian Admin',
    password = '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    is_active = TRUE,
    updated_at = NOW();

-- 3. Crear/actualizar usuario admin@anjos.com con todos los campos necesarios
INSERT INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Administrador', 'admin@anjos.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = 'Administrador',
    password = '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    is_active = TRUE,
    updated_at = NOW();

-- 4. Eliminar TODOS los roles existentes para fabian@gmail.com
DELETE mhr FROM model_has_roles mhr
INNER JOIN users u ON mhr.model_id = u.id
WHERE u.email = 'fabian@gmail.com' 
AND mhr.model_type = 'com.empresa.miproyecto.model.User';

-- 5. Eliminar TODOS los roles existentes para admin@anjos.com
DELETE mhr FROM model_has_roles mhr
INNER JOIN users u ON mhr.model_id = u.id
WHERE u.email = 'admin@anjos.com' 
AND mhr.model_type = 'com.empresa.miproyecto.model.User';

-- 6. Asignar rol admin a fabian@gmail.com (usando INSERT IGNORE para evitar duplicados)
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r
CROSS JOIN users u
WHERE r.name = 'admin' 
AND u.email = 'fabian@gmail.com'
AND u.is_active = TRUE;

-- 7. Asignar rol admin a admin@anjos.com (usando INSERT IGNORE para evitar duplicados)
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r
CROSS JOIN users u
WHERE r.name = 'admin' 
AND u.email = 'admin@anjos.com'
AND u.is_active = TRUE;

-- Verificación final: Mostrar usuarios admin creados
-- SELECT u.id, u.name, u.email, u.is_active, r.name as role_name
-- FROM users u
-- LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = 'com.empresa.miproyecto.model.User'
-- LEFT JOIN roles r ON mhr.role_id = r.id
-- WHERE u.email IN ('fabian@gmail.com', 'admin@anjos.com');

