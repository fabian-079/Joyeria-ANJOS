package com.empresa.miproyecto.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "repairs")
public class Repair {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @Column(name = "repair_number", unique = true, nullable = false)
    private String repairNumber;
    
    @NotNull
    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "user_id", nullable = false)
    private User user;
    
    @NotBlank
    @Column(name = "customer_name", nullable = false)
    private String customerName;
    
    @NotBlank
    @Column(columnDefinition = "TEXT", nullable = false)
    private String description;
    
    @NotBlank
    @Column(nullable = false)
    private String phone;
    
    @Column(name = "image")
    private String image;
    
    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private RepairStatus status = RepairStatus.PENDING;
    
    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "assigned_technician_id")
    private User assignedTechnician;
    
    @Column(name = "estimated_cost", precision = 10, scale = 2)
    private BigDecimal estimatedCost;
    
    @Column(name = "technician_notes", columnDefinition = "TEXT")
    private String technicianNotes;
    
    @Column(name = "notes", columnDefinition = "TEXT")
    private String notes;
    
    @Column(name = "created_at")
    private LocalDateTime createdAt;
    
    @Column(name = "updated_at")
    private LocalDateTime updatedAt;
    
    @PrePersist
    protected void onCreate() {
        createdAt = LocalDateTime.now();
        updatedAt = LocalDateTime.now();
        if (repairNumber == null) {
            repairNumber = "REP-" + System.currentTimeMillis();
        }
    }
    
    @PreUpdate
    protected void onUpdate() {
        updatedAt = LocalDateTime.now();
    }
    
    public enum RepairStatus {
        PENDING, IN_PROGRESS, COMPLETED, CANCELLED
    }
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getRepairNumber() { return repairNumber; }
    public void setRepairNumber(String repairNumber) { this.repairNumber = repairNumber; }
    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }
    public String getCustomerName() { return customerName; }
    public void setCustomerName(String customerName) { this.customerName = customerName; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    public RepairStatus getStatus() { return status; }
    public void setStatus(RepairStatus status) { this.status = status; }
    public User getAssignedTechnician() { return assignedTechnician; }
    public void setAssignedTechnician(User assignedTechnician) { this.assignedTechnician = assignedTechnician; }
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
