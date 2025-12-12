package com.empresa.miproyecto.repository;

import com.empresa.miproyecto.model.Product;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.math.BigDecimal;
import java.util.List;

@Repository
public interface ProductRepository extends JpaRepository<Product, Long>, org.springframework.data.jpa.repository.JpaSpecificationExecutor<Product> {
    Page<Product> findByIsActiveTrue(Pageable pageable);
    
    @Query("SELECT p FROM Product p JOIN FETCH p.category WHERE p.isFeatured = true AND p.isActive = true")
    List<Product> findByIsFeaturedTrueAndIsActiveTrue();
    
    @Query("SELECT DISTINCT p FROM Product p JOIN FETCH p.category")
    List<Product> findAllWithCategory();
    
    List<Product> findByCategoryIdAndIsActiveTrue(Long categoryId);
    List<Product> findByCategoryIdAndIdNotAndIsActiveTrue(Long categoryId, Long id, Pageable pageable);
    
    @Query("SELECT DISTINCT p.material FROM Product p WHERE p.material IS NOT NULL AND p.isActive = true")
    List<String> findDistinctMaterials();
    
    @Query("SELECT DISTINCT p.color FROM Product p WHERE p.color IS NOT NULL AND p.isActive = true")
    List<String> findDistinctColors();
    
    @Query("SELECT DISTINCT p.finish FROM Product p WHERE p.finish IS NOT NULL AND p.isActive = true")
    List<String> findDistinctFinishes();
    
    @Query("SELECT DISTINCT p.stones FROM Product p WHERE p.stones IS NOT NULL AND p.isActive = true")
    List<String> findDistinctStones();
    
    @Query(value = "SELECT p FROM Product p WHERE p.isActive = true " +
           "AND (:categoryId IS NULL OR p.category.id = :categoryId) " +
           "AND (:material IS NULL OR p.material LIKE CONCAT('%', :material, '%')) " +
           "AND (:color IS NULL OR p.color LIKE CONCAT('%', :color, '%')) " +
           "AND (:finish IS NULL OR p.finish LIKE CONCAT('%', :finish, '%')) " +
           "AND (:stones IS NULL OR p.stones LIKE CONCAT('%', :stones, '%')) " +
           "AND (:minPrice IS NULL OR p.price >= :minPrice) " +
           "AND (:maxPrice IS NULL OR p.price <= :maxPrice) " +
           "AND (:search IS NULL OR p.name LIKE CONCAT('%', :search, '%') OR p.description LIKE CONCAT('%', :search, '%'))",
           countQuery = "SELECT COUNT(p) FROM Product p WHERE p.isActive = true " +
           "AND (:categoryId IS NULL OR p.category.id = :categoryId) " +
           "AND (:material IS NULL OR p.material LIKE CONCAT('%', :material, '%')) " +
           "AND (:color IS NULL OR p.color LIKE CONCAT('%', :color, '%')) " +
           "AND (:finish IS NULL OR p.finish LIKE CONCAT('%', :finish, '%')) " +
           "AND (:stones IS NULL OR p.stones LIKE CONCAT('%', :stones, '%')) " +
           "AND (:minPrice IS NULL OR p.price >= :minPrice) " +
           "AND (:maxPrice IS NULL OR p.price <= :maxPrice) " +
           "AND (:search IS NULL OR p.name LIKE CONCAT('%', :search, '%') OR p.description LIKE CONCAT('%', :search, '%'))")
    Page<Product> searchProducts(
        @Param("categoryId") Long categoryId,
        @Param("material") String material,
        @Param("color") String color,
        @Param("finish") String finish,
        @Param("stones") String stones,
        @Param("minPrice") BigDecimal minPrice,
        @Param("maxPrice") BigDecimal maxPrice,
        @Param("search") String search,
        Pageable pageable
    );
}
