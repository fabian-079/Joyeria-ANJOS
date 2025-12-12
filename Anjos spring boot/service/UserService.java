package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.model.Role;
import java.util.List;
import java.util.Optional;

public interface UserService {
    List<User> findAll();
    Optional<User> findById(Long id);
    Optional<User> findByEmail(String email);
    User save(User user);
    User update(Long id, User user);
    void delete(Long id);
    boolean existsByEmail(String email);
    User assignRole(User user, Role role);
    User removeRole(User user, Role role);
}
