# Implementation & Integration Guide

## Quick Start Checklist

### Phase 1: Database Setup (Required First)
- [ ] Backup existing database: `mysqldump -u root telemedicine > backup.sql`
- [ ] Run updated database schema: `mysql -u root telemedicine < database.sql`
- [ ] Verify new tables created:
  ```sql
  SHOW TABLES LIKE '%sms%'; -- Should show sms_messages
  SHOW TABLES LIKE '%attendance%'; -- Should show attendance_tracking
  SHOW TABLES LIKE '%reminder%'; -- Should show reminders
  SHOW TABLES LIKE '%real_time%'; -- Should show real_time_feeds
  ```

### Phase 2: File Structure Setup
- [ ] Create directory: `mkdir -p logs`
- [ ] Set permissions: `chmod 755 logs`
- [ ] Copy new API files:
  - [ ] `api/realtime.php` - Real-time data sharing
  - [ ] `api/sms.php` - SMS chat system
  - [ ] `api/attendance.php` - Attendance tracking
  - [ ] `api/reminders.php` - Reminders system
- [ ] Copy configuration file: `config/sms.php`
- [ ] Copy scheduler: `scheduler.php`

### Phase 3: Environment Configuration
- [ ] Create `.env` file (or set environment variables):
  ```bash
  SMS_PROVIDER=local  # or 'twilio'
  SMS_GATEWAY_URL=http://localhost:9000/api/sms
  ```

### Phase 4: Scheduler Setup
- [ ] For Linux/Mac: Add to crontab: `0 * * * * /usr/bin/php /path/to/scheduler.php`
- [ ] For Windows: Set up Task Scheduler with PHP task

### Phase 5: Frontend Integration
- [ ] Integrate dashboard-enhanced.html into web interface
- [ ] Update authentication token in dashboard
- [ ] Test all dashboard functions

---

## Integration Steps by Feature

### 1. Real-Time Data Sharing Integration

**In your existing PHP files, add real-time feed logging:**

```php
<?php
// After creating a consultation
require_once 'config/db.php';

// Log to real-time feeds
$stmt = $pdo->prepare('
    INSERT INTO real_time_feeds 
    (event_type, reference_id, patient_id, created_by_id, created_by_role, event_data)
    VALUES (?, ?, ?, ?, ?, ?)
');

$stmt->execute([
    'consultation',  // event_type
    $consultation_id,  // reference_id
    $patient_id,  // patient_id
    $user_id,  // created_by_id
    $user_role,  // created_by_role (admin, cashier, healthcare, pharmacist)
    json_encode([
        'diagnosis' => 'Type 2 Diabetes',
        'notes' => 'Patient responding well to treatment',
        'provider' => 'Dr. Smith'
    ])  // event_data
]);
?>
```

**Add to healthcare provider's consultations API:**

```php
// After creating consultation
include '../api/realtime.php';
logRealTimeFeed($pdo, 'consultation', $consultation_id, $patient_id, $user_id, $user_role, $event_data);
```

---

### 2. SMS Chat System Integration

**Send SMS from healthcare provider:**

```php
<?php
require_once 'config/sms.php';

// Send SMS to patient
$phone = '+255789123456';
$message = 'Your prescription is ready. Please collect from pharmacy.';

$result = sendSMSMessage($phone, $message, $pdo);

if ($result['status'] === 'sent') {
    // Save SMS record
    $stmt = $pdo->prepare('
        INSERT INTO sms_messages 
        (sender_id, patient_id, phone_number, message_text, status)
        VALUES (?, ?, ?, ?, "sent")
    ');
    $stmt->execute([$user_id, $patient_id, $phone, $message]);
}
?>
```

**Receive incoming SMS (webhook from SMS gateway):**

```php
<?php
// Create endpoint: api/sms-webhook.php
require_once 'config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Find patient by phone
$stmt = $pdo->prepare('SELECT id FROM patients WHERE phone = ?');
$stmt->execute([$data['from']]);
$patient = $stmt->fetch();

// Save incoming SMS
$stmt = $pdo->prepare('
    INSERT INTO sms_messages 
    (patient_id, phone_number, message_text, message_type, status)
    VALUES (?, ?, ?, "incoming", "delivered")
');
$stmt->execute([$patient['id'] ?? null, $data['from'], $data['message']]);
?>
```

