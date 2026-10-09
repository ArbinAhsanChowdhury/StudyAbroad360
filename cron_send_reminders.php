<?php
// cron_send_reminders.php - Background Deadline Monitoring Engine
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');

$isCli = (php_sapi_name() === 'cli');
$results = [
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'reminders_processed' => 0,
    'triggers_fired' => []
];

try {
    // 1. Fetch active reminders with user and program details
    $stmt = $pdo->query("
        SELECT 
            r.*,
            u.name AS student_name,
            u.email AS student_email,
            p.program_name,
            uni.university_name
        FROM application_reminders r
        JOIN users u ON r.user_id = u.user_id
        JOIN university_program up ON r.university_program_id = up.university_program_id
        JOIN program p ON up.program_id = p.program_id
        JOIN university uni ON up.university_id = uni.university_id
        WHERE r.deadline_date > NOW()
    ");

    $reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results['reminders_processed'] = count($reminders);

    $nowTs = time();

    // Milestones definitions in seconds
    $milestones = [
        '30d' => ['seconds' => 30 * 86400, 'min_seconds' => 20 * 86400, 'label' => '30 Days Left',  'flag' => 'reminded_30d'],
        '20d' => ['seconds' => 20 * 86400, 'min_seconds' => 15 * 86400, 'label' => '20 Days Left',  'flag' => 'reminded_20d'],
        '15d' => ['seconds' => 15 * 86400, 'min_seconds' => 10 * 86400, 'label' => '15 Days Left',  'flag' => 'reminded_15d'],
        '10d' => ['seconds' => 10 * 86400, 'min_seconds' => 7  * 86400, 'label' => '10 Days Left',  'flag' => 'reminded_10d'],
        '7d'  => ['seconds' => 7  * 86400, 'min_seconds' => 3  * 86400, 'label' => '7 Days Left',   'flag' => 'reminded_7d'],
        '3d'  => ['seconds' => 3  * 86400, 'min_seconds' => 1  * 86400, 'label' => '3 Days Left',   'flag' => 'reminded_3d'],
        '1d'  => ['seconds' => 1  * 86400, 'min_seconds' => 5  * 3600,  'label' => '1 Day Left',    'flag' => 'reminded_1d'],
        '5h'  => ['seconds' => 5  * 3600,  'min_seconds' => 3  * 3600,  'label' => '5 Hours Left',  'flag' => 'reminded_5h'],
        '3h'  => ['seconds' => 3  * 3600,  'min_seconds' => 1  * 3600,  'label' => '3 Hours Left',  'flag' => 'reminded_3h'],
        '1h'  => ['seconds' => 1  * 3600,  'min_seconds' => 120,        'label' => '1 Hour Left',   'flag' => 'reminded_1h'],
        '2m'  => ['seconds' => 120,        'min_seconds' => 0,          'label' => '2 Minutes Left (Live Demo)', 'flag' => 'reminded_1h'],
    ];

    foreach ($reminders as $rem) {
        $deadlineTs = strtotime($rem['deadline_date']);
        $secondsLeft = $deadlineTs - $nowTs;

        if ($secondsLeft <= 0) continue;

        foreach ($milestones as $key => $m) {
            $flagCol = $m['flag'];

            // Check if current remaining time falls within this milestone bucket and hasn't been triggered yet
            if ($secondsLeft <= $m['seconds'] && $secondsLeft > $m['min_seconds'] && intval($rem[$flagCol]) === 0) {
                
                $channelsSent = [];

                // 1. Email Channel
                if ($rem['email_enabled']) {
                    sendDeadlineEmail(
                        $rem['student_email'],
                        $rem['student_name'],
                        $rem['program_name'],
                        $rem['university_name'],
                        $m['label'],
                        date('F j, Y, g:i A', $deadlineTs)
                    );
                    $channelsSent[] = 'Email';
                }

                // 2. Web Push Channel
                if ($rem['push_enabled']) {
                    $logDir = __DIR__ . '/logs';
                    if (!file_exists($logDir)) @mkdir($logDir, 0777, true);
                    $pushLog = "[" . date('Y-m-d H:i:s') . "] PUSH DISPATCH to [" . $rem['student_email'] . "] | " . $m['label'] . " | " . $rem['program_name'] . "\n";
                    @file_put_contents($logDir . '/sent_reminders.log', $pushLog, FILE_APPEND);
                    $channelsSent[] = 'WebPush';
                }

                // 3. SMS Channel
                if ($rem['sms_enabled']) {
                    $logDir = __DIR__ . '/logs';
                    if (!file_exists($logDir)) @mkdir($logDir, 0777, true);
                    $smsLog = "[" . date('Y-m-d H:i:s') . "] SMS DISPATCH to [" . $rem['student_email'] . "] | " . $m['label'] . " | " . $rem['program_name'] . "\n";
                    @file_put_contents($logDir . '/sent_reminders.log', $smsLog, FILE_APPEND);
                    $channelsSent[] = 'SMS';
                }

                // Update database flag so we never send duplicate reminders for this milestone
                $updateStmt = $pdo->prepare("UPDATE application_reminders SET {$flagCol} = 1 WHERE reminder_id = :id");
                $updateStmt->execute(['id' => $rem['reminder_id']]);

                $results['triggers_fired'][] = [
                    'reminder_id' => $rem['reminder_id'],
                    'student' => $rem['student_name'],
                    'program' => $rem['program_name'],
                    'milestone' => $m['label'],
                    'channels' => $channelsSent
                ];

                break; // Process one milestone trigger per execution
            }
        }
    }

    echo json_encode($results, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
