# ⚡ Quick Start - Enhanced Features (5 Minutes)

## What You Got ✅

1. **Real-Time Data Sharing** - All departments see updates instantly
2. **SMS Chat System** - Text patients directly, get replies
3. **Attendance Tracking** - Track who shows up/misses appointments
4. **Auto Reminders** - SMS sent when patients miss 2 appointments or before appointments
5. **System Management** - Audit trail of all actions

---

## Installation (Copy-Paste Ready)

### 1️⃣ Backup Database
```bash
mysqldump -u root telemedicine > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 2️⃣ Update Database
```bash
mysql -u root telemedicine < database.sql
```

### 3️⃣ Copy New Files (Already Done)
✅ Files already created:
- `/api/realtime.php` - Real-time feeds
- `/api/sms.php` - SMS system
- `/api/attendance.php` - Attendance tracking
- `/api/reminders.php` - Reminders system
- `/config/sms.php` - SMS configuration
- `/scheduler.php` - Automatic scheduler
- `/dashboard-enhanced.html` - Interactive dashboard

### 4️⃣ Create Log Directory
```bash
mkdir -p /path/to/telemedicine/logs
chmod 755 /path/to/telemedicine/logs
```

### 5️⃣ Configure SMS Provider (Choose One)

**Option A: Local SMS Gateway** (Recommended for Africa)
```bash
# In your system, set environment variables:
export SMS_PROVIDER=local
export SMS_GATEWAY_URL=http://localhost:9000/api/sms
```

**Option B: Twilio** (International)
```bash
export SMS_PROVIDER=twilio
export TWILIO_ACCOUNT_SID=your_sid_here
export TWILIO_AUTH_TOKEN=your_token_here
export TWILIO_PHONE_NUMBER=+1234567890
```

### 6️⃣ Setup Scheduler (Auto-send Reminders)

**Linux/Mac:**
```bash
# Edit crontab
crontab -e

# Add this line (runs every hour):
0 * * * * /usr/bin/php /path/to/telemedicine/scheduler.php

# Save and exit
```

**Windows:**
1. Open Task Scheduler
2. Create Basic Task
3. Set Trigger: Repeat every hour
4. Set Action: Run `C:\PHP\php.exe` with argument `C:\path\to\scheduler.php`

### 7️⃣ Test Everything
```bash
# Run scheduler manually
php /path/to/telemedicine/scheduler.php

# Check logs
tail -f /path/to/telemedicine/logs/scheduler.log

# Test API
curl -H "x-auth-token: test-token" \
  "http://localhost/api/attendance.php?action=summary"
```

---

## Usage (Examples)

### Send SMS to Patient
```javascript
fetch('/api/sms.php?action=send_sms', {
    method: 'POST',
    headers: { 
        'x-auth-token': 'YOUR_TOKEN',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        phone_number: '+255789123456',
        message: 'Your prescription is ready. Visit pharmacy.'
    })
});
```

### Mark Patient Present
```javascript
fetch('/api/attendance.php?action=mark_present', {
    method: 'POST',
    headers: { 
        'x-auth-token': 'YOUR_TOKEN',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        appointment_id: 5
    })
});
```

### Mark Patient Absent (Auto-triggers Reminder after 2nd absence)
```javascript
fetch('/api/attendance.php?action=mark_absent', {
    method: 'POST',
    headers: { 
        'x-auth-token': 'YOUR_TOKEN',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        appointment_id: 5
    })
});
```

### Send Appointment Reminder
```javascript
fetch('/api/reminders.php?action=send_appointment_reminder', {
    method: 'POST',
    headers: { 
        'x-auth-token': 'YOUR_TOKEN',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        consultation_id: 5,
        hours_before: 24
    })
});
```

### View Real-Time Feed
```javascript
fetch('/api/realtime.php?action=feeds&patient_id=1&limit=50', {
    headers: { 'x-auth-token': 'YOUR_TOKEN' }
})
.then(r => r.json())
.then(data => console.log(data.feeds));
```

### Get SMS Conversation
```javascript
fetch('/api/sms.php?action=get_conversation&patient_id=1', {
    headers: { 'x-auth-token': 'YOUR_TOKEN' }
})
.then(r => r.json())
.then(data => console.log(data.conversation));
```

### Get High Absence Patients
```javascript
fetch('/api/attendance.php?action=high_absence&threshold=2', {
    headers: { 'x-auth-token': 'YOUR_TOKEN' }
})
.then(r => r.json())
.then(data => {
    data.high_absence_patients.forEach(patient => {
        console.log(`${patient.name} - ${patient.consecutive_absences} absences`);
    });
});
```

---

## Verification Checklist

```sql
-- Check database tables created
SHOW TABLES LIKE '%sms%';
SHOW TABLES LIKE '%attendance%';
SHOW TABLES LIKE '%reminder%';
SHOW TABLES LIKE '%real_time%';

