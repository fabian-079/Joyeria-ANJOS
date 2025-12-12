package com.empresa.miproyecto.repository;

import com.empresa.miproyecto.model.Category;
import com.empresa.miproyecto.model.Product;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;

import java.math.BigDecimal;

import static org.junit.jupiter.api.Assertions.*;
import org.springframework.test.context.ActiveProfiles;

@DataJpaTest
@ActiveProfiles("test")
class ProductRepositoryTest {
    
    @Autowired
    private ProductRepository productRepository;
    
    @Autowired
    private CategoryRepository categoryRepository;
    
    @Test
    void testSaveAndFind() {
        Category category = new Category();
        category.setName("Anillos");
        category.setIsActive(true);
        category = categoryRepository.save(category);
        
        Product product = new Product();
        product.setName("Anillo de Oro");
        product.setDescription("Anillo de oro 18k");
        product.setPrice(new BigDecimal("500.00"));
        product.setStock(10);
        product.setCategory(category);
        product.setIsActive(true);
        
        Product saved = productRepository.save(product);
        Product found = productRepository.findById(saved.getId()).orElse(null);
        
        assertNotNull(found);
        assertEquals("Anillo de Oro", found.getName());
        assertEquals(new BigDecimal("500.00"), found.getPrice());
    }
    
    @Test
    void testFindByIsActiveTrue() {
        Category category = new Category();
        category.setName("Anillos");
        category.setIsActive(true);
        category = categoryRepository.save(category);
        
        Product product1 = new Product();
        product1.setName("Producto Activo");
        product1.setDescription("Descripción");
        product1.setPrice(new BigDecimal("100.00"));
        product1.setStock(5);
        product1.setCategory(category);
        product1.setIsActive(true);
        productRepository.save(product1);
        
        Product product2 = new Product();
        product2.setName("Producto Inactivo");
        product2.setDescription("Descripción");
        product2.setPrice(new BigDecimal("200.00"));
        product2.setStock(3);
        product2.setCategory(category);
        product2.setIsActive(false);
        productRepository.save(product2);
        
        Pageable pageable = PageRequest.of(0, 10);
        Page<Product> result = productRepository.findByIsActiveTrue(pageable);
        
        assertEquals(1, result.getTotalElements());
        assertEquals("Producto Activo", result.getContent().get(0).getName());
    }
    
    @Test
    void testFindFeaturedProducts() {
        Category category = new Category();
        category.setName("Anillos");
        category.setIsActive(true);
        category = categoryRepository.save(category);
        
        Product featured = new Product();
        featured.setName("Producto Destacado");
        featured.setDescription("Descripción");
        featured.setPrice(new BigDecimal("300.00"));
        featured.setStock(5);
        featured.setCategory(category);
        featured.setIsFeatured(true);
        featured.setIsActive(true);
        productRepository.save(featured);
        
        Product normal = new Product();
        normal.setName("Producto Normal");
        normal.setDescription("Descripción");
        normal.setPrice(new BigDecimal("200.00"));
        normal.setStock(3);
        normal.setCategory(category);
        normal.setIsFeatured(false);
        normal.setIsActive(true);
        productRepository.save(normal);
        
        var result = productRepository.findByIsFeaturedTrueAndIsActiveTrue();
        
        assertEquals(1, result.size());
        assertEquals("Producto Destacado", result.get(0).getName());
    }
}
