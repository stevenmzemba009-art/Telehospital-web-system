# 📊 IMPORT DATABASE TO XAMPP - Step-by-Step Guide

**For:** Steven Mzemba's Telemedicine System  
**Date:** December 5, 2025  
**Database File:** database.sql

---

## 🚀 QUICK IMPORT (3 STEPS)

### Option 1: PhpMyAdmin (Easiest) ⭐

**Step 1:** Start XAMPP
```
1. Open XAMPP Control Panel
2. Click "Start" → Apache
3. Click "Start" → MySQL
4. Wait for green status on both
```

**Step 2:** Open phpMyAdmin
```
Go to: http://localhost/phpmyadmin
```

**Step 3:** Import Database File
```
1. Click "Import" tab at the top
2. Click "Choose File" button
3. Select: database.sql (from telemedicine folder)
4. Scroll down and click "Go" button
5. Wait for success message
6. Database created automatically!
```

**Done!** Your database is ready.

---

### Option 2: MySQL Command Line (Advanced)

**Step 1:** Open Command Prompt
```
Press: Windows + R
Type: cmd
Press: Enter
```

**Step 2:** Navigate to MySQL
```
cd C:\xampp\mysql\bin
```

**Step 3:** Import Database
```
mysql -u root -p < "C:\xampp\htdocs\telemedicine\database.sql"
```

**Note:** If XAMPP uses empty password (default), just press Enter when asked for password.

---

### Option 3: PhpMyAdmin SQL Tab (Manual)

**Step 1:** Open phpMyAdmin
```
http://localhost/phpmyadmin
```

**Step 2:** Open SQL Tab
```
Click "SQL" tab in top menu
```

**Step 3:** Copy & Paste SQL
```
1. Open database.sql file (in telemedicine folder)
2. Copy all content (Ctrl+A → Ctrl+C)
3. Paste into phpMyAdmin SQL tab (Ctrl+V)
4. Click "Go" button
```

---

## ✅ VERIFY DATABASE CREATED

### Method 1: PhpMyAdmin Check
```
1. Go to: http://localhost/phpmyadmin
2. On left sidebar, look for "telemedicine" database
3. Click on it
4. Should see all 8 tables listed:
   ✓ users
   ✓ patients
   ✓ consultations
   ✓ pharmacy_tasks
   ✓ visits
   ✓ contact_messages
   ✓ transactions
   ✓ chat_messages
```

### Method 2: Check Default Users
```
1. In phpMyAdmin
2. Click "telemedicine" database
3. Click "users" table
4. Should see 4 users:
   ✓ admin
   ✓ cashier
   ✓ healthcare
   ✓ pharmacist
```

### Method 3: Check Sample Data
```
1. Click "patients" table
2. Should see 4 sample patients
3. Click "consultations" table
4. Should see 3 sample consultations
```

---

## 🔑 DEFAULT USER ACCOUNTS

After import, use these credentials to login:

```
ADMIN
  Username: admin
  Password: admin123

CASHIER  
  Username: cashier
  Password: cashier123

HEALTHCARE PROVIDER
  Username: healthcare
  Password: health123

PHARMACIST
  Username: pharmacist
  Password: pharm123
```

---

## 📋 DATABASE CONTENTS SUMMARY

### Users (4 records)
- admin (Administrator)
- cashier (Cashier Staff)
- healthcare (Healthcare Provider)
- pharmacist (Pharmacist)

### Patients (4 records)
- Ahmed Hassan (45, Male, Diabetes/Hypertension)
- Fatima Mohamed (32, Female, Asthma)
- Ibrahim Ali (58, Male, High Cholesterol)
- Zainab Khan (28, Female, No Known Conditions)

### Consultations (3 records)
- Ahmed Hassan consultation (completed)
- Fatima Mohamed consultation (completed)
- Ibrahim Ali consultation (scheduled)

### Pharmacy Tasks (3 records)
- Amlodipine for Ahmed (completed)
- Albuterol for Fatima (pending)
- Atorvastatin for Ibrahim (pending)

### Visits (3 records)
- 3 sample patient visits recorded

---

## 🚨 TROUBLESHOOTING

### Error: "File not found"
**Solution:**
- Make sure database.sql is in: C:\xampp\htdocs\telemedicine\
- Check file name is exactly: database.sql

### Error: "Database already exists"
**Solution:**
```
1. Go to phpMyAdmin
2. Click on "telemedicine" database (left sidebar)
3. Click "Drop" button
4. Confirm deletion
5. Re-import database.sql
```

### Error: "Access denied"
**Solution:**
- Ensure MySQL is running (check XAMPP Control Panel)
- Try phpMyAdmin import (Option 1) instead of command line
- Check XAMPP credentials (default: root, no password)

