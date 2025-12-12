package com.empresa.miproyecto.config;

import com.empresa.miproyecto.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Lazy;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.dao.DaoAuthenticationProvider;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.method.configuration.EnableMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
@EnableWebSecurity
@EnableMethodSecurity
public class SecurityConfig {
    
    @Autowired
    @Lazy
    private UserService userService;
    
    @Autowired
    private AuthenticationSuccessHandler authenticationSuccessHandler;
    
    @Bean
    public UserDetailsService userDetailsService() {
        return username -> userService.findByEmail(username)
            .orElseThrow(() -> new RuntimeException("Usuario no encontrado: " + username));
    }
    
    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }
    
    @Bean
    public DaoAuthenticationProvider authenticationProvider() {
        DaoAuthenticationProvider authProvider = new DaoAuthenticationProvider();
        authProvider.setUserDetailsService(userDetailsService());
        authProvider.setPasswordEncoder(passwordEncoder());
        return authProvider;
    }
    
    @Bean
    public AuthenticationManager authenticationManager(AuthenticationConfiguration authConfig) throws Exception {
        return authConfig.getAuthenticationManager();
    }
    
    @Bean
    public org.springframework.security.config.annotation.web.configuration.WebSecurityCustomizer webSecurityCustomizer() {
        return (web) -> web.ignoring().requestMatchers("/static/**", "/css/**", "/js/**", "/images/**", "/img/**");
    }
    
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http, AuthenticationManager authenticationManager) throws Exception {
        http
            .authenticationProvider(authenticationProvider())
            .csrf(csrf -> csrf.disable())
            .authorizeHttpRequests(auth -> auth
                // Rutas públicas
                .requestMatchers("/", "/catalogo", "/producto/**", "/buscar", 
                               "/static/**", "/css/**", "/js/**", "/images/**", "/img/**",
                               "/login", "/register", "/password/**", "/error", "/h2-console/**").permitAll()
                // Excepciones: rutas de productos que requieren autenticación pero no ADMIN (deben ir ANTES de /products/**)
                .requestMatchers("/products/*/carrito", "/products/*/favoritos").authenticated()
                // Rutas solo para ADMIN
                .requestMatchers("/dashboard/admin", "/products", "/products/**", "/users/**", "/categories/**",
                               "/reparaciones/*/edit", "/reparaciones/*/asignar",
                               "/personalizacion/*/edit", "/admin/**").hasRole("ADMIN")
                // Dashboard cliente requiere autenticación
                .requestMatchers("/dashboard/cliente").authenticated()
                // Rutas que requieren autenticación (pero no necesariamente ADMIN)
                .requestMatchers("/carrito/**", "/orders/**", "/favoritos/**", 
                               "/reparaciones/**", "/personalizacion/**").authenticated()
                .anyRequest().authenticated()
            )
            .formLogin(form -> form
                .loginPage("/login")
                .successHandler(authenticationSuccessHandler)
                .failureUrl("/login?error=true")
                .permitAll()
            )
            .logout(logout -> logout
                .logoutUrl("/logout")
                .logoutSuccessUrl("/")
                .permitAll()
            )
            .headers(headers -> headers.frameOptions(frameOptions -> frameOptions.disable())); // Para H2 console si se usa
        
        return http.build();
    }
}
