package com.empresa.miproyecto.repository;

import com.empresa.miproyecto.model.Customization;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface CustomizationRepository extends JpaRepository<Customization, Long> {
    List<Customization> findByUserId(Long userId);
    
    @Query("SELECT DISTINCT c FROM Customization c LEFT JOIN FETCH c.user")
    List<Customization> findAllWithRelations();
    
    @Query("SELECT DISTINCT c FROM Customization c LEFT JOIN FETCH c.user WHERE c.user.id = :userId")
    List<Customization> findByUserIdWithRelations(Long userId);
}
