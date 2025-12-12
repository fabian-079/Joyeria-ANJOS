package com.empresa.miproyecto.service;

import com.empresa.miproyecto.model.Category;
import java.util.List;
import java.util.Optional;

public interface CategoryService {
    List<Category> findAll();
    List<Category> findActiveCategories();
    Optional<Category> findById(Long id);
    Category save(Category category);
    Category update(Long id, Category category);
    void delete(Long id);
}
