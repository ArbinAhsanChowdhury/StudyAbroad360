<?php
// api/toggle_bookmark.php - Toggle Program Bookmark for Logged-In Student
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to bookmark programs.', 'require_login' => true]);
    exit;
}

$user = currentUser();
$programId = intval($_POST['university_program_id'] ?? $_GET['university_program_id'] ?? 0);
$universityId = intval($_POST['university_id'] ?? $_GET['university_id'] ?? 0);

// If only university_id is provided, resolve to a valid university_program_id
if ($programId <= 0 && $universityId > 0) {
    // First, check if this user already bookmarked any program from this university
    $checkUniBm = $pdo->prepare("
        SELECT b.university_program_id 
        FROM bookmarks b
        JOIN university_program up ON b.university_program_id = up.university_program_id
        WHERE b.user_id = :uid AND up.university_id = :uni_id
        LIMIT 1
    ");
    $checkUniBm->execute(['uid' => $user['id'], 'uni_id' => $universityId]);
    $alreadyBookmarkedUpid = $checkUniBm->fetchColumn();

    if ($alreadyBookmarkedUpid) {
        $programId = intval($alreadyBookmarkedUpid);
    } else {
        // Otherwise, find the primary university_program_id for this university
        $upStmt = $pdo->prepare("
            SELECT `university_program_id` 
            FROM `university_program` 
            WHERE `university_id` = :uni_id 
            ORDER BY `university_program_id` ASC 
            LIMIT 1
        ");
        $upStmt->execute(['uni_id' => $universityId]);
        $foundUpid = $upStmt->fetchColumn();

        if ($foundUpid) {
            $programId = intval($foundUpid);
        } else {
            // If no program exists yet for this university, create a default program
            $defaultProg = $pdo->query("SELECT `program_id` FROM `program` LIMIT 1")->fetchColumn();
            if (!$defaultProg) {
                $insP = $pdo->prepare("INSERT INTO `program` (`program_name`, `degree_level`) VALUES ('General Higher Education Program', 'Bachelor')");
                $insP->execute();
                $defaultProg = $pdo->lastInsertId();
            }
            $insUp = $pdo->prepare("
                INSERT INTO `university_program` 
                (`university_id`, `program_id`, `tuition_fee`, `duration`, `intake`, `scholarship`, `field`, `document_requirement`)
                VALUES (:uid, :pid, 25000, '2 Years', 'Fall 2027', 'Available', 'General', 'Academic transcripts, SOP')
            ");
            $insUp->execute(['uid' => $universityId, 'pid' => $defaultProg]);
            $programId = intval($pdo->lastInsertId());
        }
    }
}

if ($programId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid program or university ID.']);
    exit;
}

try {
    // Check if bookmark already exists
    $checkStmt = $pdo->prepare("SELECT `bookmark_id` FROM `bookmarks` WHERE `user_id` = :uid AND `university_program_id` = :upid LIMIT 1");
    $checkStmt->execute(['uid' => $user['id'], 'upid' => $programId]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        // Remove bookmark and corresponding automated deadline reminder
        $delStmt = $pdo->prepare("DELETE FROM `bookmarks` WHERE `bookmark_id` = :bid");
        $delStmt->execute(['bid' => $existing['bookmark_id']]);

        $delRemStmt = $pdo->prepare("DELETE FROM `application_reminders` WHERE `user_id` = :uid AND `university_program_id` = :upid");
        $delRemStmt->execute(['uid' => $user['id'], 'upid' => $programId]);

        echo json_encode([
            'success' => true, 
            'bookmarked' => false, 
            'program_id' => $programId,
            'message' => 'Removed from bookmarks table & automated reminders cancelled.'
        ]);
    } else {
        // 1. Insert new bookmark into database table `bookmarks`
        $insStmt = $pdo->prepare("INSERT INTO `bookmarks` (`user_id`, `university_program_id`) VALUES (:uid, :upid)");
        $insStmt->execute(['uid' => $user['id'], 'upid' => $programId]);

        // 2. Fetch program & university intake details to calculate real target deadline
        $upInfoStmt = $pdo->prepare("
            SELECT up.intake, p.program_name, u.university_name 
            FROM university_program up
            JOIN program p ON up.program_id = p.program_id
            JOIN university u ON up.university_id = u.university_id
            WHERE up.university_program_id = :upid LIMIT 1
        ");
        $upInfoStmt->execute(['upid' => $programId]);
        $upInfo = $upInfoStmt->fetch();

        $uniName = $upInfo['university_name'] ?? 'University';
        $progName = $upInfo['program_name'] ?? 'Program';
        $intakeStr = $upInfo['intake'] ?? 'Fall 2027';
        $realDeadline = parseIntakeDeadline($intakeStr);

        $checkRem = $pdo->prepare("SELECT `reminder_id` FROM `application_reminders` WHERE `user_id` = :uid AND `university_program_id` = :upid LIMIT 1");
        $checkRem->execute(['uid' => $user['id'], 'upid' => $programId]);
        if (!$checkRem->fetch()) {
            $insRem = $pdo->prepare("
                INSERT INTO `application_reminders` 
                (`user_id`, `university_program_id`, `deadline_title`, `deadline_date`, `email_enabled`, `push_enabled`, `sms_enabled`) 
                VALUES (:uid, :upid, 'Final Application & Document Submission Deadline', :deadline, 1, 1, 1)
            ");
            $insRem->execute(['uid' => $user['id'], 'upid' => $programId, 'deadline' => $realDeadline]);
        } else {
            // Update existing reminder with calculated intake deadline
            $upRem = $pdo->prepare("UPDATE `application_reminders` SET `deadline_date` = :deadline WHERE `user_id` = :uid AND `university_program_id` = :upid");
            $upRem->execute(['deadline' => $realDeadline, 'uid' => $user['id'], 'upid' => $programId]);
        }

        echo json_encode([
            'success' => true, 
            'bookmarked' => true, 
            'program_id' => $programId,
            'message' => "Successfully bookmarked {$uniName} - {$progName}! Target deadline set to " . date('M j, Y', strtotime($realDeadline)) . "."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
