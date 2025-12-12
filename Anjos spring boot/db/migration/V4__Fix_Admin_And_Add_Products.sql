-- Flyway Migration: V4__Fix_Admin_And_Add_Products
-- Corregir admin y agregar productos con imágenes

-- Asegurar que el admin tenga el rol correcto (eliminar y recrear si es necesario)
DELETE FROM model_has_roles WHERE model_id IN (SELECT id FROM users WHERE email = 'admin@anjos.com');

-- Reasignar rol de admin
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'com.empresa.miproyecto.model.User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND u.email = 'admin@anjos.com'
ON DUPLICATE KEY UPDATE role_id = r.id;

-- Actualizar contraseña del admin (admin123) - Hash BCrypt correcto
UPDATE users 
SET password = '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    is_active = TRUE
WHERE email = 'admin@anjos.com';

-- Insertar productos con imágenes
INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ROLEX DATEJUST 41',
    'Reloj de acero inoxidable y oro, movimiento automático, caja de 41mm, resistencia al agua hasta 100m',
    28500000.00,
    3,
    'Acero Inoxidable y Oro',
    'Dorado',
    'Brillante',
    'Diamantes',
    'https://galileo.tsqsa.com/FTPImagenes/rolex-img/watches-models/m126334-0002-gallery1-landscape.webp',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Relojes' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ANILLO DIAMANTE SOLITARIO',
    'Anillo de compromiso con diamante natural de 1 quilate, montura en oro blanco 18k',
    5750000.00,
    5,
    'Oro Blanco 18k',
    'Blanco',
    'Brillante',
    'Diamante 1ct',
    'https://joyeriainter.com/wp-content/uploads/2021/12/anillo-compromiso-DAS201588050B-01.webp',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Anillos' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ARETES ORO 18K',
    'Aretes en oro amarillo de 18k con diseño clásico, perfectos para ocasiones especiales',
    1850000.00,
    8,
    'Oro 18k',
    'Dorado',
    'Brillante',
    'Sin piedras',
    'https://oroexpress.com.co/wp-content/uploads/2024/04/CDAR52-01.jpg',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Aretes' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'PULSERA ESLABONES PLATA',
    'Pulsera en plata esterlina 925 con eslabones entrelazados, diseño elegante y versátil',
    980000.00,
    12,
    'Plata 925',
    'Plateado',
    'Brillante',
    'Sin piedras',
    'https://www.luxurytime.com.co/wp-content/uploads/2022/06/PU402.jpg',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Pulseras' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'COLLAR PERLAS CULTIVADAS',
    'Collar elegante con perlas blancas cultivadas de 7-8mm, cierre de oro y diseño atemporal',
    3250000.00,
    6,
    'Oro 14k',
    'Blanco',
    'Mate',
    'Perlas',
    'https://i.ebayimg.com/thumbs/images/g/hwwAAeSwe6toNSwP/s-l1200.jpg',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Collares' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ANILLO COMPROMISO ORO BLANCO',
    'Anillo de compromiso en oro blanco 18k con diamante central de alta calidad, diseño clásico',
    6500000.00,
    4,
    'Oro Blanco 18k',
    'Blanco',
    'Brillante',
    'Diamante',
    'https://joyeriainter.com/wp-content/uploads/2021/12/anillo-compromiso-DAS201310825B-01.webp',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Anillos' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'RELOJ ELEGANCIA DAMA',
    'Reloj elegante para dama con correa de cuero genuino, caja dorada y esfera con cristal de zafiro',
    4200000.00,
    7,
    'Oro y Cuero',
    'Dorado',
    'Brillante',
    'Sin piedras',
    'https://exitocol.vtexassets.com/arquivos/ids/22537197/reloj-dama-elegante-glamour-star-s401j002-estuche-modelo-2.jpg?v=638488232829870000',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Relojes' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'SET ANILLOS ORO ROSADO',
    'Juego de 3 anillos a juego en oro rosado 18k, diseño moderno y elegante',
    2150000.00,
    10,
    'Oro Rosado 18k',
    'Rosado',
    'Brillante',
    'Sin piedras',
    'https://cdn-media.glamira.com/media/product/newgeneration/view/1/sku/GD25-SET/diamond/lab-grown-diamond_AAA/alloycolour/red.jpg',
    TRUE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Anillos' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'COLLAR CADENA ORO AMARILLO',
    'Collar con cadena de oro amarillo 18k, diseño clásico y versátil, longitud ajustable',
    2800000.00,
    9,
    'Oro 18k',
    'Dorado',
    'Brillante',
    'Sin piedras',
    'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    FALSE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Collares' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'PULSERA TENNIS ORO',
    'Pulsera tennis en oro amarillo 18k con diamantes, diseño elegante y sofisticado',
    4500000.00,
    5,
    'Oro 18k',
    'Dorado',
    'Brillante',
    'Diamantes',
    'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    FALSE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Pulseras' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ARETES PERLAS Y DIAMANTES',
    'Aretes elegantes con perlas cultivadas y diamantes, montura en oro blanco 18k',
    3200000.00,
    6,
    'Oro Blanco 18k',
    'Blanco',
    'Brillante',
    'Perlas y Diamantes',
    'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    FALSE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Aretes' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'DIJE CORAZÓN ORO',
    'Dije en forma de corazón en oro amarillo 18k, perfecto para personalizar collares',
    850000.00,
    15,
    'Oro 18k',
    'Dorado',
    'Brillante',
    'Sin piedras',
    'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    FALSE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Dijes' LIMIT 1;

INSERT IGNORE INTO products (name, description, price, stock, material, color, finish, stones, image, is_featured, is_active, category_id, created_at, updated_at) 
SELECT 
    'ANILLO ETERNIDAD DIAMANTES',
    'Anillo de eternidad con diamantes alrededor, oro blanco 18k, símbolo de amor eterno',
    3800000.00,
    7,
    'Oro Blanco 18k',
    'Blanco',
    'Brillante',
    'Diamantes',
    'https://joyeriainter.com/wp-content/uploads/2021/12/anillo-compromiso-DAS201588050B-01.webp',
    FALSE,
    TRUE,
    c.id,
    NOW(),
    NOW()
FROM categories c WHERE c.name = 'Anillos' LIMIT 1;

