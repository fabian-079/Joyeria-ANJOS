package com.empresa.miproyecto.util;

import java.util.regex.Pattern;

public class Validaciones {

    private static final Pattern EMAIL_PATTERN = Pattern.compile("^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,6}$");

    public static boolean esEmailValido(String email) {
        return EMAIL_PATTERN.matcher(email).matches();
    }

    public static boolean esVacio(String valor) {
        return valor == null || valor.trim().isEmpty();
    }

    public static boolean esNumeroValido(String valor) {
        try {
            Double.parseDouble(valor);
            return true;
        } catch (NumberFormatException e) {
            return false;
        }
    }
}
