# Telemedicine Application - PHP/XAMPP Setup Guide

## Overview
Your telemedicine application has been converted to PHP and is now compatible with XAMPP. It includes a complete MySQL database with user authentication, patient management, consultations, pharmacy tasks, and contact messaging.

## Project Structure

```
telemedicine/
├── index.php                 # Home page (converted from index.html)
├── dashboard.php             # Dashboard (converted from dashboard.html)
├── styles.css                # Unchanged CSS styles
├── config/
│   ├── db.php               # Database connection
│   └── init_db.php          # Database initialization script
├── api/
│   ├── index.php            # Main API router
│   ├── auth.php             # Authentication endpoints
│   ├── patients.php         # Patient management
│   ├── consultations.php    # Consultation management
│   ├── pharmacy.php         # Pharmacy tasks
│   ├── admin.php            # Admin operations
│   ├── cashier.php          # Cashier operations
│   └── contact.php          # Contact form handling
└── images/                   # Image folder
```

## Installation & Setup

### Step 1: Install XAMPP
1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP with PHP and MySQL

### Step 2: Place Project in XAMPP htdocs
```
Copy the entire telemedicine folder to: C:\xampp\htdocs\telemedicine
```

### Step 3: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

### Step 4: Initialize the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Copy the contents of `config/init_db.php` and execute it
   - Or: Navigate to `http://localhost/telemedicine/config/init_db.php` in your browser

The database will be created automatically with:
- **Database name:** telemedicine
- **Tables:** users, patients, consultations, pharmacy_tasks, visits, contact_messages, transactions, chat_messages
- **Default users:** admin, cashier, health, pharm (all with password: 'pass')

### Step 5: Access the Application
Open your browser and navigate to:
```
http://localhost/telemedicine/
```

## Database Schema

### Users Table
- id (Primary Key)
- username (Unique)
- password (Bcrypt hashed)
- role (admin, cashier, healthcare, pharmacist)
- created_at

### Patients Table
- id (Primary Key)
- name, phone, email, address
- medical_history, sex, age, village, contact
- created_at, updated_at

### Consultations Table
- id (Primary Key)
- patient_id (Foreign Key)
- provider_id (Foreign Key)
- consultation_date, notes
- status (scheduled, completed, cancelled)

### Pharmacy Tasks Table
- id (Primary Key)
- patient_id (Foreign Key)
- medication_name, dosage, refill_date
- status (pending, completed, cancelled)

### Visits Table
- id (Primary Key)
- patient_id (Foreign Key)
- scheduled_date, role, status

### Contact Messages Table
- id (Primary Key)
- name, phone, email, message
- reply, replied_by, created_at

### Transactions Table
- id (Primary Key)
- cashier_id, patient_id
- amount, description, transaction_date

### Chat Messages Table
- id (Primary Key)
- sender_id, message, created_at

## API Endpoints

### Authentication
- **POST** `/api/index.php?action=login` - User login
- **POST** `/api/index.php?action=signup` - Create new account

### Patients
- **GET** `/api/index.php?action=patients` - Get all patients
- **GET** `/api/index.php?action=patients/1` - Get patient by ID
- **POST** `/api/index.php?action=register` - Register new patient
- **PUT** `/api/index.php?action=patients/1` - Update patient

### Consultations
- **GET** `/api/index.php?action=consultations` - Get all consultations

### Pharmacy
- **GET** `/api/index.php?action=pharmacy/tasks` - Get pharmacy tasks

### Admin
- **GET** `/api/index.php?action=admin/summary?period=today` - Admin summary
- **GET** `/api/index.php?action=admin/messages` - Get contact messages
- **POST** `/api/index.php?action=admin/messages/1/reply` - Reply to message
- **GET** `/api/index.php?action=admin/absences` - Get absent patients

### Cashier
- **GET** `/api/index.php?action=cashier/summary?period=today` - Cashier summary

### Contact
- **POST** `/api/index.php?action=contact` - Submit contact form

## Default Login Credentials

| Username | Password | Role |
|----------|----------|------|
| admin | pass | Admin |
| cashier | pass | Cashier |
| health | pass | Healthcare Provider |
| pharm | pass | Pharmacist |

## Authentication & JWT

The application uses simplified JWT tokens for authentication:
- Tokens are valid for 7 days
- Include token in request headers: `x-auth-token: <token>`
- Tokens are stored in browser localStorage

## Configuration

To change database credentials, edit `config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // XAMPP default: empty
define('DB_NAME', 'telemedicine');
```

## Troubleshooting

### Connection Error
- **Issue:** "Database connection failed"
- **Solution:** Make sure MySQL service is running in XAMPP

### Table Not Found
- **Issue:** "SQLSTATE[42S02]..."
- **Solution:** Run `http://localhost/telemedicine/config/init_db.php` to initialize database

### Login Fails
- **Issue:** "Invalid credentials"
- **Solution:** 
  1. Check database is initialized
  2. Verify default users exist in users table
  3. Check browser console for JavaScript errors

### API Returns 401 Unauthorized
- **Issue:** "Unauthorized"
- **Solution:** 
  1. Ensure you're logged in
  2. Check localStorage has 'tele_token'
  3. Token may have expired

## Features

✅ User authentication (admin, cashier, healthcare, pharmacist)
✅ Patient registration and management
✅ Consultation scheduling
✅ Pharmacy task management
✅ Contact message system
✅ Admin dashboard with summaries
✅ Cashier transaction tracking
✅ Visit scheduling and attendance
✅ Medical history tracking
✅ Role-based access control

## Next Steps

1. Customize user roles and permissions
2. Add SMS/Email integration for reminders
3. Implement file uploads for patient documents
4. Add reporting and analytics
5. Implement real-time notifications
6. Add payment processing

## Support

For issues or questions, contact: stevenmzemba@gmail.com

---

**Created:** 2025
**Contact:** Steven Mzemba - 0999564297
