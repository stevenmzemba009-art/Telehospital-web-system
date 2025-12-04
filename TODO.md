# Telemedicine System Update TODO

## Completed Tasks
- [x] Update db.js to use MySQL instead of SQLite, including new tables for patient_visit_schedules, visit_logs, absences, and sms_reminders.
- [x] Update server.js to use MySQL query syntax (db.query instead of db.get, db.all, db.run).
- [x] Add new APIs for visit scheduling (/api/patients/:id/schedule), daily check-ins (/api/patients/:id/checkin), absence tracking (/api/patients/:id/absence), SMS reminders (/api/patients/:id/remind), and absence analysis (/api/admin/absences).
- [x] Implement auto-update of patient files in pharmacist updates (insert into visit_logs).
- [x] Update cashier summary to display fees in MWK (multiplied by 850).

## Remaining Tasks
- [x] Test the server by running it and checking for any errors.
- [x] Verify database connections and table creations.
- [x] Test the new APIs with sample requests.
- [x] Fix any form label issues in dashboard.html if necessary.
- [x] Ensure all dependencies are installed (mysql2, etc.).
- [x] Add admin credentials (stevemzemba009@gmail.com / beston2010).
- [x] Update home page with real dialysis image (if possible).
- [x] Ensure all modules work as a system for multiple departments sharing real data.
