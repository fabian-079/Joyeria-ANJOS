package com.empresa.miproyecto.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;

import java.time.LocalDateTime;
import java.util.Collection;
import java.util.HashSet;
import java.util.Set;
import java.util.stream.Collectors;

@Entity
@Table(name = "users")
public class User implements UserDetails {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @NotBlank
    @Size(max = 255)
    @Column(nullable = false)
    private String name;
    
    @NotBlank
    @Email
    @Size(max = 255)
    @Column(unique = true, nullable = false)
    private String email;
    
    @NotBlank
    @Size(min = 6)
    @Column(nullable = false)
    private String password;
    
    @Column(name = "phone")
    private String phone;
    
    @Column(name = "address", columnDefinition = "TEXT")
    private String address;
    
    @Column(name = "is_active")
    private Boolean isActive = true;
    
    @Column(name = "email_verified_at")
    private LocalDateTime emailVerifiedAt;
    
    @ManyToMany(fetch = FetchType.EAGER)
    @JoinTable(
        name = "model_has_roles",
        joinColumns = @JoinColumn(name = "model_id", referencedColumnName = "id"),
        inverseJoinColumns = @JoinColumn(name = "role_id", referencedColumnName = "id"),
        foreignKey = @ForeignKey(name = "fk_user_role"),
        inverseForeignKey = @ForeignKey(name = "fk_role_user")
    )
    private Set<Role> roles = new HashSet<>();
    
    @OneToMany(mappedBy = "user", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<Order> orders = new HashSet<>();
    
    @OneToMany(mappedBy = "user", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<CartItem> cartItems = new HashSet<>();
    
    @OneToMany(mappedBy = "user", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<Favorite> favorites = new HashSet<>();
    
    @OneToMany(mappedBy = "user", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<Repair> repairs = new HashSet<>();
    
    @OneToMany(mappedBy = "assignedTechnician", cascade = CascadeType.ALL)
    private Set<Repair> assignedRepairs = new HashSet<>();
    
    @OneToMany(mappedBy = "user", cascade = CascadeType.ALL, orphanRemoval = true)
    private Set<Customization> customizations = new HashSet<>();
    
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
    
    // UserDetails implementation
    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        if (roles == null || roles.isEmpty()) {
            return java.util.Collections.emptyList();
        }
        return roles.stream()
                .filter(role -> role != null && role.getName() != null)
                .map(role -> new SimpleGrantedAuthority("ROLE_" + role.getName().toUpperCase()))
                .collect(Collectors.toList());
    }
    
    @Override
    public String getUsername() {
        return email;
    }
    
    @Override
    public boolean isAccountNonExpired() {
        return true;
    }
    
    @Override
    public boolean isAccountNonLocked() {
        return isActive;
    }
    
    @Override
    public boolean isCredentialsNonExpired() {
        return true;
    }
    
    @Override
    public boolean isEnabled() {
        return isActive;
    }
    
    // Helper methods
    public boolean isAdmin() {
        if (roles == null || roles.isEmpty()) {
            return false;
        }
        return roles.stream().anyMatch(role -> role != null && "admin".equalsIgnoreCase(role.getName()));
    }
    
    public boolean isClient() {
        return roles.stream().anyMatch(role -> "cliente".equalsIgnoreCase(role.getName()));
    }
    
    // Getters and Setters
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    @Override
    public String getPassword() { return password; }
    public void setPassword(String password) { this.password = password; }
    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }
    public String getAddress() { return address; }
    public void setAddress(String address) { this.address = address; }
    public Boolean getIsActive() { return isActive; }
    public void setIsActive(Boolean isActive) { this.isActive = isActive; }
    public LocalDateTime getEmailVerifiedAt() { return emailVerifiedAt; }
    public void setEmailVerifiedAt(LocalDateTime emailVerifiedAt) { this.emailVerifiedAt = emailVerifiedAt; }
    public Set<Role> getRoles() { return roles; }
    public void setRoles(Set<Role> roles) { this.roles = roles; }
    public Set<Order> getOrders() { return orders; }
    public void setOrders(Set<Order> orders) { this.orders = orders; }
    public Set<CartItem> getCartItems() { return cartItems; }
    public void setCartItems(Set<CartItem> cartItems) { this.cartItems = cartItems; }
    public Set<Favorite> getFavorites() { return favorites; }
    public void setFavorites(Set<Favorite> favorites) { this.favorites = favorites; }
    public Set<Repair> getRepairs() { return repairs; }
    public void setRepairs(Set<Repair> repairs) { this.repairs = repairs; }
    public Set<Repair> getAssignedRepairs() { return assignedRepairs; }
    public void setAssignedRepairs(Set<Repair> assignedRepairs) { this.assignedRepairs = assignedRepairs; }
    public Set<Customization> getCustomizations() { return customizations; }
    public void setCustomizations(Set<Customization> customizations) { this.customizations = customizations; }
    public LocalDateTime getCreatedAt() { return createdAt; }
    public void setCreatedAt(LocalDateTime createdAt) { this.createdAt = createdAt; }
    public LocalDateTime getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(LocalDateTime updatedAt) { this.updatedAt = updatedAt; }
}
