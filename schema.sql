-- MySQL Database Schema for studyabroad360 Database

CREATE DATABASE IF NOT EXISTS `studyabroad360` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studyabroad360`;

-- 1. Table: users
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: locations
CREATE TABLE IF NOT EXISTS `locations` (
    `location_id` INT AUTO_INCREMENT PRIMARY KEY,
    `country` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table: university
CREATE TABLE IF NOT EXISTS `university` (
    `university_id` INT AUTO_INCREMENT PRIMARY KEY,
    `location_id` INT NOT NULL,
    `university_name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `website_url` VARCHAR(255),
    CONSTRAINT `fk_university_location`
        FOREIGN KEY (`location_id`) 
        REFERENCES `locations`(`location_id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table: program
CREATE TABLE IF NOT EXISTS `program` (
    `program_id` INT AUTO_INCREMENT PRIMARY KEY,
    `program_name` VARCHAR(255) NOT NULL,
    `degree_level` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Table: university_program
CREATE TABLE IF NOT EXISTS `university_program` (
    `university_program_id` INT AUTO_INCREMENT PRIMARY KEY,
    `university_id` INT NOT NULL,
    `program_id` INT NOT NULL,
    `tuition_fee` DECIMAL(10, 2),
    `duration` VARCHAR(100) DEFAULT '2 Years',
    `intake` VARCHAR(100) DEFAULT 'Fall 2027',
    `scholarship` VARCHAR(255) DEFAULT 'Available',
    `field` VARCHAR(100) DEFAULT 'STEM',
    `document_requirement` TEXT,
    CONSTRAINT `fk_univ_prog_university`
        FOREIGN KEY (`university_id`) 
        REFERENCES `university`(`university_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_univ_prog_program`
        FOREIGN KEY (`program_id`) 
        REFERENCES `program`(`program_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `uq_university_program` 
        UNIQUE (`university_id`, `program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Table: requirements
CREATE TABLE IF NOT EXISTS `requirements` (
    `requirement_id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_name` VARCHAR(150) NOT NULL,
    `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Table: applications
CREATE TABLE IF NOT EXISTS `applications` (
    `application_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `university_program_id` INT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'Pending' NOT NULL,
    `academic_level` VARCHAR(100) DEFAULT 'Bachelor',
    `gpa_score` VARCHAR(50) DEFAULT '3.5',
    `english_test` VARCHAR(50) DEFAULT 'IELTS',
    `english_score` VARCHAR(50) DEFAULT '7.0',
    `transcript_file` VARCHAR(255) NULL,
    `passport_file` VARCHAR(255) NULL,
    `sop_file` VARCHAR(255) NULL,
    `english_cert_file` VARCHAR(255) NULL,
    `cv_file` VARCHAR(255) NULL,
    `additional_notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT `fk_applications_user`
        FOREIGN KEY (`user_id`) 
        REFERENCES `users`(`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_applications_univ_prog`
        FOREIGN KEY (`university_program_id`) 
        REFERENCES `university_program`(`university_program_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Table: program_requirements
CREATE TABLE IF NOT EXISTS `program_requirements` (
    `program_requirement_id` INT AUTO_INCREMENT PRIMARY KEY,
    `university_program_id` INT NOT NULL,
    `requirement_id` INT NOT NULL,
    CONSTRAINT `fk_prog_req_univ_prog`
        FOREIGN KEY (`university_program_id`) 
        REFERENCES `university_program`(`university_program_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_prog_req_requirement`
        FOREIGN KEY (`requirement_id`) 
        REFERENCES `requirements`(`requirement_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `uq_univ_prog_requirement` 
        UNIQUE (`university_program_id`, `requirement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Table: bookmarks
CREATE TABLE IF NOT EXISTS `bookmarks` (
    `bookmark_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `university_program_id` INT NOT NULL,
    `saved_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT `fk_bookmarks_user`
        FOREIGN KEY (`user_id`) 
        REFERENCES `users`(`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_bookmarks_univ_prog`
        FOREIGN KEY (`university_program_id`) 
        REFERENCES `university_program`(`university_program_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `uq_user_bookmark` 
        UNIQUE (`user_id`, `university_program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Table: application_reminders
CREATE TABLE IF NOT EXISTS `application_reminders` (
    `reminder_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `university_program_id` INT NOT NULL,
    `deadline_title` VARCHAR(255) DEFAULT 'Final Application & Document Submission',
    `deadline_date` DATETIME NOT NULL,
    `email_enabled` TINYINT(1) DEFAULT 1,
    `push_enabled` TINYINT(1) DEFAULT 1,
    `sms_enabled` TINYINT(1) DEFAULT 1,
    `reminded_30d` TINYINT(1) DEFAULT 0,
    `reminded_20d` TINYINT(1) DEFAULT 0,
    `reminded_15d` TINYINT(1) DEFAULT 0,
    `reminded_10d` TINYINT(1) DEFAULT 0,
    `reminded_7d` TINYINT(1) DEFAULT 0,
    `reminded_3d` TINYINT(1) DEFAULT 0,
    `reminded_1d` TINYINT(1) DEFAULT 0,
    `reminded_5h` TINYINT(1) DEFAULT 0,
    `reminded_3h` TINYINT(1) DEFAULT 0,
    `reminded_1h` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT `fk_reminders_user`
        FOREIGN KEY (`user_id`) 
        REFERENCES `users`(`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_reminders_univ_prog`
        FOREIGN KEY (`university_program_id`) 
        REFERENCES `university_program`(`university_program_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `uq_user_program_reminder`
        UNIQUE (`user_id`, `university_program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Table: sent_emails (Local mail log & audit)
CREATE TABLE IF NOT EXISTS `sent_emails` (
    `email_id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipient_email` VARCHAR(255) NOT NULL,
    `sender_email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body_html` LONGTEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Table: partner_applications (B2B Recruitment & University Applications)
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

