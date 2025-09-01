-- Create Database
CREATE DATABASE IF NOT EXISTS career_db;
USE career_db;

-- ========================
-- 1. Users Table
-- ========================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','company','admin') DEFAULT 'user',
    verification_token VARCHAR(255),
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- 2. Companies Table
-- ========================
CREATE TABLE IF NOT EXISTS companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- 3. Jobs Table
-- ========================
CREATE TABLE IF NOT EXISTS jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    location VARCHAR(100),
    posted_on DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('open','closed') DEFAULT 'open',
    FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE CASCADE
);

-- ========================
-- 4. Applications Table
-- ========================
CREATE TABLE IF NOT EXISTS applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    applied_on DATE,
    status ENUM('applied','reviewed','accepted','rejected') DEFAULT 'applied',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE
);

-- ========================
-- 5. CV Templates Table
-- ========================
CREATE TABLE IF NOT EXISTS cv_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_name VARCHAR(100) NOT NULL,
    file_path VARCHAR(200) NOT NULL
);

-- ========================
-- 6. User CV Table
-- ========================
CREATE TABLE IF NOT EXISTS user_cv (
    cv_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    template_id INT NOT NULL,
    content TEXT NOT NULL,
    pdf_path VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES cv_templates(template_id) ON DELETE CASCADE
);

-- ========================
-- 7. cv_downloads table
-- ========================
CREATE TABLE IF NOT EXISTS cv_downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT,
    downloaded_on DATE
);

-- ========================
-- Insert Sample Data
-- ========================

-- Admin user (Password: admin123)
INSERT INTO users (full_name, email, password, role, verified) VALUES
('System Admin', 'admin@career.com', '$2y$10$HN5ZRAJ2G8JrZ0xlzXyZ4e8RK5OBeX6CEYdiBP8H23LIab.zhQBN.', 'admin', 1);

-- Normal users (Password: user123)
INSERT INTO users (full_name, email, password, role, verified) VALUES
('Thilini Samanthika', 'thilini@example.com', '$2y$10$IQ7ozwB2j53CG2wQiw9oReGdBdDPcciAnc612J6AKm7YhPFhQBc3i', 'user', 1),
('Sangeeth Weerasigha', 'sangeeth@example.com', '$2y$10$IQ7ozwB2j53CG2wQiw9oReGdBdDPcciAnc612J6AKm7YhPFhQBc3i', 'user', 1);

-- Companies (Password: company123)
INSERT INTO companies (company_name, email, password, verified) VALUES
('Virtusa Pvt Ltd', 'virtusa@example.com', '$2y$10$GTfP/4SIyx07/XFErztheeMBoIh/QE0m51XYGZfEj9Biod7TGdsDC', 1),
('IFS Global', 'ifs@example.com', '$2y$10$GTfP/4SIyx07/XFErztheeMBoIh/QE0m51XYGZfEj9Biod7TGdsDC', 1);

-- Jobs
INSERT INTO jobs (company_id, title, description, requirements, location, posted_on) VALUES
(1, 'Software Engineer', 'Develop enterprise applications', 'Java, SQL, OOP', 'Colombo', CURDATE()),
(1, 'QA Engineer', 'Test enterprise applications', 'Automation, Selenium', 'Kandy', CURDATE()),
(2, 'Business Analyst', 'Analyze client requirements', 'Communication, UML', 'Colombo', CURDATE());

-- CV Templates
INSERT INTO cv_templates (template_name, file_path) VALUES
('Modern Template', 'assets/templates/template1.html'),
('Professional Template', 'assets/templates/template2.html');

-- User CVs
INSERT INTO user_cv (user_id, template_id, content, pdf_path) VALUES
(2, 1, 'Education: BSc IT | Skills: Java, Python, SQL', 'cvs/user2_cv1.pdf'),
(3, 2, 'Education: BSc SE | Skills: HTML, CSS, JS, PHP', 'cvs/user3_cv1.pdf');

-- Applications
INSERT INTO applications (user_id, job_id, status) VALUES
(2, 1, 'applied'),
(3, 2, 'applied');