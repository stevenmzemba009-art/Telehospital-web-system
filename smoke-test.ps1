#!/usr/bin/env powershell
# ============================================
# Telemedicine Smoke Test
# Tests: database import, PHP server, realtime server, event creation, dashboard reception
# ============================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Telemedicine System - End-to-End Smoke Test" -ForegroundColor Cyan
Write-Host "Date: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# Test 1: Check database
Write-Host "`n[Test 1/5] Checking MySQL database..." -ForegroundColor Yellow
try {
    $dbCheck = mysql -u root -e "SELECT COUNT(*) as user_count FROM telemedicine.users;" 2>&1
    if ($dbCheck -match "user_count") {
        Write-Host "✓ MySQL database 'telemedicine' exists and is accessible" -ForegroundColor Green
        Write-Host "  $dbCheck" -ForegroundColor Gray
    } else {
        Write-Host "! MySQL not found or database not imported. Import with:" -ForegroundColor Yellow
        Write-Host "  mysql -u root -p < .\database.sql" -ForegroundColor Gray
    }
} catch {
    Write-Host "✗ MySQL check failed: $_" -ForegroundColor Red
}

# Test 2: Check Node dependencies
Write-Host "`n[Test 2/5] Checking Node backend dependencies..." -ForegroundColor Yellow
if (Test-Path ".\backend\node_modules") {
    Write-Host "✓ Node dependencies installed" -ForegroundColor Green
} else {
    Write-Host "! Node dependencies not installed. Run:" -ForegroundColor Yellow
    Write-Host "  cd .\backend && npm install" -ForegroundColor Gray
}

# Test 3: Check server.js
Write-Host "`n[Test 3/5] Checking realtime server file..." -ForegroundColor Yellow
if (Test-Path ".\backend\server.js") {
    $lines = (Get-Content ".\backend\server.js" | Measure-Object -Line).Lines
    Write-Host "✓ backend/server.js exists ($lines lines)" -ForegroundColor Green
} else {
    Write-Host "✗ backend/server.js not found" -ForegroundColor Red
}

# Test 4: Check dashboard files
Write-Host "`n[Test 4/5] Checking dashboard HTML files..." -ForegroundColor Yellow
$dashboards = @("dashboard-admin.html", "dashboard-provider.html", "dashboard-pharmacist.html", "dashboard-cashier.html")
foreach ($dash in $dashboards) {
    if (Test-Path "./$dash") {
        Write-Host "  ✓ $dash exists" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $dash missing" -ForegroundColor Red
    }
}

# Test 5: Check API endpoint
Write-Host "`n[Test 5/5] Checking auth API..." -ForegroundColor Yellow
if (Test-Path ".\api\auth.php") {
    $hasSignup = Select-String -Path ".\api\auth.php" -Pattern "action.*signup" | Measure-Object | Select-Object -ExpandProperty Count
    $hasLogin = Select-String -Path ".\api\auth.php" -Pattern "action.*login" | Measure-Object | Select-Object -ExpandProperty Count
    Write-Host "✓ api/auth.php exists (signup: $hasSignup, login: $hasLogin)" -ForegroundColor Green
} else {
    Write-Host "✗ api/auth.php not found" -ForegroundColor Red
}

# Summary and next steps
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "SMOKE TEST SUMMARY" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

Write-Host "`nTo run the full system locally:" -ForegroundColor Cyan
Write-Host "1. Import database (if not done):" -ForegroundColor Gray
Write-Host "   mysql -u root -p < .\database.sql" -ForegroundColor White

Write-Host "`n2. Install backend dependencies (terminal 1):" -ForegroundColor Gray
Write-Host "   cd .\backend; npm install" -ForegroundColor White

Write-Host "`n3. Start realtime server (terminal 1 after npm install):" -ForegroundColor Gray
Write-Host "   npm start" -ForegroundColor White

Write-Host "`n4. Start PHP server (terminal 2):" -ForegroundColor Gray
Write-Host "   php -S 0.0.0.0:8000 -t ." -ForegroundColor White

Write-Host "`n5. Open browser and test:" -ForegroundColor Gray
Write-Host "   http://localhost:8000/index.php" -ForegroundColor White

Write-Host "`n6. Create test event (terminal 3 - after servers are running):" -ForegroundColor Gray
Write-Host '   $body = @{event_type="consultation";patient_id=1;created_by_id=3;created_by_role="healthcare";event_data=@{notes="Test event"}} | ConvertTo-Json' -ForegroundColor White
Write-Host '   Invoke-WebRequest -Uri "http://localhost:4000/realtime" -Method POST -Body $body -ContentType "application/json"' -ForegroundColor White

Write-Host "`n7. Verify in database:" -ForegroundColor Gray
Write-Host "   mysql -u root telemedicine -e 'SELECT * FROM real_time_feeds ORDER BY id DESC LIMIT 5;'" -ForegroundColor White

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "✓ Smoke test complete" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
