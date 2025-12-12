package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.repository.CategoryRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class ProductServiceImpl implements ProductService {
    
    @Autowired
    private ProductRepository productRepository;
    
    @Autowired
    private CategoryRepository categoryRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Product> findAll() {
        try {
            // Usar consulta con JOIN FETCH para cargar categorías
            List<Product> products = productRepository.findAllWithCategory();
            return products != null ? products : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de productos: " + e.getMessage());
            e.printStackTrace();
            // Fallback a findAll normal si falla la consulta con JOIN FETCH
            try {
                List<Product> products = productRepository.findAll();
                if (products != null) {
                    products.forEach(product -> {
                        if (product.getCategory() != null) {
                            product.getCategory().getName();
                        }
                    });
                }
                return products != null ? products : java.util.Collections.emptyList();
            } catch (Exception ex) {
                System.err.println("Error en fallback findAll: " + ex.getMessage());
                return java.util.Collections.emptyList();
            }
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Page<Product> findAll(Pageable pageable) {
        try {
            Page<Product> productsPage = productRepository.findByIsActiveTrue(pageable);
            // Forzar carga de categorías
            productsPage.getContent().forEach(product -> {
                if (product.getCategory() != null) {
                    product.getCategory().getName();
                }
            });
            return productsPage;
        } catch (Exception e) {
            System.err.println("Error en findAll paginado de productos: " + e.getMessage());
            e.printStackTrace();
            return Page.empty(pageable);
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Product> findById(Long id) {
        try {
            Optional<Product> product = productRepository.findById(id);
            if (product.isPresent()) {
                Product p = product.get();
                // Forzar carga de categoría
                if (p.getCategory() != null) {
                    p.getCategory().getName();
                }
            }
            return product;
        } catch (Exception e) {
            System.err.println("Error en findById de productos: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Product> findFeaturedProducts() {
        try {
            return productRepository.findByIsFeaturedTrueAndIsActiveTrue();
        } catch (Exception e) {
            System.err.println("Error en findFeaturedProducts: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Product> findByCategoryId(Long categoryId) {
        try {
            if (categoryId == null) {
                return java.util.Collections.emptyList();
            }
            List<Product> products = productRepository.findByCategoryIdAndIsActiveTrue(categoryId);
            if (products != null) {
                // Forzar carga de categorías
                products.forEach(product -> {
                    if (product.getCategory() != null) {
                        product.getCategory().getName();
                    }
                });
            }
            return products != null ? products : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findByCategoryId: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Product> findRelatedProducts(Long categoryId, Long productId, int limit) {
        try {
            if (categoryId == null || productId == null) {
                return java.util.Collections.emptyList();
            }
            Pageable pageable = PageRequest.of(0, limit);
            List<Product> products = productRepository.findByCategoryIdAndIdNotAndIsActiveTrue(categoryId, productId, pageable);
            if (products != null) {
                // Forzar carga de categorías
                products.forEach(product -> {
                    if (product.getCategory() != null) {
                        product.getCategory().getName();
                    }
                });
            }
            return products != null ? products : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findRelatedProducts: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Page<Product> searchProducts(Long categoryId, String material, String color, 
                                        String finish, String stones, BigDecimal minPrice, 
                                        BigDecimal maxPrice, String search, Pageable pageable) {
        try {
            Page<Product> productsPage = productRepository.searchProducts(categoryId, material, color, finish, 
                                                   stones, minPrice, maxPrice, search, pageable);
            // Forzar la carga de categorías para evitar LazyInitializationException
            productsPage.getContent().forEach(product -> {
                if (product.getCategory() != null) {
                    product.getCategory().getName(); // Forzar carga
                }
            });
            return productsPage;
        } catch (Exception e) {
            System.err.println("Error en searchProducts: " + e.getMessage());
            e.printStackTrace();
            // Retornar página vacía en caso de error
            return Page.empty(pageable);
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<String> getDistinctMaterials() {
        try {
            return productRepository.findDistinctMaterials();
        } catch (Exception e) {
            System.err.println("Error en getDistinctMaterials: " + e.getMessage());
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<String> getDistinctColors() {
        try {
            return productRepository.findDistinctColors();
        } catch (Exception e) {
            System.err.println("Error en getDistinctColors: " + e.getMessage());
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<String> getDistinctFinishes() {
        try {
            return productRepository.findDistinctFinishes();
        } catch (Exception e) {
            System.err.println("Error en getDistinctFinishes: " + e.getMessage());
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<String> getDistinctStones() {
        try {
            return productRepository.findDistinctStones();
        } catch (Exception e) {
            System.err.println("Error en getDistinctStones: " + e.getMessage());
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    public Product save(Product product) {
        // Validar que la categoría existe
        if (product.getCategory() != null && product.getCategory().getId() != null) {
            categoryRepository.findById(product.getCategory().getId())
                .orElseThrow(() -> new ResourceNotFoundException("Categoría no encontrada con id: " + product.getCategory().getId()));
        }
        return productRepository.save(product);
    }
    
    @Override
    public Product update(Long id, Product product) {
        Product existingProduct = productRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Producto no encontrado con id: " + id));
        
        // Validar que la categoría existe si se está actualizando
        if (product.getCategory() != null && product.getCategory().getId() != null) {
            categoryRepository.findById(product.getCategory().getId())
                .orElseThrow(() -> new ResourceNotFoundException("Categoría no encontrada con id: " + product.getCategory().getId()));
        }
        
        existingProduct.setName(product.getName());
        existingProduct.setDescription(product.getDescription());
        existingProduct.setPrice(product.getPrice());
        existingProduct.setStock(product.getStock());
        existingProduct.setMaterial(product.getMaterial());
        existingProduct.setColor(product.getColor());
        existingProduct.setFinish(product.getFinish());
        existingProduct.setStones(product.getStones());
        existingProduct.setCategory(product.getCategory());
        existingProduct.setIsFeatured(product.getIsFeatured());
        existingProduct.setIsActive(product.getIsActive());
        
        if (product.getImage() != null && !product.getImage().isEmpty()) {
            existingProduct.setImage(product.getImage());
        }
        
        return productRepository.save(existingProduct);
    }
    
    @Override
    public void delete(Long id) {
        Product product = productRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Producto no encontrado con id: " + id));
        productRepository.delete(product);
    }
}
