package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.service.ProductService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.security.test.context.support.WithMockUser;
import org.springframework.test.web.servlet.MockMvc;

import java.math.BigDecimal;
import java.util.Arrays;

import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@WebMvcTest(HomeController.class)
@WithMockUser
class HomeControllerTest {
    
    @Autowired
    private MockMvc mockMvc;
    
    @MockBean
    private ProductService productService;
    
    @Test
    void testIndex() throws Exception {
        Product product = new Product();
        product.setId(1L);
        product.setName("Anillo de Oro");
        product.setPrice(new BigDecimal("500.00"));
        product.setIsFeatured(true);
        product.setIsActive(true);
        
        when(productService.findFeaturedProducts()).thenReturn(Arrays.asList(product));
        
        mockMvc.perform(get("/"))
            .andExpect(status().isOk())
            .andExpect(view().name("home/index"))
            .andExpect(model().attributeExists("featuredProducts"));
    }
    
    @Test
    void testCatalogo() throws Exception {
        mockMvc.perform(get("/catalogo"))
            .andExpect(status().isOk())
            .andExpect(view().name("catalogo"));
    }
}
