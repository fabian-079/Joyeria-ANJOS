package com.empresa.miproyecto.model.dto;

import com.empresa.miproyecto.model.Order;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class OrderDTO {
    
    private Long id;
    private String orderNumber;
    private Long userId;
    private String userName;
    private BigDecimal subtotal;
    private BigDecimal tax;
    private BigDecimal total;
    private Order.OrderStatus status;
    private String shippingAddress;
    private String billingAddress;
    private String phone;
    private Order.PaymentMethod paymentMethod;
    private String notes;
    private LocalDateTime createdAt;
    private List<OrderItemDTO> items = new ArrayList<>();
    
    // Constructors
    public OrderDTO() {}
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getOrderNumber() { return orderNumber; }
    public void setOrderNumber(String orderNumber) { this.orderNumber = orderNumber; }
    public Long getUserId() { return userId; }
    public void setUserId(Long userId) { this.userId = userId; }
    public String getUserName() { return userName; }
    public void setUserName(String userName) { this.userName = userName; }
    public BigDecimal getSubtotal() { return subtotal; }
    public void setSubtotal(BigDecimal subtotal) { this.subtotal = subtotal; }
    public BigDecimal getTax() { return tax; }
    public void setTax(BigDecimal tax) { this.tax = tax; }
    public BigDecimal getTotal() { return total; }
    public void setTotal(BigDecimal total) { this.total = total; }
    public Order.OrderStatus getStatus() { return status; }
    public void setStatus(Order.OrderStatus status) { this.status = status; }
    public String getShippingAddress() { return shippingAddress; }
    public void setShippingAddress(String shippingAddress) { this.shippingAddress = shippingAddress; }
    public String getBillingAddress() { return billingAddress; }
    public void setBillingAddress(String billingAddress) { this.billingAddress = billingAddress; }
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    public Order.PaymentMethod getPaymentMethod() { return paymentMethod; }
    public void setPaymentMethod(Order.PaymentMethod paymentMethod) { this.paymentMethod = paymentMethod; }
    public String getNotes() { return notes; }
    public void setNotes(String notes) { this.notes = notes; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public List<OrderItemDTO> getItems() { return items; }
    public void setItems(List<OrderItemDTO> items) { this.items = items; }
}
