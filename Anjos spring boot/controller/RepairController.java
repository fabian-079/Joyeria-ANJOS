package com.empresa.miproyecto.controller;

import com.empresa.miproyecto.model.Repair;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.service.RepairService;
import com.empresa.miproyecto.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;
import org.springframework.validation.BindingResult;
import jakarta.validation.Valid;
import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.util.SecurityUtils;

import java.math.BigDecimal;
import java.util.List;

@Controller
@RequestMapping("/reparaciones")
public class RepairController {
    
    @Autowired
    private RepairService repairService;
    
    @Autowired
    private UserService userService;
    
    @GetMapping
    public String index(@AuthenticationPrincipal User user, Model model) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            List<Repair> repairs = java.util.Collections.emptyList();
            if (SecurityUtils.isAdmin(user)) {
                try {
                    repairs = repairService.findAll();
                    if (repairs == null) {
                        repairs = java.util.Collections.emptyList();
                    }
                } catch (Exception e) {
                    System.err.println("Error al obtener todas las reparaciones: " + e.getMessage());
                    e.printStackTrace();
                    repairs = java.util.Collections.emptyList();
                }
                // Cargar técnicos para el modal de edición
                try {
                    List<User> technicians = userService.findAll();
                    model.addAttribute("technicians", technicians != null ? technicians : java.util.Collections.emptyList());
                } catch (Exception e) {
                    System.err.println("Error al cargar técnicos: " + e.getMessage());
                    model.addAttribute("technicians", java.util.Collections.emptyList());
                }
            } else {
                try {
                    repairs = repairService.findByUser(user);
                    if (repairs == null) {
                        repairs = java.util.Collections.emptyList();
                    }
                } catch (Exception e) {
                    System.err.println("Error al obtener reparaciones del usuario: " + e.getMessage());
                    e.printStackTrace();
                    repairs = java.util.Collections.emptyList();
                }
            }
            
