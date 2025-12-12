package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.repository.UserRepository;
import com.empresa.miproyecto.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class UserServiceImpl implements UserService {
    
    @Autowired
    private UserRepository userRepository;
    
    @Autowired
    private PasswordEncoder passwordEncoder;
    
    @Override
    @Transactional(readOnly = true)
    public List<User> findAll() {
        try {
            List<User> users = userRepository.findAll();
            if (users != null) {
                // Forzar carga de roles para evitar LazyInitializationException
                users.forEach(user -> {
                    if (user.getRoles() != null) {
                        user.getRoles().size(); // Forzar carga
                    }
                });
            }
            return users != null ? users : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de usuarios: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<User> findById(Long id) {
        try {
            Optional<User> user = userRepository.findById(id);
            if (user.isPresent()) {
                User u = user.get();
                if (u.getRoles() != null) {
                    u.getRoles().size(); // Forzar carga de roles
                }
            }
            return user;
        } catch (Exception e) {
            System.err.println("Error en findById de usuarios: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<User> findByEmail(String email) {
        try {
            Optional<User> user = userRepository.findByEmail(email);
            // Forzar carga de roles si el usuario existe
            if (user.isPresent()) {
                User u = user.get();
                if (u.getRoles() != null) {
                    u.getRoles().size(); // Forzar carga
                }
            }
            return user;
        } catch (Exception e) {
            System.err.println("Error en findByEmail: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    public User save(User user) {
        if (user.getPassword() != null && !user.getPassword().startsWith("$2a$")) {
            user.setPassword(passwordEncoder.encode(user.getPassword()));
        }
        return userRepository.save(user);
    }
    
    @Override
    public User update(Long id, User user) {
        User existingUser = userRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Usuario no encontrado con id: " + id));
        
        existingUser.setName(user.getName());
        existingUser.setEmail(user.getEmail());
        existingUser.setPhone(user.getPhone());
        existingUser.setAddress(user.getAddress());
        existingUser.setIsActive(user.getIsActive());
        
        if (user.getPassword() != null && !user.getPassword().isEmpty() 
            && !user.getPassword().startsWith("$2a$")) {
            existingUser.setPassword(passwordEncoder.encode(user.getPassword()));
        }
        
        return userRepository.save(existingUser);
    }
    
    @Override
    public void delete(Long id) {
        User user = userRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Usuario no encontrado con id: " + id));
        userRepository.delete(user);
    }
    
    @Override
    public boolean existsByEmail(String email) {
        return userRepository.existsByEmail(email);
    }
    
    @Override
    public User assignRole(User user, Role role) {
        user.getRoles().add(role);
        return userRepository.save(user);
    }
    
    @Override
    public User removeRole(User user, Role role) {
        user.getRoles().remove(role);
        return userRepository.save(user);
    }
}
