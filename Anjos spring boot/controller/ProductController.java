package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.CartService;
import com.empresa.miproyecto.service.CategoryService;
import com.empresa.miproyecto.service.FavoriteService;
import com.empresa.miproyecto.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import java.util.List;
import org.springframework.validation.BindingResult;
import jakarta.validation.Valid;

import java.math.BigDecimal;

@Controller
@RequestMapping("/products")
public class ProductController {
    
    @Autowired
    private ProductService productService;
    
    @Autowired
    private CategoryService categoryService;
    
    @Autowired
    private CartService cartService;
    
    @Autowired
    private FavoriteService favoriteService;
    
    @GetMapping
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String index(
            @RequestParam(required = false) Long category,
            @RequestParam(required = false) String material,
            @RequestParam(required = false) String color,
            @RequestParam(required = false) String finish,
            @RequestParam(required = false) String stones,
            @RequestParam(required = false) BigDecimal minPrice,
            @RequestParam(required = false) BigDecimal maxPrice,
            @RequestParam(required = false) String search,
            @RequestParam(defaultValue = "0") int page,
            @RequestParam(defaultValue = "12") int size,
            Model model) {
        
        try {
            // Siempre cargar todos los productos para el admin (sin paginación en admin)
            List<Product> products = productService.findAll();
            if (products != null) {
                // Asegurar que las categorías estén cargadas
                products.forEach(product -> {
                    if (product.getCategory() != null) {
                        product.getCategory().getName(); // Forzar carga
                    }
                });
            }
            model.addAttribute("products", products != null ? products : java.util.Collections.emptyList());
            
            // Cargar categorías para los modales
            try {
                model.addAttribute("categories", categoryService.findActiveCategories());
            } catch (Exception e) {
                System.err.println("Error al cargar categorías: " + e.getMessage());
                model.addAttribute("categories", java.util.Collections.emptyList());
            }
        } catch (Exception e) {
            System.err.println("Error en index de productos: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("products", java.util.Collections.emptyList());
            model.addAttribute("categories", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar los productos: " + e.getMessage());
        }
        
        return "products/index";
    }
    
    @GetMapping("/create")
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String createForm(Model model) {
        try {
            model.addAttribute("product", new Product());
            model.addAttribute("categories", categoryService.findActiveCategories());
        } catch (Exception e) {
            System.err.println("Error en createForm de productos: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("product", new Product());
            model.addAttribute("categories", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar las categorías");
        }
        return "products/create";
    }
    
    @PostMapping
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String create(@Valid @ModelAttribute Product product,
                        @RequestParam(required = false) Long categoryId,
                        @RequestParam(required = false) String material,
                        @RequestParam(required = false) String color,
                        @RequestParam(required = false) String finish,
                        @RequestParam(required = false) String stones,
                        @RequestParam(required = false) String image,
                        @RequestParam(required = false) Boolean isFeatured,
                        @RequestParam(required = false) Boolean isActive,
                        BindingResult result,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        try {
            // Validar que la categoría es requerida
            if (categoryId == null) {
                result.rejectValue("category", "error.category", "La categoría es requerida");
            }
            
            if (result.hasErrors()) {
                model.addAttribute("categories", categoryService.findActiveCategories());
                return "products/create";
            }
            
            // Asignar categoría
            product.setCategory(categoryService.findById(categoryId)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Categoría no encontrada")));
            
            // Asignar campos opcionales
            if (material != null && !material.isEmpty()) product.setMaterial(material);
            if (color != null && !color.isEmpty()) product.setColor(color);
            if (finish != null && !finish.isEmpty()) product.setFinish(finish);
            if (stones != null && !stones.isEmpty()) product.setStones(stones);
            if (image != null && !image.isEmpty()) product.setImage(image);
            
            // Establecer valores por defecto
            product.setIsActive(isActive != null ? isActive : true);
            product.setIsFeatured(isFeatured != null ? isFeatured : false);
            
            // Validar que el stock no sea null
            if (product.getStock() == null) {
                product.setStock(0);
            }
            
            productService.save(product);
            redirectAttributes.addFlashAttribute("success", "Producto creado exitosamente");
            return "redirect:/products";
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/products/create";
        } catch (Exception e) {
            System.err.println("Error al crear producto: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("categories", categoryService.findActiveCategories());
            model.addAttribute("error", "Error al crear producto: " + e.getMessage());
            return "products/create";
        }
    }
    
    @GetMapping("/{id}/edit")
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String editForm(@PathVariable Long id, Model model, RedirectAttributes redirectAttributes) {
        try {
            Product product = productService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Producto no encontrado"));
            model.addAttribute("product", product);
            model.addAttribute("categories", categoryService.findActiveCategories());
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/products";
        } catch (Exception e) {
            System.err.println("Error en editForm de productos: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar el producto");
            return "redirect:/products";
        }
        return "products/edit";
    }
    
    @PostMapping("/{id}")
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String update(@PathVariable Long id,
                       @Valid @ModelAttribute Product product,
                       @RequestParam(required = false) Long categoryId,
                       @RequestParam(required = false) String material,
                       @RequestParam(required = false) String color,
                       @RequestParam(required = false) String finish,
                       @RequestParam(required = false) String stones,
                       @RequestParam(required = false) String image,
                       @RequestParam(required = false) Boolean isFeatured,
                       @RequestParam(required = false) Boolean isActive,
                       BindingResult result,
                       Model model,
                       RedirectAttributes redirectAttributes) {
        try {
            Product existingProduct = productService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Producto no encontrado"));
            
            // Validar que la categoría es requerida si no existe una actual
            if (categoryId == null && existingProduct.getCategory() == null) {
                result.rejectValue("category", "error.category", "La categoría es requerida");
            }
            
            if (result.hasErrors()) {
                model.addAttribute("product", existingProduct);
                model.addAttribute("categories", categoryService.findActiveCategories());
                return "products/edit";
            }
            
            // Actualizar campos
            existingProduct.setName(product.getName());
            existingProduct.setDescription(product.getDescription());
            existingProduct.setPrice(product.getPrice());
            existingProduct.setStock(product.getStock() != null ? product.getStock() : 0);
            
            // Asignar categoría si se proporciona
            if (categoryId != null) {
                existingProduct.setCategory(categoryService.findById(categoryId)
                    .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Categoría no encontrada")));
            }
            
            // Actualizar campos opcionales
            if (material != null) existingProduct.setMaterial(material);
            if (color != null) existingProduct.setColor(color);
            if (finish != null) existingProduct.setFinish(finish);
            if (stones != null) existingProduct.setStones(stones);
            if (image != null) existingProduct.setImage(image);
            if (isFeatured != null) existingProduct.setIsFeatured(isFeatured);
            if (isActive != null) existingProduct.setIsActive(isActive);
            
            productService.update(id, existingProduct);
            redirectAttributes.addFlashAttribute("success", "Producto actualizado exitosamente");
            return "redirect:/products";
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/products";
        } catch (Exception e) {
            System.err.println("Error al actualizar producto: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar producto: " + e.getMessage());
            return "redirect:/products/" + id + "/edit";
        }
    }
    
    @PostMapping("/{id}/delete")
    @org.springframework.security.access.prepost.PreAuthorize("hasRole('ADMIN')")
    public String delete(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        try {
            productService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Producto eliminado exitosamente");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar producto: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar el producto: " + e.getMessage());
        }
        return "redirect:/products";
    }
    
    @PostMapping("/{id}/carrito")
    public String addToCart(@PathVariable Long id, 
                           @RequestParam Integer quantity,
                           @AuthenticationPrincipal User user,
                           RedirectAttributes redirectAttributes) {
        if (user == null) {
            redirectAttributes.addFlashAttribute("error", "Debes iniciar sesión para agregar productos al carrito");
            return "redirect:/login";
        }
        
        try {
            cartService.addToCart(user, id, quantity);
            redirectAttributes.addFlashAttribute("success", "Producto agregado al carrito");
        } catch (com.empresa.miproyecto.exception.InsufficientStockException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al agregar al carrito: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al agregar el producto al carrito: " + e.getMessage());
        }
        return "redirect:/producto/" + id;
    }
    
    @PostMapping("/{id}/favoritos")
    public String toggleFavorite(@PathVariable Long id,
                                 @AuthenticationPrincipal User user,
                                 RedirectAttributes redirectAttributes) {
        if (user == null) {
            redirectAttributes.addFlashAttribute("error", "Debes iniciar sesión para agregar a favoritos");
            return "redirect:/login";
        }
        
        try {
            favoriteService.toggleProductFavorite(user, id);
            redirectAttributes.addFlashAttribute("success", "Favorito actualizado");
        } catch (Exception e) {
            System.err.println("Error al actualizar favorito: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar el favorito: " + e.getMessage());
        }
        return "redirect:/producto/" + id;
    }
}
