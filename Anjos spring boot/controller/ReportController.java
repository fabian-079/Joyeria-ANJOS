package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.CategoryService;
import com.empresa.miproyecto.service.ReportService;
import com.empresa.miproyecto.service.RoleService;
import com.empresa.miproyecto.util.PdfExportUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.format.annotation.DateTimeFormat;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Controller
@RequestMapping("/admin/reports")
@PreAuthorize("hasRole('ADMIN')")
public class ReportController {
    
    @Autowired
    private ReportService reportService;
    
    @Autowired
    private CategoryService categoryService;
    
    @Autowired
    private RoleService roleService;
    
    @GetMapping
    public String index(Model model) {
        try {
            // Resumen general
            Map<String, Object> salesSummary = reportService.getSalesSummary(null, null);
            Map<String, Object> productsSummary = reportService.getProductsSummary();
            Map<String, Object> usersSummary = reportService.getUsersSummary();
            
            model.addAttribute("salesSummary", salesSummary != null ? salesSummary : new java.util.HashMap<>());
            model.addAttribute("productsSummary", productsSummary != null ? productsSummary : new java.util.HashMap<>());
            model.addAttribute("usersSummary", usersSummary != null ? usersSummary : new java.util.HashMap<>());
        } catch (Exception e) {
            System.err.println("Error en index de reportes: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("salesSummary", new java.util.HashMap<>());
            model.addAttribute("productsSummary", new java.util.HashMap<>());
            model.addAttribute("usersSummary", new java.util.HashMap<>());
            model.addAttribute("error", "Error al cargar los reportes");
        }
        return "admin/reports/index";
    }
    
    @GetMapping("/sales")
    public String salesReport(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(required = false) String status,
            @RequestParam(required = false) Long userId,
            Model model) {
        try {
            List<Order> orders = reportService.getSalesReport(startDate, endDate, status, userId);
            Map<String, Object> summary = reportService.getSalesSummary(startDate, endDate);
            
            model.addAttribute("orders", orders != null ? orders : java.util.Collections.emptyList());
            model.addAttribute("summary", summary != null ? summary : new java.util.HashMap<>());
            model.addAttribute("startDate", startDate);
            model.addAttribute("endDate", endDate);
            model.addAttribute("status", status);
            model.addAttribute("userId", userId);
        } catch (Exception e) {
            System.err.println("Error en salesReport: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("orders", java.util.Collections.emptyList());
            model.addAttribute("summary", new java.util.HashMap<>());
            model.addAttribute("error", "Error al cargar el reporte de ventas");
        }
        return "admin/reports/sales";
    }
    
    @GetMapping("/products")
    public String productsReport(
            @RequestParam(required = false) Long categoryId,
            @RequestParam(required = false) Boolean isActive,
            @RequestParam(required = false) Boolean isFeatured,
            @RequestParam(required = false) BigDecimal minPrice,
            @RequestParam(required = false) BigDecimal maxPrice,
            @RequestParam(required = false) Integer minStock,
            Model model) {
        try {
            List<Product> products = reportService.getProductsReport(
                categoryId, isActive, isFeatured, minPrice, maxPrice, minStock);
            Map<String, Object> summary = reportService.getProductsSummary();
            
            model.addAttribute("products", products != null ? products : java.util.Collections.emptyList());
            model.addAttribute("summary", summary != null ? summary : new java.util.HashMap<>());
            try {
                model.addAttribute("categories", categoryService.findActiveCategories());
            } catch (Exception e) {
                model.addAttribute("categories", java.util.Collections.emptyList());
            }
            model.addAttribute("categoryId", categoryId);
            model.addAttribute("isActive", isActive);
            model.addAttribute("isFeatured", isFeatured);
            model.addAttribute("minPrice", minPrice);
            model.addAttribute("maxPrice", maxPrice);
            model.addAttribute("minStock", minStock);
        } catch (Exception e) {
            System.err.println("Error en productsReport: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("products", java.util.Collections.emptyList());
            model.addAttribute("summary", new java.util.HashMap<>());
            model.addAttribute("categories", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar el reporte de productos");
        }
        return "admin/reports/products";
    }
    
    @GetMapping("/users")
    public String usersReport(
            @RequestParam(required = false) Boolean isActive,
            @RequestParam(required = false) Long roleId,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            Model model) {
        try {
            List<User> users = reportService.getUsersReport(isActive, roleId, startDate, endDate);
            Map<String, Object> summary = reportService.getUsersSummary();
            
            model.addAttribute("users", users != null ? users : java.util.Collections.emptyList());
            model.addAttribute("summary", summary != null ? summary : new java.util.HashMap<>());
            try {
                model.addAttribute("roles", roleService.findAll());
            } catch (Exception e) {
                model.addAttribute("roles", java.util.Collections.emptyList());
            }
            model.addAttribute("isActive", isActive);
            model.addAttribute("roleId", roleId);
            model.addAttribute("startDate", startDate);
            model.addAttribute("endDate", endDate);
        } catch (Exception e) {
            System.err.println("Error en usersReport: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("users", java.util.Collections.emptyList());
            model.addAttribute("summary", new java.util.HashMap<>());
            model.addAttribute("roles", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar el reporte de usuarios");
        }
        return "admin/reports/users";
    }
    
    @GetMapping("/top-selling")
    public String topSellingProducts(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(defaultValue = "10") Integer limit,
            Model model) {
        try {
            List<Map<String, Object>> topProducts = reportService.getTopSellingProducts(
                startDate, endDate, limit);
            
            model.addAttribute("topProducts", topProducts != null ? topProducts : java.util.Collections.emptyList());
            model.addAttribute("startDate", startDate);
            model.addAttribute("endDate", endDate);
            model.addAttribute("limit", limit);
        } catch (Exception e) {
            System.err.println("Error en topSellingProducts: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("topProducts", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar los productos más vendidos");
        }
        return "admin/reports/top-selling";
    }
    
    @GetMapping("/top-users")
    public String topActiveUsers(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(defaultValue = "10") Integer limit,
            Model model) {
        try {
            List<Map<String, Object>> topUsers = reportService.getTopActiveUsers(
                startDate, endDate, limit);
            
            model.addAttribute("topUsers", topUsers != null ? topUsers : java.util.Collections.emptyList());
            model.addAttribute("startDate", startDate);
            model.addAttribute("endDate", endDate);
            model.addAttribute("limit", limit);
        } catch (Exception e) {
            System.err.println("Error en topActiveUsers: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("topUsers", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar los clientes más activos");
        }
        return "admin/reports/top-users";
    }
    
    @GetMapping("/export/sales/pdf")
    public ResponseEntity<byte[]> exportSalesReportPdf(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(required = false) String status) {
        try {
            List<Order> orders = reportService.getSalesReport(startDate, endDate, status, null);
            Map<String, Object> summary = reportService.getSalesSummary(startDate, endDate);
            
            // Convertir órdenes a formato para PDF
            List<Map<String, Object>> ordersData = new ArrayList<>();
            if (orders != null) {
                for (Order order : orders) {
                    if (order != null) {
                        Map<String, Object> orderData = new HashMap<>();
                        orderData.put("id", order.getId());
                        orderData.put("customerName", order.getUser() != null ? order.getUser().getName() : "N/A");
                        orderData.put("total", order.getTotal() != null ? order.getTotal() : BigDecimal.ZERO);
                        orderData.put("status", order.getStatus() != null ? order.getStatus().toString() : "PENDIENTE");
                        orderData.put("createdAt", order.getCreatedAt() != null ? order.getCreatedAt().toString() : "N/A");
                        orderData.put("paymentMethod", order.getPaymentMethod() != null ? order.getPaymentMethod().toString() : "N/A");
                        ordersData.add(orderData);
                    }
                }
            }
            
            byte[] pdfBytes = PdfExportUtil.generateSalesReportPdf(ordersData, summary);
            
            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_PDF);
            headers.setContentDispositionFormData("attachment", "reporte_ventas_" + LocalDateTime.now().format(java.time.format.DateTimeFormatter.ofPattern("yyyyMMdd_HHmmss")) + ".pdf");
            
            return ResponseEntity.ok()
                    .headers(headers)
                    .body(pdfBytes);
        } catch (Exception e) {
            System.err.println("Error en exportSalesReportPdf: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.internalServerError().build();
        }
    }
    
    @GetMapping("/export/products/pdf")
    public ResponseEntity<byte[]> exportProductsReportPdf(
            @RequestParam(required = false) Long categoryId,
            @RequestParam(required = false) Boolean isActive,
            @RequestParam(required = false) Boolean isFeatured) {
        try {
            List<Product> products = reportService.getProductsReport(categoryId, isActive, isFeatured, null, null, null);
            Map<String, Object> summary = reportService.getProductsSummary();
            
            // Convertir productos a formato para PDF
            List<Map<String, Object>> productsData = new ArrayList<>();
            if (products != null) {
                for (Product product : products) {
                    if (product != null) {
                        Map<String, Object> productData = new HashMap<>();
                        productData.put("id", product.getId());
                        productData.put("name", product.getName());
                        productData.put("categoryName", product.getCategory() != null ? product.getCategory().getName() : "Sin categoría");
                        productData.put("price", product.getPrice() != null ? product.getPrice() : BigDecimal.ZERO);
                        productData.put("stock", product.getStock() != null ? product.getStock() : 0);
                        productData.put("isActive", product.getIsActive() != null ? product.getIsActive() : false);
                        productsData.add(productData);
                    }
                }
            }
            
            byte[] pdfBytes = PdfExportUtil.generateProductsReportPdf(productsData, summary);
            
            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_PDF);
            headers.setContentDispositionFormData("attachment", "reporte_productos_" + LocalDateTime.now().format(java.time.format.DateTimeFormatter.ofPattern("yyyyMMdd_HHmmss")) + ".pdf");
            
            return ResponseEntity.ok()
                    .headers(headers)
                    .body(pdfBytes);
        } catch (Exception e) {
            System.err.println("Error en exportProductsReportPdf: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.internalServerError().build();
        }
    }
    
    @GetMapping("/export/users/pdf")
    public ResponseEntity<byte[]> exportUsersReportPdf(
            @RequestParam(required = false) Boolean isActive,
            @RequestParam(required = false) Long roleId) {
        try {
            List<User> users = reportService.getUsersReport(isActive, roleId, null, null);
            Map<String, Object> summary = reportService.getUsersSummary();
            
            // Convertir usuarios a formato para PDF
            List<Map<String, Object>> usersData = new ArrayList<>();
            if (users != null) {
                for (User user : users) {
                    if (user != null) {
                        Map<String, Object> userData = new HashMap<>();
                        userData.put("id", user.getId());
                        userData.put("name", user.getName());
                        userData.put("email", user.getEmail());
                        userData.put("roles", user.getRoles() != null ? 
                            user.getRoles().stream()
                                .map(role -> role.getName())
                                .reduce((a, b) -> a + ", " + b)
                                .orElse("Sin roles") : "Sin roles");
                        userData.put("isActive", user.getIsActive() != null ? user.getIsActive() : false);
                        usersData.add(userData);
                    }
                }
            }
            
            byte[] pdfBytes = PdfExportUtil.generateUsersReportPdf(usersData, summary);
            
            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_PDF);
            headers.setContentDispositionFormData("attachment", "reporte_usuarios_" + LocalDateTime.now().format(java.time.format.DateTimeFormatter.ofPattern("yyyyMMdd_HHmmss")) + ".pdf");
            
            return ResponseEntity.ok()
                    .headers(headers)
                    .body(pdfBytes);
        } catch (Exception e) {
            System.err.println("Error en exportUsersReportPdf: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.internalServerError().build();
        }
    }
    
    @GetMapping("/export/top-selling/pdf")
    public ResponseEntity<byte[]> exportTopSellingPdf(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(defaultValue = "10") Integer limit) {
        try {
            List<Map<String, Object>> topProducts = reportService.getTopSellingProducts(startDate, endDate, limit);
            
            byte[] pdfBytes = PdfExportUtil.generateTopSellingPdf(topProducts != null ? topProducts : new ArrayList<>());
            
            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_PDF);
            headers.setContentDispositionFormData("attachment", "productos_mas_vendidos_" + LocalDateTime.now().format(java.time.format.DateTimeFormatter.ofPattern("yyyyMMdd_HHmmss")) + ".pdf");
            
            return ResponseEntity.ok()
                    .headers(headers)
                    .body(pdfBytes);
        } catch (Exception e) {
            System.err.println("Error en exportTopSellingPdf: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.internalServerError().build();
        }
    }
    
    @GetMapping("/export/top-users/pdf")
    public ResponseEntity<byte[]> exportTopUsersPdf(
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime startDate,
            @RequestParam(required = false) @DateTimeFormat(iso = DateTimeFormat.ISO.DATE_TIME) LocalDateTime endDate,
            @RequestParam(defaultValue = "10") Integer limit) {
        try {
            List<Map<String, Object>> topUsers = reportService.getTopActiveUsers(startDate, endDate, limit);
            
            byte[] pdfBytes = PdfExportUtil.generateTopUsersPdf(topUsers != null ? topUsers : new ArrayList<>());
            
            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_PDF);
            headers.setContentDispositionFormData("attachment", "clientes_mas_activos_" + LocalDateTime.now().format(java.time.format.DateTimeFormatter.ofPattern("yyyyMMdd_HHmmss")) + ".pdf");
            
            return ResponseEntity.ok()
                    .headers(headers)
                    .body(pdfBytes);
        } catch (Exception e) {
            System.err.println("Error en exportTopUsersPdf: " + e.getMessage());
            e.printStackTrace();
            return ResponseEntity.internalServerError().build();
        }
    }
}

