package com.empresa.miproyecto.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "products")
public class Product {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @NotBlank
    @Column(nullable = false)
    private String name;
    
    @NotBlank
    @Column(columnDefinition = "TEXT", nullable = false)
    private String description;
    
    @NotNull
    @DecimalMin(value = "0.0", inclusive = false)
    @Column(nullable = false, precision = 10, scale = 2)
    private BigDecimal price;
    
    @Min(0)
    @Column(nullable = false)
    private Integer stock = 0;
    
    @Column(name = "material")
    private String material;
    
    @Column(name = "color")
    private String color;
    
    @Column(name = "finish")
    private String finish;
    
    @Column(name = "stones")
    private String stones;
    
    @Column(name = "image")
    private String image;
    
    @Column(name = "gallery", columnDefinition = "JSON")
    private String gallery;
    
    @Column(name = "is_featured")
    private Boolean isFeatured = false;
    
    @Column(name = "is_active")
    private Boolean isActive = true;
    
    @NotNull
    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumn(name = "category_id", nullable = false)
    private Category category;
    
    @OneToMany(mappedBy = "product", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<OrderItem> orderItems = new HashSet<>();
    
    @OneToMany(mappedBy = "product", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<CartItem> cartItems = new HashSet<>();
    
    @OneToMany(mappedBy = "product", cascade = CascadeType.ALL, orphanRemoval = true)
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
    
    // Helper methods
    public String getImageUrl() {
        if (image != null && !image.isEmpty()) {
            if (image.startsWith("http")) {
                return image;
            }
            return "/images/products/" + image;
        }
        // Imágenes por defecto según categoría
        if (category != null) {
            String categoryName = category.getName().toLowerCase();
            if (categoryName.contains("anillo")) {
                return "https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop";
            } else if (categoryName.contains("collar")) {
                return "https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800&h=800&fit=crop";
            } else if (categoryName.contains("pulsera")) {
                return "https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=800&h=800&fit=crop";
            } else if (categoryName.contains("arete")) {
                return "https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&h=800&fit=crop";
            } else if (categoryName.contains("reloj")) {
                return "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop";
            } else if (categoryName.contains("dije")) {
                return "https://images.unsplash.com/photo-1603561596111-7c8cd67663aa?w=800&h=800&fit=crop";
            }
        }
        return "https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&h=800&fit=crop"; // Default
    }
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public BigDecimal getPrice() { return price; }
    public void setPrice(BigDecimal price) { this.price = price; }
    public Integer getStock() { return stock; }
    public void setStock(Integer stock) { this.stock = stock; }
    public String getMaterial() { return material; }
    public void setMaterial(String material) { this.material = material; }
    public String getColor() { return color; }
    public void setColor(String color) { this.color = color; }
    public String getFinish() { return finish; }
    public void setFinish(String finish) { this.finish = finish; }
    public String getStones() { return stones; }
    public void setStones(String stones) { this.stones = stones; }
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    public String getGallery() { return gallery; }
    public void setGallery(String gallery) { this.gallery = gallery; }
    public Boolean getIsFeatured() { return isFeatured; }
    public void setIsFeatured(Boolean isFeatured) { this.isFeatured = isFeatured; }
    public Boolean getIsActive() { return isActive; }
    public void setIsActive(Boolean isActive) { this.isActive = isActive; }
    public Category getCategory() { return category; }
    public void setCategory(Category category) { this.category = category; }
    public Set<OrderItem> getOrderItems() { return orderItems; }
    public void setOrderItems(Set<OrderItem> orderItems) { this.orderItems = orderItems; }
    public Set<CartItem> getCartItems() { return cartItems; }
    public void setCartItems(Set<CartItem> cartItems) { this.cartItems = cartItems; }
    public Set<Favorite> getFavorites() { return favorites; }
    public void setFavorites(Set<Favorite> favorites) { this.favorites = favorites; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}
