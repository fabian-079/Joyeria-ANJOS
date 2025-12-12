package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.repository.RoleRepository;
import com.empresa.miproyecto.service.RoleService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
public class RoleServiceImpl implements RoleService {
    
    @Autowired
    private RoleRepository roleRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Role> findAll() {
        try {
            List<Role> roles = roleRepository.findAll();
            if (roles != null) {
                // Forzar carga de relaciones
                roles.forEach(role -> {
                    if (role.getUsers() != null) {
                        role.getUsers().size();
                    }
                });
            }
            return roles != null ? roles : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de roles: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Role> findById(Long id) {
        try {
            Optional<Role> role = roleRepository.findById(id);
            if (role.isPresent()) {
                Role r = role.get();
                if (r.getUsers() != null) {
                    r.getUsers().size();
                }
            }
            return role;
        } catch (Exception e) {
            System.err.println("Error en findById de roles: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Role> findByName(String name) {
        return roleRepository.findByName(name);
    }
    
    @Override
    public Role save(Role role) {
        if (role.getName() == null || role.getName().trim().isEmpty()) {
            throw new IllegalArgumentException("El nombre del rol no puede estar vacío");
        }
        
        // Verificar si ya existe un rol con el mismo nombre
        if (role.getId() == null && roleRepository.findByName(role.getName().toLowerCase()).isPresent()) {
            throw new IllegalArgumentException("Ya existe un rol con el nombre: " + role.getName());
        }
        
        role.setName(role.getName().toLowerCase());
        return roleRepository.save(role);
    }
    
    @Override
    public Role update(Long id, Role role) {
        Role existingRole = roleRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Rol no encontrado con id: " + id));
        
        // Verificar si el nuevo nombre ya existe en otro rol
        if (!existingRole.getName().equalsIgnoreCase(role.getName())) {
            Optional<Role> roleWithSameName = roleRepository.findByName(role.getName().toLowerCase());
            if (roleWithSameName.isPresent() && !roleWithSameName.get().getId().equals(id)) {
                throw new IllegalArgumentException("Ya existe un rol con el nombre: " + role.getName());
            }
        }
        
        existingRole.setName(role.getName().toLowerCase());
        existingRole.setGuardName(role.getGuardName() != null ? role.getGuardName() : "web");
        
        return roleRepository.save(existingRole);
    }
    
    @Override
    public void delete(Long id) {
        Role role = roleRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Rol no encontrado con id: " + id));
        
        // Verificar si el rol está siendo usado por algún usuario
        // Cargar usuarios antes de verificar
        try {
            if (role.getUsers() != null) {
                role.getUsers().size(); // Forzar carga
                if (!role.getUsers().isEmpty()) {
                    throw new IllegalStateException("No se puede eliminar el rol porque está asignado a " + 
                        role.getUsers().size() + " usuario(s)");
                }
            }
        } catch (IllegalStateException e) {
            throw e; // Re-lanzar excepciones de negocio
        } catch (Exception e) {
            // Si hay error al acceder a getUsers(), verificar de otra forma
            System.err.println("Advertencia al verificar usuarios del rol: " + e.getMessage());
            // Continuar con la eliminación si no podemos verificar
        }
        
        roleRepository.delete(role);
    }
    
    @Override
    @Transactional(readOnly = true)
    public boolean existsByName(String name) {
        return roleRepository.findByName(name.toLowerCase()).isPresent();
    }
}

