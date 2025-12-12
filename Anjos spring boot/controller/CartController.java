package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.CartItem;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.CartService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.math.BigDecimal;
import java.util.List;

@Controller
@RequestMapping("/carrito")
public class CartController {
    
    @Autowired
    private CartService cartService;
    
    @GetMapping
    public String index(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            List<CartItem> cartItems = cartService.getCartItems(user);
            BigDecimal total = cartService.getCartTotal(user);
            
            model.addAttribute("cartItems", cartItems != null ? cartItems : java.util.Collections.emptyList());
            model.addAttribute("total", total != null ? total : java.math.BigDecimal.ZERO);
        } catch (Exception e) {
            System.err.println("Error al cargar carrito: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("cartItems", java.util.Collections.emptyList());
            model.addAttribute("total", java.math.BigDecimal.ZERO);
            model.addAttribute("error", "Error al cargar el carrito");
        }
        return "carrito";
    }
    
    @PostMapping("/{id}/update")
    public String update(@PathVariable Long id, 
                        @RequestParam Integer quantity,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            cartService.updateCartItem(id, quantity);
            redirectAttributes.addFlashAttribute("success", "Carrito actualizado");
        } catch (Exception e) {
            System.err.println("Error al actualizar carrito: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar el carrito: " + e.getMessage());
        }
        return "redirect:/carrito";
    }
    
    @PostMapping("/{id}/remove")
    public String remove(@PathVariable Long id, 
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            cartService.removeFromCart(id);
            redirectAttributes.addFlashAttribute("success", "Producto removido del carrito");
        } catch (Exception e) {
            System.err.println("Error al remover del carrito: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al remover el producto: " + e.getMessage());
        }
        return "redirect:/carrito";
    }
    
    @PostMapping("/checkout")
    public String checkout(@RequestParam String shippingAddress,
                          @RequestParam String billingAddress,
                          @RequestParam String phone,
                          @RequestParam String paymentMethod,
                          @RequestParam(required = false) String notes,
                          @AuthenticationPrincipal User user,
                          RedirectAttributes redirectAttributes) {
        // Redirigir al checkout de orders
        return "redirect:/orders/checkout";
    }
}
