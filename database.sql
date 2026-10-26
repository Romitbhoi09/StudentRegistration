CREATE DATABASE IF NOT EXISTS student_registration;
USE student_registration;

CREATE TABLE IF NOT EXISTS student_login (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS student_registration (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    dob DATE NULL,
    phone VARCHAR(20) NULL,
    course VARCHAR(100) NULL,
    address VARCHAR(255) NULL,
    FOREIGN KEY (student_id) REFERENCES student_login(student_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS document_track (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    document_name VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_login(student_id) ON DELETE CASCADE
);