            model.addAttribute("repairs", repairs);
        } catch (Exception e) {
            System.err.println("Error en index de reparaciones: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("repairs", java.util.Collections.emptyList());
            model.addAttribute("technicians", java.util.Collections.emptyList());
            model.addAttribute("error", "Error al cargar las reparaciones");
        }
        return "reparaciones/index";
    }
    
    @GetMapping("/create")
    public String createForm(Model model) {
        model.addAttribute("repair", new Repair());
        return "reparaciones/create";
    }
    
    @PostMapping
    public String create(@Valid @ModelAttribute Repair repair,
                        BindingResult result,
                        @AuthenticationPrincipal User user,
                        Model model,
                        RedirectAttributes redirectAttributes) {
        if (user == null) {
            redirectAttributes.addFlashAttribute("error", "Debes iniciar sesión");
            return "redirect:/login";
        }
        
        if (result.hasErrors()) {
            model.addAttribute("repair", repair);
            return "reparaciones/create";
        }
        
        try {
            repair.setUser(user);
            repairService.create(repair);
            redirectAttributes.addFlashAttribute("success", "Solicitud de reparación creada");
        } catch (Exception e) {
            System.err.println("Error al crear reparación: " + e.getMessage());
            e.printStackTrace();
            model.addAttribute("repair", repair);
            model.addAttribute("error", "Error al crear la reparación: " + e.getMessage());
            return "reparaciones/create";
        }
        return "redirect:/reparaciones";
    }
    
    @GetMapping("/{id}")
    public String show(@PathVariable Long id, 
                      @AuthenticationPrincipal User user,
                      Model model,
                      RedirectAttributes redirectAttributes) {
        if (user == null) {
            return "redirect:/login";
        }
        
        try {
            Repair repair = repairService.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada"));
            
            if (repair.getUser() == null || (!SecurityUtils.isAdmin(user) && !repair.getUser().getId().equals(user.getId()))) {
                redirectAttributes.addFlashAttribute("error", "No tienes acceso a esta reparación");
                return "redirect:/reparaciones";
            }
            
            model.addAttribute("repair", repair);
            if (SecurityUtils.isAdmin(user)) {
                try {
                    List<User> technicians = userService.findAll();
                    model.addAttribute("technicians", technicians != null ? technicians : java.util.Collections.emptyList());
                } catch (Exception e) {
                    model.addAttribute("technicians", java.util.Collections.emptyList());
                }
            }
        } catch (ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/reparaciones";
        } catch (Exception e) {
            System.err.println("Error al mostrar reparación: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la reparación");
            return "redirect:/reparaciones";
        }
        return "reparaciones/show";
    }
    
    @GetMapping("/{id}/edit")
    public String editForm(@PathVariable Long id, 
                          @AuthenticationPrincipal User user,
                          Model model,
                          RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para editar reparaciones");
            return "redirect:/reparaciones";
        }
        
        try {
            Repair repair = repairService.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada"));
            model.addAttribute("repair", repair);
        } catch (ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            return "redirect:/reparaciones";
        } catch (Exception e) {
            System.err.println("Error en editForm de reparaciones: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al cargar la reparación");
            return "redirect:/reparaciones";
        }
        return "reparaciones/edit";
    }
    
    @PostMapping("/{id}")
    public String update(@PathVariable Long id, 
                        @ModelAttribute Repair repair,
                        @RequestParam(required = false) Long assignedTechnicianId,
                        @RequestParam(required = false) String status,
                        @RequestParam(required = false) BigDecimal estimatedCost,
                        @RequestParam(required = false) String technicianNotes,
                        @RequestParam(required = false) String notes,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para actualizar reparaciones");
            return "redirect:/reparaciones";
        }
        
        try {
            Repair existingRepair = repairService.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada"));
            
            // Actualizar campos
            existingRepair.setCustomerName(repair.getCustomerName());
            existingRepair.setPhone(repair.getPhone());
            existingRepair.setDescription(repair.getDescription());
            
            if (status != null) {
                existingRepair.setStatus(Repair.RepairStatus.valueOf(status));
            }
            
            if (assignedTechnicianId != null) {
                User technician = userService.findById(assignedTechnicianId).orElse(null);
                existingRepair.setAssignedTechnician(technician);
            }
            
            if (estimatedCost != null) {
                existingRepair.setEstimatedCost(estimatedCost);
            }
            
            if (technicianNotes != null) {
                existingRepair.setTechnicianNotes(technicianNotes);
            }
            
            if (notes != null) {
                existingRepair.setNotes(notes);
            }
            
            repairService.update(id, existingRepair);
            redirectAttributes.addFlashAttribute("success", "Reparación actualizada");
        } catch (ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al actualizar reparación: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al actualizar la reparación: " + e.getMessage());
        }
        return "redirect:/reparaciones";
    }
    
    @PostMapping("/{id}/asignar")
    public String assignTechnician(@PathVariable Long id,
                                  @RequestParam Long technicianId,
                                  @AuthenticationPrincipal User user,
                                  RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para asignar técnicos");
            return "redirect:/reparaciones";
        }
        
        try {
            repairService.assignTechnician(id, technicianId);
            redirectAttributes.addFlashAttribute("success", "Técnico asignado");
        } catch (Exception e) {
            System.err.println("Error al asignar técnico: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al asignar el técnico: " + e.getMessage());
        }
        return "redirect:/reparaciones/" + id;
    }
    
    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id,
                        @AuthenticationPrincipal User user,
                        RedirectAttributes redirectAttributes) {
        if (user == null || !SecurityUtils.isAdmin(user)) {
            redirectAttributes.addFlashAttribute("error", "No tienes permiso para eliminar reparaciones");
            return "redirect:/reparaciones";
        }
        
        try {
            repairService.delete(id);
            redirectAttributes.addFlashAttribute("success", "Reparación eliminada exitosamente");
        } catch (ResourceNotFoundException e) {
            redirectAttributes.addFlashAttribute("error", e.getMessage());
        } catch (Exception e) {
            System.err.println("Error al eliminar reparación: " + e.getMessage());
            e.printStackTrace();
            redirectAttributes.addFlashAttribute("error", "Error al eliminar la reparación: " + e.getMessage());
        }
        return "redirect:/reparaciones";
    }
}
