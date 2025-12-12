package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.CartItem;
import com.empresa.miproyecto.model.User;
import java.math.BigDecimal;
import java.util.List;

public interface CartService {
    List<CartItem> getCartItems(User user);
    CartItem addToCart(User user, Long productId, Integer quantity);
    CartItem updateCartItem(Long cartItemId, Integer quantity);
    void removeFromCart(Long cartItemId);
    void clearCart(User user);
    BigDecimal getCartTotal(User user);
    int getCartItemCount(User user);
}
