package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Role;
import java.util.List;
import java.util.Optional;

public interface RoleService {
    List<Role> findAll();
    Optional<Role> findById(Long id);
    Optional<Role> findByName(String name);
    Role save(Role role);
    Role update(Long id, Role role);
    void delete(Long id);
    boolean existsByName(String name);
}

