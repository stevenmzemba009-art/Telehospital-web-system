# Admin Role Guide

## Overview
The Admin role has full system access and controls all aspects of the telemedicine platform.

## Admin Credentials
```
Username: admin
Password: admin123
```

## Admin Dashboard Features

### 1. Manage Users
**Location:** Dashboard → Users (left sidebar)

**Capabilities:**
- View all users in the system
- See user details: ID, Username, Email, Role, Full Name, Created Date
- Cannot directly edit/delete from dashboard (use database)
- Monitor system user activity

**API Endpoint:** `GET /api/admin.php?action=list_users`

**Response Example:**
```json
[
  {
    "id": 1,
    "username": "admin",
    "email": "admin@telemedicine.com",
    "role": "admin",
    "full_name": "Administrator",
    "created_at": "2025-01-01 10:00:00"
  }
]
```

### 2. View Messages
**Location:** Dashboard → Messages (left sidebar)

**Capabilities:**
- View all contact form submissions from public
- See message details: Name, Phone, Email, Subject, Message, Status, Date
- Mark messages as read/replied
- Contact patients directly

**API Endpoint:** `GET /api/admin.php?action=list_messages`

**Response Example:**
```json
[
  {
    "id": 1,
    "name": "John Doe",
    "phone": "555-1234",
    "email": "john@example.com",
    "subject": "Consultation Request",
    "message": "I need to book a consultation...",
    "status": "new",
    "created_at": "2025-01-15 14:30:00"
  }
]
```

### 3. System Reports
**Location:** Dashboard → Reports (left sidebar)

**Displays:**
- Total Users Count
- Total Patients Count
- Total Consultations Count
- Total Pharmacy Tasks Count
- Total Transaction Amount (Sum of all payments)
- System Health Overview

**API Endpoint:** `GET /api/admin.php?action=system_stats`

**Response Example:**
```json
{
  "total_users": 4,
  "total_patients": 4,
  "total_consultations": 3,
  "total_pharmacy_tasks": 3,
  "total_transactions": 150.50,
  "status": "ok"
}
```

## Admin Responsibilities

### User Management
- Monitor all user accounts
- Ensure proper role assignments
- Track user creation dates
- Identify inactive users

### Quality Control
- Review patient consultations
- Monitor transaction records
- Ensure data integrity
- Track system usage

### System Maintenance
- Monitor system statistics
- Review contact messages
- Archive old records
- Manage database health

### Security
- Monitor unauthorized access attempts
- Review role-based access
- Maintain audit logs
- Ensure data protection

## Admin Workflows

### Add New User (Manual Database Edit)
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select "telemedicine" database
3. Click "users" table
4. Click "Insert" button
5. Fill in user details:
   - username: unique name
   - password: use `password_hash('password123', PASSWORD_DEFAULT)`
   - email: user email
   - role: admin/cashier/healthcare/pharmacist
   - full_name: full name
6. Click "Go"

### Reset User Password (Via Database)
1. phpMyAdmin → telemedicine → users table
2. Click "Edit" on user row
3. Update password field with: `password_hash('newpassword', PASSWORD_DEFAULT)`
4. Save changes

### Delete User (Via Database)
1. phpMyAdmin → telemedicine → users table
2. Click "Delete" on user row
3. Confirm deletion
⚠️ WARNING: Deleting user will affect their consultations/transactions

### Generate System Report
1. Login as admin
2. Go to Dashboard → Reports
3. View statistics
4. Export to Excel/PDF if needed (feature coming soon)

### Monitor Transaction Activity
1. View transaction count in Reports
2. Compare with previous days
3. Flag unusual transaction amounts
4. Follow up with Cashier if needed

## Advanced Tasks

### Backup System Data
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select "telemedicine" database
3. Click "Export"
4. Select "SQL" format
5. Click "Go"
6. Save backup file safely
```

### Audit User Activity
```
1. Check created_at timestamps for new records
2. Monitor consultation status changes
3. Review transaction reference numbers
4. Track completed pharmacy tasks
```

### System Performance
```
Monitor these metrics:
- Active users per day
- Total consultations per week
- Average transaction amount
- System response time
```

## Important Notes

⚠️ **Do not:**
- Delete system users without backup
- Modify critical database fields manually
- Share admin credentials with non-admin staff
- Disable important audit logs

✅ **Always:**
- Keep database backups regularly
- Monitor system statistics weekly
- Review security logs monthly
- Update documentation with changes

## API Reference for Developers

All admin APIs require:
- Session authentication
- Admin role verification
- Return 403 Forbidden if not authorized

### List All Users
```
GET /api/admin.php?action=list_users
```
Returns: Array of user objects

### Get User Count
```
GET /api/admin.php?action=user_count
```
Returns: `{ "count": 4 }`

### List Contact Messages
```
GET /api/admin.php?action=list_messages
```
Returns: Array of message objects

### Get System Statistics
```
GET /api/admin.php?action=system_stats
```
Returns: Object with all system counters

## Support
For admin support:
1. Check this guide first
2. Review SYSTEM_STATUS.md for troubleshooting
3. Check database logs for errors
4. Restart Apache/MySQL if needed

---
**Admin role fully configured and ready to use!**
