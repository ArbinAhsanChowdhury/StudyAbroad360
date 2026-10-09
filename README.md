# StudyAbroad 360 — Study Abroad Portal

A comprehensive web platform for students, partners, and administrators to explore international study programs, calculate costs, submit applications, upload documents, and track admissions.

---

## 🚀 Features

- **🎓 Program & Institution Search:** Browse universities and filter programs by country, degree level, intake, and scholarship eligibility.
- **📊 Cost & Budget Calculator:** Interactive estimator for tuition fees, accommodation, living expenses, and visa costs across different study destinations.
- **📝 Student Portal & Application Tracking:**
  - Online student registration and secure login.
  - Multi-step application submission with document uploads (CV, English proficiency, Transcripts, SOP, Passport).
  - Real-time application status tracker (Draft, Submitted, Under Review, Accepted, Visa Processing).
- **🛡️ Admin & Counselor Dashboard:**
  - Manage applications, review student documents, and update application stages.
  - Automated reminder notifications and status logs.
- **🤝 Partner & Agent Portal:** Partnership inquiry submission and collaboration tools.
- **⚡ Progressive Web App (PWA):** Offline-ready service worker caching for mobile responsiveness and performance.

---

## 🛠️ Tech Stack

- **Backend:** PHP 7.4+ / PHP 8.x (PDO MySQL)
- **Database:** MySQL
- **Frontend:** Vanilla HTML5, CSS3, JavaScript (ES6+)
- **Architecture:** Modular components (`includes/`, `api/`) with JSON REST-style API endpoints

---

## 💻 Getting Started Locally

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/), [WampServer](https://www.wampserver.com/), or PHP + MySQL local environment.

### 2. Setup Database
1. Start Apache and MySQL in your XAMPP/WAMP control panel.
2. Open phpMyAdmin (`http://localhost/phpmyadmin`).
3. Create a database named `studyabroad360` (or let the setup script create it).
4. Import `schema.sql`, or navigate in your browser to:
   ```
   http://localhost/UIUDHHACKATHON/setup_db.php
   ```
   This will initialize the tables and seed default programs and demo accounts.

### 3. Local Configuration (Optional)
If your MySQL credentials differ from the default (`root` with no password), you can create `includes/config.local.php`:
```php
<?php
$host = '127.0.0.1';
$db   = 'studyabroad360';
$user = 'your_username';
$pass = 'your_password';
```

### 4. Run the Project
Place the project folder inside your web root (e.g., `htdocs/UIUDHHACKATHON`) and access:
```
http://localhost/UIUDHHACKATHON/
```

---

## 📂 Project Structure

```text
├── api/                   # Backend API handlers (JSON endpoints)
├── assets/                # Images, icons, and illustrations
├── includes/              # Shared PHP components (db, header, footer, modals, auth)
├── logs/                  # Application logs
├── uploads/               # User-uploaded documents (ignored in git)
├── admin_dashboard.php    # Counselor and admin management view
├── student_dashboard.php  # Student portal and application tracker
├── index.php              # Landing page
├── calculator.php         # Study abroad cost estimator
├── destinations.php       # Study destination guides
├── institutions.php       # University listings
├── programs.php           # Degree and academic program search
├── schema.sql             # SQL database schema
├── setup_db.php           # Database migration & seed script
├── styles.css             # UI design system and responsive styles
└── script.js              # Client-side dynamic interactivity
```
