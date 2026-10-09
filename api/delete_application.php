<?php
// api/delete_application.php - API Endpoint to Withdraw/Remove a Submitted Application
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to manage your applications.']);
    exit;
}

$user = currentUser();
$appId = intval($_POST['application_id'] ?? $_GET['application_id'] ?? 0);

if ($appId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid Application ID.']);
    exit;
}

try {
    // Delete application matching application_id and user_id
    $stmt = $pdo->prepare("DELETE FROM `applications` WHERE `application_id` = :aid AND `user_id` = :uid");
    $stmt->execute([
        'aid' => $appId,
        'uid' => $user['id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => "Application #$appId has been successfully withdrawn and removed."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Application not found or unauthorized.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
