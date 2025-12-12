package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.service.ProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestParam;

import java.math.BigDecimal;
import java.util.List;

@Controller
public class HomeController {
    
    @Autowired
    private ProductService productService;
    
    @GetMapping("/")
    public String index(Model model) {
        try {
            List<Product> featuredProducts = productService.findFeaturedProducts();
            if (featuredProducts == null) {
                featuredProducts = java.util.Collections.emptyList();
            }
            model.addAttribute("featuredProducts", featuredProducts);
        } catch (Exception e) {
            // Log the error and provide empty list as fallback
            System.err.println("Error loading featured products: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("featuredProducts", java.util.Collections.emptyList());
            model.addAttribute("errorMessage", "No se pudieron cargar los productos destacados");
        }
        return "home/index";
    }
    
    @GetMapping("/catalogo")
    public String catalogo(
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
            Pageable pageable = PageRequest.of(page, size);
            Page<Product> productsPage = productService.searchProducts(
                category, material, color, finish, stones, 
                minPrice, maxPrice, search, pageable
            );
            
            model.addAttribute("products", productsPage.getContent());
            model.addAttribute("currentPage", page);
            model.addAttribute("totalPages", productsPage.getTotalPages());
            model.addAttribute("totalItems", productsPage.getTotalElements());
            
            // Intentar obtener filtros, pero no fallar si hay error
            try {
                model.addAttribute("materials", productService.getDistinctMaterials());
                model.addAttribute("colors", productService.getDistinctColors());
                model.addAttribute("finishes", productService.getDistinctFinishes());
                model.addAttribute("stones", productService.getDistinctStones());
            } catch (Exception e) {
                model.addAttribute("materials", java.util.Collections.emptyList());
                model.addAttribute("colors", java.util.Collections.emptyList());
                model.addAttribute("finishes", java.util.Collections.emptyList());
                model.addAttribute("stones", java.util.Collections.emptyList());
            }
        } catch (Exception e) {
            System.err.println("Error en catálogo: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("products", java.util.Collections.emptyList());
            model.addAttribute("currentPage", 0);
            model.addAttribute("totalPages", 0);
            model.addAttribute("totalItems", 0);
            model.addAttribute("materials", java.util.Collections.emptyList());
            model.addAttribute("colors", java.util.Collections.emptyList());
            model.addAttribute("finishes", java.util.Collections.emptyList());
            model.addAttribute("stones", java.util.Collections.emptyList());
        }
        
        return "catalogo";
    }
    
    @GetMapping("/producto/{id}")
    public String producto(@PathVariable Long id, Model model) {
        try {
            Product product = productService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Producto no encontrado"));
            
            List<Product> relatedProducts = java.util.Collections.emptyList();
            try {
                if (product.getCategory() != null && product.getCategory().getId() != null) {
                    relatedProducts = productService.findRelatedProducts(
                        product.getCategory().getId(), product.getId(), 4
                    );
                    if (relatedProducts == null) {
                        relatedProducts = java.util.Collections.emptyList();
                    }
                }
            } catch (Exception e) {
                System.err.println("Error obteniendo productos relacionados: " + e.getMessage());
                relatedProducts = java.util.Collections.emptyList();
            }
            
            model.addAttribute("product", product);
            model.addAttribute("relatedProducts", relatedProducts);
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            model.addAttribute("error", e.getMessage());
            return "error/404";
        } catch (Exception e) {
            System.err.println("Error en detalle de producto: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("error", "Error al cargar el producto");
            return "error/500";
        }
        return "producto-detalle";
    }
    
    @GetMapping("/buscar")
    public String buscar(@RequestParam(required = false) String q,
                        @RequestParam(defaultValue = "0") int page,
                        @RequestParam(defaultValue = "12") int size,
                        Model model) {
        try {
            Pageable pageable = PageRequest.of(page, size);
            Page<Product> productsPage = productService.searchProducts(
                null, null, null, null, null, 
                null, null, q, pageable
            );
            
            model.addAttribute("products", productsPage.getContent());
            model.addAttribute("currentPage", page);
            model.addAttribute("totalPages", productsPage.getTotalPages());
            model.addAttribute("totalItems", productsPage.getTotalElements());
            model.addAttribute("searchQuery", q != null ? q : "");
        } catch (Exception e) {
            System.err.println("Error en búsqueda: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("products", java.util.Collections.emptyList());
            model.addAttribute("currentPage", 0);
            model.addAttribute("totalPages", 0);
            model.addAttribute("totalItems", 0);
            model.addAttribute("searchQuery", q != null ? q : "");
        }
        return "buscar";
    }
}
