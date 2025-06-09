CREATE DATABASE clinic_db;

USE clinic_db;

-- Пользователи системы
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    access_code VARCHAR(10) UNIQUE NOT NULL,
    role ENUM('manager', 'doctor') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Пациенты
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    encrypted_data TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Записи на прием
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    diagnosis TEXT,
    recommendations TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Вставка тестовых пользователей
INSERT INTO users (access_code, role, full_name) VALUES 
('1234567890', 'manager', 'Иванов Иван Иванович'),
('0987654321', 'doctor', 'Петров Петр Петрович');
