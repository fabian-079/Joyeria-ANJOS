package com.empresa.miproyecto.model.dto;

import jakarta.validation.constraints.NotBlank;
import java.math.BigDecimal;
import java.time.LocalDateTime;

public class CustomizationDTO {
    
    private Long id;
    private Long userId;
    private String userName;
    
    @NotBlank(message = "El tipo de joyería es obligatorio")
    private String jewelryType;
    
    @NotBlank(message = "El diseño es obligatorio")
    private String design;
    
    @NotBlank(message = "Las piedras son obligatorias")
    private String stones;
    
    @NotBlank(message = "El acabado es obligatorio")
    private String finish;
    
    @NotBlank(message = "El color es obligatorio")
    private String color;
    
    @NotBlank(message = "El material es obligatorio")
    private String material;
    
    private String engraving;
    private String specialInstructions;
    private BigDecimal estimatedPrice;
    private String status;
    private String adminNotes;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;
    
    // Constructors
    public CustomizationDTO() {}
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public Long getUserId() { return userId; }
    public void setUserId(Long userId) { this.userId = userId; }
    public String getUserName() { return userName; }
    public void setUserName(String userName) { this.userName = userName; }
    public String getJewelryType() { return jewelryType; }
    public void setJewelryType(String jewelryType) { this.jewelryType = jewelryType; }
    public String getDesign() { return design; }
    public void setDesign(String design) { this.design = design; }
    public String getStones() { return stones; }
    public void setStones(String stones) { this.stones = stones; }
    public String getFinish() { return finish; }
    public void setFinish(String finish) { this.finish = finish; }
    public String getColor() { return color; }
    public void setColor(String color) { this.color = color; }
    public String getMaterial() { return material; }
    public void setMaterial(String material) { this.material = material; }
    public String getEngraving() { return engraving; }
    public void setEngraving(String engraving) { this.engraving = engraving; }
    public String getSpecialInstructions() { return specialInstructions; }
    public void setSpecialInstructions(String specialInstructions) { this.specialInstructions = specialInstructions; }
    public BigDecimal getEstimatedPrice() { return estimatedPrice; }
    public void setEstimatedPrice(BigDecimal estimatedPrice) { this.estimatedPrice = estimatedPrice; }
    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }
    public String getAdminNotes() { return adminNotes; }
    public void setAdminNotes(String adminNotes) { this.adminNotes = adminNotes; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}
