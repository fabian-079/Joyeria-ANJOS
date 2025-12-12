package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Product;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

public interface ProductService {
    List<Product> findAll();
    Page<Product> findAll(Pageable pageable);
    Optional<Product> findById(Long id);
    List<Product> findFeaturedProducts();
    List<Product> findByCategoryId(Long categoryId);
    List<Product> findRelatedProducts(Long categoryId, Long productId, int limit);
    Page<Product> searchProducts(Long categoryId, String material, String color, 
                                  String finish, String stones, BigDecimal minPrice, 
                                  BigDecimal maxPrice, String search, Pageable pageable);
    List<String> getDistinctMaterials();
    List<String> getDistinctColors();
    List<String> getDistinctFinishes();
    List<String> getDistinctStones();
    Product save(Product product);
    Product update(Long id, Product product);
    void delete(Long id);
}
