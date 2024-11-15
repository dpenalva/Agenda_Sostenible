# Agenda Sostenible

## 📝 Descripción
Agenda Sostenible/Figuerenca es una aplicación web para la gestión de eventos locales en Figueres. Permite a los usuarios crear, gestionar y participar en eventos, con funcionalidades específicas para administradores y usuarios regulares.

## 🛠 Tecnologías Utilizadas
- PHP
- MySQL
- Bootstrap 5
- JavaScript
- HTML5/CSS3
- Emeset Lite

## 🗄 Estructura de la Base de Datos

### Tabla `usuaris`
```sql
CREATE TABLE usuaris (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    cognoms VARCHAR(100),
    nom_usuari VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    biografia TEXT,
    imatge_perfil VARCHAR(255),
    banner VARCHAR(255),
    rol ENUM('user', 'admin') DEFAULT 'user'
);
```

### Tabla `esdeveniments`
```sql
CREATE TABLE esdeveniments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titol VARCHAR(200),
    descripcio TEXT,
    data_esdeveniment DATE,
    hora_esdeveniment TIME,
    longitud DECIMAL(10,8),
    latitud DECIMAL(10,8),
    visibilitat_esdeveniment BOOLEAN DEFAULT FALSE,
    tipus_esdeveniment_id INT,
    id_usuari INT,
    FOREIGN KEY (id_usuari) REFERENCES usuaris(id)
);
```

### Tabla `favorit`
```sql
CREATE TABLE favorit (
    id INT PRIMARY KEY AUTO_INCREMENT,
    esdeveniment_id INT,
    usuari_id INT,
    FOREIGN KEY (esdeveniment_id) REFERENCES esdeveniments(id),
    FOREIGN KEY (usuari_id) REFERENCES usuaris(id)
);
```

## 🔑 Características Principales

### Gestión de Usuarios
- Registro y autenticación de usuarios
- Perfiles personalizables con imagen y banner
- Roles de usuario (admin/user)
- Biografía personalizable

Referencias en el código:
```php:src/models/UsuarisPDO.php
startLine: 37
endLine: 74
```

### Gestión de Eventos
- Creación y edición de eventos
- Geolocalización de eventos
- Sistema de visibilidad (público/privado)
- Sistema de "Me gusta" para eventos

Referencias en el código:
```php:src/models/EsdevenimentsPDO.php
startLine: 130
endLine: 166
```

### Panel de Administración
- Gestión completa de usuarios
- Gestión de eventos
- Estadísticas generales
- Control de visibilidad de eventos

Referencias en el código:
```php:src/views/admin/dashboard.php
startLine: 186
endLine: 199
```

## 🚀 Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/agenda-figuerenca.git
```

2. Configurar la base de datos en `src/config.php`:
```php:src/config.php
startLine: 3
endLine: 10
```

3. Instalar dependencias
```bash
composer install
```

4. Configurar el servidor web para apuntar a la carpeta `public/`

## 👥 Roles de Usuario

### Administrador
- Acceso completo al panel de administración
- Gestión de usuarios y eventos
- Modificación de roles

### Usuario Regular
- Creación y gestión de eventos propios
- Interacción con eventos (me gusta)
- Personalización de perfil

## 🔒 Seguridad
- Contraseñas hasheadas
- Validación de formularios
- Middleware de autenticación

