<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: /telemedicine/index.php');
    exit();
}

$user = getCurrentUser();
$role = $user['role'];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Telemedicine Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard-container {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 20px;
            margin: 20px 0;
        }

        .sidebar {
            background: var(--surface);
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .sidebar h3 {
            margin-top: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin: 8px 0;
        }

        .sidebar button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            background: #222;
            border: 1px solid #666;
            color: var(--accent);
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .sidebar button:hover {
            background: var(--accent);
            color: #111;
        }

        .sidebar button.active {
            background: var(--accent);
            color: #111;
            font-weight: bold;
        }

        .main-content {
            background: var(--surface);
            padding: 20px;
            border-radius: 8px;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .card {
            background: #222;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .card h4 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #666;
        }

        table th {
            background: #333;
            font-weight: bold;
        }

        table tr:hover {
            background: #333;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            margin: 0 2px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #ff9800;
            color: white;
        }

        .status-completed {
            background: #4caf50;
            color: white;
        }

        .status-scheduled {
            background: #2196f3;
            color: white;
        }

        .status-absent {
            background: #f44336;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #666;
            border-radius: 4px;
            background: #111;
            color: var(--accent);
            font-family: inherit;
        }

        .user-info {
            background: #222;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 5px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #222;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: var(--accent);
        }

        .stat-card .label {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #4caf50;
            color: white;
        }

        .alert-error {
            background: #f44336;
            color: white;
        }

        .alert-info {
            background: #2196f3;
            color: white;
        }
    </style>
</head>


<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo">Dashboard</h1>
            <nav>
                <span style="color: var(--accent); margin-right: 20px;">
                    <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo ucfirst($role); ?>)
                </span>
                <button id="logoutBtn" class="btn">Logout</button>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard-container">
            <!-- Sidebar Navigation -->
            <div class="sidebar">
                <h3>Menu</h3>
                <ul>
                    <li><button class="menu-btn active" data-section="home">Home</button></li>

                    <?php if ($role === 'admin'): ?>
                    <li><button class="menu-btn" data-section="users">Manage Users</button></li>
                    <li><button class="menu-btn" data-section="messages">Messages</button></li>
                    <li><button class="menu-btn" data-section="reports">Reports</button></li>
                    <?php endif; ?>

                    <?php if ($role === 'cashier'): ?>
                    <li><button class="menu-btn" data-section="patients">Patients</button></li>
                    <li><button class="menu-btn" data-section="transactions">Transactions</button></li>
                    <?php endif; ?>

                    <?php if ($role === 'healthcare'): ?>
                    <li><button class="menu-btn" data-section="consultations">Consultations</button></li>
                    <li><button class="menu-btn" data-section="patients-view">View Patients</button></li>
                    <?php endif; ?>

                    <?php if ($role === 'pharmacist'): ?>
                    <li><button class="menu-btn" data-section="pharmacy">Pharmacy Tasks</button></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Home Section -->
                <div id="home" class="section active">
                    <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                    <div class="user-info">
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'Not set'); ?></p>
                    </div>

                    <?php if ($role === 'admin'): ?>
                    <h3>System Overview</h3>
                    <div class="stats-grid" id="adminStats"></div>
                    <?php elseif ($role === 'cashier'): ?>
                    <h3>Today's Summary</h3>
                    <div class="stats-grid" id="cashierStats"></div>
                    <?php elseif ($role === 'healthcare'): ?>
                    <h3>Your Consultations</h3>
                    <div id="consultationStats"></div>
                    <?php elseif ($role === 'pharmacist'): ?>
                    <h3>Pending Tasks</h3>
                    <div id="pharmacyStats"></div>
                    <?php endif; ?>
                </div>

                <!-- Admin Sections -->
                <?php if ($role === 'admin'): ?>
                <div id="users" class="section">
                    <h2>Manage Users</h2>
                    <div id="usersList"></div>
                </div>

                <div id="messages" class="section">
                    <h2>Contact Messages</h2>
                    <div id="messagesList"></div>
                </div>

                <div id="reports" class="section">
                    <h2>System Reports</h2>
                    <div id="reportsList"></div>
                </div>
                <?php endif; ?>

                <!-- Cashier Sections -->
                <?php if ($role === 'cashier'): ?>
                <div id="patients" class="section">
                    <h2>Patient Management</h2>
                    <button class="btn primary" onclick="openPatientForm()">Add New Patient</button>
                    <div id="patientsList" style="margin-top: 20px;"></div>
                </div>

                <div id="transactions" class="section">
                    <h2>Transactions</h2>
                    <div id="transactionsList"></div>
                </div>
                <?php endif; ?>

                <!-- Healthcare Provider Sections -->
                <?php if ($role === 'healthcare'): ?>
                <div id="consultations" class="section">
                    <h2>My Consultations</h2>
                    <button class="btn primary" onclick="openConsultationForm()">Schedule Consultation</button>
                    <div id="consultationsList" style="margin-top: 20px;"></div>
                </div>

                <div id="patients-view" class="section">
                    <h2>View Patients</h2>
                    <div id="patientViewList"></div>
                </div>
                <?php endif; ?>

                <!-- Pharmacist Sections -->
                <?php if ($role === 'pharmacist'): ?>
                <div id="pharmacy" class="section">
                    <h2>Pharmacy Tasks</h2>
                    <div id="pharmacyList"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div>© <strong>Steven Mzemba</strong> 2025</div>
            <div class="tagline">Telemedicine System</div>
        </div>
    </footer>

    <script>
        const role = '<?php echo $role; ?>';
        const userId = <?php echo $user['id']; ?>;

        // Menu navigation
        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.menu-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                
                btn.classList.add('active');
                const sectionId = btn.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');

                // Load data for the section
                loadSectionData(sectionId);
            });
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('/telemedicine/api/auth.php?action=logout', { method: 'POST' });
            window.location.href = '/telemedicine/index.php';
        });

        // Load section data
        async function loadSectionData(sectionId) {
            if (sectionId === 'home') {
                if (role === 'admin') loadAdminStats();
                else if (role === 'cashier') loadCashierStats();
                else if (role === 'healthcare') loadConsultationStats();
                else if (role === 'pharmacist') loadPharmacyStats();
            } else if (sectionId === 'users') loadUsers();
            else if (sectionId === 'messages') loadMessages();
            else if (sectionId === 'patients') loadPatients();
            else if (sectionId === 'transactions') loadTransactions();
            else if (sectionId === 'consultations') loadConsultations();
            else if (sectionId === 'patients-view') loadPatientList();
            else if (sectionId === 'pharmacy') loadPharmacyTasks();
        }

        // Admin Stats
        async function loadAdminStats() {
            try {
                document.getElementById('adminStats').innerHTML = `
                    <div class="stat-card">
                        <div class="number">4</div>
                        <div class="label">Total Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">4</div>
                        <div class="label">Total Patients</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">3</div>
                        <div class="label">Consultations</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">3</div>
                        <div class="label">Pharmacy Tasks</div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading admin stats:', error);
            }
        }

        // Cashier Stats
        async function loadCashierStats() {
            try {
                document.getElementById('cashierStats').innerHTML = `
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Transactions Today</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">$0.00</div>
                        <div class="label">Total Amount</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">4</div>
                        <div class="label">Patients Served</div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading cashier stats:', error);
            }
        }

        // Consultation Stats
        async function loadConsultationStats() {
            try {
                document.getElementById('consultationStats').innerHTML = `
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="number">2</div>
                            <div class="label">Scheduled</div>
                        </div>
                        <div class="stat-card">
                            <div class="number">1</div>
                            <div class="label">Completed</div>
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading consultation stats:', error);
            }
        }

        // Pharmacy Stats
        async function loadPharmacyStats() {
            try {
                document.getElementById('pharmacyStats').innerHTML = `
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="number">2</div>
                            <div class="label">Pending Tasks</div>
                        </div>
                        <div class="stat-card">
                            <div class="number">1</div>
                            <div class="label">Completed Today</div>
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading pharmacy stats:', error);
            }
        }

        // Load Users
        async function loadUsers() {
            try {
                let html = `<table><thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr></thead><tbody>
                    <tr><td>1</td><td>admin</td><td>admin@telemedicine.com</td><td>admin</td></tr>
                    <tr><td>2</td><td>cashier</td><td>cashier@telemedicine.com</td><td>cashier</td></tr>
                    <tr><td>3</td><td>healthcare</td><td>provider@telemedicine.com</td><td>healthcare</td></tr>
                    <tr><td>4</td><td>pharmacist</td><td>pharmacist@telemedicine.com</td><td>pharmacist</td></tr>
                </tbody></table>`;
                
                document.getElementById('usersList').innerHTML = html;
            } catch (error) {
                document.getElementById('usersList').innerHTML = '<div class="alert alert-error">Error loading users</div>';
            }
        }

        // Load Messages
        async function loadMessages() {
            try {
                let html = '<div>No messages yet.</div>';
                document.getElementById('messagesList').innerHTML = html;
            } catch (error) {
                document.getElementById('messagesList').innerHTML = '<div class="alert alert-error">Error loading messages</div>';
            }
        }

        // Load Patients
        async function loadPatients() {
            try {
                let html = `<table><thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Age</th></tr></thead><tbody>
                    <tr><td>1</td><td>John Banda</td><td>0976123456</td><td>john@example.com</td><td>45</td></tr>
                    <tr><td>2</td><td>Mary Chabwela</td><td>0977234567</td><td>mary@example.com</td><td>38</td></tr>
                    <tr><td>3</td><td>Joseph Mthembu</td><td>0978345678</td><td>joseph@example.com</td><td>52</td></tr>
                    <tr><td>4</td><td>Alice Phiri</td><td>0979456789</td><td>alice@example.com</td><td>41</td></tr>
                </tbody></table>`;
                
                document.getElementById('patientsList').innerHTML = html;
            } catch (error) {
                document.getElementById('patientsList').innerHTML = '<div class="alert alert-error">Error loading patients</div>';
            }
        }

        // Load Transactions
        async function loadTransactions() {
            try {
                let html = '<table><thead><tr><th>Date</th><th>Patient</th><th>Amount</th><th>Type</th></tr></thead><tbody><tr><td colspan="4">No transactions yet</td></tr></tbody></table>';
                document.getElementById('transactionsList').innerHTML = html;
            } catch (error) {
                document.getElementById('transactionsList').innerHTML = '<div class="alert alert-error">Error loading transactions</div>';
            }
        }

        // Load Consultations
        async function loadConsultations() {
            try {
                let html = `<table><thead><tr><th>Patient</th><th>Date</th><th>Status</th></tr></thead><tbody>
                    <tr><td>John Banda</td><td>2025-12-04</td><td><span class="status-badge status-completed">completed</span></td></tr>
                    <tr><td>Mary Chabwela</td><td>2025-12-04</td><td><span class="status-badge status-scheduled">scheduled</span></td></tr>
                    <tr><td>Joseph Mthembu</td><td>2025-12-05</td><td><span class="status-badge status-scheduled">scheduled</span></td></tr>
                </tbody></table>`;
                
                document.getElementById('consultationsList').innerHTML = html;
            } catch (error) {
                document.getElementById('consultationsList').innerHTML = '<div class="alert alert-error">Error loading consultations</div>';
            }
        }

        // Load Patient List (Healthcare Provider)
        async function loadPatientList() {
            try {
                let html = `<table><thead><tr><th>Name</th><th>Phone</th><th>Medical History</th></tr></thead><tbody>
                    <tr><td>John Banda</td><td>0976123456</td><td>Type 2 Diabetes, Hypertension</td></tr>
                    <tr><td>Mary Chabwela</td><td>0977234567</td><td>Hypertension</td></tr>
                    <tr><td>Joseph Mthembu</td><td>0978345678</td><td>Type 2 Diabetes</td></tr>
                    <tr><td>Alice Phiri</td><td>0979456789</td><td>Hypertension, Diabetes</td></tr>
                </tbody></table>`;
                
                document.getElementById('patientViewList').innerHTML = html;
            } catch (error) {
                document.getElementById('patientViewList').innerHTML = '<div class="alert alert-error">Error loading patients</div>';
            }
        }

        // Load Pharmacy Tasks
        async function loadPharmacyTasks() {
            try {
                let html = `<table><thead><tr><th>Patient</th><th>Medication</th><th>Dosage</th><th>Status</th></tr></thead><tbody>
                    <tr><td>John Banda</td><td>Lisinopril</td><td>10mg</td><td><span class="status-badge status-pending">pending</span></td></tr>
                    <tr><td>Mary Chabwela</td><td>Amlodipine</td><td>5mg</td><td><span class="status-badge status-completed">completed</span></td></tr>
                    <tr><td>Joseph Mthembu</td><td>Metformin</td><td>500mg</td><td><span class="status-badge status-pending">pending</span></td></tr>
                </tbody></table>`;
                
                document.getElementById('pharmacyList').innerHTML = html;
            } catch (error) {
                document.getElementById('pharmacyList').innerHTML = '<div class="alert alert-error">Error loading pharmacy tasks</div>';
            }
        }

        // Placeholder action functions
        function openPatientForm() {
            alert('Patient registration form - To be implemented');
        }

        function openConsultationForm() {
            alert('Consultation scheduling form - To be implemented');
        }
    </script>
</body>

</html>
