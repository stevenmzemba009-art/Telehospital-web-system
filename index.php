<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Telemedicine - Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #111;
            padding: 30px;
            border-radius: 8px;
            max-width: 420px;
            color: var(--accent);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
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
            background: #222;
            color: var(--accent);
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.3);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .form-actions button {
            flex: 1;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-error {
            background: #ff6b6b;
            color: white;
        }

        .alert-success {
            background: #51cf66;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .already-member {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .already-member button {
            background: none;
            border: none;
            color: var(--accent);
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }
    </style>
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo">Telemedicine</h1>
            <nav class="top-nav">
                <button id="locationBtn" class="btn">Hospital Location</button>
                <button id="contactBtn" class="btn">Contact Us</button>
                <button id="signupBtn" class="btn">Sign Up</button>
                <button id="loginBtn" class="btn primary">Login</button>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero container">
            <div class="hero-image">
                <img src="https://via.placeholder.com/420x300?text=Diabetes+Care" alt="Telemedicine services" />
            </div>
            <div class="hero-text">
                <h2>Telemedicine for Diabetes & Hypertension</h2>
                <p>Remote consultations, monitoring and support — focused on diabetic and hypertensive care.</p>
                <p class="muted">Location: Malawi — Southern Region, Phalombe District</p>
            </div>
        </section>

        <section id="blog" class="container blog">
            <h2>Patient Stories & Advice</h2>
            <div class="cards">
                <article class="card">
                    <img src="https://via.placeholder.com/220x150?text=Diabetes+Signs" alt="Diabetes signs">
                    <h3>Diabetes: Signs</h3>
                    <p>Increased thirst, frequent urination, unexplained weight loss are common signs.</p>
                    <p class="muted">Prevention: Healthy diet, regular exercise, blood sugar monitoring.</p>
                </article>

                <article class="card">
                    <img src="https://via.placeholder.com/220x150?text=Diabetes+Symptoms" alt="Diabetes symptoms">
                    <h3>Diabetes: Symptoms</h3>
                    <p>Fatigue, blurred vision, slow healing wounds can indicate high blood sugar.</p>
                    <p class="muted">Prevention: Regular checkups, medication adherence, lifestyle changes.</p>
                </article>

                <article class="card">
                    <img src="https://via.placeholder.com/220x150?text=Hypertension+Signs" alt="Hypertension signs">
                    <h3>Hypertension: Signs</h3>
                    <p>Often asymptomatic, but severe cases cause headaches and nosebleeds.</p>
                    <p class="muted">Prevention: Reduce salt, maintain healthy weight, exercise.</p>
                </article>

                <article class="card">
                    <img src="https://via.placeholder.com/220x150?text=Hypertension+Prevention" alt="Hypertension prevention">
                    <h3>Hypertension: Prevention</h3>
                    <p>Dizziness, shortness of breath in advanced stages.</p>
                    <p class="muted">Prevention: Regular BP checks, medication, lifestyle modifications.</p>
                </article>
            </div>
        </section>

        <section id="contact" class="container contact">
            <h2>Contact Us</h2>
            <p>Contact: <strong>Steven Mzemba</strong> — 0999564297 — stevenmzemba@gmail.com</p>
            <form id="contactForm" class="contact-form">
                <label>Name <input type="text" name="name" required></label>
                <label>Phone <input type="text" name="phone" required></label>
                <label>Email <input type="email" name="email" required></label>
                <label>Message <textarea name="message" required></textarea></label>
                <button class="btn primary" type="submit">Send</button>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div>© <strong>Steven Mzemba</strong> 2025</div>
            <div class="tagline">Telemedicine for diabetes and hypertension</div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h3>Login</h3>
            <div id="loginAlert"></div>
            <form id="loginForm">
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="loginRole">Role</label>
                    <select id="loginRole" name="role" required>
                        <option value="">Select your role</option>
                        <option value="admin">Admin</option>
                        <option value="cashier">Cashier</option>
                        <option value="healthcare">Healthcare Provider</option>
                        <option value="pharmacist">Pharmacist</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Login</button>
                    <button type="button" class="btn" onclick="closeLoginModal()">Cancel</button>
                </div>
                <div class="already-member">
                    Don't have an account? <button type="button" onclick="switchToSignup()">Sign up here</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="modal">
        <div class="modal-content">
            <h3>Create Account</h3>
            <div id="signupAlert"></div>
            <form id="signupForm">
                <div class="form-group">
                    <label for="signupFullName">Full Name</label>
                    <input type="text" id="signupFullName" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="signupUsername">Username</label>
                    <input type="text" id="signupUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="signupEmail">Email</label>
                    <input type="email" id="signupEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="signupPassword">Password</label>
                    <input type="password" id="signupPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="signupRole">Role</label>
                    <select id="signupRole" name="role" required>
                        <option value="">Select your role</option>
                        <option value="cashier">Cashier Officer</option>
                        <option value="healthcare">Healthcare Provider</option>
                        <option value="pharmacist">Pharmacist</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Create Account</button>
                    <button type="button" class="btn" onclick="closeSignupModal()">Cancel</button>
                </div>
                <div class="already-member">
                    Already have an account? <button type="button" onclick="switchToLogin()">Login here</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.getElementById('loginAlert').innerHTML = '';
        }

        function openSignupModal() {
            document.getElementById('signupModal').classList.add('active');
        }

        function closeSignupModal() {
            document.getElementById('signupModal').classList.remove('active');
            document.getElementById('signupAlert').innerHTML = '';
        }

        function switchToLogin() {
            closeSignupModal();
            openLoginModal();
        }

        function switchToSignup() {
            closeLoginModal();
            openSignupModal();
        }

        function showAlert(elementId, message, type) {
            const alert = document.getElementById(elementId);
            alert.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => { alert.innerHTML = ''; }, 5000);
        }

        // Navigation buttons
        document.getElementById('locationBtn').addEventListener('click', () => {
            alert('Telemedicine Location:\n\nMalawi — Southern Region\nPhalombe District\n\nProviding remote consultations for Diabetes and Hypertension');
        });

        document.getElementById('contactBtn').addEventListener('click', () => {
            document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
        });

        document.getElementById('loginBtn').addEventListener('click', openLoginModal);
        document.getElementById('signupBtn').addEventListener('click', openSignupModal);

        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.getElementById('loginUsername').value;
            const password = document.getElementById('loginPassword').value;
            const role = document.getElementById('loginRole').value;

            try {
                const response = await fetch('/telemedicine/api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password, role })
                });

                const data = await response.json();

                if (response.ok && data.ok) {
                    // store user for client-side dashboards and redirect
                    try { localStorage.setItem('user', JSON.stringify(data.user || {})); } catch (e) { /* ignore */ }
                    showAlert('loginAlert', 'Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '/telemedicine/dashboard.php';
                    }, 900);
                } else {
                    showAlert('loginAlert', data.error || 'Login failed', 'error');
                }
            } catch (error) {
                showAlert('loginAlert', 'Server error: ' + error.message, 'error');
            }
        });

        // Signup form submission
        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const full_name = document.getElementById('signupFullName').value;
            const username = document.getElementById('signupUsername').value;
            const email = document.getElementById('signupEmail').value;
            const password = document.getElementById('signupPassword').value;
            const role = document.getElementById('signupRole').value;

            if (password.length < 6) {
                showAlert('signupAlert', 'Password must be at least 6 characters', 'error');
                return;
            }

            try {
                const response = await fetch('/telemedicine/api/auth.php?action=signup', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password, email, full_name, role })
                });

                const data = await response.json();

                if (response.ok && data.ok) {
                    showAlert('signupAlert', 'Account created successfully! Please login.', 'success');
                    setTimeout(() => {
                        closeSignupModal();
                        openLoginModal();
                    }, 2000);
                } else {
                    showAlert('signupAlert', data.error || 'Signup failed', 'error');
                }
            } catch (error) {
                showAlert('signupAlert', 'Server error: ' + error.message, 'error');
            }
        });

        // Contact form submission
        document.getElementById('contactForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(document.getElementById('contactForm'));
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/telemedicine/api/contact.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.ok) {
                    alert('Message sent successfully! Admin will reply soon.');
                    document.getElementById('contactForm').reset();
                } else {
                    alert('Error sending message: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                alert('Server error: ' + error.message);
            }
        });

        // Close modals when clicking outside
        document.getElementById('loginModal').addEventListener('click', (e) => {
            if (e.target.id === 'loginModal') closeLoginModal();
        });

        document.getElementById('signupModal').addEventListener('click', (e) => {
            if (e.target.id === 'signupModal') closeSignupModal();
        });
    </script>
</body>

</html>
