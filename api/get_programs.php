<?php
// api/get_programs.php - Return JSON list of all university programs from MySQL database
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

try {
    $sql = "
        SELECT 
            up.university_program_id AS id,
            p.program_name AS title,
            p.degree_level AS degree,
            IFNULL(up.field, 'STEM') AS field,
            CAST(up.tuition_fee AS UNSIGNED) AS tuition,
            IFNULL(up.duration, '2 Years') AS duration,
            IFNULL(up.intake, 'Fall 2027') AS intake,
            IFNULL(up.scholarship, 'Available') AS scholarship,
            IFNULL(up.document_requirement, 'Certified academic transcripts, SOP, LOR') AS document_requirement,
            u.university_name AS university,
            u.description AS university_description,
            u.website_url,
            l.country,
            l.city
        FROM university_program up
        JOIN university u ON up.university_id = u.university_id
        JOIN locations l ON u.location_id = l.location_id
        JOIN program p ON up.program_id = p.program_id
        ORDER BY up.university_program_id DESC
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    $programs = [];
    foreach ($rows as $row) {
        // Generate initials for icon box (e.g. University of Toronto -> UT)
        $words = explode(' ', str_replace(['of', 'and', 'the'], '', strtolower($row['university'])));
        $icon = '';
        foreach ($words as $w) {
            $w = trim($w);
            if (!empty($w)) {
                $icon .= strtoupper($w[0]);
            }
        }
        if (strlen($icon) > 3) {
            $icon = substr($icon, 0, 3);
        }
        if (empty($icon)) {
            $icon = 'UNI';
        }

        // Generate badge
        $badge = 'Partner Univ';
        if ($row['degree'] === 'Master') {
            $badge = 'Postgraduate';
        } elseif ($row['degree'] === 'Bachelor') {
            $badge = 'Undergraduate';
        }

        $programs[] = [
            'id'                   => intval($row['id']),
            'title'                => $row['title'],
            'university'           => $row['university'],
            'country'              => $row['country'],
            'city'                 => $row['city'],
            'degree'               => $row['degree'],
            'field'                => $row['field'],
            'tuition'              => intval($row['tuition']),
            'duration'             => $row['duration'],
            'intake'               => $row['intake'],
            'scholarship'          => $row['scholarship'],
            'document_requirement' => $row['document_requirement'],
            'badge'                => $badge,
            'icon'                 => $icon
        ];
    }

    echo json_encode(['success' => true, 'count' => count($programs), 'programs' => $programs]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'programs' => []]);
}
?>
