package com.empresa.miproyecto.service;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Category;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.repository.CategoryRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.service.impl.ProductServiceImpl;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageImpl;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;

import java.math.BigDecimal;
import java.util.Arrays;
import java.util.List;
import java.util.Optional;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class ProductServiceTest {
    
    @Mock
    private ProductRepository productRepository;
    
    @Mock
    private CategoryRepository categoryRepository;
    
    @InjectMocks
    private ProductServiceImpl productService;
    
    private Product product;
    private Category category;
    
    @BeforeEach
    void setUp() {
        category = new Category();
        category.setId(1L);
        category.setName("Anillos");
        category.setIsActive(true);
        
        product = new Product();
        product.setId(1L);
        product.setName("Anillo de Oro");
        product.setDescription("Anillo de oro 18k");
        product.setPrice(new BigDecimal("500.00"));
        product.setStock(10);
        product.setCategory(category);
        product.setIsActive(true);
    }
    
    @Test
    void testFindById_Success() {
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        
        Optional<Product> result = productService.findById(1L);
        
        assertTrue(result.isPresent());
        assertEquals("Anillo de Oro", result.get().getName());
        verify(productRepository, times(1)).findById(1L);
    }
    
    @Test
    void testFindById_NotFound() {
        when(productRepository.findById(999L)).thenReturn(Optional.empty());
        
        Optional<Product> result = productService.findById(999L);
        
        assertFalse(result.isPresent());
    }
    
    @Test
    void testFindFeaturedProducts() {
        when(productRepository.findByIsFeaturedTrueAndIsActiveTrue())
            .thenReturn(Arrays.asList(product));
        
        List<Product> result = productService.findFeaturedProducts();
        
        assertEquals(1, result.size());
        assertEquals("Anillo de Oro", result.get(0).getName());
    }
    
    @Test
    void testSave() {
        when(categoryRepository.findById(1L)).thenReturn(Optional.of(category));
        when(productRepository.save(any(Product.class))).thenReturn(product);
        
        Product saved = productService.save(product);
        
        assertNotNull(saved);
        assertEquals("Anillo de Oro", saved.getName());
        verify(productRepository, times(1)).save(product);
    }
    
    @Test
    void testUpdate_Success() {
        Product updatedProduct = new Product();
        updatedProduct.setName("Anillo de Plata");
        updatedProduct.setDescription("Anillo de plata");
        updatedProduct.setPrice(new BigDecimal("300.00"));
        updatedProduct.setStock(5);
        updatedProduct.setCategory(category);
        
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        when(categoryRepository.findById(1L)).thenReturn(Optional.of(category));
        when(productRepository.save(any(Product.class))).thenReturn(product);
        
        Product result = productService.update(1L, updatedProduct);
        
        assertNotNull(result);
        verify(productRepository, times(1)).findById(1L);
        verify(categoryRepository, times(1)).findById(1L);
        verify(productRepository, times(1)).save(any(Product.class));
    }
    
    @Test
    void testUpdate_NotFound() {
        Product updatedProduct = new Product();
        updatedProduct.setName("Anillo de Plata");
        
        when(productRepository.findById(999L)).thenReturn(Optional.empty());
        
        assertThrows(ResourceNotFoundException.class, () -> {
            productService.update(999L, updatedProduct);
        });
    }
    
    @Test
    void testDelete_Success() {
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        doNothing().when(productRepository).delete(product);
        
        productService.delete(1L);
        
        verify(productRepository, times(1)).findById(1L);
        verify(productRepository, times(1)).delete(product);
    }
    
    @Test
    void testDelete_NotFound() {
        when(productRepository.findById(999L)).thenReturn(Optional.empty());
        
        assertThrows(ResourceNotFoundException.class, () -> {
            productService.delete(999L);
        });
    }
    
    @Test
    void testFindAll_Paginated() {
        Pageable pageable = PageRequest.of(0, 10);
        Page<Product> page = new PageImpl<>(Arrays.asList(product), pageable, 1);
        
        when(productRepository.findByIsActiveTrue(pageable)).thenReturn(page);
        
        Page<Product> result = productService.findAll(pageable);
        
        assertEquals(1, result.getTotalElements());
        assertEquals("Anillo de Oro", result.getContent().get(0).getName());
    }
}
