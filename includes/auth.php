<?php
// includes/auth.php - Session & Role-Based Access Control Helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function currentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'student'
    ];
}

function isAdmin() {
    return isLoggedIn() && (($_SESSION['user_role'] ?? '') === 'admin');
}

function isStudent() {
    return isLoggedIn() && (($_SESSION['user_role'] ?? '') === 'student');
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?msg=please_login');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: student_dashboard.php?msg=admin_only');
        exit;
    }
}

/**
 * Calculate Real University Intake Target Deadline Date
 * Spring -> January 15 | Summer -> May 15 | Fall -> September 15 | Winter -> December 15
 *
 * @param string $intakeStr (e.g. "Fall 2027", "Sept 2027", "Spring 2027", "May 2027")
 * @return string Formatted Datetime String (Y-m-d H:i:s)
 */
function parseIntakeDeadline($intakeStr = 'Fall 2027') {
    $intake = strtolower(trim($intakeStr));
    
    // 1. Extract 4-digit year (e.g. 2027)
    $year = date('Y') + 1; // Default next year
    if (preg_match('/20\d{2}/', $intake, $matches)) {
        $year = intval($matches[0]);
    }

    // 2. Parse Season / Month according to rules:
    // Spring -> January (01)
    // Summer -> May (05)
    // Fall -> September (09)
    // Winter -> December (12)
    $month = '09'; // Default Fall (September)
    $day = '15';

    if (strpos($intake, 'spring') !== false || strpos($intake, 'jan') !== false) {
        $month = '01'; // January (Spring)
    } elseif (strpos($intake, 'feb') !== false) {
        $month = '02'; // February
    } elseif (strpos($intake, 'summer') !== false || strpos($intake, 'may') !== false) {
        $month = '05'; // May (Summer)
    } elseif (strpos($intake, 'june') !== false) {
        $month = '06'; // June
    } elseif (strpos($intake, 'july') !== false || strpos($intake, 'jul') !== false) {
        $month = '07'; // July
    } elseif (strpos($intake, 'fall') !== false || strpos($intake, 'sept') !== false || strpos($intake, 'sep') !== false || strpos($intake, 'oct') !== false) {
        $month = '09'; // September (Fall)
    } elseif (strpos($intake, 'winter') !== false || strpos($intake, 'dec') !== false) {
        $month = '12'; // December (Winter)
    }

    return "$year-$month-$day 23:59:59";
}
?>
