<?php
// api/manage_reminders.php - API Handler for Application Deadline Reminders
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user = currentUser();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'save_deadline') {
        $upid = intval($_POST['university_program_id'] ?? 0);
        $title = trim($_POST['deadline_title'] ?? 'Final Application & Document Submission');
        $deadline = trim($_POST['deadline_date'] ?? '');

        if (!$upid || !$deadline) {
            echo json_encode(['status' => 'error', 'message' => 'Program and deadline date are required.']);
            exit;
        }

        // Format deadline date to SQL DATETIME
        $formattedDeadline = date('Y-m-d H:i:s', strtotime($deadline));

        // Insert or Update reminder
        $stmt = $pdo->prepare("
            INSERT INTO application_reminders 
            (user_id, university_program_id, deadline_title, deadline_date)
            VALUES (:uid, :upid, :title, :deadline)
            ON DUPLICATE KEY UPDATE 
                deadline_title = VALUES(deadline_title),
                deadline_date = VALUES(deadline_date),
                reminded_30d = 0, reminded_20d = 0, reminded_15d = 0, reminded_10d = 0, 
                reminded_7d = 0, reminded_3d = 0, reminded_1d = 0, reminded_5h = 0, 
                reminded_3h = 0, reminded_1h = 0
        ");

        $stmt->execute([
            'uid' => $user['id'],
            'upid' => $upid,
            'title' => $title,
            'deadline' => $formattedDeadline
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Deadline reminder saved successfully!']);
        exit;
    }

    if ($action === 'toggle_channels') {
        $reminderId = intval($_POST['reminder_id'] ?? 0);
        $emailEnabled = isset($_POST['email_enabled']) ? intval($_POST['email_enabled']) : 1;
        $pushEnabled  = isset($_POST['push_enabled']) ? intval($_POST['push_enabled']) : 1;
        $smsEnabled   = isset($_POST['sms_enabled']) ? intval($_POST['sms_enabled']) : 1;

        $stmt = $pdo->prepare("
            UPDATE application_reminders 
            SET email_enabled = :e, push_enabled = :p, sms_enabled = :s
            WHERE reminder_id = :id AND user_id = :uid
        ");
        $stmt->execute([
            'e' => $emailEnabled,
            'p' => $pushEnabled,
            's' => $smsEnabled,
            'id' => $reminderId,
            'uid' => $user['id']
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Notification preferences updated!']);
        exit;
    }

    if ($action === 'delete_reminder') {
        $reminderId = intval($_POST['reminder_id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM application_reminders WHERE reminder_id = :id AND user_id = :uid");
        $stmt->execute(['id' => $reminderId, 'uid' => $user['id']]);

        echo json_encode(['status' => 'success', 'message' => 'Reminder deleted successfully.']);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
