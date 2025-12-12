-- Flyway Migration: V2__Insert_Initial_Data
-- Verificar que las tablas existen antes de insertar datos

-- Insert Roles (solo si no existen)
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) VALUES
('admin', 'web', NOW(), NOW()),
('cliente', 'web', NOW(), NOW());

-- Insert Categories (solo si no existen)
INSERT IGNORE INTO categories (name, description, is_active, created_at, updated_at) VALUES
('Anillos', 'Anillos de diferentes materiales y diseños', TRUE, NOW(), NOW()),
('Collares', 'Collares y cadenas elegantes', TRUE, NOW(), NOW()),
('Pulseras', 'Pulseras y brazaletes', TRUE, NOW(), NOW()),
('Aretes', 'Aretes y pendientes', TRUE, NOW(), NOW()),
('Relojes', 'Relojes de lujo', TRUE, NOW(), NOW()),
('Dijes', 'Dijes y colgantes', TRUE, NOW(), NOW());

-- Insert Admin User (password: admin123)
INSERT IGNORE INTO users (name, email, password, is_active, created_at, updated_at) VALUES
('Admin Test', 'admin@anjos.com', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', TRUE, NOW(), NOW());

-- Insert Client User (password: cliente123)
INSERT IGNORE INTO users (name, email, password, is_active, created_at, updated_at) VALUES
('Cliente Anjos', 'cliente@anjos.com', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE, NOW(), NOW());

-- Assign Roles to Users
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND u.email = 'admin@anjos.com';

INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r, users u
WHERE r.name = 'cliente' AND u.email = 'cliente@anjos.com';
