# ✅ STARTUP CHECKLIST & VERIFICATION

**System:** Telemedicine Management System  
**Owner:** Steven Mzemba  
**Date:** December 4-5, 2025  
**Status:** ✅ Production Ready

---

## 📋 PRE-STARTUP CHECKLIST

### Phase 1: Preparation
- [ ] XAMPP installed on computer
- [ ] Apache service available
- [ ] MySQL service available
- [ ] Project folder at: C:\xampp\htdocs\telemedicine\
- [ ] All project files present
- [ ] Sufficient disk space available

### Phase 2: Services
- [ ] XAMPP Control Panel opened
- [ ] Apache server started (green status)
- [ ] MySQL server started (green status)
- [ ] Both services running stably
- [ ] No port conflicts (80, 3306)

### Phase 3: Database
- [ ] MySQL database created (auto via init_db.php)
- [ ] 8 tables created in database
- [ ] Default users inserted (4 accounts)
- [ ] Sample data populated (15+ records)
- [ ] Foreign keys established

### Phase 4: Files
- [ ] config/db.php present
- [ ] config/init_db.php present
- [ ] api/ folder with 7+ API files
- [ ] index.php present
- [ ] dashboard.php present
- [ ] styles.css present
- [ ] All documentation files present

---

## 🚀 5-MINUTE STARTUP

### Step 1: Start Services (1 minute)
```
□ Open XAMPP Control Panel
□ Click "Start" for Apache (watch for green)
□ Click "Start" for MySQL (watch for green)
□ Wait 10 seconds for services to stabilize
```

### Step 2: Initialize Database (1 minute)
```
□ Open web browser
□ Go to: http://localhost/telemedicine/config/init_db.php
□ Wait for page to load completely
□ Look for success message: "Database initialized successfully"
□ Do NOT close browser window yet
```

### Step 3: Access System (1 minute)
```
□ Go to: http://localhost/telemedicine/
□ Page should load with telemedicine header
□ Click "Login" button
□ Login modal should appear
```

### Step 4: Test Login (1 minute)
```
□ Username field: type "admin"
□ Password field: type "admin123"
□ Role dropdown: select "Admin"
□ Click "Login" button
□ Should redirect to dashboard.php
```

### Step 5: Verify (1 minute)
```
□ Dashboard shows "Admin Dashboard"
□ User info shows "admin" in header
□ Sidebar menu has: Users, Messages, Reports
□ Can see logout button
□ No errors in browser console (F12)
```

---

## ✅ VERIFICATION CHECKLIST

### Database Verification
```
□ Run init_db.php successfully
□ Open http://localhost/phpmyadmin/
□ Select "telemedicine" database from left sidebar
□ Verify these tables exist:
  □ users
  □ patients
  □ consultations
  □ pharmacy_tasks
  □ visits
  □ contact_messages
  □ transactions
  □ chat_messages
□ Click "users" table
□ Verify 4 default users present:
  □ admin
  □ cashier
  □ healthcare
  □ pharmacist
□ Close phpMyAdmin
```

### Authentication Verification
```
□ Login as admin / admin123 → Admin dashboard
□ Logout
□ Login as cashier / cashier123 → Cashier dashboard
□ Logout
□ Login as healthcare / health123 → Healthcare dashboard
□ Logout
□ Login as pharmacist / pharm123 → Pharmacist dashboard
□ Logout
□ Try invalid password → Error message appears
□ Try non-existent user → Error message appears
```

### Dashboard Verification (Admin)
```
□ Login as admin/admin123
□ Sidebar shows:
  □ Users button
  □ Messages button
  □ Reports button
□ Click "Users" → Should see user list
□ Click "Messages" → Should see contact messages
□ Click "Reports" → Should see statistics
□ All sections load without errors
□ Logout button works
```

### Dashboard Verification (Cashier)
```
□ Login as cashier/cashier123
□ Sidebar shows:
  □ Patient Management
  □ Transactions
  □ Daily Summary
□ Can view sections
□ No 401/403 errors
□ Logout works
```

