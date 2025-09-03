-- Drop tables if they exist to start fresh
DROP TABLE IF EXISTS cv_downloads;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS cvs;
DROP TABLE IF EXISTS cv_templates;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS users;

-- ========================
-- 1. Users Table
-- ========================
CREATE TABLE users (
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
CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- 3. Jobs Table
-- ========================
CREATE TABLE jobs (
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
CREATE TABLE applications (
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
-- 5. CV Templates Table (corrected)
-- ========================
CREATE TABLE cv_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_name VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    preview_image_path VARCHAR(255) NULL
);

-- ========================
-- 6. User CV Table
-- ========================
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    fullname VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(40),
    linkedin VARCHAR(200),
    about TEXT,
    education TEXT,
    work_experiences TEXT,
    projects TEXT,
    skills TEXT,
    template VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- 7. CV Downloads Table
-- ========================
CREATE TABLE cv_downloads (
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
INSERT INTO companies (company_name, email, password, verified, status) VALUES
('Virtusa Pvt Ltd', 'virtusa@example.com', '$2y$10$GTfP/4SIyx07/XFErztheeMBoIh/QE0m51XYGZfEj9Biod7TGdsDC', 1, 'approved'),
('IFS Global', 'ifs@example.com', '$2y$10$GTfP/4SIyx07/XFErztheeMBoIh/QE0m51XYGZfEj9Biod7TGdsDC', 1, 'pending');

-- Jobs
INSERT INTO jobs (company_id, title, description, requirements, location, posted_on) VALUES
(1, 'Software Engineer', 'Develop enterprise applications', 'Java, SQL, OOP', 'Colombo', CURDATE()),
(1, 'QA Engineer', 'Test enterprise applications', 'Automation, Selenium', 'Kandy', CURDATE()),
(2, 'Business Analyst', 'Analyze client requirements', 'Communication, UML', 'Colombo', CURDATE());

-- CV Templates (with a placeholder for the preview image path)
INSERT INTO cv_templates (template_name, file_path, preview_image_path) VALUES
('Modern Template', 'assets/templates/template1.html', 'assets/previews/preview1.jpg'),
('Professional Template', 'assets/templates/template2.html', 'assets/previews/preview2.jpg');

-- User CVs
INSERT INTO cvs (user_id, fullname, email, phone, linkedin, about, education, work_experiences, projects, skills, template) VALUES
(2, 'Thilini Samanthika', 'thilini@example.com', '1234567890', 'linkedin.com/in/thilini', 'A passionate developer.', 'BSc IT', 'Software Intern', 'Project A', 'Java, Python', 'Modern Template'),
(3, 'Sangeeth Weerasigha', 'sangeeth@example.com', '0987654321', 'linkedin.com/in/sangeeth', 'A creative designer.', 'BSc SE', 'UI/UX Designer', 'Project B', 'HTML, CSS, JS', 'Professional Template');

-- Applications
INSERT INTO applications (user_id, job_id, status) VALUES
(2, 1, 'applied'),
(3, 2, 'applied');

-- CV Downloads (just for demonstration)
INSERT INTO cv_downloads (user_id, job_id, downloaded_on) VALUES
(2, 1, CURDATE()),
(3, 2, CURDATE());