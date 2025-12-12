package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Order;
import com.empresa.miproyecto.model.User;
import java.util.List;
import java.util.Optional;

public interface OrderService {
    List<Order> findAll();
    List<Order> findByUser(User user);
    Optional<Order> findById(Long id);
    Optional<Order> findByOrderNumber(String orderNumber);
    Order createOrder(User user, String shippingAddress, String billingAddress, 
                     String phone, String paymentMethod, String notes);
    Order updateOrderStatus(Long orderId, Order.OrderStatus status);
    Order update(Long id, Order order);
    void delete(Long id);
}
