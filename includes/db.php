<?php
// includes/db.php - PDO Database Connection for studyabroad360

$host = '127.0.0.1';
$db   = 'studyabroad360';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    try {
        $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    } catch (\PDOException $e2) {
        die("Database connection failed: " . $e2->getMessage());
    }
}

// Auto-migrate schema updates if university_program table exists
if (isset($pdo)) {
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM `university_program`")->fetchAll(PDO::FETCH_COLUMN);
        if ($cols) {
            if (!in_array('duration', $cols)) {
                $pdo->exec("ALTER TABLE `university_program` ADD COLUMN `duration` VARCHAR(100) DEFAULT '2 Years'");
            }
            if (!in_array('intake', $cols)) {
                $pdo->exec("ALTER TABLE `university_program` ADD COLUMN `intake` VARCHAR(100) DEFAULT 'Fall 2027'");
            }
            if (!in_array('scholarship', $cols)) {
                $pdo->exec("ALTER TABLE `university_program` ADD COLUMN `scholarship` VARCHAR(255) DEFAULT 'Available'");
            }
            if (!in_array('field', $cols)) {
                $pdo->exec("ALTER TABLE `university_program` ADD COLUMN `field` VARCHAR(100) DEFAULT 'STEM'");
            }
        }

        // Auto-migrate schema updates for applications table
        $appCols = $pdo->query("SHOW COLUMNS FROM `applications`")->fetchAll(PDO::FETCH_COLUMN);
        if ($appCols) {
            if (!in_array('academic_level', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `academic_level` VARCHAR(100) DEFAULT 'Bachelor'");
            }
            if (!in_array('gpa_score', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `gpa_score` VARCHAR(50) DEFAULT '3.5'");
            }
            if (!in_array('english_test', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `english_test` VARCHAR(50) DEFAULT 'IELTS'");
            }
            if (!in_array('english_score', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `english_score` VARCHAR(50) DEFAULT '7.0'");
            }
            if (!in_array('transcript_file', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `transcript_file` VARCHAR(255) NULL");
            }
            if (!in_array('passport_file', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `passport_file` VARCHAR(255) NULL");
            }
            if (!in_array('sop_file', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `sop_file` VARCHAR(255) NULL");
            }
            if (!in_array('english_cert_file', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `english_cert_file` VARCHAR(255) NULL");
            }
            if (!in_array('cv_file', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `cv_file` VARCHAR(255) NULL");
            }
            if (!in_array('additional_notes', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `additional_notes` TEXT NULL");
            }
            if (!in_array('created_at', $appCols)) {
                $pdo->exec("ALTER TABLE `applications` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            }
        }

        // Auto-create sent_emails table for localhost mail inspection & delivery simulation
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `sent_emails` (
                `email_id` INT AUTO_INCREMENT PRIMARY KEY,
                `recipient_email` VARCHAR(255) NOT NULL,
                `sender_email` VARCHAR(255) NOT NULL,
                `subject` VARCHAR(255) NOT NULL,
                `body_html` LONGTEXT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Auto-create partner_applications table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `partner_applications` (
                `partner_id` INT AUTO_INCREMENT PRIMARY KEY,
                `org_name` VARCHAR(255) NOT NULL,
                `partner_type` VARCHAR(100) NOT NULL,
                `contact_name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `location` VARCHAR(255) NOT NULL,
                `student_volume` VARCHAR(100) NOT NULL,
                `status` VARCHAR(50) DEFAULT 'Pending',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    } catch (\Throwable $th) {
        // Ignore if database or table doesn't exist yet
    }
}
?>
