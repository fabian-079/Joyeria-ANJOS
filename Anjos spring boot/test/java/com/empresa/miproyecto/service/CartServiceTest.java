package com.empresa.miproyecto.service;

import com.empresa.miproyecto.exception.InsufficientStockException;
import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.CartItem;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.CartItemRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.service.impl.CartServiceImpl;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;

import java.math.BigDecimal;
import java.util.Arrays;
import java.util.List;
import java.util.Optional;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class CartServiceTest {
    
    @Mock
    private CartItemRepository cartItemRepository;
    
    @Mock
    private ProductRepository productRepository;
    
    @InjectMocks
    private CartServiceImpl cartService;
    
    private User user;
    private Product product;
    private CartItem cartItem;
    
    @BeforeEach
    void setUp() {
        user = new User();
        user.setId(1L);
        user.setEmail("test@test.com");
        user.setName("Test User");
        
        product = new Product();
        product.setId(1L);
        product.setName("Anillo de Oro");
        product.setPrice(new BigDecimal("500.00"));
        product.setStock(10);
        
        cartItem = new CartItem();
        cartItem.setId(1L);
        cartItem.setUser(user);
        cartItem.setProduct(product);
        cartItem.setQuantity(2);
    }
    
    @Test
    void testAddToCart_NewItem() {
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        when(cartItemRepository.findByUserIdAndProductId(1L, 1L)).thenReturn(Optional.empty());
        when(cartItemRepository.save(any(CartItem.class))).thenReturn(cartItem);
        
        CartItem result = cartService.addToCart(user, 1L, 2);
        
        assertNotNull(result);
        verify(cartItemRepository, times(1)).save(any(CartItem.class));
    }
    
    @Test
    void testAddToCart_ExistingItem() {
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        when(cartItemRepository.findByUserIdAndProductId(1L, 1L)).thenReturn(Optional.of(cartItem));
        when(cartItemRepository.save(any(CartItem.class))).thenReturn(cartItem);
        
        cartService.addToCart(user, 1L, 3);
        
        assertEquals(5, cartItem.getQuantity()); // 2 + 3
        verify(cartItemRepository, times(1)).save(cartItem);
    }
    
    @Test
    void testAddToCart_InsufficientStock() {
        product.setStock(1);
        when(productRepository.findById(1L)).thenReturn(Optional.of(product));
        
        assertThrows(InsufficientStockException.class, () -> {
            cartService.addToCart(user, 1L, 5);
        });
    }
    
    @Test
    void testAddToCart_ProductNotFound() {
        when(productRepository.findById(999L)).thenReturn(Optional.empty());
        
        assertThrows(ResourceNotFoundException.class, () -> {
            cartService.addToCart(user, 999L, 1);
        });
    }
    
    @Test
    void testGetCartItems() {
        when(cartItemRepository.findByUserId(1L)).thenReturn(Arrays.asList(cartItem));
        
        List<CartItem> result = cartService.getCartItems(user);
        
        assertEquals(1, result.size());
        assertEquals(1L, result.get(0).getId());
    }
    
    @Test
    void testGetCartTotal() {
        CartItem item2 = new CartItem();
        Product product2 = new Product();
        product2.setPrice(new BigDecimal("300.00"));
        item2.setProduct(product2);
        item2.setQuantity(1);
        
        when(cartItemRepository.findByUserId(1L))
            .thenReturn(Arrays.asList(cartItem, item2));
        
        BigDecimal total = cartService.getCartTotal(user);
        
        // 500 * 2 + 300 * 1 = 1300
        assertEquals(new BigDecimal("1300.00"), total);
    }
    
    @Test
    void testGetCartItemCount() {
        CartItem item2 = new CartItem();
        item2.setQuantity(3);
        
        when(cartItemRepository.findByUserId(1L))
            .thenReturn(Arrays.asList(cartItem, item2));
        
        int count = cartService.getCartItemCount(user);
        
        assertEquals(5, count); // 2 + 3
    }
    
    @Test
    void testUpdateCartItem_Success() {
        when(cartItemRepository.findById(1L)).thenReturn(Optional.of(cartItem));
        when(cartItemRepository.save(any(CartItem.class))).thenReturn(cartItem);
        
        cartService.updateCartItem(1L, 5);
        
        assertEquals(5, cartItem.getQuantity());
        verify(cartItemRepository, times(1)).save(cartItem);
    }
    
    @Test
    void testUpdateCartItem_InsufficientStock() {
        product.setStock(3);
        when(cartItemRepository.findById(1L)).thenReturn(Optional.of(cartItem));
        
        assertThrows(InsufficientStockException.class, () -> {
            cartService.updateCartItem(1L, 5);
        });
    }
    
    @Test
    void testRemoveFromCart() {
        when(cartItemRepository.findById(1L)).thenReturn(Optional.of(cartItem));
        doNothing().when(cartItemRepository).delete(cartItem);
        
        cartService.removeFromCart(1L);
        
        verify(cartItemRepository, times(1)).delete(cartItem);
    }
    
    @Test
    void testClearCart() {
        doNothing().when(cartItemRepository).deleteByUserId(1L);
        
        cartService.clearCart(user);
        
        verify(cartItemRepository, times(1)).deleteByUserId(1L);
    }
}
