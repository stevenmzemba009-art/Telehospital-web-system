# Telemedicine Database Setup Guide

## Prerequisites
- XAMPP installed and running (Apache + MySQL)
- Telemedicine project files in `C:\xampp\htdocs\telemedicine\`

## Database Setup Steps

### 1. Start XAMPP Services
```
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL
4. Wait for both to show "Running" status
```

### 2. Initialize Database
```
1. Open browser and go to: http://localhost/telemedicine/config/init_db.php
2. You should see success message: "Database initialized successfully"
3. This creates all 8 tables and adds default data
```

### 3. Verify Database Creation
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Login (usually no password required for XAMPP root)
3. On left sidebar, click "telemedicine" database
4. Verify these tables exist:
   - users
   - patients
   - consultations
   - pharmacy_tasks
   - visits
   - contact_messages
   - transactions
   - chat_messages
```

## Database Schema Summary

### users table
```
id (PRIMARY KEY)
username (UNIQUE)
password (hashed)
email
role (admin, cashier, healthcare, pharmacist)
full_name
created_at
```

**Default Users:**
- admin / admin123 (role: admin)
- cashier / cashier123 (role: cashier)
- healthcare / health123 (role: healthcare)
- pharmacist / pharm123 (role: pharmacist)

### patients table
```
id (PRIMARY KEY)
name
phone
email
address
medical_history
sex
age
village
created_by (FOREIGN KEY → users.id)
created_at
```

**Sample Data:** 4 patients

### consultations table
```
id (PRIMARY KEY)
patient_id (FOREIGN KEY → patients.id)
provider_id (FOREIGN KEY → users.id)
consultation_date
notes
diagnosis
status (scheduled, completed, cancelled)
created_at
```

**Sample Data:** 3 consultations

### pharmacy_tasks table
```
id (PRIMARY KEY)
patient_id (FOREIGN KEY → patients.id)
medication_name
dosage
frequency
duration
status (pending, completed)
requested_by (FOREIGN KEY → users.id)
completed_by (FOREIGN KEY → users.id, nullable)
created_at
```

**Sample Data:** 3 tasks

### visits table
```
id (PRIMARY KEY)
patient_id (FOREIGN KEY → patients.id)
provider_id (FOREIGN KEY → users.id)
visit_date
notes
created_at
```

**Sample Data:** 3 visits

### contact_messages table
```
id (PRIMARY KEY)
name
phone
email
subject
message
status (new, read, replied)
created_at
```

### transactions table
```
id (PRIMARY KEY)
cashier_id (FOREIGN KEY → users.id)
patient_id (FOREIGN KEY → patients.id)
amount
description
type (payment, refund, charge)
reference_number (auto-generated: TRX-YYYYMMDDHHmmss-XXXX)
created_at
```

### chat_messages table
```
id (PRIMARY KEY)
sender_id (FOREIGN KEY → users.id)
receiver_id (FOREIGN KEY → users.id)
message
read_at (nullable)
created_at
```

## Reset Database
To reset database to defaults at any time:
```
1. Navigate to: http://localhost/telemedicine/config/init_db.php
2. This will DROP all tables and recreate them with default data
3. All custom data will be lost
```

## Backup Database
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select "telemedicine" database
3. Click "Export" tab
4. Click "Go" to download SQL backup file
```

## Restore from Backup
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "Import" tab
3. Select your backup SQL file
4. Click "Go" to restore
```

## Troubleshooting

### Error: "Connection refused"
- Ensure MySQL is running in XAMPP
- Check port 3306 is available
- Restart MySQL service

### Error: "Database does not exist"
- Run init_db.php to create database
- Check database name is "telemedicine"

### Error: "Table already exists"
- Run init_db.php (it drops and recreates tables)
- Or manually delete tables in phpMyAdmin

### Data Not Saving
- Verify database connection in config/db.php
- Check user permissions (should have full permissions on "telemedicine" database)
- Review MySQL error logs in XAMPP folder

## Next Steps
After database setup:
1. Test login at http://localhost/telemedicine/
2. Try each role's dashboard
3. Test API endpoints with sample data
4. Verify all features working before production
