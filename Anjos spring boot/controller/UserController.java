package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.RoleService;
import com.empresa.miproyecto.service.UserService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;
import java.util.Set;

@Controller
@RequestMapping("/users")
@PreAuthorize("hasRole('ADMIN')")
public class UserController {
    
    @Autowired
    private UserService userService;
    
    @Autowired
    private RoleService roleService;
    
    @Autowired
    private PasswordEncoder passwordEncoder;
    
    @GetMapping
    public String index(Model model) {
        try {
            List<User> users = userService.findAll();
            model.addAttribute("users", users != null ? users : java.util.Collections.emptyList());
            // Cargar roles para los modales
            try {
                model.addAttribute("roles", roleService.findAll());
            } catch (Exception e) {
                model.addAttribute("roles", java.util.Collections.emptyList());
            }
        } catch (Exception e) {
            System.err.println("Error en index de usuarios: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("users", java.util.Collections.emptyList());
            model.addAttribute("roles", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar los usuarios");
        }
        return "users/index";
    }
    
    @GetMapping("/create")
    public String createForm(Model model) {
        try {
            model.addAttribute("user", new User());
            model.addAttribute("roles", roleService.findAll());
        } catch (Exception e) {
            System.err.println("Error en createForm de usuarios: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("user", new User());
            model.addAttribute("roles", java.util.Collections.emptyList());
        }
        return "users/create";
    }
    
    @PostMapping
    public String create(@Valid @ModelAttribute User user,
                        @RequestParam(required = false) List<Long> roleIds,
                        BindingResult result,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        if (result.hasErrors()) {
            model.addAttribute("roles", roleService.findAll());
            return "users/create";
        }
        
        try {
            // Encriptar contraseña
            if (user.getPassword() != null && !user.getPassword().isEmpty()) {
                user.setPassword(passwordEncoder.encode(user.getPassword()));
            }
            
            User savedUser = userService.save(user);
            
            // Asignar roles
            if (roleIds != null && !roleIds.isEmpty()) {
                for (Long roleId : roleIds) {
                    Role role = roleService.findById(roleId)
                        .orElse(null);
                    if (role != null) {
                        userService.assignRole(savedUser, role);
                    }
                }
            }
            
            redirectAttributes.addFlashAttribute("success", "Usuario creado exitosamente");
            return "redirect:/users";
        } catch (Exception e) {
            System.err.println("Error al crear usuario: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al crear usuario: " + e.getMessage());
            return "redirect:/users";
        }
    }
    
    @GetMapping("/{id}/edit")
    public String editForm(@PathVariable Long id, Model model, RedirectAttributes redirectAttributes) {
        try {
            User user = userService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Usuario no encontrado"));
            
            // No incluir la contraseña en el modelo
            user.setPassword("");
            
            model.addAttribute("user", user);
            model.addAttribute("roles", roleService.findAll());
            model.addAttribute("userRoles", user.getRoles());
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/users";
        } catch (Exception e) {
            System.err.println("Error en editForm de usuarios: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar el usuario");
            return "redirect:/users";
        }
        return "users/edit";
    }
    
    @PostMapping("/{id}")
    public String update(@PathVariable Long id,
                        @ModelAttribute User user,
                        @RequestParam(required = false) List<Long> roleIds,
                        @RequestParam(required = false) String newPassword,
                        @RequestParam(required = false) Boolean isActive,
                        BindingResult result,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        try {
            User existingUser = userService.findById(id)
                .orElseThrow(() -> new com.empresa.miproyecto.exception.ResourceNotFoundException("Usuario no encontrado"));
            
            // Actualizar campos
            if (user.getName() != null && !user.getName().trim().isEmpty()) {
                existingUser.setName(user.getName());
            }
            if (user.getEmail() != null && !user.getEmail().trim().isEmpty()) {
                existingUser.setEmail(user.getEmail());
            }
            if (user.getPhone() != null) {
                existingUser.setPhone(user.getPhone());
            }
            if (user.getAddress() != null) {
                existingUser.setAddress(user.getAddress());
            }
            if (isActive != null) {
                existingUser.setIsActive(isActive);
            } else if (user.getIsActive() != null) {
                existingUser.setIsActive(user.getIsActive());
            }
            
            // Actualizar contraseña si se proporciona
            if (newPassword != null && !newPassword.trim().isEmpty()) {
                existingUser.setPassword(passwordEncoder.encode(newPassword));
            }
            
            userService.update(id, existingUser);
            
            // Actualizar roles
            if (roleIds != null) {
                // Remover todos los roles actuales
                Set<Role> currentRoles = new java.util.HashSet<>(existingUser.getRoles());
                for (Role role : currentRoles) {
                    userService.removeRole(existingUser, role);
                }
                
                // Recargar usuario para obtener la versión actualizada
                existingUser = userService.findById(id).orElse(existingUser);
                
                // Asignar nuevos roles
                for (Long roleId : roleIds) {
                    Role role = roleService.findById(roleId).orElse(null);
                    if (role != null) {
                        userService.assignRole(existingUser, role);
                    }
                }
            }
            
            redirectAttributes.addFlashAttribute("success", "Usuario actualizado exitosamente");
            return "redirect:/users";
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/users";
        } catch (Exception e) {
            System.err.println("Error al actualizar usuario: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar usuario: " + e.getMessage());
            try {
                User existingUser = userService.findById(id).orElse(null);
                if (existingUser != null) {
                    existingUser.setPassword("");
                    model.addAttribute("user", existingUser);
                    model.addAttribute("roles", roleService.findAll());
                    model.addAttribute("userRoles", existingUser.getRoles());
                }
            } catch (Exception ex) {
                // Ignorar
            }
            return "users/edit";
        }
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        try {
            userService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Usuario eliminado exitosamente");
        } catch (com.empresa.miproyecto.exception.ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar usuario: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar el usuario: " + e.getMessage());
        }
        return "redirect:/users";
    }
}

