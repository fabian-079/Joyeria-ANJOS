package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Customization;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.CustomizationRepository;
import com.empresa.miproyecto.service.CustomizationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class CustomizationServiceImpl implements CustomizationService {
    
    @Autowired
    private CustomizationRepository customizationRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Customization> findAll() {
        try {
            // Usar consulta con JOIN FETCH para cargar relaciones
            List<Customization> customizations = customizationRepository.findAllWithRelations();
            if (customizations != null) {
                // Asegurar que los roles estén cargados
                customizations.forEach(customization -> {
                    if (customization.getUser() != null && customization.getUser().getRoles() != null) {
                        customization.getUser().getRoles().size();
                    }
                });
            }
            return customizations != null ? customizations : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de personalizaciones: " + e.getMessage());
            e.printStackTrace();
            // Fallback
            try {
                List<Customization> customizations = customizationRepository.findAll();
                if (customizations != null) {
                    customizations.forEach(customization -> {
                        if (customization.getUser() != null) {
                            customization.getUser().getName();
                            if (customization.getUser().getRoles() != null) {
                                customization.getUser().getRoles().size();
                            }
                        }
                    });
                }
                return customizations != null ? customizations : java.util.Collections.emptyList();
            } catch (Exception ex) {
                return java.util.Collections.emptyList();
            }
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Customization> findByUser(User user) {
        try {
            if (user == null || user.getId() == null) {
                return java.util.Collections.emptyList();
            }
            // Usar consulta con JOIN FETCH
            List<Customization> customizations = customizationRepository.findByUserIdWithRelations(user.getId());
            return customizations != null ? customizations : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findByUser de personalizaciones: " + e.getMessage());
            e.printStackTrace();
            // Fallback
            try {
                List<Customization> customizations = customizationRepository.findByUserId(user.getId());
                if (customizations != null) {
                    customizations.forEach(customization -> {
                        if (customization.getUser() != null) {
                            customization.getUser().getName();
                        }
                    });
                }
                return customizations != null ? customizations : java.util.Collections.emptyList();
            } catch (Exception ex) {
                return java.util.Collections.emptyList();
            }
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Customization> findById(Long id) {
        try {
            Optional<Customization> customization = customizationRepository.findById(id);
            if (customization.isPresent()) {
                Customization c = customization.get();
                // Forzar carga de relaciones
                if (c.getUser() != null) {
                    c.getUser().getName();
                    if (c.getUser().getRoles() != null) {
                        c.getUser().getRoles().size();
                    }
                }
            }
            return customization;
        } catch (Exception e) {
            System.err.println("Error en findById de personalizaciones: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    public Customization create(Customization customization) {
        return customizationRepository.save(customization);
    }
    
    @Override
    public Customization update(Long id, Customization customization) {
        Customization existing = customizationRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Personalización no encontrada con id: " + id));
        
        existing.setJewelryType(customization.getJewelryType());
        existing.setDesign(customization.getDesign());
        existing.setStones(customization.getStones());
        existing.setFinish(customization.getFinish());
        existing.setColor(customization.getColor());
        existing.setMaterial(customization.getMaterial());
        existing.setEngraving(customization.getEngraving());
        existing.setSpecialInstructions(customization.getSpecialInstructions());
        existing.setEstimatedPrice(customization.getEstimatedPrice());
        existing.setStatus(customization.getStatus());
        existing.setAdminNotes(customization.getAdminNotes());
        
        return customizationRepository.save(existing);
    }
    
    @Override
    public void delete(Long id) {
        Customization customization = customizationRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Personalización no encontrada con id: " + id));
        customizationRepository.delete(customization);
    }
}
