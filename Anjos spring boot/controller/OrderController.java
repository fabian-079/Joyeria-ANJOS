package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.OrderService;
import com.empresa.miproyecto.service.CartService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import com.empresa.miproyecto.util.SecurityUtils;

import java.util.List;

@Controller
@RequestMapping("/orders")
public class OrderController {
    
    @Autowired
    private OrderService orderService;
    
    @Autowired
    private CartService cartService;
    
    @GetMapping
    public String index(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            List<Order> orders;
            if (SecurityUtils.isAdmin(user)) {
                orders = orderService.findAll();
            } else {
                orders = orderService.findByUser(user);
            }
            
            model.addAttribute("orders", orders != null ? orders : java.util.Collections.emptyList());
        } catch (Exception e) {
            System.err.println("Error en index de órdenes: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("orders", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar las órdenes");
        }
        return "orders/index";
    }
    
    @GetMapping("/{id}")
    public String show(@PathVariable Long id, 
                      @AuthenticationPrincipal User user,
                      Model model,
                      RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            Order order = orderService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Orden no encontrada"));
            
            // Verificar que el usuario tenga acceso
            if (order.getUser() == null || (!SecurityUtils.isAdmin(user) && !order.getUser().getId().equals(user.getId()))) {
                redirectAttributes.addFlashAttribute("error", "No tienes acceso a esta orden");
                return "redirect:/orders";
            }
            
            model.addAttribute("order", order);
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/orders";
        } catch (Exception e) {
            System.err.println("Error al mostrar orden: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la orden");
            return "redirect:/orders";
        }
        return "orders/show";
    }
    
    @PostMapping("/{id}")
    public String update(@PathVariable Long id,
                        @RequestParam String status,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            // Verificar que la orden existe
            Order order = orderService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Orden no encontrada"));
            
            // Verificar permisos
            if (!SecurityUtils.isAdmin(user) && (order.getUser() == null || !order.getUser().getId().equals(user.getId()))) {
                redirectAttributes.addFlashAttribute("error", "No tienes permiso para actualizar esta orden");
                return "redirect:/orders";
            }
            
            orderService.updateOrderStatus(id, Order.OrderStatus.valueOf(status));
            redirectAttributes.addFlashAttribute("success", "Estado de orden actualizado");
        } catch (IllegalArgumentException e) {
            redirectAttributes.addFlashAttribute("error", "Estado inválido: " + e.getMessage());
            return "redirect:/orders/" + id;
        } catch (Exception e) {
            System.err.println("Error al actualizar orden: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar la orden: " + e.getMessage());
            return "redirect:/orders/" + id;
        }
        return "redirect:/orders/" + id;
    }
    
    @GetMapping("/checkout")
    public String checkoutForm(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            // Obtener items del carrito para mostrar en checkout
            java.util.List<com.empresa.miproyecto.model.CartItem> cartItems = cartService.getCartItems(user);
            java.math.BigDecimal total = cartService.getCartTotal(user);
            model.addAttribute("cartItems", cartItems != null ? cartItems : java.util.Collections.emptyList());
            model.addAttribute("total", total != null ? total : java.math.BigDecimal.ZERO);
        } catch (Exception e) {
            System.err.println("Error al cargar checkout: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("cartItems", java.util.Collections.emptyList());
            model.addAttribute("total", java.math.BigDecimal.ZERO);
        }
        
        return "orders/checkout";
    }
    
    @PostMapping("/checkout")
    public String checkout(@RequestParam String shippingAddress,
                          @RequestParam String billingAddress,
                          @RequestParam String phone,
                          @RequestParam String paymentMethod,
                          @RequestParam(required = false) String notes,
                          @AuthenticationPrincipal User user,
                          RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        try {
            Order order = orderService.createOrder(user, shippingAddress, 
                billingAddress, phone, paymentMethod, notes);
            redirectAttributes.addFlashAttribute("success", 
                "Orden creada exitosamente. Número de orden: " + order.getOrderNumber());
            return "redirect:/orders/" + order.getId();
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/carrito";
        }
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            // Verificar que la orden existe
            orderService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Orden no encontrada"));
            
            // Solo admin puede eliminar órdenes
            if (!SecurityUtils.isAdmin(user)) {
                redirectAttributes.addFlashAttribute("error", "No tienes permiso para eliminar órdenes");
                return "redirect:/orders";
            }
            
            orderService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Orden eliminada exitosamente");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar orden: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar la orden: " + e.getMessage());
        }
        return "redirect:/orders";
    }
}
