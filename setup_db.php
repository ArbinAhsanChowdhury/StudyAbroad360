<?php
// setup_db.php - Build Database `studyabroad360` using the User's Exact Schema
require_once __DIR__ . '/includes/auth.php';

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dbname = 'studyabroad360';

echo "=== Initializing Database: `$dbname` ===\n";

try {
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // 1. Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database `$dbname` ready.\n";

    $pdo->exec("USE `$dbname`");

    // 2. Table: users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `users` ready.\n";

    // 3. Table: locations
    $pdo->exec("
        CREATE TABLE locations (
            location_id INT AUTO_INCREMENT PRIMARY KEY,
            country VARCHAR(100) NOT NULL,
            city VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `locations` created.\n";

    // 4. Table: university
    $pdo->exec("
        CREATE TABLE university (
            university_id INT AUTO_INCREMENT PRIMARY KEY,
            location_id INT NOT NULL,
            university_name VARCHAR(255) NOT NULL,
            description TEXT,
            website_url VARCHAR(255),
            CONSTRAINT fk_university_location
                FOREIGN KEY (location_id) 
                REFERENCES locations(location_id)
                ON DELETE RESTRICT
                ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `university` created.\n";

    // 5. Table: program
    $pdo->exec("
        CREATE TABLE program (
            program_id INT AUTO_INCREMENT PRIMARY KEY,
            program_name VARCHAR(255) NOT NULL,
            degree_level VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `program` created.\n";

    // 6. Table: university_program
    $pdo->exec("
        CREATE TABLE university_program (
            university_program_id INT AUTO_INCREMENT PRIMARY KEY,
            university_id INT NOT NULL,
            program_id INT NOT NULL,
            tuition_fee DECIMAL(10, 2),
            duration VARCHAR(100) DEFAULT '2 Years',
            intake VARCHAR(100) DEFAULT 'Fall 2027',
            scholarship VARCHAR(255) DEFAULT 'Available',
            field VARCHAR(100) DEFAULT 'STEM',
            document_requirement TEXT,
            CONSTRAINT fk_univ_prog_university
                FOREIGN KEY (university_id) 
                REFERENCES university(university_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT fk_univ_prog_program
                FOREIGN KEY (program_id) 
                REFERENCES program(program_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT uq_university_program 
                UNIQUE (university_id, program_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `university_program` created.\n";

    // 7. Table: requirements
    $pdo->exec("
        CREATE TABLE requirements (
            requirement_id INT AUTO_INCREMENT PRIMARY KEY,
            document_name VARCHAR(150) NOT NULL,
            description TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `requirements` created.\n";

    // 8. Table: applications
    $pdo->exec("
        CREATE TABLE applications (
            application_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            university_program_id INT NOT NULL,
            status VARCHAR(50) DEFAULT 'Pending' NOT NULL,
            academic_level VARCHAR(100) DEFAULT 'Bachelor',
            gpa_score VARCHAR(50) DEFAULT '3.5',
            english_test VARCHAR(50) DEFAULT 'IELTS',
            english_score VARCHAR(50) DEFAULT '7.0',
            transcript_file VARCHAR(255) NULL,
            passport_file VARCHAR(255) NULL,
            sop_file VARCHAR(255) NULL,
            english_cert_file VARCHAR(255) NULL,
            cv_file VARCHAR(255) NULL,
            additional_notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            CONSTRAINT fk_applications_user
                FOREIGN KEY (user_id) 
                REFERENCES users(user_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT fk_applications_univ_prog
                FOREIGN KEY (university_program_id) 
                REFERENCES university_program(university_program_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `applications` created.\n";

    // 9. Table: program_requirements
    $pdo->exec("
        CREATE TABLE program_requirements (
            program_requirement_id INT AUTO_INCREMENT PRIMARY KEY,
            university_program_id INT NOT NULL,
            requirement_id INT NOT NULL,
            CONSTRAINT fk_prog_req_univ_prog
                FOREIGN KEY (university_program_id) 
                REFERENCES university_program(university_program_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT fk_prog_req_requirement
                FOREIGN KEY (requirement_id) 
                REFERENCES requirements(requirement_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT uq_univ_prog_requirement 
                UNIQUE (university_program_id, requirement_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `program_requirements` created.\n";

    // 10. Table: bookmarks
    $pdo->exec("
        CREATE TABLE bookmarks (
            bookmark_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            university_program_id INT NOT NULL,
            saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            CONSTRAINT fk_bookmarks_user
                FOREIGN KEY (user_id) 
                REFERENCES users(user_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT fk_bookmarks_univ_prog
                FOREIGN KEY (university_program_id) 
                REFERENCES university_program(university_program_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT uq_user_bookmark 
                UNIQUE (user_id, university_program_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `bookmarks` created.\n";

    // 11. Table: application_reminders
    $pdo->exec("
        CREATE TABLE application_reminders (
            reminder_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            university_program_id INT NOT NULL,
            deadline_title VARCHAR(255) DEFAULT 'Final Application & Document Submission',
            deadline_date DATETIME NOT NULL,
            email_enabled TINYINT(1) DEFAULT 1,
            push_enabled TINYINT(1) DEFAULT 1,
            sms_enabled TINYINT(1) DEFAULT 1,
            reminded_30d TINYINT(1) DEFAULT 0,
            reminded_20d TINYINT(1) DEFAULT 0,
            reminded_15d TINYINT(1) DEFAULT 0,
            reminded_10d TINYINT(1) DEFAULT 0,
            reminded_7d TINYINT(1) DEFAULT 0,
            reminded_3d TINYINT(1) DEFAULT 0,
            reminded_1d TINYINT(1) DEFAULT 0,
            reminded_5h TINYINT(1) DEFAULT 0,
            reminded_3h TINYINT(1) DEFAULT 0,
            reminded_1h TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            CONSTRAINT fk_reminders_user
                FOREIGN KEY (user_id) 
                REFERENCES users(user_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT fk_reminders_univ_prog
                FOREIGN KEY (university_program_id) 
                REFERENCES university_program(university_program_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
            CONSTRAINT uq_user_program_reminder
                UNIQUE (user_id, university_program_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✓ Table `application_reminders` created.\n";

    // ==========================================
    // SEED INITIAL DATA FOR TESTING & DEMONSTRATION
    // ==========================================

    // Seed 3 Team Admin Accounts + 1 Student Account
    $adminPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
    $userStmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
    
    $userStmt->execute(['name' => 'Team Admin 1', 'email' => 'admin1@studyabroad360.com', 'password' => $adminPasswordHash, 'role' => 'admin']);
    $userStmt->execute(['name' => 'Team Admin 2', 'email' => 'admin2@studyabroad360.com', 'password' => $adminPasswordHash, 'role' => 'admin']);
    $userStmt->execute(['name' => 'Team Admin 3', 'email' => 'admin3@studyabroad360.com', 'password' => $adminPasswordHash, 'role' => 'admin']);
    
    $studentPasswordHash = password_hash('student123', PASSWORD_DEFAULT);
    $userStmt->execute(['name' => 'Ananya Roy', 'email' => 'ananya@example.com', 'password' => $studentPasswordHash, 'role' => 'student']);
    $studentUserId = $pdo->lastInsertId();

    echo "✓ Users seeded (3 Admins + 1 Demo Student).\n";

    // Seed Locations
    $locStmt = $pdo->prepare("INSERT INTO locations (country, city) VALUES (:country, :city)");
    $locStmt->execute(['country' => 'Canada', 'city' => 'Toronto']);
    $loc1 = $pdo->lastInsertId();
    $locStmt->execute(['country' => 'United Kingdom', 'city' => 'Manchester']);
    $loc2 = $pdo->lastInsertId();
    $locStmt->execute(['country' => 'United States', 'city' => 'Boston']);
    $loc3 = $pdo->lastInsertId();
    $locStmt->execute(['country' => 'Australia', 'city' => 'Melbourne']);
    $loc4 = $pdo->lastInsertId();
    $locStmt->execute(['country' => 'Bangladesh', 'city' => 'Dhaka']);
    $loc5 = $pdo->lastInsertId();

    echo "✓ Locations seeded.\n";

    // Seed Universities
    $uniStmt = $pdo->prepare("INSERT INTO university (location_id, university_name, description, website_url) VALUES (:location_id, :university_name, :description, :website_url)");
    $uniStmt->execute(['location_id' => $loc1, 'university_name' => 'University of Toronto', 'description' => 'Top ranked Canadian public research university.', 'website_url' => 'https://utoronto.ca']);
    $uni1 = $pdo->lastInsertId();
    $uniStmt->execute(['location_id' => $loc2, 'university_name' => 'University of Manchester', 'description' => 'Historic UK red brick university.', 'website_url' => 'https://manchester.ac.uk']);
    $uni2 = $pdo->lastInsertId();
    $uniStmt->execute(['location_id' => $loc3, 'university_name' => 'Northeastern University', 'description' => 'Leading US private research university.', 'website_url' => 'https://northeastern.edu']);
    $uni3 = $pdo->lastInsertId();
    $uniStmt->execute(['location_id' => $loc4, 'university_name' => 'University of Melbourne', 'description' => 'Australia Go8 premier university.', 'website_url' => 'https://unimelb.edu.au']);
    $uni4 = $pdo->lastInsertId();
    $uniStmt->execute(['location_id' => $loc5, 'university_name' => 'BRAC University', 'description' => 'Premier private university in Dhaka, Bangladesh offering world-class undergraduate and postgraduate education.', 'website_url' => 'https://bracu.ac.bd']);
    $uni5 = $pdo->lastInsertId();

    echo "✓ Universities seeded.\n";

    // Seed Programs (B.Sc., M.Sc., MBA, Ph.D.)
    $progStmt = $pdo->prepare("INSERT INTO program (program_name, degree_level) VALUES (:program_name, :degree_level)");
    $progStmt->execute(['program_name' => 'M.Sc. in Data Analytics & Artificial Intelligence', 'degree_level' => 'Master']);
    $prog1 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'B.Sc. in Computer Science & Cyber Security', 'degree_level' => 'Bachelor']);
    $prog2 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'MBA in International Business Strategy', 'degree_level' => 'Master']);
    $prog3 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'Master of Information Technology', 'degree_level' => 'Master']);
    $prog4 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'B.Sc. in Computer Science & Engineering', 'degree_level' => 'Bachelor']);
    $prog5 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'M.Sc. in Computer Science & Engineering', 'degree_level' => 'Master']);
    $prog6 = $pdo->lastInsertId();
    $progStmt->execute(['program_name' => 'Ph.D. in Computer Science & Intelligent Systems', 'degree_level' => 'PhD']);
    $prog7 = $pdo->lastInsertId();

    echo "✓ Programs seeded.\n";

    // Seed University Programs (linking universities with multiple offered programs)
    $upStmt = $pdo->prepare("INSERT INTO university_program (university_id, program_id, tuition_fee, duration, intake, scholarship, field, document_requirement) VALUES (:university_id, :program_id, :tuition_fee, :duration, :intake, :scholarship, :field, :document_requirement)");
    $upStmt->execute(['university_id' => $uni1, 'program_id' => $prog1, 'tuition_fee' => 32500.00, 'duration' => '2 Years', 'intake' => 'Fall 2027', 'scholarship' => 'Up to $10,000', 'field' => 'STEM', 'document_requirement' => 'B.Sc. transcript, IELTS 7.0, SOP, 2 LORs']);
    $up1 = $pdo->lastInsertId();
    $upStmt->execute(['university_id' => $uni2, 'program_id' => $prog2, 'tuition_fee' => 26000.00, 'duration' => '3 Years', 'intake' => 'Sept 2027', 'scholarship' => 'Up to £5,000', 'field' => 'STEM', 'document_requirement' => 'High school diploma, IELTS 6.5, Statement of Purpose']);
    $up2 = $pdo->lastInsertId();
    $upStmt->execute(['university_id' => $uni3, 'program_id' => $prog3, 'tuition_fee' => 41000.00, 'duration' => '1.5 Years', 'intake' => 'Spring 2027', 'scholarship' => 'Up to $15,000', 'field' => 'Business', 'document_requirement' => 'Bachelor transcript, GMAT/GRE optional, Resume']);
    $upStmt->execute(['university_id' => $uni4, 'program_id' => $prog4, 'tuition_fee' => 38000.00, 'duration' => '2 Years', 'intake' => 'Feb 2027', 'scholarship' => 'Up to AUD 12,000', 'field' => 'STEM', 'document_requirement' => 'Bachelor transcript, IELTS 6.5']);
    
    // Seed BRAC University Multiple Programs (B.Sc., M.Sc., Ph.D.)
    $upStmt->execute(['university_id' => $uni5, 'program_id' => $prog5, 'tuition_fee' => 4200.00, 'duration' => '4 Years', 'intake' => 'Spring 2027', 'scholarship' => 'Up to 100% Merit Waiver', 'field' => 'STEM', 'document_requirement' => 'HSC / A-Levels Marksheet, SOP, Admission Test']);
    $upStmt->execute(['university_id' => $uni5, 'program_id' => $prog6, 'tuition_fee' => 5500.00, 'duration' => '2 Years', 'intake' => 'Fall 2027', 'scholarship' => '50% Graduate Research Assistantship', 'field' => 'STEM', 'document_requirement' => 'B.Sc. Certificate, IELTS 6.5, SOP, 2 Recommendation Letters']);
    $upStmt->execute(['university_id' => $uni5, 'program_id' => $prog7, 'tuition_fee' => 6800.00, 'duration' => '3-4 Years', 'intake' => 'Fall 2027', 'scholarship' => 'Full Doctoral Fellowship & Stipend', 'field' => 'STEM', 'document_requirement' => 'M.Sc. Thesis, Research Proposal, GRE/IELTS, Interview']);

    echo "✓ University Programs seeded.\n";

    // Seed Requirements Master List
    $reqStmt = $pdo->prepare("INSERT INTO requirements (document_name, description) VALUES (:document_name, :description)");
    
    $reqStmt->execute(['document_name' => 'Academic Transcripts', 'description' => 'Official certified transcripts from prior degree or high school.']);
    $req1 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'IELTS / TOEFL Score Report', 'description' => 'Official English proficiency test results.']);
    $req2 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'Statement of Purpose (SOP)', 'description' => 'Personal essay detailing academic and career goals.']);
    $req3 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'Letters of Recommendation (LOR)', 'description' => 'Academic or professional recommendation letters.']);
    $req4 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'Valid Passport Copy', 'description' => 'Clear color scan of valid passport.']);
    $req5 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'CV / Resume', 'description' => 'Updated professional or academic CV.']);
    $req6 = $pdo->lastInsertId();
    $reqStmt->execute(['document_name' => 'Bank Statement / Financial Proof', 'description' => 'Proof of sufficient funds for tuition and living.']);
    $req7 = $pdo->lastInsertId();

    echo "✓ Requirements seeded.\n";

    // Seed Program Requirements (linking university_program_id with requirement_id)
    $prStmt = $pdo->prepare("INSERT IGNORE INTO program_requirements (university_program_id, requirement_id) VALUES (:upid, :reqid)");
    $prStmt->execute(['upid' => $up1, 'reqid' => $req1]);
    $prStmt->execute(['upid' => $up1, 'reqid' => $req2]);
    $prStmt->execute(['upid' => $up1, 'reqid' => $req3]);
    $prStmt->execute(['upid' => $up1, 'reqid' => $req4]);

    $prStmt->execute(['upid' => $up2, 'reqid' => $req1]);
    $prStmt->execute(['upid' => $up2, 'reqid' => $req2]);
    $prStmt->execute(['upid' => $up2, 'reqid' => $req3]);

    echo "✓ Program Requirements seeded.\n";

    // Seed Applications
    $appStmt = $pdo->prepare("INSERT INTO applications (user_id, university_program_id, status) VALUES (:user_id, :university_program_id, :status)");
    $appStmt->execute(['user_id' => $studentUserId, 'university_program_id' => $up1, 'status' => 'Pending']);
    $appStmt->execute(['user_id' => $studentUserId, 'university_program_id' => $up2, 'status' => 'Approved']);

    echo "✓ Sample Applications seeded.\n";

    // Seed Bookmarks (linking user_id with bookmarked university_program_id)
    $bmStmt = $pdo->prepare("INSERT IGNORE INTO bookmarks (user_id, university_program_id) VALUES (:user_id, :university_program_id)");
    $bmStmt->execute(['user_id' => $studentUserId, 'university_program_id' => $up1]);
    $bmStmt->execute(['user_id' => $studentUserId, 'university_program_id' => $up2]);

    echo "✓ Bookmarks seeded.\n";

    // Seed Sample Reminders (1 deadline in 5 hours for quick test, 1 deadline in 15 days)
    $remStmt = $pdo->prepare("
        INSERT IGNORE INTO application_reminders 
        (user_id, university_program_id, deadline_title, deadline_date, email_enabled, push_enabled, sms_enabled) 
        VALUES (:user_id, :upid, :title, :deadline, 1, 1, 1)
    ");
    
    // Target 1: M.Sc. Data Analytics (Fall 2027 Intake Target -> Sept 15, 2027)
    $testDeadline1 = parseIntakeDeadline('Fall 2027');
    $remStmt->execute([
        'user_id' => $studentUserId,
        'upid' => $up1,
        'title' => 'M.Sc. Data Analytics Final SOP & IELTS Submission (Fall 2027)',
        'deadline' => $testDeadline1
    ]);

    // Target 2: B.Sc. CS Cyber Security (Sept 2027 Intake Target -> Sept 15, 2027)
    $testDeadline2 = parseIntakeDeadline('Sept 2027');
    $remStmt->execute([
        'user_id' => $studentUserId,
        'upid' => $up2,
        'title' => 'B.Sc. Cyber Security Application Form Deadline (Sept 2027)',
        'deadline' => $testDeadline2
    ]);

    echo "✓ Sample Reminders seeded.\n";

    echo "=== ALL 11 TABLES CREATED & SEEDED SUCCESSFULLY IN `studyabroad360` ===\n";

} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?>
