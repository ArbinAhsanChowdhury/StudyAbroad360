<?php
// api/manage_university.php - Admin Endpoint for Adding and Deleting Universities
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

// Enforce Admin Authorization
if (!isAdmin()) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized access. Admin privileges required.']);
        exit;
    } else {
        header('Location: ../login.php?msg=admin_required');
        exit;
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add_university') {
    $name         = trim($_POST['university_name'] ?? '');
    $country      = trim($_POST['country'] ?? '');
    $city         = trim($_POST['city'] ?? '');
    $desc         = trim($_POST['description'] ?? '');
    $website      = trim($_POST['website_url'] ?? '');
    
    // Program & Cost Details
    $progName     = trim($_POST['program_name'] ?? '');
    $degreeLevel  = trim($_POST['degree_level'] ?? 'Master');
    $field        = trim($_POST['field'] ?? 'STEM');
    $duration     = trim($_POST['duration'] ?? '2 Years');
    $intake       = trim($_POST['intake'] ?? 'Fall 2027');
    $scholarship  = trim($_POST['scholarship'] ?? 'Available');
    $tuitionFee   = floatval($_POST['tuition_fee'] ?? 25000);

    // Document Requirements & Requirement File Upload Handling
    $docTypesArr   = $_POST['doc_types'] ?? [];
    $docNotes      = trim($_POST['document_requirement'] ?? '');
    
    $docTypeList   = !empty($docTypesArr) && is_array($docTypesArr) ? implode(', ', array_map('trim', $docTypesArr)) : '';
    $finalDocReq   = '';
    if (!empty($docTypeList)) {
        $finalDocReq .= "Required Documents: " . $docTypeList;
    }
    if (!empty($docNotes)) {
        $finalDocReq .= (!empty($finalDocReq) ? " | Details: " : "") . $docNotes;
    }

    // Process sample / specification document file upload
    if (isset($_FILES['doc_requirement_file']) && $_FILES['doc_requirement_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $originalName = basename($_FILES['doc_requirement_file']['name']);
        $safeName     = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
        $targetPath   = $uploadDir . $safeName;
        
        if (move_uploaded_file($_FILES['doc_requirement_file']['tmp_name'], $targetPath)) {
            $relativeUrl = 'uploads/documents/' . $safeName;
            $finalDocReq .= (!empty($finalDocReq) ? " | Specification File: " : "Specification File: ") . $relativeUrl;
        }
    }

    if (empty($finalDocReq)) {
        $finalDocReq = 'Certified academic transcripts, Statement of Purpose, English language test score';
    }

    if (empty($name) || empty($country) || empty($city)) {
        header('Location: ../institutions.php?msg=error_missing_fields');
        exit;
    }

    try {
        // 1. Find or create location_id
        $locStmt = $pdo->prepare("SELECT `location_id` FROM `locations` WHERE `country` = :country AND `city` = :city LIMIT 1");
        $locStmt->execute(['country' => $country, 'city' => $city]);
        $loc = $locStmt->fetch();

        if ($loc) {
            $locationId = $loc['location_id'];
        } else {
            $insertLoc = $pdo->prepare("INSERT INTO `locations` (`country`, `city`) VALUES (:country, :city)");
            $insertLoc->execute(['country' => $country, 'city' => $city]);
            $locationId = $pdo->lastInsertId();
        }

        // 2. Find existing university OR create new university
        $existingUni = $pdo->prepare("SELECT `university_id` FROM `university` WHERE `university_name` = :name LIMIT 1");
        $existingUni->execute(['name' => $name]);
        $uniRow = $existingUni->fetch();

        if ($uniRow) {
            $universityId = $uniRow['university_id'];
        } else {
            $uniStmt = $pdo->prepare("INSERT INTO `university` (`location_id`, `university_name`, `description`, `website_url`) VALUES (:location_id, :university_name, :description, :website_url)");
            $uniStmt->execute([
                'location_id' => $locationId,
                'university_name' => $name,
                'description' => $desc ?: "$name located in $city, $country.",
                'website_url' => $website ?: '#'
            ]);
            $universityId = $pdo->lastInsertId();
        }

        // 3. Find or create program
        if (empty($progName)) {
            $progName = "General Higher Education Program";
        }

        $progStmt = $pdo->prepare("SELECT `program_id` FROM `program` WHERE `program_name` = :name AND `degree_level` = :degree LIMIT 1");
        $progStmt->execute(['name' => $progName, 'degree' => $degreeLevel]);
        $prog = $progStmt->fetch();

        if ($prog) {
            $programId = $prog['program_id'];
        } else {
            $insertProg = $pdo->prepare("INSERT INTO `program` (`program_name`, `degree_level`) VALUES (:name, :degree)");
            $insertProg->execute(['name' => $progName, 'degree' => $degreeLevel]);
            $programId = $pdo->lastInsertId();
        }

        // 4. Insert into university_program table
        $upStmt = $pdo->prepare("
            INSERT INTO `university_program` 
            (`university_id`, `program_id`, `tuition_fee`, `duration`, `intake`, `scholarship`, `field`, `document_requirement`) 
            VALUES (:university_id, :program_id, :tuition_fee, :duration, :intake, :scholarship, :field, :doc_req)
            ON DUPLICATE KEY UPDATE 
                `tuition_fee` = VALUES(`tuition_fee`),
                `duration` = VALUES(`duration`),
                `intake` = VALUES(`intake`),
                `scholarship` = VALUES(`scholarship`),
                `field` = VALUES(`field`),
                `document_requirement` = VALUES(`document_requirement`)
        ");
        $upStmt->execute([
            'university_id' => $universityId,
            'program_id' => $programId,
            'tuition_fee' => $tuitionFee,
            'duration' => $duration ?: '2 Years',
            'intake' => $intake ?: 'Fall 2027',
            'scholarship' => $scholarship ?: 'Available',
            'field' => $field ?: 'STEM',
            'doc_req' => $finalDocReq
        ]);

        // 5. Populate requirements & program_requirements relational table
        $upFindStmt = $pdo->prepare("SELECT `university_program_id` FROM `university_program` WHERE `university_id` = :uid AND `program_id` = :pid LIMIT 1");
        $upFindStmt->execute(['uid' => $universityId, 'pid' => $programId]);
        $upRow = $upFindStmt->fetch();
        $univProgId = $upRow ? $upRow['university_program_id'] : 0;

        if ($univProgId > 0 && !empty($docTypesArr) && is_array($docTypesArr)) {
            $reqFindStmt = $pdo->prepare("SELECT `requirement_id` FROM `requirements` WHERE `document_name` = :doc_name LIMIT 1");
            $reqInsStmt  = $pdo->prepare("INSERT INTO `requirements` (`document_name`, `description`) VALUES (:doc_name, :desc)");
            $prInsStmt   = $pdo->prepare("INSERT IGNORE INTO `program_requirements` (`university_program_id`, `requirement_id`) VALUES (:upid, :reqid)");

            foreach ($docTypesArr as $docType) {
                $docType = trim($docType);
                if (empty($docType)) continue;

                $reqFindStmt->execute(['doc_name' => $docType]);
                $reqFound = $reqFindStmt->fetch();
                if ($reqFound) {
                    $reqId = $reqFound['requirement_id'];
                } else {
                    $reqInsStmt->execute(['doc_name' => $docType, 'desc' => "Official $docType required for application verification."]);
                    $reqId = $pdo->lastInsertId();
                }

                $prInsStmt->execute(['upid' => $univProgId, 'reqid' => $reqId]);
            }
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'University and Program added successfully.']);
            exit;
        } else {
            header('Location: ../institutions.php?msg=uni_added');
            exit;
        }

    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
            exit;
        } else {
            header('Location: ../institutions.php?msg=db_error');
            exit;
        }
    }

} elseif ($action === 'delete_university') {
    $uniId = intval($_POST['university_id'] ?? $_GET['university_id'] ?? 0);

    if ($uniId <= 0) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid University ID.']);
            exit;
        } else {
            header('Location: ../institutions.php?msg=invalid_id');
            exit;
        }
    }

    try {
        $delStmt = $pdo->prepare("DELETE FROM `university` WHERE `university_id` = :id");
        $delStmt->execute(['id' => $uniId]);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'University deleted successfully.']);
            exit;
        } else {
            header('Location: ../institutions.php?msg=uni_deleted');
            exit;
        }

    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Cannot delete university: ' . $e->getMessage()]);
            exit;
        } else {
            header('Location: ../institutions.php?msg=cannot_delete');
            exit;
        }
    }
} else {
    header('Location: ../institutions.php');
    exit;
}
?>
