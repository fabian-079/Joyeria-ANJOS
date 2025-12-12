package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.RoleService;
import com.empresa.miproyecto.service.UserService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;
import java.util.Set;

@Controller
@RequestMapping("/admin/roles")
@PreAuthorize("hasRole('ADMIN')")
public class RoleController {
    
    @Autowired
    private RoleService roleService;
    
    @Autowired
    private UserService userService;
    
    @GetMapping
    public String index(Model model) {
        List<Role> roles = roleService.findAll();
        model.addAttribute("roles", roles);
        return "admin/roles/list";
    }
    
    @GetMapping("/create")
    public String createForm(Model model) {
        model.addAttribute("role", new Role());
        return "admin/roles/form";
    }
    
    @PostMapping("/create")
    public String create(@Valid @ModelAttribute Role role, 
                        BindingResult result,
                        RedirectAttributes redirectAttributes) {
        if (result.hasErrors()) {
            return "admin/roles/form";
        }
        
        try {
            roleService.save(role);
            redirectAttributes.addFlashAttribute("success", "Rol creado exitosamente");
            return "redirect:/admin/roles";
        } catch (IllegalArgumentException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/admin/roles/create";
        }
    }
    
    @GetMapping("/{id}/edit")
    public String editForm(@PathVariable Long id, Model model) {
        Role role = roleService.findById(id)
            .orElseThrow(() -> new RuntimeException("Rol no encontrado"));
        model.addAttribute("role", role);
        return "admin/roles/form";
    }
    
    @PostMapping("/{id}/edit")
    public String update(@PathVariable Long id,
                        @Valid @ModelAttribute Role role,
                        BindingResult result,
                        RedirectAttributes redirectAttributes) {
        if (result.hasErrors()) {
            return "admin/roles/form";
        }
        
        try {
            roleService.update(id, role);
            redirectAttributes.addFlashAttribute("success", "Rol actualizado exitosamente");
            return "redirect:/admin/roles";
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/admin/roles/" + id + "/edit";
        }
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        try {
            roleService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Rol eliminado exitosamente");
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/admin/roles";
    }
    
    @GetMapping("/{id}/users")
    public String showUsers(@PathVariable Long id, Model model) {
        Role role = roleService.findById(id)
            .orElseThrow(() -> new RuntimeException("Rol no encontrado"));
        
        List<User> allUsers = userService.findAll();
        Set<User> usersWithRole = role.getUsers();
        
        model.addAttribute("role", role);
        model.addAttribute("allUsers", allUsers);
        model.addAttribute("usersWithRole", usersWithRole);
        
        return "admin/roles/users";
    }
    
    @PostMapping("/{roleId}/assign/{userId}")
    public String assignRole(@PathVariable Long roleId,
                            @PathVariable Long userId,
                            RedirectAttributes redirectAttributes) {
        try {
            Role role = roleService.findById(roleId)
                .orElseThrow(() -> new RuntimeException("Rol no encontrado"));
            User user = userService.findById(userId)
                .orElseThrow(() -> new RuntimeException("Usuario no encontrado"));
            
            userService.assignRole(user, role);
            redirectAttributes.addFlashAttribute("success", 
                "Rol asignado exitosamente a " + user.getName());
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/admin/roles/" + roleId + "/users";
    }
    
    @PostMapping("/{roleId}/remove/{userId}")
    public String removeRole(@PathVariable Long roleId,
                            @PathVariable Long userId,
                            RedirectAttributes redirectAttributes) {
        try {
            Role role = roleService.findById(roleId)
                .orElseThrow(() -> new RuntimeException("Rol no encontrado"));
            User user = userService.findById(userId)
                .orElseThrow(() -> new RuntimeException("Usuario no encontrado"));
            
            userService.removeRole(user, role);
            redirectAttributes.addFlashAttribute("success", 
                "Rol removido exitosamente de " + user.getName());
        } catch (Exception e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        }
        return "redirect:/admin/roles/" + roleId + "/users";
    }
}

