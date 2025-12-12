package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Category;
import com.empresa.miproyecto.service.CategoryService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;

@Controller
@RequestMapping("/categories")
@PreAuthorize("hasRole('ADMIN')")
public class CategoryController {
    
    @Autowired
    private CategoryService categoryService;
    
    @GetMapping
    public String index(Model model) {
        try {
            List<Category> categories = categoryService.findAll();
            model.addAttribute("categories", categories != null ? categories : java.util.Collections.emptyList());
        } catch (Exception e) {
            System.err.println("Error en index de categorías: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("categories", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar las categorías");
        }
        return "categories/index";
    }
    
    @GetMapping("/create")
    public String createForm(Model model) {
        model.addAttribute("category", new Category());
        return "categories/create";
    }
    
    @PostMapping
    public String create(@Valid @ModelAttribute Category category,
                        BindingResult result,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        if (result.hasErrors()) {
            return "categories/create";
        }
        try {
            // Establecer valores por defecto
            if (category.getIsActive() == null) {
                category.setIsActive(true);
            }
            categoryService.save(category);
            redirectAttributes.addFlashAttribute("success", "Categoría creada exitosamente");
            return "redirect:/categories";
        } catch (Exception e) {
            System.err.println("Error al crear categoría: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("error", "Error al crear categoría: " + e.getMessage());
            return "categories/create";
        }
    }
    
    @GetMapping("/{id}/edit")
    public String editForm(@PathVariable Long id, Model model, RedirectAttributes redirectAttributes) {
        try {
            Category category = categoryService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Categoría no encontrada"));
            model.addAttribute("category", category);
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/categories";
        } catch (Exception e) {
            System.err.println("Error en editForm de categorías: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la categoría");
            return "redirect:/categories";
        }
        return "categories/edit";
    }
    
    @PostMapping("/{id}")
    public String update(@PathVariable Long id,
                        @ModelAttribute Category category,
                        @RequestParam(required = false) Boolean isActive,
                        BindingResult result,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        try {
            Category existingCategory = categoryService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Categoría no encontrada"));
            
            // Actualizar campos
            if (category.getName() != null && !category.getName().trim().isEmpty()) {
                existingCategory.setName(category.getName());
            }
            if (category.getDescription() != null) {
                existingCategory.setDescription(category.getDescription());
            }
            if (category.getImage() != null) {
                existingCategory.setImage(category.getImage());
            }
            if (isActive != null) {
                existingCategory.setIsActive(isActive);
            } else if (category.getIsActive() != null) {
                existingCategory.setIsActive(category.getIsActive());
            }
            
            categoryService.update(id, existingCategory);
            redirectAttributes.addFlashAttribute("success", "Categoría actualizada exitosamente");
            return "redirect:/categories";
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/categories";
        } catch (Exception e) {
            System.err.println("Error al actualizar categoría: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar categoría: " + e.getMessage());
            try {
                Category existingCategory = categoryService.findById(id).orElse(null);
                if (existingCategory != null) {
                    model.addAttribute("category", existingCategory);
                }
            } catch (Exception ex) {
                // Ignorar
            }
            return "categories/edit";
        }
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        try {
            categoryService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Categoría eliminada exitosamente");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar categoría: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar la categoría: " + e.getMessage());
        }
        return "redirect:/categories";
    }
}

