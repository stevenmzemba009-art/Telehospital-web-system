# Telemedicine (Diabetes & Hypertension) - Local XAMPP App

Simple PHP app intended to run under XAMPP (Apache + PHP). Provides roles: `admin`, `cashier`, `provider`, `pharmacist`.

Features implemented:
- SQLite data storage (`data/database.sqlite`)
- Cashier: add/search patient, give attendance code, upload patient file
- Provider: view assigned patients, edit patient file, request refill
- Pharmacist: view refill requests, write medication refill, mark complete
- Admin: add/remove actors, send SMS reminders for absent patients (simulated), offline chat/messages
- Attendance tracking and showing present/absent

Setup (XAMPP on Windows):
1. Copy the `telemedicine` folder into XAMPP `htdocs` (e.g. `C:\xampp\htdocs\telemedicine`)
2. Start Apache via XAMPP Control Panel
3. Open `http://localhost/telemedicine/index.php`

Notes:
- SMS sending is simulated; to integrate Twilio or similar, implement `send_sms()` in `inc/db.php`.
- SQLite database is auto-created on first run in `telemedicine/data/database.sqlite`.

Want me to add authentication, Twilio SMS integration, or prettier UI next?