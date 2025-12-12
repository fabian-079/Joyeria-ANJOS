package com.empresa.miproyecto.repository;

import com.empresa.miproyecto.model.Repair;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface RepairRepository extends JpaRepository<Repair, Long> {
    Optional<Repair> findByRepairNumber(String repairNumber);
    List<Repair> findByUserId(Long userId);
    List<Repair> findByAssignedTechnicianId(Long technicianId);
    
    @Query("SELECT DISTINCT r FROM Repair r LEFT JOIN FETCH r.user LEFT JOIN FETCH r.assignedTechnician")
    List<Repair> findAllWithRelations();
    
    @Query("SELECT DISTINCT r FROM Repair r LEFT JOIN FETCH r.user LEFT JOIN FETCH r.assignedTechnician WHERE r.user.id = :userId")
    List<Repair> findByUserIdWithRelations(Long userId);
}
