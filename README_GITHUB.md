# GitHub Repository Configuration for Telemedicine System

This repository contains the complete Telemedicine Management System with enhanced features.

## Project Structure

```
telemedicine/
├── api/                    # API endpoints
│   ├── realtime.php       # Real-time data sharing
│   ├── sms.php            # SMS chat system
│   ├── attendance.php     # Attendance tracking
│   └── reminders.php      # Automated reminders
├── config/                # Configuration files
│   ├── db.php             # Database connection
│   └── sms.php            # SMS provider config
├── backend/               # Node.js backend (optional)
├── database/              # Database files
│   └── database.sql       # Complete schema
├── docs/                  # Documentation
├── scheduler.php          # Background scheduler
└── dashboard-enhanced.html # Admin dashboard
```

## Features

### ✅ Multi-Department Real-Time Data Sharing
- Real-time event streaming across departments
- Department-specific access control
- Complete audit logging

### ✅ SMS/Text Chat System
- Send and receive SMS messages
- Offline communication support
- Conversation history tracking
- Multi-provider support (Twilio, local gateway)

### ✅ Attendance Tracking
- Patient check-in/check-out
- Absence tracking with auto-alerts
- Attendance statistics and reports

### ✅ Automated Reminders
- SMS reminders for missed appointments
- Pre-appointment notifications (24h)
- Automatic background scheduler
- Retry logic for failed messages

### ✅ Admin Dashboard
- System-wide service monitoring
- Real-time statistics
- SMS management
- Attendance reports
- Reminder queue management

## Quick Start

### Option 1: Local Installation

#### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Node.js 14+ (optional, for backend)

#### Installation Steps
```bash
# 1. Clone repository
git clone https://github.com/yourusername/telemedicine.git
cd telemedicine

# 2. Setup database
mysql -u root -p < database/database.sql

# 3. Configure environment
cp .env.example .env
# Edit .env with your settings

# 4. Install dependencies (if using backend)
cd backend
npm install
npm start

# 5. Setup scheduler (cron)
0 * * * * /usr/bin/php /path/to/scheduler.php

# 6. Access dashboard
# Open: http://localhost/dashboard-enhanced.html
```

### Option 2: Supabase Integration

Supabase provides hosted PostgreSQL database with real-time capabilities.

#### Setup Steps
1. Create Supabase account at https://supabase.com
2. Create new project
3. Import database schema (see `docs/SUPABASE_SETUP.md`)
4. Configure API keys in `.env`
5. Update connection string to Supabase

#### Benefits
- No database management required
- Real-time database synchronization
- Built-in authentication
- Auto-backups and disaster recovery
- Scalable infrastructure

## Environment Variables

Create `.env` file in root directory:

```bash
# Database (Local MySQL)
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=telemedicine

# Or Supabase PostgreSQL
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-anon-key
SUPABASE_SECRET=your-secret-key

# SMS Configuration
SMS_PROVIDER=local  # or 'twilio'
SMS_GATEWAY_URL=http://localhost:9000/api/sms
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## API Documentation

### Real-Time Data Sharing
```
GET  /api/realtime.php?action=feeds&patient_id=X
GET  /api/realtime.php?action=department_feeds&department=healthcare
POST /api/realtime.php?action=create_feed
```

### SMS Chat
```
POST /api/sms.php?action=send_sms
GET  /api/sms.php?action=get_conversation&patient_id=X
POST /api/sms.php?action=receive_sms
```

### Attendance Tracking
```
POST /api/attendance.php?action=mark_present
POST /api/attendance.php?action=mark_absent
GET  /api/attendance.php?action=get_records&patient_id=X
```

### Reminders
```
POST /api/reminders.php?action=create
POST /api/reminders.php?action=send_absence_reminder
GET  /api/reminders.php?action=pending
```

## Database Schema

### Core Tables
- **users** - System users (admin, cashier, healthcare, pharmacist)
- **patients** - Patient records
- **consultations** - Consultation records
- **pharmacy_tasks** - Medication orders
- **transactions** - Payment records

### Enhanced Tables
- **sms_messages** - SMS communication
- **attendance_tracking** - Patient attendance
- **reminders** - Automated reminders
- **real_time_feeds** - Event streaming
- **department_access_logs** - Audit logs

## Deployment

### GitHub Deployment
1. Push code to GitHub repository
2. Set up GitHub Actions for CI/CD (optional)
3. Deploy to your hosting provider

### Supabase Deployment
1. Create Supabase project
2. Import database schema
3. Configure API credentials
4. Update application configuration
5. Deploy API layer to your server

### Production Checklist
- [ ] Database backup configured
- [ ] Environment variables set
- [ ] SSL/TLS certificate installed
- [ ] Scheduler cron job running
- [ ] SMS provider configured
- [ ] Admin credentials secured
- [ ] Monitoring and alerts enabled
- [ ] Documentation reviewed

## Admin Dashboard

Access the admin dashboard at: `dashboard-enhanced.html`

### Dashboard Features
- Real-time system statistics
- SMS management interface
- Attendance tracking
- Reminder queue monitoring
- System health status
- Service logs viewer

### Dashboard Tabs
1. **Real-Time Feed** - View live events
2. **SMS Chat** - Send/receive messages
3. **Attendance** - Mark and track attendance
4. **Reminders** - Manage notifications
5. **Statistics** - System metrics

## Documentation

Full documentation available in `/docs/` directory:

- `QUICK_START.md` - 5-minute setup
- `API_REFERENCE.md` - Complete API documentation
- `DEPLOYMENT.md` - Deployment guide
- `SUPABASE_SETUP.md` - Supabase integration
- `TROUBLESHOOTING.md` - Common issues

## Support

For issues and questions:

1. Check `/docs/TROUBLESHOOTING.md`
2. Review API endpoint documentation
3. Check system logs in `/logs/`
4. Open GitHub issue with details

## License

This project is licensed under the MIT License - see LICENSE file for details.

## Contributors

See CONTRIBUTORS.md for list of contributors.

## Security

- All API endpoints require JWT authentication
- Database passwords stored in environment variables
- HTTPS/TLS for all communications
- Regular security updates
- Audit logging of all actions

## Version History

- **v1.0.0** (December 5, 2025) - Initial release with all enhanced features
  - Real-time data sharing
  - SMS chat system
  - Attendance tracking
  - Automated reminders
  - Admin dashboard
  - Supabase integration
  - GitHub deployment ready

## Contact

- Project: Telemedicine Management System
- Version: 1.0.0
- Status: Production Ready
- Last Updated: December 5, 2025

---

**Ready to deploy? Check out [Quick Start Guide](docs/QUICK_START.md)**