### Dashboard Verification (Healthcare)
```
□ Login as healthcare/health123
□ Sidebar shows:
  □ Consultations
  □ View Patients
  □ Schedule Consultation
□ Can view sections
□ No permission errors
□ Logout works
```

### Dashboard Verification (Pharmacist)
```
□ Login as pharmacist/pharm123
□ Sidebar shows:
  □ Pharmacy Tasks
  □ Pending Tasks
□ Can view task list
□ No access denied errors
□ Logout works
```

### API Verification (Advanced)
```
Optional - Test API endpoints:

□ Open browser dev tools (F12)
□ Go to http://localhost/telemedicine/api/patients.php?action=list
□ Should see JSON array of patients
□ Go to http://localhost/telemedicine/api/consultations.php?action=list
□ Should see JSON array of consultations
□ Go to http://localhost/telemedicine/api/pharmacy.php?action=list
□ Should see JSON array of pharmacy tasks
□ Go to http://localhost/telemedicine/api/admin.php?action=system_stats
□ Should see statistics JSON (requires login)
□ Close dev tools
```

---

## 🔍 TROUBLESHOOTING QUICK FIXES

### Problem: "Connection refused" or "Cannot reach server"
**Solution:**
```
□ Check XAMPP Control Panel
□ Verify Apache is "Running" (green)
□ Verify MySQL is "Running" (green)
□ If not, click "Start" button for each
□ Wait 5 seconds
□ Try accessing http://localhost/ again
□ Should see XAMPP welcome page
```

### Problem: "Database does not exist" error
**Solution:**
```
□ Go to: http://localhost/telemedicine/config/init_db.php
□ Wait for success message
□ Page should say "Database initialized successfully"
□ If error appears, check MySQL is running
□ Refresh browser and try again
□ Check phpmyadmin for "telemedicine" database
```

### Problem: "Login failed" or "Invalid credentials"
**Solution:**
```
□ Check database was initialized (step above)
□ Verify exact spelling: "admin" (lowercase)
□ Verify exact password: "admin123" (all lowercase)
□ Check Caps Lock is NOT on
□ Try refreshing browser (F5)
□ Clear browser cookies
□ Try different browser
□ Check MySQL is still running
```

### Problem: "Dashboard doesn't load" after login
**Solution:**
```
□ Check browser console (F12)
□ Look for red error messages
□ Check XAMPP Apache is still running
□ Refresh page (F5)
□ Try logging out and in again
□ Try different browser
□ Check MySQL connection in config/db.php
```

### Problem: "Cannot see user list" or "No data loads"
**Solution:**
```
□ Check database initialization
□ Open phpMyAdmin to verify data exists
□ Check sample patients in "patients" table
□ Check sample consultations in "consultations" table
□ Verify MySQL is running
□ Try refreshing browser
□ Check browser console for errors
```

---

## 🎯 SUCCESS INDICATORS

### ✅ System is Working If:
```
✓ XAMPP Control Panel shows both services green
✓ init_db.php page shows success message
✓ http://localhost/telemedicine/ loads
✓ Login modal appears when clicking Login
✓ Can login with admin/admin123
✓ Dashboard loads after login
✓ User info shows in header
✓ Sidebar menu appears
✓ Logout button works
✓ Can login as different roles
✓ Each role shows different dashboard
✓ No console errors (F12)
✓ No 404 errors
✓ No 401/403 access errors
✓ Database tables exist in phpMyAdmin
✓ Default users visible in database
✓ Sample data visible in database tables
```

---

## 📖 DOCUMENTATION QUICK REFERENCE

### If You Need To...

**Get Started Fast:**
→ Read: **QUICK_START.md** (5 min)

**Understand The System:**
→ Read: **README.md** or **SYSTEM_STATUS.md** (10-20 min)

**Set Up For First Time:**
→ Read: **SETUP_GUIDE.md** (15 min)

**Troubleshoot Problems:**
→ Check: **SYSTEM_STATUS.md** → Troubleshooting section

