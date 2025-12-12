# Mi Proyecto Spring Boot

Sistema de gestión de joyería desarrollado con Spring Boot que cumple con todos los requisitos de la lista de chequeo.

## Características Implementadas

### ✅ 1. Autenticación y Manejo Dinámico de Roles
- Sistema de autenticación con Spring Security
- Manejo dinámico de roles (crear, editar, eliminar roles)
- Asignación y remoción de roles a usuarios en tiempo de ejecución
- Control de acceso basado en roles (ADMIN, CLIENTE, etc.)
- Rutas protegidas según roles

### ✅ 2. CRUD Completo con Validaciones
- CRUD completo para Productos, Usuarios, Órdenes, Reparaciones, Personalizaciones
- Validaciones de formularios usando Jakarta Bean Validation
- Validaciones en el lado del servidor con mensajes de error claros
- Manejo de excepciones y mensajes de éxito/error

### ✅ 3. Generación de Reportes con Filtros Multicriterio
- Módulo completo de reportes administrativos
- Reportes de ventas con filtros por fecha, estado, usuario
- Reportes de productos con filtros por categoría, precio, stock, estado
- Reportes de usuarios con filtros por rol, estado, fecha de registro
- Top productos más vendidos
- Top clientes más activos
- Exportación de reportes (CSV)

### ✅ 4. Criterios de Usabilidad
- Interfaz intuitiva y fácil de navegar
- Menú lateral con navegación clara
- Diseño responsive
- Mensajes de confirmación y retroalimentación
- Iconos Font Awesome para mejor comprensión visual
- Navegación contextual según el rol del usuario

### ✅ 5. Repositorio Git
- Repositorio Git inicializado
- Archivo .gitignore configurado
- Listo para trabajo en equipo y versionamiento

### ✅ 6. Uso de Frameworks
- **Spring Boot 3.1.4**: Framework principal
- **Spring Security**: Autenticación y autorización
- **Spring Data JPA**: Acceso a datos
- **Thymeleaf**: Motor de plantillas
- **Flyway**: Migraciones de base de datos
- **Jakarta Bean Validation**: Validaciones
- **MySQL**: Base de datos

## Requisitos Previos

- Java 17 o superior
- Maven 3.6+
- MySQL 8.0+
- Git (opcional, para versionamiento)

## Configuración

1. Clonar el repositorio:
```bash
git clone <url-del-repositorio>
cd mi-proyecto-spring
```

2. Configurar la base de datos en `src/main/resources/application.properties`:
```properties
spring.datasource.url=jdbc:mysql://localhost:3306/anjos_db
spring.datasource.username=root
spring.datasource.password=tu_password
```

3. Ejecutar las migraciones de Flyway (se ejecutan automáticamente al iniciar)

4. Compilar y ejecutar:
```bash
mvn clean install
mvn spring-boot:run
```

5. Acceder a la aplicación:
- URL: http://localhost:8081
- Usuario admin por defecto: (crear manualmente o usar migraciones)

## Estructura del Proyecto

```
src/
├── main/
│   ├── java/com/empresa/miproyecto/
│   │   ├── config/          # Configuraciones (Security, Web)
│   │   ├── controller/       # Controladores REST/MVC
│   │   ├── model/           # Entidades JPA
│   │   ├── repository/      # Repositorios Spring Data
│   │   ├── service/         # Lógica de negocio
│   │   └── exception/       # Manejo de excepciones
│   └── resources/
│       ├── templates/       # Plantillas Thymeleaf
│       ├── static/          # Archivos estáticos (CSS, JS)
│       └── db/migration/    # Migraciones Flyway
└── test/                    # Pruebas unitarias
```

## Rutas Principales

### Públicas
- `/` - Página de inicio
- `/catalogo` - Catálogo de productos
- `/login` - Inicio de sesión
- `/register` - Registro de usuarios

### Autenticadas
- `/carrito` - Carrito de compras
- `/orders` - Mis órdenes
- `/favoritos` - Productos favoritos
- `/reparaciones` - Solicitudes de reparación
- `/personalizacion` - Solicitudes de personalización

### Administración (requiere rol ADMIN)
- `/products` - Gestión de productos
- `/users` - Gestión de usuarios
- `/admin/roles` - Gestión de roles
- `/admin/reports` - Panel de reportes

## Roles del Sistema

- **ADMIN**: Acceso completo al sistema
- **CLIENTE**: Usuario regular con acceso a compras y servicios
- Roles adicionales pueden crearse dinámicamente

## Desarrollo

### Agregar un nuevo módulo CRUD

1. Crear entidad en `model/`
2. Crear repositorio en `repository/`
3. Crear servicio en `service/`
4. Crear controlador en `controller/`
5. Crear plantillas en `resources/templates/`

### Agregar un nuevo reporte

1. Agregar método en `ReportService`
2. Implementar en `ReportServiceImpl`
3. Agregar endpoint en `ReportController`
4. Crear plantilla en `resources/templates/admin/reports/`

## Contribución

1. Crear una rama para la nueva característica
2. Realizar los cambios
3. Commit con mensajes descriptivos
4. Push a la rama
5. Crear Pull Request

## Licencia

Este proyecto es de uso educativo.

## Autor

Desarrollado para cumplir con los requisitos de la lista de chequeo del proyecto.
