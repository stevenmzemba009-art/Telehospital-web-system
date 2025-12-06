# Supabase Deployment Guide
## Cloud-Ready Telemedicine System with Automatic PostgreSQL Schema Import

**Date**: December 6, 2025  
**Status**: Production-Ready  
**Target**: Supabase (PostgreSQL cloud database)

---

## Overview

This guide sets up your telemedicine system on **Supabase**, a Firebase alternative with PostgreSQL. The system will:
- Store all data in cloud PostgreSQL database
- Run realtime server on Render, Heroku, or Railway
- Host PHP app on any PHP hosting provider
- Sync real-time events across all dashboards globally

---

## Part 1: Create Supabase Account & Project

### 1.1 Sign Up
1. Go to **https://supabase.com**
2. Click "Start your project for free"
3. Sign up with GitHub or email
4. Create organization (e.g., "TelemedicineOrg")

### 1.2 Create Project
1. Click "New Project"
2. **Project name**: `telemedicine`
3. **Database password**: Create strong password (save this!)
4. **Region**: Choose closest to your users (e.g., `us-east-1` for US)
5. Click "Create new project"
6. **Wait 2-3 minutes** for project to initialize

### 1.3 Get Connection Details
Once project is ready:
1. Go to **Settings → Database** (left sidebar)
2. Find **Connection string** section
3. Copy the following (you'll need these):
   - **Host**: `db.PROJECT_ID.supabase.co`
   - **Port**: `5432` (standard PostgreSQL)
   - **Database**: `postgres`
   - **User**: `postgres`
   - **Password**: (the one you set above)
   - **Full connection string**: `postgresql://postgres:PASSWORD@db.PROJECT_ID.supabase.co:5432/postgres`

**Example**:
```
postgresql://postgres:MyPassword123@db.abc123def456.supabase.co:5432/postgres
```

---

## Part 2: Automatic Schema Import (2 Methods)

### Method 1: Supabase Web UI (Easiest - 5 minutes)

#### 2.1.1 Access SQL Editor
1. In Supabase dashboard, go to **SQL Editor** (left sidebar)
2. Click **"New query"**

#### 2.1.2 Import Schema
1. Read entire contents of `database_supabase.sql` from your local telemedicine folder
2. Paste ALL contents into the SQL editor
3. Click **"Run"** (blue button, top right)
4. **Wait** for all 13 tables to be created (~10-30 seconds)

**Expected Output**:
```
Query executed successfully
Executed queries: 13
Rows affected: 0
```

#### 2.1.3 Verify Tables
1. Go to **Table Editor** (left sidebar)
2. Select database dropdown → `postgres`
3. You should see tables:
   - `users` (with 4 sample users)
   - `patients` (with 4 sample patients)
   - `consultations`
   - `pharmacy_tasks`
   - `visits`
   - `contact_messages`
   - `transactions`
   - `chat_messages`
   - `sms_messages`
   - `attendance_tracking`
   - `reminders`
   - `real_time_feeds` (empty initially)
   - `department_access_logs`

4. Click each table to verify data loaded (should see sample records in `users` and `patients`)

---

### Method 2: Automatic SQL Import Script (Advanced - CLI)

If you prefer command-line automation:

```powershell
# Step 1: Set environment variables
$env:SUPABASE_DB_HOST = "db.abc123def456.supabase.co"
$env:SUPABASE_DB_PORT = "5432"
$env:SUPABASE_DB_USER = "postgres"
$env:SUPABASE_DB_PASSWORD = "YourPassword123"
$env:SUPABASE_DB_NAME = "postgres"

# Step 2: Import schema using psql (if installed)
# First, download and install PostgreSQL client from: https://www.postgresql.org/download/windows/
# Then run:
psql -h $env:SUPABASE_DB_HOST -U $env:SUPABASE_DB_USER -d $env:SUPABASE_DB_NAME -f .\database_supabase.sql

# You'll be prompted for password (use the one from 1.3 above)
# Expected: "CREATE TABLE" messages for all 13 tables
```

---

## Part 3: Configure Node Realtime Server

### 3.1 Install Dependencies Locally

```powershell
cd "C:\Users\MZEMBA\Desktop\New folder (2)\telemedicine\backend"
npm install
```

### 3.2 Update Environment File

Create `.env` file in `backend/` folder:

```env
# Database: Supabase PostgreSQL
DB_HOST=db.abc123def456.supabase.co
DB_PORT=5432
DB_USER=postgres
DB_PASSWORD=YourPassword123
DB_NAME=postgres

# Supabase API (optional - for direct API inserts)
SUPABASE_URL=https://abc123def456.supabase.co
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

# Server
PORT=4000
NODE_ENV=production
```

**How to get these values**:
- **DB_HOST, DB_PORT, DB_USER, DB_PASSWORD**: From Supabase Settings → Database (Step 1.3)
- **SUPABASE_URL**: From Supabase dashboard top-left corner (shows your project URL)
- **SUPABASE_SERVICE_ROLE_KEY**: Go to **Settings → API** (left sidebar), copy "service_role" key

### 3.3 Test Local Connection

```powershell
cd backend
npm start
```

**Expected output**:
```
Connected to MySQL for realtime server
Realtime server listening on port 4000
```

If error: "ECONNREFUSED", verify DB credentials and Supabase is accepting connections.

---

## Part 4: Update PHP App Configuration

### 4.1 Update Database Connection

Edit `config/db.php`:

```php
<?php
// Before: MySQL connection
// $host = 'localhost';
// $username = 'root';
// $password = '';
// $database = 'telemedicine';

// After: Supabase PostgreSQL connection
$host = 'db.abc123def456.supabase.co';
$port = 5432;
$username = 'postgres';
$password = 'YourPassword123';
$database = 'postgres';

// Update PDO connection string
$dsn = "pgsql:host=$host;port=$port;dbname=$database";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "Connected to Supabase PostgreSQL";
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>
```

### 4.2 Verify API Connection

Test locally:
```powershell
php -S 0.0.0.0:8000 -t .
```

Open browser to `http://localhost:8000/index.php`:
- Try to login with: Username: `admin`, Password: `admin123`
- Should show dashboard (if auth works, Supabase connection is working)

---

## Part 5: Deploy Node Realtime Server

### 5.1 Push to GitHub

```powershell
cd "C:\Users\MZEMBA\Desktop\New folder (2)\telemedicine"

# Initialize git (if not already done)
git init
git add .
git commit -m "Add Supabase deployment config"
git remote add origin https://github.com/YOUR_USERNAME/telemedicine.git
git push -u origin main
```

### 5.2 Deploy to Render.com (Free + Production-Ready)

1. **Create account**: Go to **https://render.com** → Sign up with GitHub
2. **Create new Web Service**:
   - Connect GitHub repository (`telemedicine`)
   - Build command: `cd backend && npm install`
   - Start command: `cd backend && npm start`
   - Environment variables (add all from Step 3.2):
     - `DB_HOST=db.abc123def456.supabase.co`
     - `DB_PORT=5432`
     - `DB_USER=postgres`
     - `DB_PASSWORD=YourPassword123`
     - `DB_NAME=postgres`
     - `SUPABASE_URL=https://abc123def456.supabase.co`
     - `SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`
     - `PORT=4000`
     - `NODE_ENV=production`

3. **Deploy**: Click "Create Web Service" → Render deploys automatically
4. **Get URL**: Once deployed, you'll see `https://telemedicine-XXXXX.onrender.com`

### 5.3 Deploy to Heroku (Alternative)

```powershell
# Install Heroku CLI: https://devcenter.heroku.com/articles/heroku-cli

# Login
heroku login

# Create app
heroku create telemedicine-app

# Add environment variables
heroku config:set -a telemedicine-app DB_HOST=db.abc123def456.supabase.co
heroku config:set -a telemedicine-app DB_PORT=5432
heroku config:set -a telemedicine-app DB_USER=postgres
heroku config:set -a telemedicine-app DB_PASSWORD=YourPassword123
heroku config:set -a telemedicine-app DB_NAME=postgres
heroku config:set -a telemedicine-app SUPABASE_URL=https://abc123def456.supabase.co
heroku config:set -a telemedicine-app SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

# Deploy
git push heroku main

# View logs
heroku logs -a telemedicine-app --tail
```

---

## Part 6: Update Dashboard Configuration

### 6.1 Update Dashboard URLs

All dashboard HTML files connect to realtime server. Update the Socket.IO URL:

**Before** (localhost):
```html
const socket = io('http://localhost:4000');
```

**After** (production):
```html
const socket = io('https://telemedicine-XXXXX.onrender.com');
```

Update in all 4 files:
- `dashboard-admin.html`
- `dashboard-provider.html`
- `dashboard-pharmacist.html`
- `dashboard-cashier.html`

### 6.2 Update Realtime Event Endpoint

**Before** (localhost):
```javascript
fetch('http://localhost:4000/realtime', { ... })
```

**After** (production):
```javascript
fetch('https://telemedicine-XXXXX.onrender.com/realtime', { ... })
```

---

## Part 7: Deploy PHP App

### 7.1 Option A: Free Hosting (Netlify/Vercel - Static Only)

Since you have PHP, you need PHP hosting:
- **https://000webhost.com** (Free PHP hosting, 1 GB)
- **https://hostinger.com** (Paid, recommended)
- **https://heroku.com** (Free tier deprecated, but container support available)

### 7.2 Option B: Deploy to 000webhost (Free + PHP Support)

1. Create account: **https://www.000webhost.com**
2. Create new website
3. Use FTP to upload all files from telemedicine folder
4. Update `config/db.php` with Supabase credentials
5. Access via their provided domain (e.g., `https://telemedicine.000webhostapp.com`)

### 7.3 Option C: Deploy to Hostinger (Paid, Recommended)

1. Create account: **https://www.hostinger.com**
2. Buy hosting plan (PHP + MySQL/PostgreSQL support)
3. Use File Manager or FTP to upload all files
4. Update `config/db.php` with Supabase credentials
5. Point domain to your new host
6. Access via domain (e.g., `https://telemedicine.com`)

---

## Part 8: Final Integration & Testing

### 8.1 Verify All Connections

```powershell
# Test Supabase PostgreSQL connection
# Option 1: Use psql (if installed)
psql -h db.abc123def456.supabase.co -U postgres -d postgres

# Option 2: Use online query tool in Supabase dashboard
# SQL Editor → New query → SELECT 1;
```

### 8.2 Test Real-Time Event Flow (Production)

```powershell
# 1. Open production PHP app in browser
# https://telemedicine.000webhostapp.com or your domain

# 2. Login with: admin / admin123

# 3. Create test event (from any dashboard or via curl)
$event = @{
    event_type = "consultation"
    patient_id = 1
    created_by_id = 3
    created_by_role = "healthcare"
    event_data = @{
        notes = "Production test event"
        timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    }
} | ConvertTo-Json

# Replace with your production realtime server URL
Invoke-WebRequest -Uri "https://telemedicine-XXXXX.onrender.com/realtime" `
    -Method POST `
    -Body $event `
    -ContentType "application/json"

# 4. Check Supabase real_time_feeds table
# Should show new event row within 1-2 seconds
```

### 8.3 Verify Multi-Role Real-Time Updates

1. Open **2 browser windows** (or incognito)
2. **Window 1**: Login as Healthcare Provider → Open `dashboard-provider.html`
3. **Window 2**: Login as Admin → Open `dashboard-admin.html`
4. **From Window 1 or admin panel**: Create a consultation event
5. **Expected**: Both dashboards receive notification instantly (cross-platform real-time sync)

---

## Part 9: Environment Variables Summary

### Node Realtime Server (Render/Heroku)
```env
DB_HOST=db.abc123def456.supabase.co
DB_PORT=5432
DB_USER=postgres
DB_PASSWORD=YourPassword123
DB_NAME=postgres
SUPABASE_URL=https://abc123def456.supabase.co
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
PORT=4000
NODE_ENV=production
```

### PHP App (000webhost/Hostinger)
File: `config/db.php`
```php
$host = 'db.abc123def456.supabase.co';
$port = 5432;
$username = 'postgres';
$password = 'YourPassword123';
$database = 'postgres';
$dsn = "pgsql:host=$host;port=$port;dbname=$database";
```

### Dashboards (HTML files)
Update Socket.IO URL in all 4 dashboard HTML files:
```javascript
const socket = io('https://telemedicine-XXXXX.onrender.com');
```

---

## Part 10: Troubleshooting

| Issue | Solution |
|-------|----------|
| "Connection refused" on Supabase | Check Supabase project is running; verify IP whitelisting (Settings → Database → Allow all connections) |
| "No route to host" | Supabase default is private; go to **Settings → Database → Connection pooling → Enable** |
| "Authentication failed" for postgres user | Verify password is correct (include special chars carefully); check user is `postgres` not `postgres-admin` |
| Dashboards not receiving events | Check realtime server URL is correct; verify Socket.IO port 4000 is open (Render/Heroku should proxy automatically) |
| "Cannot POST /realtime" (404) | Realtime server not deployed; check Render/Heroku deployment logs for errors |
| PHP app can't connect to Supabase | Update `config/db.php` with correct credentials; test connection via Supabase SQL Editor first |
| Real-time events not in database | Check if event was POST-ed to correct URL; verify Node server logs show "Inserted into PostgreSQL" |

---

## Part 11: Security Best Practices

### 11.1 Protect Credentials
- **Never commit** `.env` files to GitHub
- Use Render/Heroku **Environment Variables** (not committed to repo)
- Supabase Service Role Key should only be on backend server (not in frontend HTML)

### 11.2 Database Backups
Supabase automatically backs up daily. To manual backup:
1. Go to **Settings → Backups**
2. Click **"Create backup"**
3. Download if needed

### 11.3 Enable Row-Level Security (RLS)
For production, enable Supabase RLS policies:
1. **Authentication** → **Policies**
2. Create policies per table to restrict access by role

---

## Part 12: Monitoring & Logs

### Supabase Logs
- **Database Logs**: Settings → Logs (slow queries, errors)
- **API Logs**: Settings → API (auth failures, rate limits)

### Render Logs
- **Deployment Logs**: Go to service → "Logs" tab
- **Live Logs**: See realtime server output

### Heroku Logs
```powershell
heroku logs -a telemedicine-app --tail
```

---

## Deployment Checklist

- [ ] Supabase account created
- [ ] Project created and initialized
- [ ] Schema imported via SQL Editor (all 13 tables visible)
- [ ] Connection string saved
- [ ] `.env` file created with Supabase credentials
- [ ] Node realtime server tested locally (`npm start`)
- [ ] Code pushed to GitHub
- [ ] Realtime server deployed (Render/Heroku)
- [ ] Realtime server URL updated in all 4 dashboard HTML files
- [ ] PHP app deployed (000webhost/Hostinger/other)
- [ ] `config/db.php` updated with Supabase credentials
- [ ] Test event created and verified in database
- [ ] Multi-role real-time sync tested (2+ browsers)
- [ ] All 4 dashboards receiving live events
- [ ] Backups configured
- [ ] Monitoring/logs accessible

---

## Architecture Diagram (Production)

```
┌─────────────────────────────────────────────────────────────────┐
│                         INTERNET                                │
└─────────────────────────────────────────────────────────────────┘
                    ↓                          ↓
        ┌───────────────────┐      ┌──────────────────────┐
        │   PHP App Host    │      │  Node Realtime       │
        │ (000webhost/      │      │  Server (Render/     │
        │  Hostinger)       │      │  Heroku)             │
        │  - index.php      │      │  - Socket.IO (ws)    │
        │  - api/auth.php   │      │  - POST /realtime    │
        │  - dashboards     │      │  - Event broadcast   │
        └───────────────────┘      └──────────────────────┘
                ↓                              ↓
                └──────────────┬───────────────┘
                               ↓
                  ┌────────────────────────────┐
                  │   Supabase PostgreSQL      │
                  │   (Cloud Database)         │
                  │  - users                   │
                  │  - patients                │
                  │  - consultations           │
                  │  - real_time_feeds         │
                  │  - 9+ other tables         │
                  └────────────────────────────┘
```

---

## Support & Next Steps

**Once deployed**:
1. Users can login from anywhere
2. All activity syncs to cloud in real-time
3. Dashboards work across browsers (multi-screen monitoring)
4. Data persists in cloud-hosted PostgreSQL

**Optional enhancements**:
- Enable Supabase Authentication (replaces PHP auth.php)
- Add Supabase Realtime subscriptions (replaces Socket.IO)
- Implement Row-Level Security (database-level access control)
- Set up alerting for critical events

---

**Document Version**: 1.0  
**Last Updated**: December 6, 2025  
**Status**: Production-Ready ✅