**Configure SMS gateway webhook:**
```
POST https://your-domain.com/api/sms-webhook.php
Body: {"from": "+255789123456", "message": "Message text"}
```

---

### 3. Attendance Tracking Integration

**In clinic/healthcare dashboard:**

```php
<?php
require_once 'config/db.php';

// When patient arrives
$stmt = $pdo->prepare('
    INSERT INTO attendance_tracking 
    (patient_id, appointment_id, attendance_date, status, check_in_time, consecutive_absences)
    VALUES (?, ?, CURDATE(), "present", NOW(), 0)
');
$stmt->execute([$patient_id, $appointment_id]);

// When patient leaves
$stmt = $pdo->prepare('
    UPDATE attendance_tracking 
    SET check_out_time = NOW() 
    WHERE patient_id = ? AND DATE(attendance_date) = CURDATE()
');
$stmt->execute([$patient_id]);
?>
```

**Mark absence (if patient doesn't show up):**

```php
<?php
// Get consecutive absences
$stmt = $pdo->prepare('
    SELECT MAX(consecutive_absences) as count FROM attendance_tracking 
    WHERE patient_id = ? AND status = "absent"
');
$stmt->execute([$patient_id]);
$consecutive = ($stmt->fetch()['count'] ?? 0) + 1;

// Record absence
$stmt = $pdo->prepare('
    INSERT INTO attendance_tracking 
    (patient_id, appointment_id, attendance_date, status, consecutive_absences)
    VALUES (?, ?, CURDATE(), "absent", ?)
');
$stmt->execute([$patient_id, $appointment_id, $consecutive]);

// If 2+ absences, trigger reminder
if ($consecutive >= 2) {
    // Create reminder (will be sent by scheduler)
    $stmt = $pdo->prepare('
        INSERT INTO reminders 
        (patient_id, reminder_type, title, message, phone_number, scheduled_at)
        VALUES (?, "absent", ?, ?, ?, NOW())
    ');
    $stmt->execute([$patient_id, "Absence Alert", $reminder_message, $patient_phone]);
}
?>
```

---

### 4. Reminders & Notifications Integration

**Add to existing consultation booking:**

```php
<?php
require_once 'config/db.php';

// After creating consultation
$appointment_date = $data['consultation_date'];

// Create appointment reminder (will be sent 24h before)
$scheduled_time = date('Y-m-d H:i:s', strtotime($appointment_date . ' -24 hours'));

$stmt = $pdo->prepare('
    INSERT INTO reminders 
    (patient_id, reminder_type, title, message, phone_number, scheduled_at)
    VALUES (?, "appointment", "Appointment Reminder", ?, ?, ?)
');

$reminder_msg = "Dear {$patient['name']}, you have an appointment on {$appointment_date}. Please arrive 15 minutes early.";

$stmt->execute([
    $patient_id,
    $reminder_msg,
    $patient['phone'],
    $scheduled_time
]);
?>
```

**Absence reminder (auto-triggered):**

```php
<?php
// This is automatically handled by scheduler.php
// When 2+ consecutive absences detected, reminder created and SMS sent

// To manually send:
require_once 'api/reminders.php';

// Get patient
$stmt = $pdo->prepare('SELECT name, phone FROM patients WHERE id = ?');
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

// Create and send reminder
$message = "Dear {$patient['name']}, you have been absent for 2 appointments. Please contact us.";
sendReminder($patient['phone'], $message);
?>
```

---

### 5. Admin Dashboard Integration

**Display real-time feeds:**

```html
<!-- In admin dashboard -->
<div id="realtime-feed"></div>

<script>
async function loadFeeds() {
    const response = await fetch('/api/realtime.php?action=department_feeds&department=admin&limit=100', {
        headers: { 'x-auth-token': authToken }
    });
    const data = await response.json();
    
    const feed = document.getElementById('realtime-feed');
    feed.innerHTML = data.feeds.map(f => `
        <div class="feed-item">
            <strong>${f.created_by_role}</strong>: ${f.event_type}
            <div>${JSON.stringify(f.event_data)}</div>
        </div>
    `).join('');
}

// Load every 30 seconds
setInterval(loadFeeds, 30000);
loadFeeds(); // Initial load
</script>
```

**Display high-absence patients:**

```html
<div id="high-absence"></div>

<script>
async function loadHighAbsence() {
    const response = await fetch('/api/attendance.php?action=high_absence&threshold=2', {
        headers: { 'x-auth-token': authToken }
    });
    const data = await response.json();
    
    const container = document.getElementById('high-absence');
    container.innerHTML = data.high_absence_patients.map(p => `
        <div class="alert alert-warning">
            <strong>${p.name}</strong> (${p.phone}) - ${p.consecutive_absences} absences
            <button onclick="sendAbsenceReminder(${p.patient_id})">Send Reminder</button>
        </div>
    `).join('');
}

loadHighAbsence();
</script>
```

---

## Testing Checklist

### Database Tests
```sql
-- Check new tables exist
SHOW TABLES;

-- Check attendance records
SELECT * FROM attendance_tracking LIMIT 5;

-- Check SMS messages
SELECT * FROM sms_messages LIMIT 5;

-- Check reminders
SELECT * FROM reminders WHERE status = 'pending' LIMIT 5;

-- Check real-time feeds
SELECT * FROM real_time_feeds LIMIT 5;
```

### API Tests
```bash
# Test real-time feeds
curl -H "x-auth-token: YOUR_TOKEN" \
  "http://localhost/api/realtime.php?action=feeds&patient_id=1"

# Test SMS
curl -X POST -H "x-auth-token: YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"patient_id":1,"phone_number":"+255789123456","message":"Test"}' \
  "http://localhost/api/sms.php?action=send_sms"

# Test attendance
curl -X POST -H "x-auth-token: YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"patient_id":1}' \
  "http://localhost/api/attendance.php?action=mark_present"

# Test reminders
curl -H "x-auth-token: YOUR_TOKEN" \
  "http://localhost/api/reminders.php?action=pending&limit=10"
```

### Scheduler Tests
```bash
# Run scheduler manually
php /path/to/scheduler.php

# Check logs
tail -f /path/to/logs/scheduler.log

# Verify pending reminders sent
mysql -u root -e "SELECT * FROM reminders WHERE status='sent' ORDER BY sent_at DESC LIMIT 5;" telemedicine
```

---

## Troubleshooting

### Problem: SMS not sending
**Solution:**
1. Check SMS provider configuration in `config/sms.php`
2. Verify SMS gateway URL is accessible
3. Check logs: `tail logs/scheduler.log`
4. Test SMS gateway directly:
   ```bash
   curl -X POST http://localhost:9000/api/sms -d '{"to":"+255789123456","message":"Test"}'
   ```

### Problem: Scheduler not running
**Solution:**
1. Check cron job: `crontab -l`
2. Verify cron is running: `service cron status`
3. Check permissions: `ls -la scheduler.php` (should be readable)
4. Test manually: `php scheduler.php`

### Problem: Database migration failed
**Solution:**
1. Check database connection
2. Verify user has CREATE TABLE permissions
3. Try importing file directly:
   ```bash
   mysql -u root telemedicine < database.sql
   ```
4. Check error logs: `mysql -u root -e "SHOW ENGINE INNODB STATUS\G"` telemedicine

---

## Security Checklist

- [ ] All API endpoints verify JWT token
- [ ] Database has proper indexes for performance
- [ ] SMS messages stored securely
- [ ] Audit logs track all actions
- [ ] Rate limiting implemented for SMS sending
- [ ] Phone numbers validated before sending
- [ ] SSL/TLS enabled for SMS gateway communication
- [ ] Environment variables used for sensitive data
- [ ] Database backups scheduled

---

## Deployment Checklist

- [ ] Database schema updated
- [ ] New API files deployed
- [ ] Configuration files set
- [ ] Scheduler configured and running
- [ ] Dashboard integrated
- [ ] SMS gateway configured
- [ ] All tests passed
- [ ] Documentation reviewed
- [ ] Team trained on new features
- [ ] Monitoring configured

---

## Support & Documentation

For detailed API documentation, see: `ENHANCED_FEATURES_GUIDE.md`
For dashboard usage, see: `dashboard-enhanced.html`

