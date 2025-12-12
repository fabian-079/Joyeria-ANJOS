package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Customization;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.CustomizationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import org.springframework.validation.BindingResult;
import jakarta.validation.Valid;
import com.empresa.miproyecto.util.SecurityUtils;

import java.util.List;

@Controller
@RequestMapping("/personalizacion")
public class CustomizationController {
    
    @Autowired
    private CustomizationService customizationService;
    
    @GetMapping
    public String index(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            List<Customization> customizations = java.util.Collections.emptyList();
            if (SecurityUtils.isAdmin(user)) {
                try {
                    customizations = customizationService.findAll();
                    if (customizations == null) {
                        customizations = java.util.Collections.emptyList();
                    }
                } catch (Exception e) {
                    System.err.println("Error al obtener todas las personalizaciones: " + e.getMessage());
                    e.printStackTrace();
                    customizations = java.util.Collections.emptyList();
                }
            } else {
                try {
                    customizations = customizationService.findByUser(user);
                    if (customizations == null) {
                        customizations = java.util.Collections.emptyList();
                    }
                } catch (Exception e) {
                    System.err.println("Error al obtener personalizaciones del usuario: " + e.getMessage());
                    e.printStackTrace();
                    customizations = java.util.Collections.emptyList();
                }
            }
            
            model.addAttribute("customizations", customizations);
        } catch (Exception e) {
            System.err.println("Error en index de personalizaciones: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("customizations", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar las personalizaciones");
        }
        return "personalizacion/index";
    }
    
    @GetMapping("/create")
    public String createForm(Model model) {
        model.addAttribute("customization", new Customization());
        return "personalizacion/create";
    }
    
    @PostMapping
    public String create(@Valid @ModelAttribute Customization customization,
                        BindingResult result,
                        @AuthenticationPrincipal User user,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            redirectAttributes.addFlashAttribute("error", "Debes iniciar sesión");
            return "redirect:/login";
        }
        
        if (result.hasErrors()) {
            model.addAttribute("customization", customization);
            return "personalizacion/create";
        }
        
        try {
            customization.setUser(user);
            customizationService.create(customization);
            redirectAttributes.addFlashAttribute("success", "Solicitud de personalización creada");
        } catch (Exception e) {
            System.err.println("Error al crear personalización: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("customization", customization);
            model.addAttribute("error", "Error al crear la personalización: " + e.getMessage());
            return "personalizacion/create";
        }
        return "redirect:/personalizacion";
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
            Customization customization = customizationService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Personalización no encontrada"));
            
            if (customization.getUser() == null || (!SecurityUtils.isAdmin(user) && !customization.getUser().getId().equals(user.getId()))) {
                redirectAttributes.addFlashAttribute("error", "No tienes acceso a esta personalización");
                return "redirect:/personalizacion";
            }
            
            model.addAttribute("customization", customization);
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/personalizacion";
        } catch (Exception e) {
            System.err.println("Error al mostrar personalización: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la personalización");
            return "redirect:/personalizacion";
        }
        return "personalizacion/show";
    }
    
    @GetMapping("/{id}/edit")
    public String editForm(@PathVariable Long id, 
                          @AuthenticationPrincipal User user,
                          Model model,
                          RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para editar personalizaciones");
            return "redirect:/personalizacion";
        }
        
        try {
            Customization customization = customizationService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Personalización no encontrada"));
            model.addAttribute("customization", customization);
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/personalizacion";
        } catch (Exception e) {
            System.err.println("Error en editForm de personalizaciones: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la personalización");
            return "redirect:/personalizacion";
        }
        return "personalizacion/edit";
    }
    
    @PostMapping("/{id}")
    public String update(@PathVariable Long id,
                        @ModelAttribute Customization customization,
                        @RequestParam(required = false) String status,
                        @RequestParam(required = false) java.math.BigDecimal estimatedPrice,
                        @RequestParam(required = false) String adminNotes,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para actualizar personalizaciones");
            return "redirect:/personalizacion";
        }
        
        try {
            Customization existingCustomization = customizationService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Personalización no encontrada"));
            
            // Actualizar campos
            existingCustomization.setJewelryType(customization.getJewelryType());
            existingCustomization.setDesign(customization.getDesign());
            existingCustomization.setMaterial(customization.getMaterial());
            existingCustomization.setColor(customization.getColor());
            existingCustomization.setFinish(customization.getFinish());
            existingCustomization.setStones(customization.getStones());
            existingCustomization.setEngraving(customization.getEngraving());
            existingCustomization.setSpecialInstructions(customization.getSpecialInstructions());
            
            if (status != null) {
                existingCustomization.setStatus(status);
            }
            
            if (estimatedPrice != null) {
                existingCustomization.setEstimatedPrice(estimatedPrice);
            }
            
            if (adminNotes != null) {
                existingCustomization.setAdminNotes(adminNotes);
            }
            
            customizationService.update(id, existingCustomization);
            redirectAttributes.addFlashAttribute("success", "Personalización actualizada");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al actualizar personalización: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar la personalización: " + e.getMessage());
        }
        return "redirect:/personalizacion";
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para eliminar personalizaciones");
            return "redirect:/personalizacion";
        }
        
        try {
            customizationService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Personalización eliminada exitosamente");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar personalización: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar la personalización: " + e.getMessage());
        }
        return "redirect:/personalizacion";
    }
}
