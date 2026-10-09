<?php
// api/submit_partner.php - Handle Partner Application Form Submissions
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$org_name       = trim($input['org_name'] ?? '');
$partner_type   = trim($input['partner_type'] ?? '');
$contact_name   = trim($input['contact_name'] ?? '');
$email          = trim($input['email'] ?? '');
$location       = trim($input['location'] ?? '');
$student_volume = trim($input['student_volume'] ?? '');

if (empty($org_name) || empty($partner_type) || empty($contact_name) || empty($email) || empty($location)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid work email address.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO `partner_applications` (`org_name`, `partner_type`, `contact_name`, `email`, `location`, `student_volume`, `status`)
        VALUES (:org_name, :partner_type, :contact_name, :email, :location, :student_volume, 'Pending')
    ");
    
    $stmt->execute([
        ':org_name'       => $org_name,
        ':partner_type'   => $partner_type,
        ':contact_name'   => $contact_name,
        ':email'          => $email,
        ':location'       => $location,
        ':student_volume' => $student_volume,
    ]);

    $partner_id = $pdo->lastInsertId();

    // Log welcome email simulation
    $mailStmt = $pdo->prepare("
        INSERT INTO `sent_emails` (`recipient_email`, `sender_email`, `subject`, `body_html`)
        VALUES (:recipient_email, 'partners@studyabroad360.com', :subject, :body_html)
    ");
    $mailStmt->execute([
        ':recipient_email' => $email,
        ':subject'         => "🤝 StudyAbroad360 Partnership Application Received - " . $org_name,
        ':body_html'       => "
            <h2>Partnership Application Confirmation</h2>
            <p>Dear {$contact_name},</p>
            <p>Thank you for registering <strong>{$org_name}</strong> as a <strong>{$partner_type}</strong> partner on StudyAbroad360.</p>
            <p>Our Partner Account Team will review your application and contact you within 24 hours.</p>
        "
    ]);

    echo json_encode([
        'success' => true,
        'message' => "Thank you {$contact_name}! Your partnership application for {$org_name} has been successfully registered.",
        'partner_id' => $partner_id
    ]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
