package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Favorite;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.FavoriteService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;

@Controller
@RequestMapping("/favoritos")
public class FavoriteController {
    
    @Autowired
    private FavoriteService favoriteService;
    
    @GetMapping
    public String index(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            List<Favorite> favorites = favoriteService.getUserFavorites(user);
            model.addAttribute("favorites", favorites != null ? favorites : java.util.Collections.emptyList());
        } catch (Exception e) {
            System.err.println("Error al cargar favoritos: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("favorites", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar los favoritos");
        }
        return "favoritos";
    }
    
    @PostMapping("/toggle")
    public String toggle(@RequestParam(required = false) Long productId,
                        @RequestParam(required = false) Long customizationId,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        if (productId != null) {
            favoriteService.toggleProductFavorite(user, productId);
        } else if (customizationId != null) {
            favoriteService.toggleCustomizationFavorite(user, customizationId);
        }
        
        redirectAttributes.addFlashAttribute("success", "Favorito actualizado");
        return "redirect:/favoritos";
    }
    
    @PostMapping("/{id}/remove")
    public String remove(@PathVariable Long id, 
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            favoriteService.removeFavorite(id);
            redirectAttributes.addFlashAttribute("success", "Favorito removido");
        } catch (Exception e) {
            System.err.println("Error al remover favorito: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al remover el favorito: " + e.getMessage());
        }
        return "redirect:/favoritos";
    }
}