### Error: "Syntax error in SQL"
**Solution:**
- Use phpMyAdmin Import (Option 1) - it handles errors better
- Ensure database.sql file is not corrupted
- Download database.sql again if needed

### Error: "Cannot connect to database"
**Solution:**
1. Check MySQL is running in XAMPP
2. Verify database name is "telemedicine"
3. Check config/db.php has correct database name
4. Restart MySQL service in XAMPP

### Tables created but no data showing
**Solution:**
- Import database.sql again (overwrites empty tables)
- Or manually re-run the INSERT statements in phpMyAdmin SQL tab

---

## 🔄 RESET DATABASE

### If You Need to Start Over
```
1. Go to phpMyAdmin
2. Click "telemedicine" database
3. Click "Drop" button to delete
4. Confirm "Yes, drop the database"
5. Re-import database.sql file
```

### Alternative: Use init_db.php
```
1. Go to: http://localhost/telemedicine/config/init_db.php
2. This also resets the database (same result as above)
```

---

## 📁 FILE LOCATIONS

```
Database SQL File:
  C:\xampp\htdocs\telemedicine\database.sql

Database Configuration:
  C:\xampp\htdocs\telemedicine\config\db.php

PHP Initialization Script (Alternative):
  C:\xampp\htdocs\telemedicine\config\init_db.php

PhpMyAdmin URL:
  http://localhost/phpmyadmin

XAMPP MySQL Location:
  C:\xampp\mysql\
```

---

## ✅ AFTER DATABASE IMPORT

### Step 1: Verify Setup
```
Go to: http://localhost/telemedicine/
You should see the landing page
```

### Step 2: Test Login
```
1. Click "Login" button
2. Enter: admin / admin123
3. Click "Login"
4. Should see Admin Dashboard
```

### Step 3: Test Other Roles
```
Logout and login as:
- cashier / cashier123
- healthcare / health123
- pharmacist / pharm123
```

### Step 4: Explore Data
```
1. As Healthcare Provider, click "Consultations"
2. Should see 3 sample consultations
3. Click "Patients" should see 4 sample patients
4. As Pharmacist, click "Pharmacy Tasks" 
5. Should see 3 sample medication tasks
```

---

## 💾 BACKUP YOUR DATABASE

### After You Import - Create Backup!

**Step 1:** Open phpMyAdmin
```
http://localhost/phpmyadmin
```

**Step 2:** Select Database
```
Click "telemedicine" on left sidebar
```

**Step 3:** Export (Backup)
```
1. Click "Export" tab
2. Click "Go" button
3. Downloads as: telemedicine.sql
4. Save this file safely - it's your backup!
```

**Step 4:** Keep Safe
```
- Keep backup in safe location
- Back up regularly (daily/weekly)
- Use for disaster recovery
```

---

## 🔧 DATABASE CONFIGURATION

### If Database Import Fails - Check Config

**File:** config/db.php
```
Should contain:
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Empty for XAMPP default
define('DB_NAME', 'telemedicine');
```

### Edit if Needed
```
1. Open config/db.php in text editor
2. Update DB_HOST, DB_USER, DB_PASS if different
3. Save file
4. Try importing again
```

---

## 📊 DATABASE SCHEMA REFERENCE

All 8 tables created with:
- ✓ Primary Keys (auto-increment)
- ✓ Foreign Key Relationships
- ✓ Proper Indexes
- ✓ Character Set: utf8mb4
- ✓ Collation: utf8mb4_unicode_ci
- ✓ Engine: InnoDB (supports transactions)

---

## 🎯 NEXT STEPS AFTER DATABASE IMPORT

1. ✅ Verify database imported (section above)
2. ✅ Test login (admin/admin123)
3. ✅ Explore each dashboard (4 roles)
4. ✅ Check sample data loads
5. ✅ Create backup of database
6. ✅ Start using system!

---

## 📞 QUICK REFERENCE URLS

```
PhpMyAdmin (Database Manager):
  http://localhost/phpmyadmin

Telemedicine Landing Page:
  http://localhost/telemedicine/

Dashboard (After Login):
  http://localhost/telemedicine/dashboard.php

XAMPP Home:
  http://localhost/xampp
```

---

## ✨ YOU'RE READY!

After completing this import:
- ✅ Database created automatically
- ✅ All tables set up
- ✅ Sample data included
- ✅ Default users ready
- ✅ System fully operational

**Next:** Go to http://localhost/telemedicine/ and login!

---

**Database File:** database.sql  
**Size:** ~15 KB  
**Tables:** 8  
**Records:** 15+  
**Status:** Ready to Import  

**Estimated Import Time:** 5 seconds
