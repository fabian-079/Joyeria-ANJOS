package com.empresa.miproyecto.util;

import com.itextpdf.html2pdf.ConverterProperties;
import com.itextpdf.html2pdf.HtmlConverter;
import com.itextpdf.kernel.pdf.PdfDocument;
import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.layout.Document;
import com.itextpdf.layout.element.Paragraph;
import com.itextpdf.layout.element.Table;
import com.itextpdf.layout.properties.UnitValue;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Map;

public class PdfExportUtil {
    
    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm");
    
    public static byte[] generateSalesReportPdf(List<Map<String, Object>> orders, Map<String, Object> summary) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        PdfWriter writer = new PdfWriter(baos);
        PdfDocument pdf = new PdfDocument(writer);
        Document document = new Document(pdf);
        
        // Título
        document.add(new Paragraph("REPORTE DE VENTAS").setBold().setFontSize(18));
        document.add(new Paragraph("Fecha: " + LocalDateTime.now().format(DATE_FORMATTER)).setFontSize(10));
        document.add(new Paragraph(" "));
        
        // Resumen
        if (summary != null) {
            document.add(new Paragraph("RESUMEN").setBold().setFontSize(14));
            document.add(new Paragraph("Total de Órdenes: " + (summary.get("totalOrders") != null ? summary.get("totalOrders") : 0)));
            document.add(new Paragraph("Total Vendido: $" + (summary.get("totalSales") != null ? summary.get("totalSales") : "0.00")));
            document.add(new Paragraph(" "));
        }
        
