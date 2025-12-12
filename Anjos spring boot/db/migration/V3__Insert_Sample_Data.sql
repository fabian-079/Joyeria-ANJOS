-- Flyway Migration: V3__Insert_Sample_Data
-- Insert Sample Products (opcional - solo si quieres datos de ejemplo)
-- Estos productos son ejemplos, puedes comentarlos si no los necesitas

-- Ejemplo de productos de joyería
INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'Anillo de Compromiso Esmeralda',
    'Hermoso anillo de compromiso con esmeralda natural y diamantes',
    2500.00,
    5,
    'Oro 18k',
    'Verde',
    'Brillante',
    'Esmeralda, Diamantes',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Anillos'
LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'Collar de Perlas',
    'Elegante collar de perlas cultivadas con cierre de oro',
    850.00,
    3,
    'Oro 14k',
    'Blanco',
    'Mate',
    'Perlas',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Collares'
LIMIT 1;
