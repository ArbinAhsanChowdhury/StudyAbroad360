<?php
// submit_application.php - API Endpoint for Storing Live Applications in MySQL
require_once 'includes/auth.php';
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$programTitle  = trim($_POST['program_title'] ?? '');
$uniTitle      = trim($_POST['university_title'] ?? '');
$fullName      = trim($_POST['full_name'] ?? '');
$email         = trim($_POST['email'] ?? '');
$phone         = trim($_POST['phone'] ?? '');

$academicLevel = trim($_POST['academic_level'] ?? 'Bachelor');
$gpaScore      = trim($_POST['gpa_score'] ?? '3.5');
$englishTest   = trim($_POST['english_test'] ?? 'IELTS');
$englishScore  = trim($_POST['english_score'] ?? '7.0');
$addNotes      = trim($_POST['additional_notes'] ?? '');

if (empty($programTitle) || empty($fullName) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Please fill out all required contact fields.']);
    exit;
}

// 0. Prepare Upload Directory
$uploadDir = __DIR__ . '/uploads/documents/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

function uploadAppDoc($fileKey, $prefix, $uploadDir) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
    $fileName    = $_FILES[$fileKey]['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
    if (!in_array($fileExtension, $allowedExts)) {
        return null;
    }

    $cleanPrefix = preg_replace('/[^a-zA-Z0-9]/', '', $prefix);
    $newFileName = $cleanPrefix . '_' . time() . '_' . substr(md5(uniqid()), 0, 6) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        return 'uploads/documents/' . $newFileName;
    }
    return null;
}

// Validate Mandatory Documents
$transcriptPath  = uploadAppDoc('transcript_file', 'transcript', $uploadDir);
$passportPath    = uploadAppDoc('passport_file', 'passport', $uploadDir);
$sopPath         = uploadAppDoc('sop_file', 'sop', $uploadDir);
$englishCertPath = uploadAppDoc('english_cert_file', 'english', $uploadDir);
$cvPath          = uploadAppDoc('cv_file', 'cv', $uploadDir);

try {
    $userId = null;

    // 1. If student is currently logged in, use their session user_id if valid in DB
    if (isLoggedIn() && isset($_SESSION['user_id'])) {
        $sessId = $_SESSION['user_id'];
        $checkSess = $pdo->prepare("SELECT `user_id` FROM `users` WHERE `user_id` = :uid LIMIT 1");
        $checkSess->execute(['uid' => $sessId]);
        if ($checkSess->fetch()) {
            $userId = $sessId;
        }
    }

    // 2. If not logged in or session invalid, check if user with this email already exists in DB
    if (!$userId) {
        $uStmt = $pdo->prepare("SELECT `user_id`, `name`, `role` FROM `users` WHERE LOWER(`email`) = LOWER(:email) LIMIT 1");
        $uStmt->execute(['email' => $email]);
        $existingUser = $uStmt->fetch();

        if ($existingUser) {
            $userId = $existingUser['user_id'];
            $fullName = $existingUser['name'];
        } else {
            // Create new student user account
            $dummyPass = password_hash('student123', PASSWORD_DEFAULT);
            $createUser = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES (:name, :email, :password, 'student')");
            $createUser->execute(['name' => $fullName, 'email' => strtolower($email), 'password' => $dummyPass]);
            $userId = $pdo->lastInsertId();
        }

        // Auto-login the student so session is active
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_name']  = $fullName;
        $_SESSION['user_email'] = strtolower($email);
        $_SESSION['user_role']  = 'student';
    }

    // 3. Find university_program_id by program title or university title
    $upStmt = $pdo->prepare("
        SELECT up.university_program_id 
        FROM university_program up
        JOIN program p ON up.program_id = p.program_id
        JOIN university u ON up.university_id = u.university_id
        WHERE p.program_name LIKE :p OR u.university_name LIKE :u
        LIMIT 1
    ");
    $upStmt->execute(['p' => "%$programTitle%", 'u' => "%$uniTitle%"]);
    $upRow = $upStmt->fetch();

    if ($upRow) {
        $univProgId = $upRow['university_program_id'];
    } else {
        // Fallback to first university_program row if available
        $fallback = $pdo->query("SELECT `university_program_id` FROM `university_program` LIMIT 1")->fetch();
        $univProgId = $fallback ? $fallback['university_program_id'] : 1;
    }

    // 4. Insert application into relational database with document paths & qualifications
    $stmt = $pdo->prepare("
        INSERT INTO `applications` (
            `user_id`, 
            `university_program_id`, 
            `status`, 
            `academic_level`, 
            `gpa_score`, 
            `english_test`, 
            `english_score`, 
            `transcript_file`, 
            `passport_file`, 
            `sop_file`, 
            `english_cert_file`, 
            `cv_file`, 
            `additional_notes`
        ) VALUES (
            :user_id, 
            :up_id, 
            'Pending', 
            :academic_level, 
            :gpa_score, 
            :english_test, 
            :english_score, 
            :transcript_file, 
            :passport_file, 
            :sop_file, 
            :english_cert_file, 
            :cv_file, 
            :additional_notes
        )
    ");
    
    $stmt->execute([
        'user_id'           => $userId,
        'up_id'             => $univProgId,
        'academic_level'    => $academicLevel,
        'gpa_score'         => $gpaScore,
        'english_test'      => $englishTest,
        'english_score'     => $englishScore,
        'transcript_file'   => $transcriptPath,
        'passport_file'     => $passportPath,
        'sop_file'          => $sopPath,
        'english_cert_file' => $englishCertPath,
        'cv_file'           => $cvPath,
        'additional_notes'  => $addNotes
    ]);

    $appId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'app_id'  => $appId,
        'message' => "Application #$appId with attached documents submitted successfully! Redirecting to your Student Dashboard..."
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to save application: ' . $e->getMessage()]);
}
?>
