-- =====================================================
-- TELEMEDICINE - Supabase/Postgres Schema
-- Converted for PostgreSQL / Supabase import
-- Date: 2025-12-06
-- NOTE: This file is intended for use with Supabase SQL editor or psql.
--       Some MySQL-specific constructs (ENUM, AUTO_INCREMENT, ENGINE, CHARSET) were adapted.
-- IMPORTANT: Replace password placeholders with properly hashed passwords using bcrypt before putting into production.
-- =====================================================

-- Drop tables if they exist (use with care)
DROP TABLE IF EXISTS department_access_logs CASCADE;
DROP TABLE IF EXISTS real_time_feeds CASCADE;
DROP TABLE IF EXISTS reminders CASCADE;
DROP TABLE IF EXISTS attendance_tracking CASCADE;
DROP TABLE IF EXISTS sms_messages CASCADE;
DROP TABLE IF EXISTS chat_messages CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS contact_messages CASCADE;
DROP TABLE IF EXISTS visits CASCADE;
DROP TABLE IF EXISTS pharmacy_tasks CASCADE;
DROP TABLE IF EXISTS consultations CASCADE;
DROP TABLE IF EXISTS patients CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Users
CREATE TABLE users (
  id bigserial PRIMARY KEY,
  username varchar(100) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  email varchar(150) NOT NULL,
  role varchar(32) NOT NULL CHECK (role IN ('admin','cashier','healthcare','pharmacist')),
  full_name varchar(150) NOT NULL,
  created_at timestamptz DEFAULT now()
);

-- Patients
CREATE TABLE patients (
  id bigserial PRIMARY KEY,
  name varchar(150) NOT NULL,
  phone varchar(50) NOT NULL,
  email varchar(150),
  address text,
  medical_history text,
  sex varchar(10),
  age integer,
  village varchar(100),
  created_by bigint NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  created_at timestamptz DEFAULT now()
);

-- Consultations
CREATE TABLE consultations (
  id bigserial PRIMARY KEY,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  provider_id bigint NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  consultation_date timestamptz NOT NULL,
  notes text,
  diagnosis text,
  status varchar(20) DEFAULT 'scheduled',
  created_at timestamptz DEFAULT now()
);

-- Pharmacy tasks
CREATE TABLE pharmacy_tasks (
  id bigserial PRIMARY KEY,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  medication_name varchar(200) NOT NULL,
  dosage varchar(100),
  frequency varchar(100),
  duration varchar(100),
  status varchar(20) DEFAULT 'pending',
  requested_by bigint NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  completed_by bigint REFERENCES users(id) ON DELETE SET NULL,
  created_at timestamptz DEFAULT now()
);

-- Visits
CREATE TABLE visits (
  id bigserial PRIMARY KEY,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  provider_id bigint NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  visit_date timestamptz NOT NULL,
  notes text,
  created_at timestamptz DEFAULT now()
);

-- Contact messages
CREATE TABLE contact_messages (
  id bigserial PRIMARY KEY,
  name varchar(150) NOT NULL,
  phone varchar(50),
  email varchar(150),
  subject varchar(200),
  message text NOT NULL,
  status varchar(20) DEFAULT 'new',
  created_at timestamptz DEFAULT now()
);

-- Transactions
CREATE TABLE transactions (
  id bigserial PRIMARY KEY,
  cashier_id bigint NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  amount numeric(12,2) NOT NULL,
  description varchar(255),
  type varchar(20) DEFAULT 'payment',
  reference_number varchar(50) UNIQUE,
  created_at timestamptz DEFAULT now()
);

-- Chat messages
CREATE TABLE chat_messages (
  id bigserial PRIMARY KEY,
  sender_id bigint NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  receiver_id bigint NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  message text NOT NULL,
  read_at timestamptz,
  created_at timestamptz DEFAULT now()
);

-- SMS messages
CREATE TABLE sms_messages (
  id bigserial PRIMARY KEY,
  sender_id bigint NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  patient_id bigint REFERENCES patients(id) ON DELETE SET NULL,
  phone_number varchar(50) NOT NULL,
  message_text text NOT NULL,
  message_type varchar(20) DEFAULT 'outgoing',
  status varchar(20) DEFAULT 'pending',
  sms_gateway_id varchar(200),
  created_at timestamptz DEFAULT now(),
  delivered_at timestamptz,
  read_at timestamptz
);

-- Attendance tracking
CREATE TABLE attendance_tracking (
  id bigserial PRIMARY KEY,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  appointment_id bigint REFERENCES consultations(id) ON DELETE SET NULL,
  attendance_date date NOT NULL,
  status varchar(20) DEFAULT 'present',
  check_in_time timestamptz,
  check_out_time timestamptz,
  notes text,
  consecutive_absences integer DEFAULT 0,
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

-- Reminders
CREATE TABLE reminders (
  id bigserial PRIMARY KEY,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  reminder_type varchar(20) DEFAULT 'appointment',
  title varchar(255) NOT NULL,
  message text NOT NULL,
  phone_number varchar(50),
  scheduled_at timestamptz NOT NULL,
  sent_at timestamptz,
  status varchar(20) DEFAULT 'pending',
  retry_count integer DEFAULT 0,
  created_at timestamptz DEFAULT now()
);

-- Real-time feeds
CREATE TABLE real_time_feeds (
  id bigserial PRIMARY KEY,
  event_type varchar(50) DEFAULT 'consultation',
  reference_id bigint,
  patient_id bigint NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
  created_by_id bigint NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  created_by_role varchar(32),
  event_data jsonb,
  timestamp timestamptz DEFAULT now()
);

-- Department access logs
CREATE TABLE department_access_logs (
  id bigserial PRIMARY KEY,
  user_id bigint NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  department varchar(32) NOT NULL,
  action varchar(255) NOT NULL,
  resource_type varchar(100),
  resource_id bigint,
  ip_address varchar(45),
  timestamp timestamptz DEFAULT now()
);

-- Indexes (for performance)
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_patients_phone ON patients(phone);
CREATE INDEX idx_consultations_patient ON consultations(patient_id);
CREATE INDEX idx_real_time_patient ON real_time_feeds(patient_id);

-- Sample data (optional):
-- NOTE: Passwords must be hashed; these are placeholders and will NOT work as-is.
-- Use a script or psql to insert real password hashes using bcrypt.

-- INSERT INTO users (username, password, email, role, full_name) VALUES
-- ('admin', '$2y$10$YourHashedPasswordHere1', 'admin@telemedicine.com', 'admin', 'Administrator');

-- End of Supabase/Postgres schema
