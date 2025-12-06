# End-to-End Smoke Test Guide
## Testing Telemedicine System: Database → APIs → Real-time Events → Dashboards

Date: 2025-12-06  
System: Windows PowerShell + PHP + Node.js + MySQL/Supabase

---

## Prerequisites
- MySQL installed (or Supabase account with PostgreSQL)
- Node.js and npm installed
- PHP installed (tested with PHP 7.4+)
- PowerShell or Command Prompt

---

## Step 1: Prepare Database

### Option A: MySQL (Local)
If MySQL is installed (e.g., via XAMPP, WampServer):

```powershell
# Navigate to project root
cd "C:\Users\MZEMBA\Desktop\New folder (2)\telemedicine"

# Import schema (provide MySQL root password when prompted)
mysql -u root -p < .\database.sql

# Verify import
mysql -u root -e "SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema='telemedicine';"
```

**Expected output**: table_count should be 13 (13 tables created).

### Option B: Supabase/PostgreSQL
1. Create a Supabase account at https://supabase.com
2. Create a new project
3. In the SQL editor, paste contents of `database_supabase.sql`
4. Run to create all tables
5. Note your `SUPABASE_URL` and `SUPABASE_SERVICE_ROLE_KEY` for later

---

## Step 2: Start Realtime Server (Terminal 1)

```powershell
cd "C:\Users\MZEMBA\Desktop\New folder (2)\telemedicine\backend"

# First time only: install dependencies
npm install

# Start server (listens on port 4000)
npm start
# or
node server.js
```

**Expected output**:
```
> npm start
Connected to MySQL for realtime server
Realtime server listening on port 4000
```

---

## Step 3: Start PHP Development Server (Terminal 2)

```powershell
cd "C:\Users\MZEMBA\Desktop\New folder (2)\telemedicine"

# Start built-in PHP server (listens on port 8000)
php -S 0.0.0.0:8000 -t .
```

**Expected output**:
```
Development Server started at http://0.0.0.0:8000
```

---

## Step 4: Test Login & Dashboards (Browser)

1. Open browser to: **http://localhost:8000/index.php**
2. Click "Sign Up" to create a test user:
   - Full Name: Test Provider
   - Username: testprovider
   - Email: test@example.com
   - Password: Test123456
   - Role: Healthcare Provider
3. Click "Create Account"
4. Login with credentials:
   - Username: testprovider
   - Password: Test123456
   - Role: Healthcare Provider
5. You'll be redirected to dashboard (or can open `dashboard-provider.html` directly)

**Expected**: Dashboard shows gold & black theme with menu on left, recent activities/stats on right.

---

## Step 5: Create a Test Event (Terminal 3)

Create a real-time event that triggers database insert and broadcasts to dashboards:

```powershell
# Define event payload
$event = @{
    event_type = "consultation"
    reference_id = 1
    patient_id = 1
    created_by_id = 3
    created_by_role = "healthcare"
    event_data = @{
        notes = "Test consultation started at $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
        duration = "15 minutes"
        status = "in-progress"
    }
} | ConvertTo-Json

# Send event to realtime server
Invoke-WebRequest -Uri "http://localhost:4000/realtime" `
    -Method POST `
    -Body $event `
    -ContentType "application/json" `
    -Verbose

# Expected output: 200 OK with returned feed object
```

---

## Step 6: Verify Database Insertion

### MySQL:
```powershell
# Check recent events in real_time_feeds table
mysql -u root telemedicine -e "SELECT id, event_type, patient_id, created_by_id, timestamp FROM real_time_feeds ORDER BY id DESC LIMIT 5;"
```

**Expected output**:
```
id | event_type    | patient_id | created_by_id | timestamp
14 | consultation  | 1          | 3             | 2025-12-06 14:35:22
```

### Supabase:
1. Open Supabase project → Table Editor
2. Select `real_time_feeds` table
3. Scroll to bottom — you should see the new event row

---

## Step 7: Verify Dashboard Reception

