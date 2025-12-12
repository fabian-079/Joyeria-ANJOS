package com.empresa.miproyecto.model.dto;

import com.empresa.miproyecto.model.Repair;
import jakarta.validation.constraints.NotBlank;
import java.math.BigDecimal;
import java.time.LocalDateTime;

public class RepairDTO {
    
    private Long id;
    private String repairNumber;
    private Long userId;
    private String userName;
    
    @NotBlank(message = "El nombre del cliente es obligatorio")
    private String customerName;
    
    @NotBlank(message = "La descripción es obligatoria")
    private String description;
    
    @NotBlank(message = "El teléfono es obligatorio")
    private String phone;
    
    private String image;
    private Repair.RepairStatus status;
    private Long assignedTechnicianId;
    private String assignedTechnicianName;
    private BigDecimal estimatedCost;
    private String technicianNotes;
    private String notes;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;
    
    // Constructors
    public RepairDTO() {}
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getRepairNumber() { return repairNumber; }
    public void setRepairNumber(String repairNumber) { this.repairNumber = repairNumber; }
    public Long getUserId() { return userId; }
    public void setUserId(Long userId) { this.userId = userId; }
    public String getUserName() { return userName; }
    public void setUserName(String userName) { this.userName = userName; }
    public String getCustomerName() { return customerName; }
    public void setCustomerName(String customerName) { this.customerName = customerName; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    public Repair.RepairStatus getStatus() { return status; }
    public void setStatus(Repair.RepairStatus status) { this.status = status; }
    public Long getAssignedTechnicianId() { return assignedTechnicianId; }
    public void setAssignedTechnicianId(Long assignedTechnicianId) { this.assignedTechnicianId = assignedTechnicianId; }
    public String getAssignedTechnicianName() { return assignedTechnicianName; }
    public void setAssignedTechnicianName(String assignedTechnicianName) { this.assignedTechnicianName = assignedTechnicianName; }
    public BigDecimal getEstimatedCost() { return estimatedCost; }
    public void setEstimatedCost(BigDecimal estimatedCost) { this.estimatedCost = estimatedCost; }
    public String getTechnicianNotes() { return technicianNotes; }
    public void setTechnicianNotes(String technicianNotes) { this.technicianNotes = technicianNotes; }
    public String getNotes() { return notes; }
    public void setNotes(String notes) { this.notes = notes; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}
