package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Repair;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.RepairRepository;
import com.empresa.miproyecto.repository.UserRepository;
import com.empresa.miproyecto.service.RepairService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class RepairServiceImpl implements RepairService {
    
    @Autowired
    private RepairRepository repairRepository;
    
    @Autowired
    private UserRepository userRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Repair> findAll() {
        try {
            // Usar consulta con JOIN FETCH para cargar relaciones
            List<Repair> repairs = repairRepository.findAllWithRelations();
            if (repairs != null) {
                // Asegurar que los roles estén cargados
                repairs.forEach(repair -> {
                    if (repair.getUser() != null && repair.getUser().getRoles() != null) {
                        repair.getUser().getRoles().size();
                    }
                    if (repair.getAssignedTechnician() != null && repair.getAssignedTechnician().getRoles() != null) {
                        repair.getAssignedTechnician().getRoles().size();
                    }
                });
            }
            return repairs != null ? repairs : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de reparaciones: " + e.getMessage());
            e.printStackTrace();
            // Fallback
            try {
                List<Repair> repairs = repairRepository.findAll();
                if (repairs != null) {
                    repairs.forEach(repair -> {
                        if (repair.getUser() != null) {
                            repair.getUser().getName();
                            if (repair.getUser().getRoles() != null) {
                                repair.getUser().getRoles().size();
                            }
                        }
                        if (repair.getAssignedTechnician() != null) {
                            repair.getAssignedTechnician().getName();
                        }
                    });
                }
                return repairs != null ? repairs : java.util.Collections.emptyList();
            } catch (Exception ex) {
                return java.util.Collections.emptyList();
            }
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Repair> findByUser(User user) {
        try {
            if (user == null || user.getId() == null) {
                return java.util.Collections.emptyList();
            }
            // Usar consulta con JOIN FETCH
            List<Repair> repairs = repairRepository.findByUserIdWithRelations(user.getId());
            return repairs != null ? repairs : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findByUser de reparaciones: " + e.getMessage());
            e.printStackTrace();
            // Fallback
            try {
                List<Repair> repairs = repairRepository.findByUserId(user.getId());
                if (repairs != null) {
                    repairs.forEach(repair -> {
                        if (repair.getUser() != null) {
                            repair.getUser().getName();
                        }
                        if (repair.getAssignedTechnician() != null) {
                            repair.getAssignedTechnician().getName();
                        }
                    });
                }
                return repairs != null ? repairs : java.util.Collections.emptyList();
            } catch (Exception ex) {
                return java.util.Collections.emptyList();
            }
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Repair> findByTechnician(User technician) {
        try {
            if (technician == null || technician.getId() == null) {
                return java.util.Collections.emptyList();
            }
            List<Repair> repairs = repairRepository.findByAssignedTechnicianId(technician.getId());
            if (repairs != null) {
                // Forzar carga de relaciones
                repairs.forEach(repair -> {
                    if (repair.getUser() != null) {
                        repair.getUser().getName();
                    }
                    if (repair.getAssignedTechnician() != null) {
                        repair.getAssignedTechnician().getName();
                    }
                });
            }
            return repairs != null ? repairs : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findByTechnician de reparaciones: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Repair> findById(Long id) {
        try {
            Optional<Repair> repair = repairRepository.findById(id);
            if (repair.isPresent()) {
                Repair r = repair.get();
                // Forzar carga de relaciones
                if (r.getUser() != null) {
                    r.getUser().getName();
                    if (r.getUser().getRoles() != null) {
                        r.getUser().getRoles().size();
                    }
                }
                if (r.getAssignedTechnician() != null) {
                    r.getAssignedTechnician().getName();
                }
            }
            return repair;
        } catch (Exception e) {
            System.err.println("Error en findById de reparaciones: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    public Optional<Repair> findByRepairNumber(String repairNumber) {
        return repairRepository.findByRepairNumber(repairNumber);
    }
    
    @Override
    public Repair create(Repair repair) {
        // Generar número de reparación único si no existe
        if (repair.getRepairNumber() == null || repair.getRepairNumber().isEmpty()) {
            String repairNumber = "REP-" + System.currentTimeMillis();
            // Verificar que no exista
            int attempts = 0;
            while (repairRepository.findByRepairNumber(repairNumber).isPresent() && attempts < 10) {
                repairNumber = "REP-" + System.currentTimeMillis() + "-" + attempts;
                attempts++;
            }
            repair.setRepairNumber(repairNumber);
        }
        
        // Establecer estado por defecto si no existe
        if (repair.getStatus() == null) {
            repair.setStatus(Repair.RepairStatus.PENDING);
        }
        
        return repairRepository.save(repair);
    }
    
    @Override
    public Repair update(Long id, Repair repair) {
        Repair existingRepair = repairRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada con id: " + id));
        
        existingRepair.setCustomerName(repair.getCustomerName());
        existingRepair.setDescription(repair.getDescription());
        existingRepair.setPhone(repair.getPhone());
        existingRepair.setStatus(repair.getStatus());
        existingRepair.setEstimatedCost(repair.getEstimatedCost());
        existingRepair.setTechnicianNotes(repair.getTechnicianNotes());
        existingRepair.setNotes(repair.getNotes());
        
        if (repair.getImage() != null && !repair.getImage().isEmpty()) {
            existingRepair.setImage(repair.getImage());
        }
        
        return repairRepository.save(existingRepair);
    }
    
    @Override
    public Repair assignTechnician(Long repairId, Long technicianId) {
        Repair repair = repairRepository.findById(repairId)
            .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada con id: " + repairId));
        
        User technician = userRepository.findById(technicianId)
            .orElseThrow(() -> new ResourceNotFoundException("Técnico no encontrado con id: " + technicianId));
        
        repair.setAssignedTechnician(technician);
        return repairRepository.save(repair);
    }
    
    @Override
    public Repair updateStatus(Long repairId, Repair.RepairStatus status) {
        Repair repair = repairRepository.findById(repairId)
            .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada con id: " + repairId));
        repair.setStatus(status);
        return repairRepository.save(repair);
    }
    
    @Override
    public void delete(Long id) {
        Repair repair = repairRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Reparación no encontrada con id: " + id));
        repairRepository.delete(repair);
    }
}
