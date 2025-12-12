package com.empresa.miproyecto.model.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import java.util.Set;

public class UserDTO {
    
    private Long id;
    
    @NotBlank(message = "El nombre es obligatorio")
    @Size(max = 255, message = "El nombre no puede exceder 255 caracteres")
    private String name;
    
    @NotBlank(message = "El email es obligatorio")
    @Email(message = "El email debe ser válido")
    @Size(max = 255, message = "El email no puede exceder 255 caracteres")
    private String email;
    
    @Size(min = 6, message = "La contraseña debe tener al menos 6 caracteres")
    private String password;
    
    private String phone;
    private String address;
    private Boolean isActive;
    private Set<String> roles;
    
    // Constructors
    public UserDTO() {}
    
    public UserDTO(String name, String email, String password) {
        this.name = name;
        this.email = email;
        this.password = password;
    }
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    public String getPassword() { return password; }
    public void setPassword(String password) { this.password = password; }
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    public String getAddress() { return address; }
    public void setAddress(String address) { this.address = address; }
    public Boolean getIsActive() { return isActive; }
    public void setIsActive(Boolean isActive) { this.isActive = isActive; }
    public Set<String> getRoles() { return roles; }
    public void setRoles(Set<String> roles) { this.roles = roles; }
}
