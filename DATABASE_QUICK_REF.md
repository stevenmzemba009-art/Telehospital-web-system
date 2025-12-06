# 🎯 DATABASE IMPORT - QUICK REFERENCE CARD

**Date:** December 5, 2025  
**System:** Telemedicine for Steven Mzemba  
**Database File:** database.sql

---

## ⚡ FASTEST WAY (2 MINUTES)

### Step 1: Start XAMPP
```
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL
```

### Step 2: Import Database
```
1. Go to: http://localhost/phpmyadmin
2. Click "Import" tab
3. Click "Choose File"
4. Select: database.sql
5. Click "Go"
6. Done! ✅
```

### Step 3: Test
```
1. Go to: http://localhost/telemedicine/
2. Click "Login"
3. Enter: admin / admin123
4. Click "Login"
```

---

## 📋 LOGIN CREDENTIALS

After importing database.sql, use:

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Cashier | `cashier` | `cashier123` |
| Healthcare | `healthcare` | `health123` |
| Pharmacist | `pharmacist` | `pharm123` |

---

## ✅ VERIFY IMPORT WORKED

### Check in PhpMyAdmin:

✓ "telemedicine" database exists  
✓ 8 tables present (users, patients, consultations, etc.)  
✓ 4 users in users table  
✓ 4 patients in patients table  
✓ 3 consultations in consultations table  

---

## 🚨 IF IMPORT FAILS

### Solution 1: Delete & Try Again
```
1. PhpMyAdmin → telemedicine database
2. Click "Drop" 
3. Click "Yes"
4. Re-import database.sql
```

### Solution 2: Use PHP Script
```
Go to: http://localhost/telemedicine/config/init_db.php
This creates database from scratch
```

### Solution 3: Check MySQL Running
```
1. XAMPP Control Panel
2. Make sure MySQL shows green "Running"
3. If not, click "Start" for MySQL
4. Try import again
```

---

## 📁 FILES NEEDED

```
✅ database.sql .................. Import this file
✅ config/db.php ................. Already configured
✅ config/init_db.php ............ Backup option
```

---

## 🔗 IMPORTANT URLS

```
Import Database:
  http://localhost/phpmyadmin

Start Using System:
  http://localhost/telemedicine/

After Login:
  http://localhost/telemedicine/dashboard.php
```

---

## 📖 DETAILED GUIDES

For more detailed instructions, read:
- **IMPORT_DATABASE.md** - Step-by-step import guide
- **QUICK_START.md** - 5-minute setup guide
- **DATABASE_SETUP.md** - Database information

---

## ✨ THAT'S IT!

Your database is now ready to use!

**Next:** Login with admin/admin123 and explore!

---

Generated: December 5, 2025  
For: Steven Mzemba's Telemedicine System
