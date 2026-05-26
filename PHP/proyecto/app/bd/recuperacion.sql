-- ============================================================
--  BASE DE DATOS: recuperacion
--  Clínica Veterinaria – DAW2 DWES
-- ============================================================

CREATE DATABASE IF NOT EXISTS recuperacion
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE recuperacion;

-- ---- Tabla personas (dueños de mascotas) ----
CREATE TABLE IF NOT EXISTS personas (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50)  NOT NULL,
    apellidos VARCHAR(100),
    telefono  VARCHAR(20),
    email     VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tabla mascotas ----
CREATE TABLE IF NOT EXISTS mascotas (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(50)  NOT NULL,
    tipo             VARCHAR(30)  NOT NULL,
    fecha_nacimiento DATE,
    foto_url         VARCHAR(255),
    id_persona       INT NOT NULL,
    FOREIGN KEY (id_persona) REFERENCES personas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tabla veterinarios (para el login) ----
CREATE TABLE IF NOT EXISTS veterinarios (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)  NOT NULL,
    email  VARCHAR(100) NOT NULL UNIQUE,
    clave  VARCHAR(50)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Datos iniciales ----

-- Veterinario del examen (OBLIGATORIO)
INSERT INTO veterinarios (nombre, email, clave)
VALUES ('Felix', 'felix@veterinarios.com', 'dwes2026');

-- Personas de prueba
INSERT INTO personas (nombre, apellidos, telefono, email) VALUES
('Juan',  'Pérez García',  '600111222', 'juan@test.com'),
('Ana',   'López Ruiz',    '600333444', 'ana@test.com'),
('Carlos','Martín Sanz',   '611222333', 'carlos@test.com');

-- Mascotas de prueba
INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona) VALUES
('Rallito', 'tortuga', '2015-09-21', '', 1),
('Carl',    'gato',    '2013-05-07', '', 1),
('Torete',  'agaponi', '2019-01-15', '', 2);
