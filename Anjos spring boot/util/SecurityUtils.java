package com.empresa.miproyecto.util;

import com.empresa.miproyecto.model.User;

public class SecurityUtils {
    
    public static boolean isAdmin(User user) {
        if (user == null || user.getRoles() == null || user.getRoles().isEmpty()) {
            return false;
        }
        return user.getRoles().stream()
            .anyMatch(role -> role != null && "admin".equalsIgnoreCase(role.getName()));
    }
}

