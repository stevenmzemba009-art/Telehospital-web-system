Deployment & Hosting Notes — Telemedicine System

Date: 2025-12-06

Overview

This file explains how to deploy the Telemedicine system so:
- The PHP app (frontend + API) is hosted on a PHP-capable host or VPS
- The Node realtime server (Socket.IO) runs to provide multi-department real-time events
- The database (MySQL or Supabase/Postgres) is imported and configured
- GitHub is used as source control and CI if desired

1) Prepare the repository

- Ensure you have a GitHub repository created (e.g., yourusername/telemedicine)
- Push the entire project to GitHub (root contains `index.php`, `api/`, `backend/`, `config/`, `database.sql`)

2) Database import (MySQL local / production)

- For MySQL (recommended for initial test and many shared hosts):

Open PowerShell and run (adjust credentials):

```powershell
# from project root
mysql -u root -p < .\database.sql
```

- If using phpMyAdmin, import `database.sql` via UI.

Notes: The `database.sql` is written for MySQL (InnoDB). It includes sample users and sample password hashes placeholders. If you want predictable passwords, run the `init_db.php` script in `config/` (if present) or replace values with `password_hash()` outputs.

If you want to deploy on Supabase (Postgres) we've added `database_supabase.sql` which is a Postgres-compatible conversion of the schema and can be imported into the Supabase SQL editor. Password placeholders are intentionally left commented — replace them with bcrypt hashes or insert users via a secure server-side flow.

3) Deploying the PHP app

Options:
- Shared PHP hosting: upload files via FTP; point document root to project folder
- VPS (recommended): Install PHP, Nginx/Apache, configure virtual host pointing to project root, ensure PHP-FPM is configured
- Local test: Use built-in PHP server (development only):

```powershell
# from project root
php -S 0.0.0.0:8000 -t .
```

Then open http://localhost:8000/index.php in your browser.

4) Realtime server (Node + Socket.IO)

This project contains `backend/server.js` — a small Express + Socket.IO server that writes to `real_time_feeds` table and broadcasts events.

To run locally (PowerShell):

```powershell
# Install dependencies (first time)
cd .\backend
npm install

# Start realtime server
npm start
# or
node server.js
```

The server listens by default on port 4000. When deployed, you should run this server on a reliable host (Heroku, Render, DigitalOcean) and expose port 4000 (or use a reverse proxy).

5) Wiring frontend dashboards to realtime server

Each role's dashboard (admin, provider, cashier, pharmacist) includes a small Socket.IO client that connects to `http://<host>:4000`. When you deploy to production, ensure the realtime server address is reachable (replace with your domain or use an env var).

6) Supabase / PostgreSQL deployment

Supabase uses PostgreSQL; `database.sql` is MySQL-flavored and will need conversion. Steps:

- Create a new Supabase project
- Use the Supabase SQL editor or `psql` to create tables. Manual conversion will be required for some MySQL-specific statements (ENUMs, `AUTO_INCREMENT`, `ENGINE`, `COLLATE`).
- Alternative: export only the schema and adapt types to PostgreSQL, or recreate tables using Supabase UI.
- After creating tables in Supabase, you can use the Supabase REST or Realtime features instead of the Node realtime server (requires additional integration).

Recommended path: Start with a MySQL host for production (or a managed MySQL instance), import `database.sql`, and run the Node realtime server. If you must use Supabase(Postgres), ask me and I will convert the schema and provide migration SQL.

7) GitHub Actions (optional)

You can add a GitHub Actions workflow to deploy on push to `main`. Typical flow:
- Build & run unit tests (if any)
- Deploy PHP files to hosting (via FTP or SSH)
- Deploy Node backend to a Node host (Heroku/Render)
- Run database migrations on the target database

8) Verifying real-time multi-department sharing

- Start the Node realtime server (backend)
- Start the PHP app (hosted locally or remote)
- Login as multiple roles (in different browsers or incognito windows)
- Trigger actions that call the realtime endpoint — e.g., a consultation created, pharmacy dispense — the app can POST to `/backend/realtime` to notify all connected dashboards

Example POST from any client (cURL):

```powershell
curl -X POST http://localhost:4000/realtime -H "Content-Type: application/json" -d '{"event_type":"consultation","patient_id":1,"created_by_id":3,"created_by_role":"healthcare","event_data":{"notes":"Started consultation"}}'
```

This will insert a row into `real_time_feeds` and broadcast the event to connected dashboards.

9) Making sign-up and login work

- The `index.php` login/signup forms call `api/auth.php?action=login` and `api/auth.php?action=signup` respectively.
- `api/auth.php` implements both `login`, `signup`, `logout`, and `current` endpoints. Ensure your database is imported and writable.
- Successful login sets a PHP session on the server and the front-end stores a `user` object in `localStorage` to improve dashboard UX.

10) Final checklist before 'production'

- [ ] Import `database.sql` to the production database
- [ ] Update `config/env_loader.php` / `.env` with production DB credentials
- [ ] Start Node realtime server on a stable host
- [ ] Point front-end to correct realtime server URL (if remote)
- [ ] Secure the system (HTTPS, strong JWT secret, rotate admin passwords)
- [ ] Disable development flags in `.env` (API_DEBUG=false)

Need help converting the SQL to Supabase/Postgres or automating deployment? Reply and I will convert the schema and create deployment scripts (Docker/compose or GitHub Actions) for you.
