package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.BadRequestException;
import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.*;
import com.empresa.miproyecto.repository.*;
import com.empresa.miproyecto.service.CartService;
import com.empresa.miproyecto.service.OrderService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class OrderServiceImpl implements OrderService {
    
    @Autowired
    private OrderRepository orderRepository;
    
    @Autowired
    private OrderItemRepository orderItemRepository;
    
    @Autowired
    private ProductRepository productRepository;
    
    @Autowired
    private CartService cartService;
    
    @Override
    @Transactional(readOnly = true)
    public List<Order> findAll() {
        try {
            List<Order> orders = orderRepository.findAll();
            if (orders != null) {
                // Forzar carga de relaciones
                orders.forEach(order -> {
                    if (order.getUser() != null) {
                        order.getUser().getName();
                    }
                    if (order.getOrderItems() != null) {
                        order.getOrderItems().size(); // Forzar carga
                        order.getOrderItems().forEach(item -> {
                            if (item.getProduct() != null) {
                                item.getProduct().getName();
                            }
                        });
                    }
                });
            }
            return orders != null ? orders : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de órdenes: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Order> findByUser(User user) {
        try {
            if (user == null || user.getId() == null) {
                return java.util.Collections.emptyList();
            }
            List<Order> orders = orderRepository.findByUserId(user.getId());
            if (orders != null) {
                // Forzar carga de relaciones
                orders.forEach(order -> {
                    if (order.getUser() != null) {
                        order.getUser().getName();
                    }
                    if (order.getOrderItems() != null) {
                        order.getOrderItems().size(); // Forzar carga
                        order.getOrderItems().forEach(item -> {
                            if (item.getProduct() != null) {
                                item.getProduct().getName();
                            }
                        });
                    }
                });
            }
            return orders != null ? orders : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findByUser de órdenes: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Order> findById(Long id) {
        try {
            Optional<Order> order = orderRepository.findById(id);
            if (order.isPresent()) {
                Order o = order.get();
                // Forzar carga de relaciones
                if (o.getUser() != null) {
                    o.getUser().getName();
                }
                if (o.getOrderItems() != null) {
                    o.getOrderItems().size(); // Forzar carga
                    o.getOrderItems().forEach(item -> {
                        if (item.getProduct() != null) {
                            item.getProduct().getName();
                        }
                    });
                }
            }
            return order;
        } catch (Exception e) {
            System.err.println("Error en findById de órdenes: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    public Optional<Order> findByOrderNumber(String orderNumber) {
        return orderRepository.findByOrderNumber(orderNumber);
    }
    
    @Override
    public Order createOrder(User user, String shippingAddress, String billingAddress, 
                             String phone, String paymentMethod, String notes) {
        List<CartItem> cartItems = cartService.getCartItems(user);
        
        if (cartItems.isEmpty()) {
            throw new BadRequestException("El carrito está vacío");
        }
        
        BigDecimal subtotal = cartService.getCartTotal(user);
        BigDecimal tax = subtotal.multiply(new BigDecimal("0.19")); // 19% IVA
        BigDecimal total = subtotal.add(tax);
        
        Order order = new Order();
        order.setUser(user);
        order.setSubtotal(subtotal);
        order.setTax(tax);
        order.setTotal(total);
        order.setShippingAddress(shippingAddress);
        order.setBillingAddress(billingAddress);
        order.setPhone(phone);
        order.setPaymentMethod(Order.PaymentMethod.valueOf(paymentMethod.toUpperCase()));
        order.setNotes(notes);
        order.setStatus(Order.OrderStatus.PENDING);
        
        order = orderRepository.save(order);
        
        // Crear order items y actualizar stock
        for (CartItem cartItem : cartItems) {
            Product product = cartItem.getProduct();
            
            // Verificar stock antes de crear la orden
            if (product.getStock() < cartItem.getQuantity()) {
                throw new BadRequestException("Stock insuficiente para el producto: " + product.getName());
            }
            
            OrderItem orderItem = new OrderItem();
            orderItem.setOrder(order);
            orderItem.setProduct(product);
            orderItem.setQuantity(cartItem.getQuantity());
            orderItem.setPrice(product.getPrice());
            orderItemRepository.save(orderItem);
            
            // Actualizar stock
            product.setStock(product.getStock() - cartItem.getQuantity());
            productRepository.save(product);
        }
        
        // Limpiar carrito
        cartService.clearCart(user);
        
        return order;
    }
    
    @Override
    public Order updateOrderStatus(Long orderId, Order.OrderStatus status) {
        Order order = orderRepository.findById(orderId)
            .orElseThrow(() -> new ResourceNotFoundException("Orden no encontrada con id: " + orderId));
        order.setStatus(status);
        return orderRepository.save(order);
    }
    
    @Override
    public Order update(Long id, Order order) {
        Order existingOrder = orderRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Orden no encontrada con id: " + id));
        
        existingOrder.setStatus(order.getStatus());
        existingOrder.setShippingAddress(order.getShippingAddress());
        existingOrder.setBillingAddress(order.getBillingAddress());
        existingOrder.setPhone(order.getPhone());
        existingOrder.setPaymentMethod(order.getPaymentMethod());
        existingOrder.setNotes(order.getNotes());
        
        return orderRepository.save(existingOrder);
    }
    
    @Override
    public void delete(Long id) {
        Order order = orderRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Orden no encontrada con id: " + id));
        orderRepository.delete(order);
    }
}
