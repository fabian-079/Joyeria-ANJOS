package com.empresa.miproyecto.service;

import com.empresa.miproyecto.exception.BadRequestException;
import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.*;
import com.empresa.miproyecto.repository.*;
import com.empresa.miproyecto.service.impl.OrderServiceImpl;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Optional;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class OrderServiceTest {
    
    @Mock
    private OrderRepository orderRepository;
    
    @Mock
    private OrderItemRepository orderItemRepository;
    
    @Mock
    private CartItemRepository cartItemRepository;
    
    @Mock
    private ProductRepository productRepository;
    
    @Mock
    private CartService cartService;
    
    @InjectMocks
    private OrderServiceImpl orderService;
    
    private User user;
    private Product product;
    private CartItem cartItem;
    private Order order;
    
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
        
        order = new Order();
        order.setId(1L);
        order.setOrderNumber("ORD-123");
        order.setUser(user);
        order.setSubtotal(new BigDecimal("1000.00"));
        order.setTax(new BigDecimal("190.00"));
        order.setTotal(new BigDecimal("1190.00"));
    }
    
    @Test
    void testCreateOrder_Success() {
        List<CartItem> cartItems = Arrays.asList(cartItem);
        
        when(cartService.getCartItems(user)).thenReturn(cartItems);
        when(cartService.getCartTotal(user)).thenReturn(new BigDecimal("1000.00"));
        when(orderRepository.save(any(Order.class))).thenReturn(order);
        when(orderItemRepository.save(any(OrderItem.class))).thenReturn(new OrderItem());
        when(productRepository.save(any(Product.class))).thenReturn(product);
        doNothing().when(cartService).clearCart(user);
        
        Order result = orderService.createOrder(user, "Calle 123", "Calle 123", 
            "123456789", "TARJETA", "Notas");
        
        assertNotNull(result);
        assertEquals("ORD-123", result.getOrderNumber());
        verify(orderRepository, times(1)).save(any(Order.class));
        verify(orderItemRepository, times(1)).save(any(OrderItem.class));
        verify(cartService, times(1)).clearCart(user);
    }
    
    @Test
    void testCreateOrder_EmptyCart() {
        when(cartService.getCartItems(user)).thenReturn(new ArrayList<>());
        
        assertThrows(BadRequestException.class, () -> {
            orderService.createOrder(user, "Calle 123", "Calle 123", 
                "123456789", "TARJETA", "Notas");
        });
    }
    
    @Test
    void testCreateOrder_InsufficientStock() {
        product.setStock(1);
        List<CartItem> cartItems = Arrays.asList(cartItem);
        
        when(cartService.getCartItems(user)).thenReturn(cartItems);
        when(cartService.getCartTotal(user)).thenReturn(new BigDecimal("1000.00"));
        when(orderRepository.save(any(Order.class))).thenReturn(order);
        
        assertThrows(BadRequestException.class, () -> {
            orderService.createOrder(user, "Calle 123", "Calle 123", 
                "123456789", "TARJETA", "Notas");
        });
    }
    
    @Test
    void testFindByUser() {
        when(orderRepository.findByUserId(1L)).thenReturn(Arrays.asList(order));
        
        List<Order> result = orderService.findByUser(user);
        
        assertEquals(1, result.size());
        assertEquals("ORD-123", result.get(0).getOrderNumber());
    }
    
    @Test
    void testFindById_Success() {
        when(orderRepository.findById(1L)).thenReturn(Optional.of(order));
        
        Optional<Order> result = orderService.findById(1L);
        
        assertTrue(result.isPresent());
        assertEquals("ORD-123", result.get().getOrderNumber());
    }
    
    @Test
    void testUpdateOrderStatus() {
        when(orderRepository.findById(1L)).thenReturn(Optional.of(order));
        when(orderRepository.save(any(Order.class))).thenReturn(order);
        
        Order result = orderService.updateOrderStatus(1L, Order.OrderStatus.PROCESSING);
        
        assertEquals(Order.OrderStatus.PROCESSING, result.getStatus());
        verify(orderRepository, times(1)).save(order);
    }
    
    @Test
    void testUpdateOrderStatus_NotFound() {
        when(orderRepository.findById(999L)).thenReturn(Optional.empty());
        
        assertThrows(ResourceNotFoundException.class, () -> {
            orderService.updateOrderStatus(999L, Order.OrderStatus.PROCESSING);
        });
    }
    
    @Test
    void testDelete() {
        when(orderRepository.findById(1L)).thenReturn(Optional.of(order));
        doNothing().when(orderRepository).delete(order);
        
        orderService.delete(1L);
        
        verify(orderRepository, times(1)).delete(order);
    }
}
