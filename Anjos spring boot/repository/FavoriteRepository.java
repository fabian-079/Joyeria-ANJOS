package com.empresa.miproyecto.repository;

import com.empresa.miproyecto.model.Favorite;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface FavoriteRepository extends JpaRepository<Favorite, Long> {
    List<Favorite> findByUserId(Long userId);
    Optional<Favorite> findByUserIdAndProductId(Long userId, Long productId);
    Optional<Favorite> findByUserIdAndCustomizationId(Long userId, Long customizationId);
    boolean existsByUserIdAndProductId(Long userId, Long productId);
    boolean existsByUserIdAndCustomizationId(Long userId, Long customizationId);
}
