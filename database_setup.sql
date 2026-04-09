-- MariaDB Database Setup Script
-- Language Learning Application Database

-- Create Database
CREATE DATABASE IF NOT EXISTS layop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE layop;

-- Table 1: usuarios (User Authentication)
CREATE TABLE IF NOT EXISTS usuarios (
    usuario VARCHAR(255) PRIMARY KEY,
    contrasena VARCHAR(255) NOT NULL,
    privilegio VARCHAR(50) DEFAULT 'user',
    correo VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: diccionariousuarios (User Language Preferences)
CREATE TABLE IF NOT EXISTS diccionariousuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL,
    idioma1 VARCHAR(100) DEFAULT 'Spanish Female',
    idioma2 VARCHAR(100) DEFAULT 'UK English Female',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario) REFERENCES usuarios(usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_user_lang (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: diccionarioimagenes (Dictionary Entries with Images)
CREATE TABLE IF NOT EXISTS diccionarioimagenes (
    indice INT AUTO_INCREMENT PRIMARY KEY,
    idioma VARCHAR(100) NOT NULL,
    imagen LONGBLOB,
    extension VARCHAR(10),
    palabra VARCHAR(255) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    usuario VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario) REFERENCES usuarios(usuario) ON DELETE CASCADE,
    INDEX idx_usuario_tipo (usuario, tipo),
    INDEX idx_usuario (usuario),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create User for Application (optional)
-- CREATE USER IF NOT EXISTS 'adriandecradmin'@'localhost' IDENTIFIED BY 'Administrador1';
-- GRANT ALL PRIVILEGES ON adriandecradmin.* TO 'adriandecradmin'@'localhost';
-- FLUSH PRIVILEGES;
