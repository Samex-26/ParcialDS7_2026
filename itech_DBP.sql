

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS itech_DBP CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE itech_DBP;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================================
-- TABLAS CATÁLOGO
-- ============================================================

DROP TABLE IF EXISTS perfiles_laborales;
DROP TABLE IF EXISTS colaboradores;
DROP TABLE IF EXISTS cat_ocupaciones;
DROP TABLE IF EXISTS cat_tipos_empleado;
DROP TABLE IF EXISTS cat_tipos_planilla;
DROP TABLE IF EXISTS cat_rutas;
DROP TABLE IF EXISTS cat_tipos_sangre;
DROP TABLE IF EXISTS paises;

-- Tabla: Países (para nacionalidad y residencia)
CREATE TABLE paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY uk_paises_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Tipos de Sangre
CREATE TABLE cat_tipos_sangre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(10) NOT NULL,
    UNIQUE KEY uk_cat_tipos_sangre_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Rutas del Colaborador (Panamá Este, Oeste, Norte)
CREATE TABLE cat_rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY uk_cat_rutas_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Tipos de Planilla (Eventual, Permanente, Interino)
CREATE TABLE cat_tipos_planilla (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY uk_cat_tipos_planilla_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Tipos de Empleado
CREATE TABLE cat_tipos_empleado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY uk_cat_tipos_empleado_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Ocupaciones (Puesto de trabajo)
CREATE TABLE cat_ocupaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    UNIQUE KEY uk_cat_ocupaciones_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA PRINCIPAL: COLABORADORES
-- ============================================================
-- Requisitos 1-10: Campos del formulario web

CREATE TABLE colaboradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_empleado INT UNIQUE NULL,
    
    -- Datos del Colaborador (Requisitos 1-10)
    identidad VARCHAR(20) NOT NULL UNIQUE,         -- Req 1: Documento de Identificación (2 pts)
    nombre VARCHAR(100) NOT NULL,                   -- Req 2: Nombre (1 pt)
    apellido VARCHAR(100) NOT NULL,                 -- Req 3: Apellido (1 pt)
    edad INT NOT NULL,                              -- Req 4: Edad (1 pt)
    tipo_sangre VARCHAR(10) NOT NULL,              -- Req 5: Tipo de Sangre (1 pt)
    sexo ENUM('Masculino', 'Femenino', 'Otro') NOT NULL, -- Req 6: Sexo (1 pt)
    ruta_id INT NOT NULL,                           -- Req 8: De qué ruta es el Colaborador (7 pts)
    pais_residencia_id INT NOT NULL,               -- Requisito 7: Nacionalidad (2 pts)
    nacionalidad VARCHAR(100) NOT NULL,             -- Campo adicional para nacionalidad
    
    -- Información de Contacto (Req 9-10)
    correo VARCHAR(150) NOT NULL UNIQUE,           -- Req 9: Correo (5 pts)
    celular VARCHAR(20) NOT NULL UNIQUE,           -- Req 10: Celular (5 pts)
    observaciones TEXT,
    
    -- Control
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Índices y restricciones
    UNIQUE KEY uk_colaboradores_identidad (identidad),
    UNIQUE KEY uk_colaboradores_correo (correo),
    KEY idx_colaboradores_pais (pais_residencia_id),
    KEY idx_colaboradores_ruta (ruta_id),
    
    CONSTRAINT fk_colaboradores_paises FOREIGN KEY (pais_residencia_id) 
        REFERENCES paises(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_colaboradores_rutas FOREIGN KEY (ruta_id) 
        REFERENCES cat_rutas(id) ON DELETE RESTRICT ON UPDATE CASCADE
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA: PERFILES LABORALES
-- ============================================================
-- Requisitos 11-18: Datos del perfil laboral y promoción

CREATE TABLE perfiles_laborales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    colaborador_id INT NOT NULL,
    
    -- Datos del Perfil Laboral (Requisitos 11-15)
    tipo_empleado_id INT NOT NULL,                  -- Tipo de Empleado (Req 16)
    planilla_id INT NOT NULL,                       -- Planilla: Eventual, Permanente, Interino (Req 16, 1-3)
    ocupacion_id INT NOT NULL,                      -- Req 11: Puesto/Ocupación (2 pts)
    puesto VARCHAR(150) NOT NULL,                   -- Nombre del puesto (redundante pero útil)
    salario DECIMAL(12,2) NOT NULL,                -- Req 12: Salario (1 pt)
    fecha_inicio DATE NOT NULL,                     -- Req 13: Fecha de Inicio (1 pt)
    fecha_fin DATE NULL,                            -- Req 14: Fecha de Fin (1 pt) - NULL si aún labora
    
    -- Control de estado (Requisitos 15, 17)
    es_activo TINYINT(1) NOT NULL DEFAULT 1,       -- Req 15, 17: 1="interruptor" true (actual), 0=histórico (2+2 pts)
    
    -- Motivo de baja (Requisito 18)
    motivo TEXT NULL,                               -- Req 18: En caso de una baja (2 pts)
    observaciones TEXT,
    
    -- Control
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Índices y restricciones
    KEY idx_perfiles_colaborador (colaborador_id),
    KEY idx_perfiles_tipo_empleado (tipo_empleado_id),
    KEY idx_perfiles_planilla (planilla_id),
    KEY idx_perfiles_ocupacion (ocupacion_id),
    
    CONSTRAINT fk_perfiles_colaborador FOREIGN KEY (colaborador_id) 
        REFERENCES colaboradores(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_perfiles_tipos_empleado FOREIGN KEY (tipo_empleado_id) 
        REFERENCES cat_tipos_empleado(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_perfiles_planilla FOREIGN KEY (planilla_id) 
        REFERENCES cat_tipos_planilla(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_perfiles_ocupaciones FOREIGN KEY (ocupacion_id) 
        REFERENCES cat_ocupaciones(id) ON DELETE RESTRICT ON UPDATE CASCADE
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INSERTAR DATOS DE CATÁLOGO
-- ============================================================

-- Países
INSERT INTO paises (nombre) VALUES
('Panamá'),
('Colombia'),
('Costa Rica'),
('México'),
('Estados Unidos'),
('España'),
('Argentina'),
('Chile'),
('Perú'),
('Venezuela');

-- Tipos de Sangre
INSERT INTO cat_tipos_sangre (nombre) VALUES
('A+'),
('A-'),
('B+'),
('B-'),
('AB+'),
('AB-'),
('O+'),
('O-');

-- Rutas del Colaborador (Panamá Este, Oeste, Norte)
INSERT INTO cat_rutas (nombre) VALUES
('Panamá Este'),
('Panamá Oeste'),
('Panamá Norte');

-- Tipos de Planilla (Eventual, Permanente, Interino)
INSERT INTO cat_tipos_planilla (nombre) VALUES
('Eventual'),
('Permanente'),
('Interino');

-- Tipos de Empleado
INSERT INTO cat_tipos_empleado (nombre) VALUES
('Empleado'),
('Consultor'),
('Temporal'),
('Practicante');

-- Ocupaciones
INSERT INTO cat_ocupaciones (nombre) VALUES
('Secretaria'),
('Albañil'),
('Ingeniero'),
('Analista'),
('Gerente'),
('Desarrollador');

-- ============================================================
-- INSERTAR DATOS DE EJEMPLO
-- ============================================================

INSERT INTO colaboradores (codigo_empleado, identidad, nombre, apellido, edad, tipo_sangre, sexo, ruta_id, pais_residencia_id, nacionalidad, correo, celular, observaciones)
VALUES
(NULL, '1234-5678', 'Juan', 'Pérez', 28, 'O+', 'Masculino', 1, 1, 'Panameña', 'juan@empresa.com', '61234567', 'Empleado destacado');

-- Actualizar código de empleado
UPDATE colaboradores SET codigo_empleado = id WHERE codigo_empleado IS NULL;

-- Insertar perfil laboral
INSERT INTO perfiles_laborales (colaborador_id, tipo_empleado_id, planilla_id, ocupacion_id, puesto, salario, fecha_inicio, fecha_fin, es_activo, motivo, observaciones)
VALUES
(1, 1, 2, 6, 'Desarrollador Senior', 2500.00, '2024-01-15', NULL, 1, NULL, 'Cargo actual');


