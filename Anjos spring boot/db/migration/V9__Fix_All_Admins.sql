-- Flyway Migration: V9__Fix_All_Admins
-- Asegurar que todos los usuarios admin funcionen correctamente

-- 1. Asegurar que el rol 'admin' existe
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) 
VALUES ('admin', 'web', NOW(), NOW());

-- 2. Crear/actualizar usuario fabian@gmail.com
INSERT INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Fabian Admin', 'fabian@gmail.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = 'Fabian Admin',
    is_active = TRUE,
    updated_at = NOW();

-- 3. Crear/actualizar usuario admin@anjos.com
INSERT INTO users (name, email, password, is_active, created_at, updated_at) 
VALUES ('Administrador', 'admin@anjos.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = 'Administrador',
    is_active = TRUE,
    updated_at = NOW();

-- 4. Eliminar roles existentes para fabian@gmail.com
DELETE mhr FROM model_has_roles mhr
INNER JOIN users u ON mhr.model_id = u.id
WHERE u.email = 'fabian@gmail.com' 
AND mhr.model_type = 'com.empresa.miproyecto.model.User';

-- 5. Eliminar roles existentes para admin@anjos.com
DELETE mhr FROM model_has_roles mhr
INNER JOIN users u ON mhr.model_id = u.id
WHERE u.email = 'admin@anjos.com' 
AND mhr.model_type = 'com.empresa.miproyecto.model.User';

-- 6. Asignar rol admin a fabian@gmail.com
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r
CROSS JOIN users u
WHERE r.name = 'admin' AND u.email = 'fabian@gmail.com'
AND NOT EXISTS (
    SELECT 1 FROM model_has_roles mhr 
    WHERE mhr.role_id = r.id 
    AND mhr.model_id = u.id 
    AND mhr.model_type = 'com.empresa.miproyecto.model.User'
);

-- 7. Asignar rol admin a admin@anjos.com
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r
CROSS JOIN users u
WHERE r.name = 'admin' AND u.email = 'admin@anjos.com'
AND NOT EXISTS (
    SELECT 1 FROM model_has_roles mhr 
    WHERE mhr.role_id = r.id 
    AND mhr.model_id = u.id 
    AND mhr.model_type = 'com.empresa.miproyecto.model.User'
);

