package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.OrderRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.repository.UserRepository;
import com.empresa.miproyecto.service.ReportService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.jpa.domain.Specification;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import jakarta.persistence.criteria.Predicate;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.*;
import java.util.stream.Collectors;

@Service
@Transactional
public class ReportServiceImpl implements ReportService {
    
    @Autowired
    private OrderRepository orderRepository;
    
    @Autowired
    private ProductRepository productRepository;
    
    @Autowired
    private UserRepository userRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Order> getSalesReport(LocalDateTime startDate, LocalDateTime endDate, 
                                      String status, Long userId) {
        try {
            Specification<Order> spec = (root, query, cb) -> {
                List<Predicate> predicates = new ArrayList<>();
                
                if (startDate != null) {
                    predicates.add(cb.greaterThanOrEqualTo(root.get("createdAt"), startDate));
                }
                if (endDate != null) {
                    predicates.add(cb.lessThanOrEqualTo(root.get("createdAt"), endDate));
                }
                if (status != null && !status.isEmpty()) {
                    try {
                        predicates.add(cb.equal(root.get("status"), Order.OrderStatus.valueOf(status)));
                    } catch (IllegalArgumentException e) {
                        // Status inválido, ignorar
                    }
                }
                if (userId != null) {
                    predicates.add(cb.equal(root.get("user").get("id"), userId));
                }
                
                return cb.and(predicates.toArray(new Predicate[0]));
            };
            
            List<Order> orders = orderRepository.findAll(spec);
            return orders != null ? orders : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en getSalesReport: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Map<String, Object> getSalesSummary(LocalDateTime startDate, LocalDateTime endDate) {
        try {
            List<Order> orders = getSalesReport(startDate, endDate, null, null);
            if (orders == null) {
                orders = java.util.Collections.emptyList();
            }
            
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalOrders", orders.size());
            
            BigDecimal totalRevenue = orders.stream()
                .filter(o -> o != null && o.getStatus() != null && o.getStatus() == Order.OrderStatus.DELIVERED)
                .filter(o -> o.getTotal() != null)
                .map(Order::getTotal)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
            
            summary.put("totalRevenue", totalRevenue);
            
            Map<String, Long> statusCount = orders.stream()
                .filter(o -> o != null && o.getStatus() != null)
                .collect(Collectors.groupingBy(
                    o -> o.getStatus().toString(),
                    Collectors.counting()
                ));
            
            summary.put("statusCount", statusCount);
            
            return summary;
        } catch (Exception e) {
            System.err.println("Error en getSalesSummary: " + e.getMessage());
            e.printStackTrace();
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalOrders", 0);
            summary.put("totalRevenue", BigDecimal.ZERO);
            summary.put("statusCount", new HashMap<>());
            return summary;
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Product> getProductsReport(Long categoryId, Boolean isActive, 
                                           Boolean isFeatured, BigDecimal minPrice, 
                                           BigDecimal maxPrice, Integer minStock) {
        try {
            Specification<Product> spec = (root, query, cb) -> {
                List<Predicate> predicates = new ArrayList<>();
                
                if (categoryId != null) {
                    predicates.add(cb.equal(root.get("category").get("id"), categoryId));
                }
                if (isActive != null) {
                    predicates.add(cb.equal(root.get("isActive"), isActive));
                }
                if (isFeatured != null) {
                    predicates.add(cb.equal(root.get("isFeatured"), isFeatured));
                }
                if (minPrice != null) {
                    predicates.add(cb.greaterThanOrEqualTo(root.get("price"), minPrice));
                }
                if (maxPrice != null) {
                    predicates.add(cb.lessThanOrEqualTo(root.get("price"), maxPrice));
                }
                if (minStock != null) {
                    predicates.add(cb.greaterThanOrEqualTo(root.get("stock"), minStock));
                }
                
                return cb.and(predicates.toArray(new Predicate[0]));
            };
            
            List<Product> products = productRepository.findAll(spec);
            return products != null ? products : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en getProductsReport: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Map<String, Object> getProductsSummary() {
        try {
            List<Product> allProducts = productRepository.findAll();
            if (allProducts == null) {
                allProducts = java.util.Collections.emptyList();
            }
            
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalProducts", allProducts.size());
            
            long activeProducts = allProducts.stream()
                .filter(p -> p != null && Boolean.TRUE.equals(p.getIsActive()))
                .count();
            summary.put("activeProducts", activeProducts);
            
            long featuredProducts = allProducts.stream()
                .filter(p -> p != null && Boolean.TRUE.equals(p.getIsFeatured()))
                .count();
            summary.put("featuredProducts", featuredProducts);
            
            long lowStockProducts = allProducts.stream()
                .filter(p -> p != null && p.getStock() != null && p.getStock() < 10)
                .count();
            summary.put("lowStockProducts", lowStockProducts);
            
            BigDecimal totalValue = allProducts.stream()
                .filter(p -> p != null && p.getPrice() != null && p.getStock() != null)
                .map(p -> p.getPrice().multiply(BigDecimal.valueOf(p.getStock())))
                .reduce(BigDecimal.ZERO, BigDecimal::add);
            summary.put("totalInventoryValue", totalValue);
            
            return summary;
        } catch (Exception e) {
            System.err.println("Error en getProductsSummary: " + e.getMessage());
            e.printStackTrace();
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalProducts", 0);
            summary.put("activeProducts", 0);
            summary.put("featuredProducts", 0);
            summary.put("lowStockProducts", 0);
            summary.put("totalInventoryValue", BigDecimal.ZERO);
            return summary;
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<User> getUsersReport(Boolean isActive, Long roleId, 
                                    LocalDateTime startDate, LocalDateTime endDate) {
        Specification<User> spec = (root, query, cb) -> {
            List<Predicate> predicates = new ArrayList<>();
            
            if (isActive != null) {
                predicates.add(cb.equal(root.get("isActive"), isActive));
            }
            if (startDate != null) {
                predicates.add(cb.greaterThanOrEqualTo(root.get("createdAt"), startDate));
            }
            if (endDate != null) {
                predicates.add(cb.lessThanOrEqualTo(root.get("createdAt"), endDate));
            }
            if (roleId != null) {
                predicates.add(cb.equal(
                    root.join("roles").get("id"), roleId
                ));
            }
            
            return cb.and(predicates.toArray(new Predicate[0]));
        };
        
        return userRepository.findAll(spec);
    }
    
    @Override
    @Transactional(readOnly = true)
    public Map<String, Object> getUsersSummary() {
        try {
            List<User> allUsers = userRepository.findAll();
            if (allUsers == null) {
                allUsers = java.util.Collections.emptyList();
            }
            
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalUsers", allUsers.size());
            
            long activeUsers = allUsers.stream()
                .filter(u -> u != null && Boolean.TRUE.equals(u.getIsActive()))
                .count();
            summary.put("activeUsers", activeUsers);
            
            return summary;
        } catch (Exception e) {
            System.err.println("Error en getUsersSummary: " + e.getMessage());
            e.printStackTrace();
            Map<String, Object> summary = new HashMap<>();
            summary.put("totalUsers", 0);
            summary.put("activeUsers", 0);
            return summary;
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Map<String, Object>> getTopSellingProducts(LocalDateTime startDate, 
                                                           LocalDateTime endDate, 
                                                           Integer limit) {
        try {
            List<Order> orders = getSalesReport(startDate, endDate, "DELIVERED", null);
            if (orders == null) {
                orders = java.util.Collections.emptyList();
            }
            
            Map<Product, Integer> productQuantities = new HashMap<>();
            
            for (Order order : orders) {
                if (order != null && order.getOrderItems() != null) {
                    order.getOrderItems().forEach(item -> {
                        if (item != null && item.getProduct() != null && item.getQuantity() != null) {
                            Product product = item.getProduct();
                            int quantity = item.getQuantity();
                            productQuantities.merge(product, quantity, Integer::sum);
                        }
                    });
                }
            }
            
            return productQuantities.entrySet().stream()
                .filter(entry -> entry.getKey() != null && entry.getValue() != null)
                .sorted((e1, e2) -> e2.getValue().compareTo(e1.getValue()))
                .limit(limit != null ? limit : 10)
                .map(entry -> {
                    Map<String, Object> data = new HashMap<>();
                    data.put("product", entry.getKey());
                    data.put("quantity", entry.getValue());
                    data.put("quantitySold", entry.getValue());
                    if (entry.getKey().getPrice() != null) {
                        data.put("revenue", entry.getKey().getPrice()
                            .multiply(BigDecimal.valueOf(entry.getValue())));
                        data.put("totalRevenue", entry.getKey().getPrice()
                            .multiply(BigDecimal.valueOf(entry.getValue())));
                    } else {
                        data.put("revenue", BigDecimal.ZERO);
                        data.put("totalRevenue", BigDecimal.ZERO);
                    }
                    return data;
                })
                .collect(Collectors.toList());
        } catch (Exception e) {
            System.err.println("Error en getTopSellingProducts: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Map<String, Object>> getTopActiveUsers(LocalDateTime startDate, 
                                                       LocalDateTime endDate, 
                                                       Integer limit) {
        try {
            List<Order> ordersList = getSalesReport(startDate, endDate, null, null);
            final List<Order> orders = (ordersList != null) ? ordersList : java.util.Collections.emptyList();
            
            Map<User, Integer> userOrderCounts = orders.stream()
                .filter(o -> o != null && o.getUser() != null)
                .collect(Collectors.groupingBy(
                    Order::getUser,
                    Collectors.collectingAndThen(Collectors.counting(), Long::intValue)
                ));
            
            return userOrderCounts.entrySet().stream()
                .filter(entry -> entry.getKey() != null && entry.getValue() != null)
                .sorted((e1, e2) -> e2.getValue().compareTo(e1.getValue()))
                .limit(limit != null ? limit : 10)
                .map(entry -> {
                    Map<String, Object> data = new HashMap<>();
                    data.put("user", entry.getKey());
                    data.put("orderCount", entry.getValue());
                    
                    BigDecimal totalSpent = orders.stream()
                        .filter(o -> o != null && o.getUser() != null && o.getUser().getId() != null)
                        .filter(o -> o.getUser().getId().equals(entry.getKey().getId()))
                        .filter(o -> o.getStatus() != null && o.getStatus() == Order.OrderStatus.DELIVERED)
                        .filter(o -> o.getTotal() != null)
                        .map(Order::getTotal)
                        .reduce(BigDecimal.ZERO, BigDecimal::add);
                    
                    data.put("totalSpent", totalSpent);
                    return data;
                })
                .collect(Collectors.toList());
        } catch (Exception e) {
            System.err.println("Error en getTopActiveUsers: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
}

