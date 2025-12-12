-- Flyway Migration: V6__Update_Product_Images
-- Actualizar imágenes de productos con URLs funcionales

-- Imágenes usando placeholders de Unsplash (servicio gratuito de imágenes)
UPDATE products SET image = 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Anillos') AND (image IS NULL OR image = '');

UPDATE products SET image = 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Collares') AND (image IS NULL OR image = '');

UPDATE products SET image = 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Pulseras') AND (image IS NULL OR image = '');

UPDATE products SET image = 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Aretes') AND (image IS NULL OR image = '');

UPDATE products SET image = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Relojes') AND (image IS NULL OR image = '');

UPDATE products SET image = 'https://images.unsplash.com/photo-1603561596111-7c8cd67663aa?w=800&h=800&fit=crop' 
WHERE category_id IN (SELECT id FROM categories WHERE name = 'Dijes') AND (image IS NULL OR image = '');

-- Si algún producto aún no tiene imagen, asignar una genérica
UPDATE products SET image = 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop' 
WHERE image IS NULL OR image = '';

