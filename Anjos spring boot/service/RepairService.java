package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Repair;
import com.empresa.miproyecto.model.User;
import java.util.List;
import java.util.Optional;

public interface RepairService {
    List<Repair> findAll();
    List<Repair> findByUser(User user);
    List<Repair> findByTechnician(User technician);
    Optional<Repair> findById(Long id);
    Optional<Repair> findByRepairNumber(String repairNumber);
    Repair create(Repair repair);
    Repair update(Long id, Repair repair);
    Repair assignTechnician(Long repairId, Long technicianId);
    Repair updateStatus(Long repairId, Repair.RepairStatus status);
    void delete(Long id);
}