**Learn Your Role:**
→ Read: Your role guide
  - Admin: **ADMIN_GUIDE.md**
  - Cashier: **CASHIER_GUIDE.md**
  - Healthcare: **HEALTHCARE_PROVIDER_GUIDE.md**
  - Pharmacist: **PHARMACIST_GUIDE.md**

**Find Files/APIs:**
→ Check: **FILE_INVENTORY.md**

**Check Project Status:**
→ Read: **PROJECT_TODO.md**

**Navigate All Docs:**
→ Use: **MASTER_INDEX.md**

**Find Complete List:**
→ See: **FILE_INVENTORY.md**

---

## 🔐 SECURITY CHECKLIST

Before using in production:

```
□ Changed all default passwords
□ Updated CORS headers in API files (api/*.php)
□ Enabled HTTPS/SSL certificate
□ Configured firewall rules
□ Set up daily database backups
□ Configured error logging
□ Disabled debug mode in production
□ Set secure session cookie options
□ Implemented rate limiting
□ Set up security headers
□ Reviewed user access permissions
□ Tested backup restore procedures
□ Documented security procedures
□ Created incident response plan
```

---

## 📋 DEPLOYMENT CHECKLIST

### Pre-Deployment
```
□ All functionality tested
□ All databases verified
□ All APIs tested
□ All documentation reviewed
□ All users trained
□ Backup created
□ Disaster recovery plan ready
□ Support procedures documented
```

### Deployment Day
```
□ Backup production database
□ Deploy files to production server
□ Run init_db.php (or restore from backup)
□ Test all functionality
□ Verify all users can login
□ Test all role dashboards
□ Verify data integrity
□ Monitor system for 1 hour
□ Notify all users of go-live
```

### Post-Deployment
```
□ Monitor system performance
□ Respond to user issues
□ Create daily backups
□ Review error logs
□ Track system uptime
□ Document issues and fixes
□ Plan for maintenance windows
```

---

## 📞 SUPPORT CONTACTS

### For Setup Help
```
Read: SETUP_GUIDE.md
Then: QUICK_START.md
Then: SYSTEM_STATUS.md
```

### For Login Issues
```
Read: QUICK_START.md → Troubleshooting
Then: Check database initialized
Then: Verify default users exist
```

### For Role-Specific Help
```
Admin: Read ADMIN_GUIDE.md
Cashier: Read CASHIER_GUIDE.md
Healthcare: Read HEALTHCARE_PROVIDER_GUIDE.md
Pharmacist: Read PHARMACIST_GUIDE.md
```

### For Technical Issues
```
API Issues: Check FILE_INVENTORY.md
Database Issues: Check DATABASE_SETUP.md
System Issues: Check SYSTEM_STATUS.md
General Questions: Check MASTER_INDEX.md
```

---

## ✅ FINAL VERIFICATION

Before declaring system ready, verify:

```
□ Database: 8 tables created
□ Users: 4 default accounts
□ Data: 15+ sample records
□ APIs: All 15+ endpoints working
□ Dashboards: All 4 roles showing correct interface
□ Security: Passwords hashed, access controlled
□ Documentation: All 12 guides present and readable
□ Code: All PHP files present and functional
□ Frontend: All HTML files and CSS loading
□ Performance: System responds quickly
□ Errors: No critical errors in logs
□ Backups: Backup procedures documented
```

---

## 🎉 YOU'RE READY!

### Next Steps:
1. Run this checklist
2. Verify everything
3. Read QUICK_START.md
4. Start using system

### System Status:
```
✅ Database: READY
✅ APIs: READY
✅ Dashboards: READY
✅ Documentation: READY
✅ Security: READY
✅ Overall: PRODUCTION READY
```

---

**Date:** December 4-5, 2025  
**System:** Telemedicine Management System v1.0  
**Owner:** Steven Mzemba  
**Status:** ✅ READY FOR USE

Print this checklist and use it before each deployment or major system change.
