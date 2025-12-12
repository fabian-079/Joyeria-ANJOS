package com.empresa.miproyecto.exception;

import org.springframework.http.HttpStatus;
import org.springframework.ui.Model;
import org.springframework.validation.FieldError;
import org.springframework.web.bind.MethodArgumentNotValidException;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.ResponseStatus;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.HashMap;
import java.util.Map;

@ControllerAdvice
public class GlobalExceptionHandler {

    @ExceptionHandler(ResourceNotFoundException.class)
    @ResponseStatus(HttpStatus.NOT_FOUND)
    public String handleNotFound(ResourceNotFoundException e, Model model) {
        model.addAttribute("error", e.getMessage());
        model.addAttribute("status", 404);
        return "error/404";
    }

    @ExceptionHandler(BadRequestException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public String handleBadRequest(BadRequestException e, Model model, RedirectAttributes redirectAttributes) {
        redirectAttributes.addFlashAttribute("error", e.getMessage());
        return "redirect:/";
    }

    @ExceptionHandler(UnauthorizedException.class)
    @ResponseStatus(HttpStatus.UNAUTHORIZED)
    public String handleUnauthorized(UnauthorizedException e, Model model) {
        model.addAttribute("error", e.getMessage());
        model.addAttribute("status", 401);
        return "error/401";
    }

    @ExceptionHandler(ValidationException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public String handleValidation(ValidationException e, Model model, RedirectAttributes redirectAttributes) {
        redirectAttributes.addFlashAttribute("errors", e.getErrors());
        redirectAttributes.addFlashAttribute("error", "Errores de validación");
        return "redirect:/";
    }

    @ExceptionHandler(InsufficientStockException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public String handleInsufficientStock(InsufficientStockException e, RedirectAttributes redirectAttributes) {
        redirectAttributes.addFlashAttribute("error", e.getMessage());
        return "redirect:/carrito";
    }

    @ExceptionHandler(MethodArgumentNotValidException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public String handleValidationExceptions(MethodArgumentNotValidException e, Model model, RedirectAttributes redirectAttributes) {
        Map<String, String> errors = new HashMap<>();
        e.getBindingResult().getAllErrors().forEach((error) -> {
            String fieldName = ((FieldError) error).getField();
            String errorMessage = error.getDefaultMessage();
            errors.put(fieldName, errorMessage);
        });
        redirectAttributes.addFlashAttribute("validationErrors", errors);
        redirectAttributes.addFlashAttribute("error", "Errores de validación");
        return "redirect:/";
    }

    @ExceptionHandler(IllegalArgumentException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public String handleIllegalArgument(IllegalArgumentException e, RedirectAttributes redirectAttributes) {
        redirectAttributes.addFlashAttribute("error", e.getMessage());
        return "redirect:/";
    }

    @ExceptionHandler(Exception.class)
    @ResponseStatus(HttpStatus.INTERNAL_SERVER_ERROR)
    public String handleException(Exception e, Model model) {
        model.addAttribute("error", "Ocurrió un error interno: " + e.getMessage());
        model.addAttribute("status", 500);
        e.printStackTrace(); // Log the exception
        return "error/500";
    }
}
