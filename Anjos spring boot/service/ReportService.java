package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.List;
import java.util.Map;

public interface ReportService {
    // Reportes de ventas
    List<Order> getSalesReport(LocalDateTime startDate, LocalDateTime endDate, 
                               String status, Long userId);
    
    Map<String, Object> getSalesSummary(LocalDateTime startDate, LocalDateTime endDate);
    
    // Reportes de productos
    List<Product> getProductsReport(Long categoryId, Boolean isActive, 
                                    Boolean isFeatured, BigDecimal minPrice, 
                                    BigDecimal maxPrice, Integer minStock);
    
    Map<String, Object> getProductsSummary();
    
    // Reportes de usuarios
    List<User> getUsersReport(Boolean isActive, Long roleId, 
                             LocalDateTime startDate, LocalDateTime endDate);
    
    Map<String, Object> getUsersSummary();
    
    // Reporte de productos más vendidos
    List<Map<String, Object>> getTopSellingProducts(LocalDateTime startDate, 
                                                    LocalDateTime endDate, 
                                                    Integer limit);
    
    // Reporte de clientes más activos
    List<Map<String, Object>> getTopActiveUsers(LocalDateTime startDate, 
                                                LocalDateTime endDate, 
                                                Integer limit);
}

