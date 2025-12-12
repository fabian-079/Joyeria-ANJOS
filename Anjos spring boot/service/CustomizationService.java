package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Customization;
import com.empresa.miproyecto.model.User;
import java.util.List;
import java.util.Optional;

public interface CustomizationService {
    List<Customization> findAll();
    List<Customization> findByUser(User user);
    Optional<Customization> findById(Long id);
    Customization create(Customization customization);
    Customization update(Long id, Customization customization);
    void delete(Long id);
}
