package com.empresa.miproyecto.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "customizations")
public class Customization {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @NotNull
    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "user_id", nullable = false)
    private User user;
    
    @NotBlank
    @Column(name = "jewelry_type", nullable = false)
    private String jewelryType;
    
    @NotBlank
    @Column(nullable = false)
    private String design;
    
    @NotBlank
    @Column(nullable = false)
    private String stones;
    
    @NotBlank
    @Column(nullable = false)
    private String finish;
    
    @NotBlank
    @Column(nullable = false)
    private String color;
    
    @NotBlank
    @Column(nullable = false)
    private String material;
    
    @Column(name = "engraving")
    private String engraving;
    
    @Column(name = "special_instructions", columnDefinition = "TEXT")
    private String specialInstructions;
    
    @Column(name = "estimated_price", precision = 10, scale = 2)
    private BigDecimal estimatedPrice;
    
    @Column(nullable = false)
    private String status = "pending";
    
    @Column(name = "admin_notes", columnDefinition = "TEXT")
    private String adminNotes;
    
    @OneToMany(mappedBy = "customization", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<Favorite> favorites = new HashSet<>();
    
    @Column(name = "created_at")
    private LocalDateTime createdAt;
    
    @Column(name = "updated_at")
    private LocalDateTime updatedAt;
    
    @PrePersist
    protected void onCreate() {
        createdAt = LocalDateTime.now();
        updatedAt = LocalDateTime.now();
    }
    
    @PreUpdate
    protected void onUpdate() {
        updatedAt = LocalDateTime.now();
    }
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public User getUser() { return user; }
    public void setUser(User user) { this.user = user; }
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
    public Set<Favorite> getFavorites() { return favorites; }
    public void setFavorites(Set<Favorite> favorites) { this.favorites = favorites; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}
