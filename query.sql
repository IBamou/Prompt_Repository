-- =====================================================
-- Prompts Manager Database Schema
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS prompts_manager;
USE prompts_manager;

-- -----------------------------------------------------
-- Table: categories
-- Stores prompt categories
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: users
-- Stores user accounts with roles and status
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'superAdmin') DEFAULT 'user',
    status ENUM('active', 'blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: prompts
-- Stores AI prompts with category association
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS prompts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category_id INT,
    user_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------------------------------
-- Table: prompt_logs
-- Audit trail for prompt changes
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS prompt_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    prompt_id INT NOT NULL,
    action ENUM('CREATE', 'UPDATE', 'DELETE') NOT NULL,
    field_name VARCHAR(50) NULL,
    old_value TEXT NULL,
    new_value TEXT NULL,
    message TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (prompt_id) REFERENCES prompts(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table: user_sessions
-- Session tracking for login/logout
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time DATETIME NOT NULL,
    logout_time DATETIME NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Insert default data
-- -----------------------------------------------------

-- Default 'Uncategorized' category
INSERT INTO categories (id, name, description) 
VALUES (1, 'Uncategorized', 'Default category for uncategorized prompts')
ON DUPLICATE KEY UPDATE name = name;

-- Default super admin user (password: password123)
-- Hash generated with password_hash('password123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role, status)
VALUES ('Ilyas', 'ilyas0bmp@gmail.com', '$2y$10$YourHashHere', 'superAdmin', 'active')
ON DUPLICATE KEY UPDATE name = name;
