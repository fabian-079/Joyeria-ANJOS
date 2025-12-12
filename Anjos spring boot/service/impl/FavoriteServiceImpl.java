package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Favorite;
import com.empresa.miproyecto.model.Product;
import com.empresa.miproyecto.model.Customization;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.FavoriteRepository;
import com.empresa.miproyecto.repository.ProductRepository;
import com.empresa.miproyecto.repository.CustomizationRepository;
import com.empresa.miproyecto.service.FavoriteService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class FavoriteServiceImpl implements FavoriteService {
    
    @Autowired
    private FavoriteRepository favoriteRepository;
    
    @Autowired
    private ProductRepository productRepository;
    
    @Autowired
    private CustomizationRepository customizationRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Favorite> getUserFavorites(User user) {
        try {
            if (user == null || user.getId() == null) {
                return java.util.Collections.emptyList();
            }
            List<Favorite> favorites = favoriteRepository.findByUserId(user.getId());
            if (favorites != null) {
                // Forzar carga de relaciones
                favorites.forEach(favorite -> {
                    if (favorite.getProduct() != null) {
                        favorite.getProduct().getName();
                        if (favorite.getProduct().getCategory() != null) {
                            favorite.getProduct().getCategory().getName();
                        }
                    }
                    if (favorite.getCustomization() != null) {
                        favorite.getCustomization().getJewelryType();
                    }
                    if (favorite.getUser() != null) {
                        favorite.getUser().getName();
                    }
                });
            }
            return favorites != null ? favorites : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en getUserFavorites: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    public Favorite toggleProductFavorite(User user, Long productId) {
        Optional<Favorite> existing = favoriteRepository.findByUserIdAndProductId(user.getId(), productId);
        
        if (existing.isPresent()) {
            favoriteRepository.delete(existing.get());
            return null; // Removido
        } else {
            Product product = productRepository.findById(productId)
                .orElseThrow(() -> new ResourceNotFoundException("Producto no encontrado con id: " + productId));
            
            Favorite favorite = new Favorite();
            favorite.setUser(user);
            favorite.setProduct(product);
            return favoriteRepository.save(favorite);
        }
    }
    
    @Override
    public Favorite toggleCustomizationFavorite(User user, Long customizationId) {
        Optional<Favorite> existing = favoriteRepository.findByUserIdAndCustomizationId(user.getId(), customizationId);
        
        if (existing.isPresent()) {
            favoriteRepository.delete(existing.get());
            return null; // Removido
        } else {
            Customization customization = customizationRepository.findById(customizationId)
                .orElseThrow(() -> new ResourceNotFoundException("Personalización no encontrada con id: " + customizationId));
            
            Favorite favorite = new Favorite();
            favorite.setUser(user);
            favorite.setCustomization(customization);
            return favoriteRepository.save(favorite);
        }
    }
    
    @Override
    public void removeFavorite(Long favoriteId) {
        Favorite favorite = favoriteRepository.findById(favoriteId)
            .orElseThrow(() -> new ResourceNotFoundException("Favorito no encontrado con id: " + favoriteId));
        favoriteRepository.delete(favorite);
    }
    
    @Override
    public boolean isProductFavorite(User user, Long productId) {
        return favoriteRepository.existsByUserIdAndProductId(user.getId(), productId);
    }
    
    @Override
    public boolean isCustomizationFavorite(User user, Long customizationId) {
        return favoriteRepository.existsByUserIdAndCustomizationId(user.getId(), customizationId);
    }
}
