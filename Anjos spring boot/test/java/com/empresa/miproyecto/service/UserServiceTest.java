package com.empresa.miproyecto.service;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Role;
import com.empresa.miproyecto.model.User;
import com.empresa.miproyecto.repository.RoleRepository;
import com.empresa.miproyecto.repository.UserRepository;
import com.empresa.miproyecto.service.impl.UserServiceImpl;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;
import org.springframework.security.crypto.password.PasswordEncoder;

import java.util.HashSet;
import java.util.Optional;
import java.util.Set;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class UserServiceTest {
    
    @Mock
    private UserRepository userRepository;
    
    @Mock
    private PasswordEncoder passwordEncoder;
    
    @Mock
    private RoleRepository roleRepository;
    
    @InjectMocks
    private UserServiceImpl userService;
    
    private User user;
    private Role role;
    
    @BeforeEach
    void setUp() {
        user = new User();
        user.setId(1L);
        user.setName("Test User");
        user.setEmail("test@test.com");
        user.setPassword("password123");
        user.setIsActive(true);
        
        role = new Role();
        role.setId(1L);
        role.setName("cliente");
    }
    
    @Test
    void testFindByEmail_Success() {
        when(userRepository.findByEmail("test@test.com")).thenReturn(Optional.of(user));
        
        Optional<User> result = userService.findByEmail("test@test.com");
        
        assertTrue(result.isPresent());
        assertEquals("test@test.com", result.get().getEmail());
    }
    
    @Test
    void testFindByEmail_NotFound() {
        when(userRepository.findByEmail("notfound@test.com")).thenReturn(Optional.empty());
        
        Optional<User> result = userService.findByEmail("notfound@test.com");
        
        assertFalse(result.isPresent());
    }
    
    @Test
    void testSave_NewUser() {
        when(passwordEncoder.encode("password123")).thenReturn("encoded_password");
        when(userRepository.save(any(User.class))).thenReturn(user);
        
        User saved = userService.save(user);
        
        assertNotNull(saved);
        verify(passwordEncoder, times(1)).encode("password123");
        verify(userRepository, times(1)).save(user);
    }
    
    @Test
    void testSave_ExistingUserWithEncryptedPassword() {
        user.setPassword("$2a$10$alreadyEncrypted");
        when(userRepository.save(any(User.class))).thenReturn(user);
        
        User saved = userService.save(user);
        
        assertNotNull(saved);
        verify(passwordEncoder, never()).encode(anyString());
    }
    
    @Test
    void testUpdate_Success() {
        User updatedData = new User();
        updatedData.setName("Updated Name");
        updatedData.setEmail("updated@test.com");
        updatedData.setPhone("123456789");
        
        when(userRepository.findById(1L)).thenReturn(Optional.of(user));
        when(userRepository.save(any(User.class))).thenReturn(user);
        
        User result = userService.update(1L, updatedData);
        
        assertNotNull(result);
        verify(userRepository, times(1)).findById(1L);
        verify(userRepository, times(1)).save(any(User.class));
    }
    
    @Test
    void testUpdate_NotFound() {
        User updatedData = new User();
        updatedData.setName("Updated Name");
        
        when(userRepository.findById(999L)).thenReturn(Optional.empty());
        
        assertThrows(ResourceNotFoundException.class, () -> {
            userService.update(999L, updatedData);
        });
    }
    
    @Test
    void testDelete() {
        when(userRepository.findById(1L)).thenReturn(Optional.of(user));
        doNothing().when(userRepository).delete(user);
        
        userService.delete(1L);
        
        verify(userRepository, times(1)).delete(user);
    }
    
    @Test
    void testAssignRole() {
        Set<Role> roles = new HashSet<>();
        user.setRoles(roles);
        
        when(userRepository.save(any(User.class))).thenReturn(user);
        
        User result = userService.assignRole(user, role);
        
        assertTrue(result.getRoles().contains(role));
        verify(userRepository, times(1)).save(user);
    }
    
    @Test
    void testExistsByEmail() {
        when(userRepository.existsByEmail("test@test.com")).thenReturn(true);
        
        boolean exists = userService.existsByEmail("test@test.com");
        
        assertTrue(exists);
    }
}
