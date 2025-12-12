package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.RoleRepository;
import com.empresa.miproyecto.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import org.springframework.validation.BindingResult;
import jakarta.validation.Valid;

import java.util.HashSet;
import java.util.Optional;
import java.util.Set;

@Controller
public class AuthController {
    
    @Autowired
    private UserService userService;
    
    @Autowired
    private RoleRepository roleRepository;
    
    @Autowired
    private PasswordEncoder passwordEncoder;
    
    @GetMapping("/login")
    public String login() {
        return "auth/login";
    }
    
    @GetMapping("/register")
    public String registerForm(Model model) {
        model.addAttribute("user", new User());
        return "auth/register";
    }
    
    @PostMapping("/register")
    public String register(@Valid @ModelAttribute User user,
                          BindingResult result,
                          RedirectAttributes redirectAttributes) {
        if (result.hasErrors()) {
            return "auth/register";
        }
        
        if (userService.existsByEmail(user.getEmail())) {
            redirectAttributes.addFlashAttribute("error", "El email ya está registrado");
            return "redirect:/register";
        }
        
        if (user.getPassword() == null || user.getPassword().length() < 6) {
            redirectAttributes.addFlashAttribute("error", "La contraseña debe tener al menos 6 caracteres");
            return "redirect:/register";
        }
        
        user.setPassword(passwordEncoder.encode(user.getPassword()));
        user.setIsActive(true);
        
        // Asignar rol de cliente por defecto
        Optional<Role> clienteRole = roleRepository.findByName("cliente");
        if (clienteRole.isPresent()) {
            Set<Role> roles = new HashSet<>();
            roles.add(clienteRole.get());
            user.setRoles(roles);
        }
        
        try {
            userService.save(user);
            redirectAttributes.addFlashAttribute("success", "Registro exitoso. Por favor inicia sesión.");
            return "redirect:/login";
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", "Error al registrar usuario: " + e.getMessage());
            return "redirect:/register";
        }
    }
}
