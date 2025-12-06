-- =====================================================
-- TELEMEDICINE MANAGEMENT SYSTEM - Database Setup
-- Owner: Steven Mzemba
-- Database Name: telemedicine
-- Created: December 5, 2025
-- =====================================================

-- Drop existing database if it exists
DROP DATABASE IF EXISTS `telemedicine`;

-- Create the database
CREATE DATABASE `telemedicine` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE `telemedicine`;

-- =====================================================
-- TABLE 1: USERS
-- =====================================================
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `role` ENUM('admin', 'cashier', 'healthcare', 'pharmacist') NOT NULL DEFAULT 'healthcare',
  `full_name` VARCHAR(150) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_username` (`username`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 2: PATIENTS
-- =====================================================
CREATE TABLE `patients` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150),
  `address` VARCHAR(255),
  `medical_history` TEXT,
  `sex` ENUM('M', 'F', 'Other'),
  `age` INT,
  `village` VARCHAR(100),
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
  INDEX `idx_phone` (`phone`),
  INDEX `idx_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 3: CONSULTATIONS
-- =====================================================
CREATE TABLE `consultations` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `provider_id` INT NOT NULL,
  `consultation_date` DATETIME NOT NULL,
  `notes` TEXT,
  `diagnosis` TEXT,
  `status` ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`provider_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_provider_id` (`provider_id`),
  INDEX `idx_consultation_date` (`consultation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 4: PHARMACY_TASKS
-- =====================================================
CREATE TABLE `pharmacy_tasks` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `medication_name` VARCHAR(200) NOT NULL,
  `dosage` VARCHAR(100),
  `frequency` VARCHAR(100),
  `duration` VARCHAR(100),
  `status` ENUM('pending', 'completed') DEFAULT 'pending',
  `requested_by` INT NOT NULL,
  `completed_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`completed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_requested_by` (`requested_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 5: VISITS
-- =====================================================
CREATE TABLE `visits` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `provider_id` INT NOT NULL,
  `visit_date` DATETIME NOT NULL,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`provider_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_provider_id` (`provider_id`),
  INDEX `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 6: CONTACT_MESSAGES
-- =====================================================
CREATE TABLE `contact_messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20),
  `email` VARCHAR(150),
  `subject` VARCHAR(200),
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 7: TRANSACTIONS
-- =====================================================
CREATE TABLE `transactions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `cashier_id` INT NOT NULL,
  `patient_id` INT NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `description` VARCHAR(255),
  `type` ENUM('payment', 'refund', 'charge') DEFAULT 'payment',
  `reference_number` VARCHAR(50) UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cashier_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  INDEX `idx_cashier_id` (`cashier_id`),
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_reference_number` (`reference_number`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 8: CHAT_MESSAGES
-- =====================================================
CREATE TABLE `chat_messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `receiver_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `read_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_sender_id` (`sender_id`),
  INDEX `idx_receiver_id` (`receiver_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 9: SMS_MESSAGES (OFFLINE SMS CHAT)
-- =====================================================
CREATE TABLE `sms_messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `patient_id` INT,
  `phone_number` VARCHAR(20) NOT NULL,
  `message_text` TEXT NOT NULL,
  `message_type` ENUM('outgoing', 'incoming') DEFAULT 'outgoing',
  `status` ENUM('pending', 'sent', 'delivered', 'failed', 'read') DEFAULT 'pending',
  `sms_gateway_id` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` TIMESTAMP NULL,
  `read_at` TIMESTAMP NULL,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE SET NULL,
  INDEX `idx_sender_id` (`sender_id`),
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_phone_number` (`phone_number`),
  INDEX `idx_status` (`status`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 10: ATTENDANCE_TRACKING
-- =====================================================
CREATE TABLE `attendance_tracking` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `appointment_id` INT,
  `attendance_date` DATE NOT NULL,
  `status` ENUM('present', 'absent', 'cancelled') DEFAULT 'present',
  `check_in_time` DATETIME,
  `check_out_time` DATETIME,
  `notes` TEXT,
  `consecutive_absences` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`appointment_id`) REFERENCES `consultations`(`id`) ON DELETE SET NULL,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_attendance_date` (`attendance_date`),
  INDEX `idx_status` (`status`),
  INDEX `idx_consecutive_absences` (`consecutive_absences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 11: REMINDERS
-- =====================================================
CREATE TABLE `reminders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `reminder_type` ENUM('absent', 'appointment', 'follow_up', 'medication') DEFAULT 'appointment',
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `phone_number` VARCHAR(20),
  `scheduled_at` DATETIME NOT NULL,
  `sent_at` TIMESTAMP NULL,
  `status` ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
  `retry_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_reminder_type` (`reminder_type`),
  INDEX `idx_status` (`status`),
  INDEX `idx_scheduled_at` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 12: REAL_TIME_FEEDS (MULTI-DEPARTMENT DATA SHARING)
-- =====================================================
CREATE TABLE `real_time_feeds` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `event_type` ENUM('consultation', 'prescription', 'payment', 'attendance', 'message') DEFAULT 'consultation',
  `reference_id` INT,
  `patient_id` INT NOT NULL,
  `created_by_id` INT NOT NULL,
  `created_by_role` ENUM('admin', 'cashier', 'healthcare', 'pharmacist'),
  `event_data` JSON,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_patient_id` (`patient_id`),
  INDEX `idx_event_type` (`event_type`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_created_by_id` (`created_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE 13: DEPARTMENT_ACCESS_LOGS
-- =====================================================
CREATE TABLE `department_access_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `department` ENUM('admin', 'cashier', 'healthcare', 'pharmacist') NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `resource_type` VARCHAR(100),
  `resource_id` INT,
  `ip_address` VARCHAR(45),
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_department` (`department`),
  INDEX `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT USERS
-- =====================================================

-- Admin User
INSERT INTO `users` (`username`, `password`, `email`, `role`, `full_name`) VALUES
('admin', '$2y$10$YourHashedPasswordHere1', 'admin@telemedicine.com', 'admin', 'Administrator');

-- Cashier User
INSERT INTO `users` (`username`, `password`, `email`, `role`, `full_name`) VALUES
('cashier', '$2y$10$YourHashedPasswordHere2', 'cashier@telemedicine.com', 'cashier', 'Cashier Staff');

-- Healthcare Provider User
INSERT INTO `users` (`username`, `password`, `email`, `role`, `full_name`) VALUES
('healthcare', '$2y$10$YourHashedPasswordHere3', 'health@telemedicine.com', 'healthcare', 'Healthcare Provider');

-- Pharmacist User
INSERT INTO `users` (`username`, `password`, `email`, `role`, `full_name`) VALUES
('pharmacist', '$2y$10$YourHashedPasswordHere4', 'pharmacist@telemedicine.com', 'pharmacist', 'Pharmacist');

-- =====================================================
-- INSERT SAMPLE PATIENTS
-- =====================================================
INSERT INTO `patients` (`name`, `phone`, `email`, `address`, `medical_history`, `sex`, `age`, `village`, `created_by`) VALUES
('Ahmed Hassan', '555-1001', 'ahmed@example.com', '123 Main Street', 'Diabetes, Hypertension', 'M', 45, 'Downtown', 2),
('Fatima Mohamed', '555-1002', 'fatima@example.com', '456 Oak Road', 'Asthma', 'F', 32, 'Uptown', 2),
('Ibrahim Ali', '555-1003', 'ibrahim@example.com', '789 Pine Lane', 'High Cholesterol', 'M', 58, 'Midtown', 2),
('Zainab Khan', '555-1004', 'zainab@example.com', '321 Elm Street', 'No Known Conditions', 'F', 28, 'Westside', 2);

-- =====================================================
-- INSERT SAMPLE CONSULTATIONS
-- =====================================================
INSERT INTO `consultations` (`patient_id`, `provider_id`, `consultation_date`, `notes`, `diagnosis`, `status`) VALUES
(1, 3, '2025-01-10 10:00:00', 'Patient complained of fatigue and headaches', 'Possible hypertensive crisis - recommend medication adjustment', 'completed'),
(2, 3, '2025-01-12 14:30:00', 'Follow-up for asthma management', 'Asthma well controlled with current inhaler', 'completed'),
(3, 3, '2025-01-15 09:00:00', 'Annual checkup', 'High cholesterol detected - recommend diet and exercise', 'scheduled');

-- =====================================================
-- INSERT SAMPLE PHARMACY TASKS
-- =====================================================
INSERT INTO `pharmacy_tasks` (`patient_id`, `medication_name`, `dosage`, `frequency`, `duration`, `status`, `requested_by`, `completed_by`) VALUES
(1, 'Amlodipine', '5mg', 'Once daily', '30 days', 'completed', 3, 4),
(2, 'Albuterol Inhaler', 'As needed', 'When symptomatic', 'Continuous', 'pending', 3, NULL),
(3, 'Atorvastatin', '20mg', 'Once daily at night', '30 days', 'pending', 3, NULL);

-- =====================================================
-- INSERT SAMPLE VISITS
-- =====================================================
INSERT INTO `visits` (`patient_id`, `provider_id`, `visit_date`, `notes`) VALUES
(1, 3, '2025-01-10 10:30:00', 'Initial consultation for hypertension management'),
(2, 3, '2025-01-12 14:45:00', 'Asthma control assessment'),
(4, 3, '2025-01-14 11:00:00', 'General health screening');

-- =====================================================
-- DATABASE SETUP COMPLETE
-- =====================================================

-- To use this database with the PHP application:
-- 1. Import this SQL file into phpMyAdmin or MySQL command line
-- 2. The database "telemedicine" will be created
-- 3. Update config/db.php with correct database credentials if needed:
--    - DB_HOST: localhost
--    - DB_USER: root
--    - DB_PASS: (empty for XAMPP default)
--    - DB_NAME: telemedicine

-- Note: Password hashes above are placeholders. They will be replaced by the init_db.php script
-- which uses proper password_hash() function with actual passwords.

-- Passwords are:
-- admin: admin123
-- cashier: cashier123
-- healthcare: health123
-- pharmacist: pharm123

-- For production use, generate proper password hashes using PHP:
-- echo password_hash('admin123', PASSWORD_DEFAULT);
-- And replace the hashes in the INSERT statements above.