        // Tabla de órdenes
        if (orders != null && !orders.isEmpty()) {
            document.add(new Paragraph("ÓRDENES").setBold().setFontSize(14));
            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 2, 2, 2, 2, 2}));
            table.addHeaderCell("ID");
            table.addHeaderCell("Cliente");
            table.addHeaderCell("Total");
            table.addHeaderCell("Estado");
            table.addHeaderCell("Fecha");
            table.addHeaderCell("Método Pago");
            
            for (Map<String, Object> order : orders) {
                if (order != null) {
                    table.addCell(String.valueOf(order.get("id") != null ? order.get("id") : ""));
                    table.addCell(String.valueOf(order.get("customerName") != null ? order.get("customerName") : "N/A"));
                    table.addCell("$" + (order.get("total") != null ? order.get("total") : "0.00"));
                    table.addCell(String.valueOf(order.get("status") != null ? order.get("status") : "PENDIENTE"));
                    table.addCell(order.get("createdAt") != null ? order.get("createdAt").toString() : "N/A");
                    table.addCell(String.valueOf(order.get("paymentMethod") != null ? order.get("paymentMethod") : "N/A"));
                }
            }
            document.add(table);
        }
        
        document.close();
        return baos.toByteArray();
    }
    
    public static byte[] generateProductsReportPdf(List<Map<String, Object>> products, Map<String, Object> summary) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        PdfWriter writer = new PdfWriter(baos);
        PdfDocument pdf = new PdfDocument(writer);
        Document document = new Document(pdf);
        
        document.add(new Paragraph("REPORTE DE PRODUCTOS").setBold().setFontSize(18));
        document.add(new Paragraph("Fecha: " + LocalDateTime.now().format(DATE_FORMATTER)).setFontSize(10));
        document.add(new Paragraph(" "));
        
        if (summary != null) {
            document.add(new Paragraph("RESUMEN").setBold().setFontSize(14));
            document.add(new Paragraph("Total de Productos: " + (summary.get("totalProducts") != null ? summary.get("totalProducts") : 0)));
            document.add(new Paragraph("Productos Activos: " + (summary.get("activeProducts") != null ? summary.get("activeProducts") : 0)));
            document.add(new Paragraph(" "));
        }
        
        if (products != null && !products.isEmpty()) {
            document.add(new Paragraph("PRODUCTOS").setBold().setFontSize(14));
            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 3, 2, 2, 2, 2}));
            table.addHeaderCell("ID");
            table.addHeaderCell("Nombre");
            table.addHeaderCell("Categoría");
            table.addHeaderCell("Precio");
            table.addHeaderCell("Stock");
            table.addHeaderCell("Estado");
            
            for (Map<String, Object> product : products) {
                if (product != null) {
                    table.addCell(String.valueOf(product.get("id") != null ? product.get("id") : ""));
                    table.addCell(String.valueOf(product.get("name") != null ? product.get("name") : "N/A"));
                    table.addCell(String.valueOf(product.get("categoryName") != null ? product.get("categoryName") : "Sin categoría"));
                    table.addCell("$" + (product.get("price") != null ? product.get("price") : "0.00"));
                    table.addCell(String.valueOf(product.get("stock") != null ? product.get("stock") : 0));
                    table.addCell(String.valueOf(product.get("isActive") != null && (Boolean) product.get("isActive") ? "Activo" : "Inactivo"));
                }
            }
            document.add(table);
        }
        
        document.close();
        return baos.toByteArray();
    }
    
    public static byte[] generateUsersReportPdf(List<Map<String, Object>> users, Map<String, Object> summary) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        PdfWriter writer = new PdfWriter(baos);
        PdfDocument pdf = new PdfDocument(writer);
        Document document = new Document(pdf);
        
        document.add(new Paragraph("REPORTE DE USUARIOS").setBold().setFontSize(18));
        document.add(new Paragraph("Fecha: " + LocalDateTime.now().format(DATE_FORMATTER)).setFontSize(10));
        document.add(new Paragraph(" "));
        
        if (summary != null) {
            document.add(new Paragraph("RESUMEN").setBold().setFontSize(14));
            document.add(new Paragraph("Total de Usuarios: " + (summary.get("totalUsers") != null ? summary.get("totalUsers") : 0)));
            document.add(new Paragraph("Usuarios Activos: " + (summary.get("activeUsers") != null ? summary.get("activeUsers") : 0)));
            document.add(new Paragraph(" "));
        }
        
        if (users != null && !users.isEmpty()) {
            document.add(new Paragraph("USUARIOS").setBold().setFontSize(14));
            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 3, 3, 2, 2}));
            table.addHeaderCell("ID");
            table.addHeaderCell("Nombre");
            table.addHeaderCell("Email");
            table.addHeaderCell("Roles");
            table.addHeaderCell("Estado");
            
            for (Map<String, Object> user : users) {
                if (user != null) {
                    table.addCell(String.valueOf(user.get("id") != null ? user.get("id") : ""));
                    table.addCell(String.valueOf(user.get("name") != null ? user.get("name") : "N/A"));
                    table.addCell(String.valueOf(user.get("email") != null ? user.get("email") : "N/A"));
                    table.addCell(String.valueOf(user.get("roles") != null ? user.get("roles") : "Sin roles"));
                    table.addCell(String.valueOf(user.get("isActive") != null && (Boolean) user.get("isActive") ? "Activo" : "Inactivo"));
                }
            }
            document.add(table);
        }
        
        document.close();
        return baos.toByteArray();
    }
    
    public static byte[] generateTopSellingPdf(List<Map<String, Object>> topProducts) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        PdfWriter writer = new PdfWriter(baos);
        PdfDocument pdf = new PdfDocument(writer);
        Document document = new Document(pdf);
        
        document.add(new Paragraph("PRODUCTOS MÁS VENDIDOS").setBold().setFontSize(18));
        document.add(new Paragraph("Fecha: " + LocalDateTime.now().format(DATE_FORMATTER)).setFontSize(10));
        document.add(new Paragraph(" "));
        
        if (topProducts != null && !topProducts.isEmpty()) {
            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 3, 2, 2, 2}));
            table.addHeaderCell("Posición");
            table.addHeaderCell("Producto");
            table.addHeaderCell("Cantidad Vendida");
            table.addHeaderCell("Total Vendido");
            table.addHeaderCell("Categoría");
            
            int position = 1;
            for (Map<String, Object> product : topProducts) {
                if (product != null) {
                    table.addCell(String.valueOf(position++));
                    table.addCell(String.valueOf(product.get("productName") != null ? product.get("productName") : "N/A"));
                    table.addCell(String.valueOf(product.get("quantitySold") != null ? product.get("quantitySold") : 0));
                    table.addCell("$" + (product.get("totalRevenue") != null ? product.get("totalRevenue") : "0.00"));
                    table.addCell(String.valueOf(product.get("categoryName") != null ? product.get("categoryName") : "Sin categoría"));
                }
            }
            document.add(table);
        }
        
        document.close();
        return baos.toByteArray();
    }
    
    public static byte[] generateTopUsersPdf(List<Map<String, Object>> topUsers) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        PdfWriter writer = new PdfWriter(baos);
        PdfDocument pdf = new PdfDocument(writer);
        Document document = new Document(pdf);
        
        document.add(new Paragraph("CLIENTES MÁS ACTIVOS").setBold().setFontSize(18));
        document.add(new Paragraph("Fecha: " + LocalDateTime.now().format(DATE_FORMATTER)).setFontSize(10));
        document.add(new Paragraph(" "));
        
        if (topUsers != null && !topUsers.isEmpty()) {
            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 3, 3, 2, 2}));
            table.addHeaderCell("Posición");
            table.addHeaderCell("Cliente");
            table.addHeaderCell("Email");
            table.addHeaderCell("Órdenes");
            table.addHeaderCell("Total Gastado");
            
            int position = 1;
            for (Map<String, Object> user : topUsers) {
                if (user != null) {
                    table.addCell(String.valueOf(position++));
                    table.addCell(String.valueOf(user.get("userName") != null ? user.get("userName") : "N/A"));
                    table.addCell(String.valueOf(user.get("email") != null ? user.get("email") : "N/A"));
                    table.addCell(String.valueOf(user.get("orderCount") != null ? user.get("orderCount") : 0));
                    table.addCell("$" + (user.get("totalSpent") != null ? user.get("totalSpent") : "0.00"));
                }
            }
            document.add(table);
        }
        
        document.close();
        return baos.toByteArray();
    }
}

