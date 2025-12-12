package com.empresa.miproyecto.exception;

public class InsufficientStockException extends RuntimeException {
    private String productName;
    private Integer requestedQuantity;
    private Integer availableStock;
    
    public InsufficientStockException(String productName, Integer requestedQuantity, Integer availableStock) {
        super(String.format("Stock insuficiente para el producto '%s'. Solicitado: %d, Disponible: %d", 
            productName, requestedQuantity, availableStock));
        this.productName = productName;
        this.requestedQuantity = requestedQuantity;
        this.availableStock = availableStock;
    }
    
    public String getProductName() {
        return productName;
    }
    
    public Integer getRequestedQuantity() {
        return requestedQuantity;
    }
    
    public Integer getAvailableStock() {
        return availableStock;
    }
}
