package com.empresa.miproyecto.model.dto;

import jakarta.validation.constraints.NotBlank;

public class CategoryDTO {
    
    private Long id;
    
    @NotBlank(message = "El nombre es obligatorio")
    private String name;
    
    private String description;
    private String image;
    private Boolean isActive;
    
    // Constructors
    public CategoryDTO() {}
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    public Boolean getIsActive() { return isActive; }
    public void setIsActive(Boolean isActive) { this.isActive = isActive; }
}
