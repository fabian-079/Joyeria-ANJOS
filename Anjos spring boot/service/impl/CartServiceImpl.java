package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.InsufficientStockException;
import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.CartItem;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.CartItemRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.service.CartService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class CartServiceImpl implements CartService {
    
    @Autowired
    private CartItemRepository cartItemRepository;
    
    @Autowired
    private ProductRepository productRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<CartItem> getCartItems(User user) {
        try {
            if (user == null || user.getId() == null) {
                return java.util.Collections.emptyList();
            }
            List<CartItem> items = cartItemRepository.findByUserId(user.getId());
            if (items != null) {
                // Forzar carga de relaciones
                items.forEach(item -> {
                    if (item.getProduct() != null) {
                        item.getProduct().getName();
                        if (item.getProduct().getCategory() != null) {
                            item.getProduct().getCategory().getName();
                        }
                    }
                    if (item.getUser() != null) {
                        item.getUser().getName();
                    }
                });
            }
            return items != null ? items : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en getCartItems: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    public CartItem addToCart(User user, Long productId, Integer quantity) {
        Product product = productRepository.findById(productId)
            .orElseThrow(() -> new ResourceNotFoundException("Producto no encontrado con id: " + productId));
        
        if (product.getStock() < quantity) {
            throw new InsufficientStockException(product.getName(), quantity, product.getStock());
        }
        
        Optional<CartItem> existingItem = cartItemRepository.findByUserIdAndProductId(user.getId(), productId);
        
        if (existingItem.isPresent()) {
            CartItem item = existingItem.get();
            item.setQuantity(item.getQuantity() + quantity);
            return cartItemRepository.save(item);
        } else {
            CartItem cartItem = new CartItem();
            cartItem.setUser(user);
            cartItem.setProduct(product);
            cartItem.setQuantity(quantity);
            return cartItemRepository.save(cartItem);
        }
    }
    
    @Override
    public CartItem updateCartItem(Long cartItemId, Integer quantity) {
        CartItem cartItem = cartItemRepository.findById(cartItemId)
            .orElseThrow(() -> new ResourceNotFoundException("Item del carrito no encontrado con id: " + cartItemId));
        
        Product product = cartItem.getProduct();
        if (product.getStock() < quantity) {
            throw new InsufficientStockException(product.getName(), quantity, product.getStock());
        }
        
        cartItem.setQuantity(quantity);
        return cartItemRepository.save(cartItem);
    }
    
    @Override
    public void removeFromCart(Long cartItemId) {
        CartItem cartItem = cartItemRepository.findById(cartItemId)
            .orElseThrow(() -> new ResourceNotFoundException("Item del carrito no encontrado con id: " + cartItemId));
        cartItemRepository.delete(cartItem);
    }
    
    @Override
    public void clearCart(User user) {
        cartItemRepository.deleteByUserId(user.getId());
    }
    
    @Override
    @Transactional(readOnly = true)
    public BigDecimal getCartTotal(User user) {
        try {
            if (user == null || user.getId() == null) {
                return BigDecimal.ZERO;
            }
            List<CartItem> items = cartItemRepository.findByUserId(user.getId());
            if (items == null || items.isEmpty()) {
                return BigDecimal.ZERO;
            }
            // Forzar carga de relaciones antes de calcular
            items.forEach(item -> {
                if (item.getProduct() != null) {
                    item.getProduct().getPrice();
                }
            });
            return items.stream()
                .filter(item -> item != null && item.getSubtotal() != null)
                .map(CartItem::getSubtotal)
                .reduce(BigDecimal.ZERO, BigDecimal::add);
        } catch (Exception e) {
            System.err.println("Error en getCartTotal: " + e.getMessage());
            e.printStackTrace();
            return BigDecimal.ZERO;
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public int getCartItemCount(User user) {
        try {
            if (user == null || user.getId() == null) {
                return 0;
            }
            List<CartItem> items = cartItemRepository.findByUserId(user.getId());
            if (items == null) {
                return 0;
            }
            return items.stream()
                .filter(item -> item != null)
                .mapToInt(CartItem::getQuantity)
                .sum();
        } catch (Exception e) {
            System.err.println("Error en getCartItemCount: " + e.getMessage());
            e.printStackTrace();
            return 0;
        }
    }
}