-- Check sample data
SELECT COUNT(*) FROM sms_messages;
SELECT COUNT(*) FROM attendance_tracking;
SELECT COUNT(*) FROM reminders;
SELECT COUNT(*) FROM real_time_feeds;
```

---

## Dashboard Access

Open in browser:
```
http://your-domain.com/dashboard-enhanced.html
```

Features:
- 📊 Real-Time Feed viewer
- 💬 Send/receive SMS
- 📋 Mark attendance
- 🔔 Manage reminders
- 📈 View statistics

**Set your auth token in dashboard:**
```javascript
// In dashboard-enhanced.html, update:
let authToken = localStorage.getItem('authToken') || 'YOUR_TOKEN_HERE';
```

---

## Common Tasks

### Task 1: Patient Misses Appointment
```
1. Check in clinic: Mark patient absent
   → Scheduler detects 2nd absence
   → SMS automatically sent: "You've missed 2 appointments..."

2. Or manually send reminder:
   GET /api/reminders.php?action=send_absence_reminder
```

### Task 2: Schedule Appointment Reminder
```
1. Create appointment via existing system
2. Scheduler automatically sends SMS 24h before
3. Patient receives: "Appointment tomorrow at 2 PM..."
```

### Task 3: Real-Time Department Updates
```
1. Healthcare provider creates consultation
2. Pharmacist sees prescription immediately
3. Cashier sees billing entry
4. Admin sees all events in audit log
```

### Task 4: Send Custom SMS
```
Use /api/sms.php?action=send_sms endpoint
Send to any patient via phone number
Message stored in conversation history
```

---

## Troubleshooting

### ❌ SMS Not Sending
```bash
# Check configuration
cat /path/to/config/sms.php

# Check logs
tail -f /path/to/logs/scheduler.log

# Test gateway manually
curl -X POST http://localhost:9000/api/sms \
  -H "Content-Type: application/json" \
  -d '{"to":"+255789123456","message":"Test"}'
```

### ❌ Scheduler Not Running
```bash
# Check cron
crontab -l

# Run manually for testing
php /path/to/scheduler.php

# Check for errors
php -l /path/to/scheduler.php
```

### ❌ Database Issues
```bash
# Verify connection
mysql -u root -p telemedicine
SHOW TABLES;
SELECT * FROM sms_messages LIMIT 1;
```

---

## Documentation Files

**Start Here:**
1. `IMPLEMENTATION_COMPLETE_SUMMARY.md` - Full overview of all features
2. `ENHANCED_FEATURES_GUIDE.md` - Complete API documentation
3. `IMPLEMENTATION_GUIDE.md` - Detailed integration steps

---

## Next Steps

1. ✅ Update database schema
2. ✅ Copy new files
3. ✅ Configure SMS provider
4. ✅ Setup scheduler
5. ✅ Test all features
6. ✅ Integrate dashboard
7. ✅ Train team
8. ✅ Deploy to production

---

## Support

- **API Documentation**: See `ENHANCED_FEATURES_GUIDE.md`
- **Integration Help**: See `IMPLEMENTATION_GUIDE.md`
- **Dashboard Usage**: Open `dashboard-enhanced.html`
- **Logs**: Check `/logs/scheduler.log`

---

**Status**: ✅ Ready to Use  
**Version**: 1.0.0  
**Updated**: December 5, 2025

