package com.empresa.miproyecto.model.dto;

import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import java.math.BigDecimal;

public class ProductDTO {
    
    private Long id;
    
    @NotBlank(message = "El nombre es obligatorio")
    private String name;
    
    @NotBlank(message = "La descripción es obligatoria")
    private String description;
    
    @NotNull(message = "El precio es obligatorio")
    @DecimalMin(value = "0.01", message = "El precio debe ser mayor a 0")
    private BigDecimal price;
    
    @Min(value = 0, message = "El stock no puede ser negativo")
    private Integer stock;
    
    private String material;
    private String color;
    private String finish;
    private String stones;
    private String image;
    private Boolean isFeatured;
    private Boolean isActive;
    
    @NotNull(message = "La categoría es obligatoria")
    private Long categoryId;
    
    private String categoryName;
    
    // Constructors
    public ProductDTO() {}
    
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
    public Boolean getIsFeatured() { return isFeatured; }
    public void setIsFeatured(Boolean isFeatured) { this.isFeatured = isFeatured; }
    public Boolean getIsActive() { return isActive; }
    public void setIsActive(Boolean isActive) { this.isActive = isActive; }
    public Long getCategoryId() { return categoryId; }
    public void setCategoryId(Long categoryId) { this.categoryId = categoryId; }
    public String getCategoryName() { return categoryName; }
    public void setCategoryName(String categoryName) { this.categoryName = categoryName; }
}
