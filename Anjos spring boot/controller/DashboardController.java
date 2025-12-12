package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.*;
import com.empresa.miproyecto.util.SecurityUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.math.BigDecimal;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Controller
@RequestMapping("/dashboard")
public class DashboardController {
    
    @Autowired
    private OrderService orderService;
    
    
    @Autowired
    private ReportService reportService;
    
    @Autowired
    private RepairService repairService;
    
    @Autowired
    private CustomizationService customizationService;
    
    @Autowired
    private CartService cartService;
    
    @Autowired
    private FavoriteService favoriteService;
    
    @GetMapping("/admin")
    public String adminDashboard(@AuthenticationPrincipal User user, Model model) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            return "redirect:/login";
        }
        
        try {
            // Estadísticas generales
            Map<String, Object> salesSummary = reportService.getSalesSummary(null, null);
            Map<String, Object> productsSummary = reportService.getProductsSummary();
            Map<String, Object> usersSummary = reportService.getUsersSummary();
            
            // Órdenes recientes
            List<Order> allOrders = orderService.findAll();
            List<Order> recentOrders = (allOrders != null && allOrders.size() > 10) 
                ? new java.util.ArrayList<>(allOrders.subList(0, 10))
                : (allOrders != null ? allOrders : java.util.Collections.emptyList());
            
            // Productos más vendidos
            List<Map<String, Object>> topProducts = reportService.getTopSellingProducts(null, null, 5);
            if (topProducts == null) {
                topProducts = java.util.Collections.emptyList();
            }
            
            // Clientes más activos
            List<Map<String, Object>> topUsers = reportService.getTopActiveUsers(null, null, 5);
            if (topUsers == null) {
                topUsers = java.util.Collections.emptyList();
            }
            
            // Reparaciones pendientes
            List<com.empresa.miproyecto.model.Repair> allRepairs = repairService.findAll();
            long pendingRepairs = 0L;
            if (allRepairs != null) {
                pendingRepairs = allRepairs.stream()
                    .filter(r -> r != null && r.getStatus() != null && r.getStatus().toString().equals("PENDING"))
                    .count();
            }
            
            // Personalizaciones pendientes
            List<com.empresa.miproyecto.model.Customization> allCustomizations = customizationService.findAll();
            long pendingCustomizations = 0L;
            if (allCustomizations != null) {
                pendingCustomizations = allCustomizations.stream()
                    .filter(c -> c != null && c.getStatus() != null && c.getStatus().equals("pending"))
                    .count();
            }
            
            model.addAttribute("salesSummary", salesSummary);
            model.addAttribute("productsSummary", productsSummary);
            model.addAttribute("usersSummary", usersSummary);
            model.addAttribute("recentOrders", recentOrders);
            model.addAttribute("topProducts", topProducts);
            model.addAttribute("topUsers", topUsers);
            model.addAttribute("pendingRepairs", pendingRepairs);
            model.addAttribute("pendingCustomizations", pendingCustomizations);
            
        } catch (Exception e) {
            System.err.println("Error en dashboard admin: " + e.getMessage());
            e.printStackTrace();
            // Agregar valores por defecto
            model.addAttribute("salesSummary", new HashMap<>());
            model.addAttribute("productsSummary", new HashMap<>());
            model.addAttribute("usersSummary", new HashMap<>());
            model.addAttribute("recentOrders", java.util.Collections.emptyList());
            model.addAttribute("topProducts", java.util.Collections.emptyList());
            model.addAttribute("topUsers", java.util.Collections.emptyList());
            model.addAttribute("pendingRepairs", 0L);
            model.addAttribute("pendingCustomizations", 0L);
        }
        
        return "dashboard/admin";
    }
    
    @GetMapping("/cliente")
    public String clienteDashboard(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            // Órdenes del cliente
            List<Order> orders = orderService.findByUser(user);
            if (orders == null) {
                orders = java.util.Collections.emptyList();
            }
            final List<Order> finalOrders = orders;
            
            long totalOrders = finalOrders.size();
            long pendingOrders = finalOrders.stream()
                .filter(o -> o != null && o.getStatus() != null && 
                           (o.getStatus() == Order.OrderStatus.PENDING || 
                            o.getStatus() == Order.OrderStatus.PROCESSING))
                .count();
            
            BigDecimal totalSpent = finalOrders.stream()
                .filter(o -> o != null && o.getStatus() != null && o.getStatus() == Order.OrderStatus.DELIVERED)
                .filter(o -> o.getTotal() != null)
                .map(Order::getTotal)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
            
            // Reparaciones del cliente
            List<com.empresa.miproyecto.model.Repair> repairs = repairService.findByUser(user);
            if (repairs == null) {
                repairs = java.util.Collections.emptyList();
            }
            final List<com.empresa.miproyecto.model.Repair> finalRepairs = repairs;
            
            long totalRepairs = finalRepairs.size();
            long pendingRepairs = finalRepairs.stream()
                .filter(r -> r != null && r.getStatus() != null && r.getStatus().toString().equals("PENDING"))
                .count();
            
            // Personalizaciones del cliente
            List<com.empresa.miproyecto.model.Customization> customizations = customizationService.findByUser(user);
            if (customizations == null) {
                customizations = java.util.Collections.emptyList();
            }
            final List<com.empresa.miproyecto.model.Customization> finalCustomizations = customizations;
            
            long totalCustomizations = finalCustomizations.size();
            long pendingCustomizations = finalCustomizations.stream()
                .filter(c -> c != null && c.getStatus() != null && c.getStatus().equals("pending"))
                .count();
            
            // Items en carrito
            List<com.empresa.miproyecto.model.CartItem> cartItems = cartService.getCartItems(user);
            int cartItemsCount = cartItems != null ? cartItems.size() : 0;
            BigDecimal cartTotal = cartService.getCartTotal(user);
            if (cartTotal == null) {
                cartTotal = BigDecimal.ZERO;
            }
            
            // Favoritos
            List<com.empresa.miproyecto.model.Favorite> favorites = favoriteService.getUserFavorites(user);
            int favoritesCount = favorites != null ? favorites.size() : 0;
            
            // Órdenes recientes (últimas 5)
            List<Order> recentOrders = finalOrders.size() > 5 
                ? new java.util.ArrayList<>(finalOrders.subList(0, 5))
                : new java.util.ArrayList<>(finalOrders);
            
            // Reparaciones recientes (últimas 3)
            List<com.empresa.miproyecto.model.Repair> recentRepairs = finalRepairs.size() > 3 
                ? new java.util.ArrayList<>(finalRepairs.subList(0, 3))
                : new java.util.ArrayList<>(finalRepairs);
            
            // Personalizaciones recientes (últimas 3)
            List<com.empresa.miproyecto.model.Customization> recentCustomizations = 
                finalCustomizations.size() > 3 
                    ? new java.util.ArrayList<>(finalCustomizations.subList(0, 3))
                    : new java.util.ArrayList<>(finalCustomizations);
            
            model.addAttribute("totalOrders", totalOrders);
            model.addAttribute("pendingOrders", pendingOrders);
            model.addAttribute("totalSpent", totalSpent);
            model.addAttribute("totalRepairs", totalRepairs);
            model.addAttribute("pendingRepairs", pendingRepairs);
            model.addAttribute("totalCustomizations", totalCustomizations);
            model.addAttribute("pendingCustomizations", pendingCustomizations);
            model.addAttribute("cartItemsCount", cartItemsCount);
            model.addAttribute("cartTotal", cartTotal);
            model.addAttribute("favoritesCount", favoritesCount);
            model.addAttribute("recentOrders", recentOrders);
            model.addAttribute("recentRepairs", recentRepairs);
            model.addAttribute("recentCustomizations", recentCustomizations);
            
        } catch (Exception e) {
            System.err.println("Error en dashboard cliente: " + e.getMessage());
            e.printStackTrace();
            // Valores por defecto
            model.addAttribute("totalOrders", 0L);
            model.addAttribute("pendingOrders", 0L);
            model.addAttribute("totalSpent", BigDecimal.ZERO);
            model.addAttribute("totalRepairs", 0L);
            model.addAttribute("pendingRepairs", 0L);
            model.addAttribute("totalCustomizations", 0L);
            model.addAttribute("pendingCustomizations", 0L);
            model.addAttribute("cartItemsCount", 0);
            model.addAttribute("cartTotal", BigDecimal.ZERO);
            model.addAttribute("favoritesCount", 0);
            model.addAttribute("recentOrders", java.util.Collections.emptyList());
            model.addAttribute("recentRepairs", java.util.Collections.emptyList());
            model.addAttribute("recentCustomizations", java.util.Collections.emptyList());
        }
        
        return "dashboard/cliente";
    }
}

