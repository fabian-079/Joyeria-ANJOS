package com.empresa.miproyecto.exception;

import java.util.ArrayList;
import java.util.List;

public class ValidationException extends RuntimeException {
    private List<String> errors = new ArrayList<>();
    
    public ValidationException(String message) {
        super(message);
        this.errors.add(message);
    }
    
    public ValidationException(List<String> errors) {
        super("Errores de validación: " + String.join(", ", errors));
        this.errors = errors;
    }
    
    public List<String> getErrors() {
        return errors;
    }
    
    public void addError(String error) {
        this.errors.add(error);
    }
}
