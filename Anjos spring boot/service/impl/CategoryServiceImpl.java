package com.empresa.miproyecto.service.impl;

import com.empresa.miproyecto.exception.ResourceNotFoundException;
import com.empresa.miproyecto.model.Category;
import com.empresa.miproyecto.repository.CategoryRepository;
import com.empresa.miproyecto.service.CategoryService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@Transactional
@SuppressWarnings("unchecked")
public class CategoryServiceImpl implements CategoryService {
    
    @Autowired
    private CategoryRepository categoryRepository;
    
    @Override
    @Transactional(readOnly = true)
    public List<Category> findAll() {
        try {
            List<Category> categories = categoryRepository.findAll();
            return categories != null ? categories : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findAll de categorías: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public List<Category> findActiveCategories() {
        try {
            List<Category> categories = categoryRepository.findByIsActiveTrue();
            return categories != null ? categories : java.util.Collections.emptyList();
        } catch (Exception e) {
            System.err.println("Error en findActiveCategories: " + e.getMessage());
            e.printStackTrace();
            return java.util.Collections.emptyList();
        }
    }
    
    @Override
    @Transactional(readOnly = true)
    public Optional<Category> findById(Long id) {
        try {
            return categoryRepository.findById(id);
        } catch (Exception e) {
            System.err.println("Error en findById de categorías: " + e.getMessage());
            e.printStackTrace();
            return Optional.empty();
        }
    }
    
    @Override
    public Category save(Category category) {
        return categoryRepository.save(category);
    }
    
    @Override
    public Category update(Long id, Category category) {
        Category existingCategory = categoryRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Categoría no encontrada con id: " + id));
        
        existingCategory.setName(category.getName());
        existingCategory.setDescription(category.getDescription());
        existingCategory.setImage(category.getImage());
        existingCategory.setIsActive(category.getIsActive());
        
        return categoryRepository.save(existingCategory);
    }
    
    @Override
    public void delete(Long id) {
        Category category = categoryRepository.findById(id)
            .orElseThrow(() -> new ResourceNotFoundException("Categoría no encontrada con id: " + id));
        categoryRepository.delete(category);
    }
}
