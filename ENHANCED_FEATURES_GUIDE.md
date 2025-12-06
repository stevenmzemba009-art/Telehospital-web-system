# Telemedicine System - Enhanced Features Implementation Guide

## Overview

This document outlines the implementation of multi-department real-time data sharing, SMS chat system, attendance tracking, and automated reminder system for the telemedicine application.

---

## Table of Contents

1. [Real-Time Data Sharing](#real-time-data-sharing)
2. [SMS & Chat System](#sms--chat-system)
3. [Attendance Tracking](#attendance-tracking)
4. [Reminders & Notifications](#reminders--notifications)
5. [System Setup](#system-setup)
6. [API Endpoints](#api-endpoints)
7. [Cron Job Setup](#cron-job-setup)

---

## Real-Time Data Sharing

### Features

- **Multi-Department Visibility**: All departments (Admin, Cashier, Healthcare Provider, Pharmacist) can view relevant updates in real-time
- **Event Streaming**: Consultations, prescriptions, payments, attendance, and messages are streamed to authorized users
- **Audit Logging**: All department access is logged for compliance and security

### Database Tables Added

- `real_time_feeds` - Stores all events and updates
- `department_access_logs` - Tracks who accesses what information

### API Endpoints

```text
GET  /api/realtime.php?action=feeds&patient_id=X&limit=50
GET  /api/realtime.php?action=department_feeds&department=healthcare&limit=100
GET  /api/realtime.php?action=stats&patient_id=X
POST /api/realtime.php?action=create_feed
POST /api/realtime.php?action=log_access
```

### Usage Example

```javascript
// Get real-time feeds for a patient
fetch('/api/realtime.php?action=feeds&patient_id=1&limit=50', {
    headers: { 'x-auth-token': authToken }
})
.then(r => r.json())
.then(data => console.log(data.feeds));
```

---

## SMS & Chat System

### SMS Features

- **Offline SMS Communication**: Send and receive SMS between Admin/Healthcare Providers and Patients
- **SMS Gateway Integration**: Support for Twilio, local SMS providers, or African mobile operators
- **Message History**: Full conversation history with timestamps
- **Delivery Tracking**: Track SMS delivery, read status, and failed attempts

### SMS Database Tables Added

- `sms_messages` - Stores all SMS communication
- Tracks: sender, recipient, message content, delivery status, timestamps

### SMS Gateway Configuration

Set environment variables:

```bash
export SMS_PROVIDER=local  # or 'twilio'
export SMS_GATEWAY_URL=http://localhost:9000/api/sms
export TWILIO_ACCOUNT_SID=your_sid
export TWILIO_AUTH_TOKEN=your_token
export TWILIO_PHONE_NUMBER=+1234567890
```

### SMS API Endpoints

```text
POST /api/sms.php?action=send_sms
GET  /api/sms.php?action=get_conversation&patient_id=X
POST /api/sms.php?action=mark_as_read
GET  /api/sms.php?action=unread_count
POST /api/sms.php?action=receive_sms  (webhook from SMS gateway)
GET  /api/sms.php?action=stats
```

### SMS Usage Example

```javascript
// Send SMS to patient
fetch('/api/sms.php?action=send_sms', {
    method: 'POST',
    headers: { 
        'x-auth-token': authToken,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        phone_number: '+255789123456',
        message: 'Your prescription is ready. Please visit the pharmacy.'
    })
})
.then(r => r.json())
.then(data => console.log(data));
```

---

## Attendance Tracking

### Attendance Features

- **Check-in/Check-out**: Track when patients arrive and leave
- **Absence Management**: Mark patients as present or absent
- **Consecutive Absence Tracking**: Automatically tracks and alerts on 2+ consecutive absences
- **Attendance Reports**: Generate attendance statistics and summaries

### Attendance Database Tables Added

- `attendance_tracking` - Records patient attendance for each appointment

### Attendance API Endpoints

```text
POST /api/attendance.php?action=mark_present
POST /api/attendance.php?action=mark_absent
POST /api/attendance.php?action=check_out
GET  /api/attendance.php?action=get_records&patient_id=X&days=30
GET  /api/attendance.php?action=high_absence&threshold=2
GET  /api/attendance.php?action=summary&days=30
```

### Attendance Usage Example

```javascript
// Mark patient as present
fetch('/api/attendance.php?action=mark_present', {
    method: 'POST',
    headers: { 
        'x-auth-token': authToken,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        appointment_id: 5
    })
})
.then(r => r.json())
.then(data => console.log(data));

// Get patient attendance records
fetch('/api/attendance.php?action=get_records&patient_id=1&days=30', {
    headers: { 'x-auth-token': authToken }
})
.then(r => r.json())
.then(data => {
    console.log('Attendance Rate:', data.statistics.attendance_rate + '%');
});
```

---

## Reminders & Notifications

### Reminders Features

- **Automatic Reminders**: Sends SMS reminders for 2+ consecutive absences
- **Appointment Reminders**: Sends SMS 24 hours before appointments
- **Flexible Scheduling**: Schedule reminders for future dates/times
- **Retry Logic**: Automatically retries failed reminders up to 3 times
- **Status Tracking**: Track pending, sent, and failed reminders

### Reminders Database Tables Added

- `reminders` - Stores all scheduled and sent reminders

### Reminder Types

1. **Absence Reminders**: Sent when patient has 2+ consecutive absences
2. **Appointment Reminders**: Sent 24 hours before scheduled consultations
3. **Follow-up Reminders**: Custom follow-up messages
4. **Medication Reminders**: Medication instructions and refill reminders

### Reminders API Endpoints

```text
POST /api/reminders.php?action=create
POST /api/reminders.php?action=send_absence_reminder
POST /api/reminders.php?action=send_appointment_reminder
GET  /api/reminders.php?action=pending&limit=50
POST /api/reminders.php?action=send_pending  (cron endpoint)
GET  /api/reminders.php?action=patient_reminders&patient_id=X
GET  /api/reminders.php?action=stats
```

### Reminders Usage Example

```javascript
// Send absence reminder for patient with 2+ absences
fetch('/api/reminders.php?action=send_absence_reminder', {
    method: 'POST',
    headers: { 
        'x-auth-token': authToken,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1
    })
})
.then(r => r.json())
.then(data => console.log(data));

// Send pre-appointment reminder
fetch('/api/reminders.php?action=send_appointment_reminder', {
    method: 'POST',
    headers: { 
        'x-auth-token': authToken,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        consultation_id: 5,
        hours_before: 24
    })
})
.then(r => r.json())
.then(data => console.log(data));
```

---

## System Setup

### Database Migration

1. Back up your existing database:

   ```bash
   mysqldump -u root telemedicine > backup_$(date +%s).sql
   ```

2. Update the database schema:

   ```bash
   mysql -u root telemedicine < database.sql
   ```

3. Verify new tables:

   ```sql
   SHOW TABLES;
   -- Should see: sms_messages, attendance_tracking, reminders, real_time_feeds, department_access_logs
   ```

### Directory Setup

Create necessary directories:

```bash
mkdir -p /path/to/telemedicine/logs
chmod 755 /path/to/telemedicine/logs
```

### Environment Configuration

Create a `.env` file in the project root:

```bash
SMS_PROVIDER=local
SMS_GATEWAY_URL=http://localhost:9000/api/sms
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
```

---

## Complete API Reference

### Authentication Requirement

All endpoints (except webhook receivers) require an authentication token in the header:

```text
x-auth-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Standard Response Format

All endpoints return JSON with the following structure:

```json
{
  "status": "success|error",
  "message": "Optional message",
  "data": {}
}
```

### Standard Error Response

```json
{
  "error": "Error description",
  "status": 401  
}
```

---

## Cron Job Setup

### Linux/Mac Setup

Add to crontab:

```bash
# Run scheduler every hour
0 * * * * /usr/bin/php /path/to/telemedicine/scheduler.php

# Run scheduler every 30 minutes for frequent reminders
*/30 * * * * /usr/bin/php /path/to/telemedicine/scheduler.php

# Run at 9 AM daily for appointment reminders
0 9 * * * /usr/bin/php /path/to/telemedicine/scheduler.php
```

Edit crontab:

```bash
crontab -e
```

View crontab:

```bash
crontab -l
```

### Windows Setup (Task Scheduler)

1. Open Task Scheduler
2. Create Basic Task
3. Trigger: Repeat hourly
4. Action: Start program
   - Program: `C:\PHP\php.exe`
   - Arguments: `C:\path\to\scheduler.php`

### Monitoring

Check scheduler logs:

```bash
tail -f /path/to/telemedicine/logs/scheduler.log
```

---

## Feature Workflows

### Workflow 1: Patient Absence & Reminder

```text
1. Patient is marked as ABSENT for appointment
   ↓
2. System tracks consecutive absences
   ↓
3. If 2+ consecutive absences detected
   ↓
4. Automatic reminder created in reminders table
   ↓
5. Scheduler runs (hourly) and sends SMS
   ↓
6. Patient receives: "You have been absent for 2+ appointments..."
   ↓
7. Status tracked: pending → sent/failed
```

### Workflow 2: Appointment Reminder

```text
1. Appointment scheduled for patient
   ↓
2. Scheduler detects appointment within 24 hours
   ↓
3. Automatically creates appointment reminder
   ↓
4. SMS sent to patient: "You have appointment tomorrow at X time..."
   ↓
5. Patient can reply YES/NO
   ↓
6. Admin notified of patient response
```

### Workflow 3: Real-Time Department Updates

```text
1. Healthcare provider creates consultation
   ↓
2. Real-time feed event created
   ↓
3. Pharmacist sees new prescription immediately
   ↓
4. Cashier sees consultation for billing
   ↓
5. Admin sees complete audit trail
```

---

## Testing

### Test SMS Sending

```javascript
fetch('/api/sms.php?action=send_sms', {
    method: 'POST',
    headers: { 
        'x-auth-token': YOUR_AUTH_TOKEN,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        phone_number: '+255789123456',
        message: 'Test message'
    })
});
```

### Test Attendance Tracking

```javascript
fetch('/api/attendance.php?action=mark_present', {
    method: 'POST',
    headers: { 
        'x-auth-token': YOUR_AUTH_TOKEN,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        patient_id: 1,
        appointment_id: 1
    })
});
```

### Test Real-Time Feeds

```javascript
fetch('/api/realtime.php?action=feeds&patient_id=1', {
    headers: { 'x-auth-token': YOUR_AUTH_TOKEN }
});
```

---

## Troubleshooting

### SMS Not Sending

1. Check SMS gateway URL is correct
2. Verify SMS provider credentials
3. Check scheduler logs: `logs/scheduler.log`
4. Test SMS gateway directly:

   ```bash
   curl -X POST http://localhost:9000/api/sms \
     -H "Content-Type: application/json" \
     -d '{"to":"+255789123456","message":"Test"}'
   ```

### Reminders Not Firing

1. Check cron job is running: `crontab -l`
2. Check scheduler logs
3. Verify database reminders table has pending records
4. Run scheduler manually for testing:

   ```bash
   php /path/to/scheduler.php
   ```

### Database Connection Issues

1. Verify credentials in `config/db.php`
2. Test connection:

   ```bash
   mysql -u root -p telemedicine
   ```

---

## Security Considerations

1. **Authentication**: All endpoints require valid JWT token
2. **Data Privacy**: SMS contains sensitive information - ensure SSL/TLS
3. **Rate Limiting**: Implement rate limits to prevent SMS abuse
4. **Input Validation**: All inputs validated before database insertion
5. **Audit Logging**: All actions logged in department_access_logs

---

## Performance Optimization

1. **Indexing**: Database tables have proper indexes for fast queries
2. **Query Optimization**: Use LIMIT for large result sets
3. **Batch Processing**: Scheduler processes reminders in batches of 50
4. **Cleanup**: Old reminders automatically deleted after 90 days

---

## Future Enhancements

1. WhatsApp integration for messaging
2. Voice call reminders
3. Email notifications as backup
4. Patient self-service confirmation via SMS reply
5. Analytics dashboard for reminder success rates
6. Multi-language SMS support
7. Bulk SMS sending to patient groups

---

## Support & Documentation

For issues or questions:

1. Check scheduler logs: `logs/scheduler.log`
2. Check API response errors
3. Verify database tables with: `SHOW CREATE TABLE table_name;`
4. Test endpoints with provided examples

---

**Last Updated**: December 5, 2025
**System Version**: 1.0.0
**Database Schema**: Updated with real-time data sharing, SMS, attendance, and reminders

