package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Favorite;
import com.empresa.miproyecto.model.User;
import java.util.List;

public interface FavoriteService {
    List<Favorite> getUserFavorites(User user);
    Favorite toggleProductFavorite(User user, Long productId);
    Favorite toggleCustomizationFavorite(User user, Long customizationId);
    void removeFavorite(Long favoriteId);
    boolean isProductFavorite(User user, Long productId);
    boolean isCustomizationFavorite(User user, Long customizationId);
}