### In Browser:
1. Keep the dashboard open (from Step 4)
2. After posting the event in Step 5, check:
   - **Admin dashboard** (`dashboard-admin.html`): Look for a new row in "Recent Activities" table
   - **Provider dashboard** (`dashboard-provider.html`): Look for a notification alert at the top (gold/black color, temporary)
   - **Pharmacist dashboard** (`dashboard-pharmacist.html`): Similar notification alert
   - **Cashier dashboard** (`dashboard-cashier.html`): Similar notification alert

**Expected**: Within 1-2 seconds, the event appears as a visual notification or appended row.

### In Browser Console:
Open DevTools (F12) → Console tab:
- You should see: `Realtime event received (admin):` or similar
- Event data should be logged

---

## Step 8: Verify Multi-Role Real-Time Sharing

Open **multiple browsers** (or incognito windows) logged in as different roles:
1. Terminal 1: Realtime server running
2. Terminal 2: PHP server running
3. Browser A: Logged in as Healthcare Provider (dashboard-provider.html open)
4. Browser B: Logged in as Pharmacist (dashboard-pharmacist.html open)
5. Terminal 3: Create event via Invoke-WebRequest
6. **Expected**: Both dashboards receive the event notification instantly

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "MySQL connection error" on realtime server | Ensure MySQL is running; check DB credentials in `backend/server.js` or `.env` |
| "Cannot POST /realtime" (404) | Realtime server not running (Terminal 1); check port 4000 is listening |
| "Connection refused" on dashboard | PHP server not running (Terminal 2); start with `php -S 0.0.0.0:8000 -t .` |
| Event not in database | Check MySQL is accepting writes; verify `real_time_feeds` table exists |
| No notification on dashboard | Check browser console for errors (F12); ensure Socket.IO client connected |
| Supabase inserts not working | Verify `SUPABASE_URL` and `SUPABASE_SERVICE_ROLE_KEY` env vars are set; check Supabase API key has insert permission |

---

## Database Activity Inspection

### View all events:
```powershell
mysql -u root telemedicine -e "SELECT * FROM real_time_feeds;"
```

### View events by patient:
```powershell
mysql -u root telemedicine -e "SELECT * FROM real_time_feeds WHERE patient_id = 1 ORDER BY timestamp DESC;"
```

### View events by user:
```powershell
mysql -u root telemedicine -e "SELECT * FROM real_time_feeds WHERE created_by_id = 3 ORDER BY timestamp DESC;"
```

### Check all real-time event types:
```powershell
mysql -u root telemedicine -e "SELECT DISTINCT event_type, COUNT(*) as count FROM real_time_feeds GROUP BY event_type;"
```

---

## Expected End Result

✓ Database has `real_time_feeds` table populated with test events  
✓ All dashboards connected to realtime server show new events in real-time  
✓ Multiple users/roles see the same event simultaneously (multi-department sharing)  
✓ Events persist in database and are queryable  
✓ System works locally without errors  

---

## Next: GitHub & Supabase Deployment

Once smoke test passes:
1. Push code to GitHub (git add, commit, push)
2. For Supabase: Set `SUPABASE_URL` and `SUPABASE_SERVICE_ROLE_KEY` on Node server and realtime events will also insert into Postgres
3. Host Node realtime server (Render/Heroku/DigitalOcean)
4. Host PHP app (any PHP hosting, adjust dashboard realtime server URL to production host)
5. Dashboards will work remotely with multi-department real-time event sharing

---

## Files Reference

| File | Purpose |
|------|---------|
| `database.sql` | MySQL schema (import for local testing) |
| `database_supabase.sql` | PostgreSQL schema (import to Supabase) |
| `backend/server.js` | Node realtime server + Socket.IO + HTTP endpoint |
| `index.php` | Login/signup interface |
| `dashboard-*.html` | Role-specific dashboards (gold/black theme) |
| `api/auth.php` | Authentication endpoints (login/signup/logout) |
| `config/db.php` | Database configuration |

---

Date: 2025-12-06  
Status: Ready for smoke test
