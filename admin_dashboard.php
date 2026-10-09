<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireAdmin();

$msg = '';

// Handle Delete Inquiry POST Action
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_inquiry') {
    $appId = intval($_POST['app_id'] ?? 0);

    if ($appId > 0) {
        $stmt = $pdo->prepare("DELETE FROM `applications` WHERE `application_id` = :id");
        $stmt->execute(['id' => $appId]);
        $msg = "Inquiry record #$appId removed from database.";
    }
}

// Handle Delete Bookmark POST Action
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_bookmark') {
    $bmId = intval($_POST['bookmark_id'] ?? 0);

    if ($bmId > 0) {
        $stmt = $pdo->prepare("DELETE FROM `bookmarks` WHERE `bookmark_id` = :id");
        $stmt->execute(['id' => $bmId]);
        $msg = "Bookmark record #$bmId removed from database.";
    }
}

// Handle Delete Partner Application POST Action
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_partner') {
    $partnerId = intval($_POST['partner_id'] ?? 0);

    if ($partnerId > 0) {
        $stmt = $pdo->prepare("DELETE FROM `partner_applications` WHERE `partner_id` = :id");
        $stmt->execute(['id' => $partnerId]);
        $msg = "Partner application #$partnerId removed from database.";
    }
}

// Fetch Directory Metrics
$totalApps      = $pdo->query("SELECT COUNT(*) FROM `applications`")->fetchColumn();
$totalUnis      = $pdo->query("SELECT COUNT(*) FROM `university`")->fetchColumn();
$totalProgs     = $pdo->query("SELECT COUNT(*) FROM `university_program`")->fetchColumn();
$totalPartners  = $pdo->query("SELECT COUNT(*) FROM `partner_applications`")->fetchColumn();
$totalBookmarks = $pdo->query("SELECT COUNT(*) FROM `bookmarks`")->fetchColumn();

// Fetch Admin Team Accounts
$adminsList  = $pdo->query("SELECT `user_id`, `name`, `email`, `role` FROM `users` WHERE `role` = 'admin' ORDER BY `user_id` ASC")->fetchAll();

// Fetch Partner Applications
$partnerApps = $pdo->query("SELECT * FROM `partner_applications` ORDER BY `partner_id` DESC")->fetchAll();

// Fetch Relational Student Inquiries (3NF JOIN)
$search = trim($_GET['q'] ?? '');
if (!empty($search)) {
    $appStmt = $pdo->prepare("
        SELECT 
            a.*,
            u.name AS student_name,
            u.email AS student_email,
            p.program_name,
            p.degree_level,
            uni.university_name,
            uni.website_url,
            up.tuition_fee
        FROM applications a
        JOIN users u ON a.user_id = u.user_id
        JOIN university_program up ON a.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN program p ON up.program_id = p.program_id
        WHERE u.name LIKE :q OR uni.university_name LIKE :q OR p.program_name LIKE :q
        ORDER BY a.application_id DESC
    ");
    $appStmt->execute(['q' => "%$search%"]);
} else {
    $appStmt = $pdo->query("
        SELECT 
            a.*,
            u.name AS student_name,
            u.email AS student_email,
            p.program_name,
            p.degree_level,
            uni.university_name,
            uni.website_url,
            up.tuition_fee
        FROM applications a
        JOIN users u ON a.user_id = u.user_id
        JOIN university_program up ON a.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN program p ON up.program_id = p.program_id
        ORDER BY a.application_id DESC
    ");
}
$applications = $appStmt->fetchAll();

// Fetch All Student Bookmarks with 3NF JOIN
$bmSearch = trim($_GET['bm_q'] ?? '');
if (!empty($bmSearch)) {
    $bmStmt = $pdo->prepare("
        SELECT 
            b.bookmark_id,
            b.saved_at,
            u.user_id,
            u.name AS student_name,
            u.email AS student_email,
            uni.university_id,
            uni.university_name,
            uni.website_url,
            l.country,
            l.city,
            p.program_name,
            p.degree_level,
            up.tuition_fee,
            up.duration,
            up.intake,
            up.scholarship,
            r.reminder_id,
            r.deadline_title,
            r.deadline_date
        FROM bookmarks b
        JOIN users u ON b.user_id = u.user_id
        JOIN university_program up ON b.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN locations l ON uni.location_id = l.location_id
        JOIN program p ON up.program_id = p.program_id
        LEFT JOIN application_reminders r ON (r.user_id = b.user_id AND r.university_program_id = b.university_program_id)
        WHERE u.name LIKE :bm_q OR u.email LIKE :bm_q OR uni.university_name LIKE :bm_q OR p.program_name LIKE :bm_q OR l.country LIKE :bm_q
        ORDER BY b.bookmark_id DESC
    ");
    $bmStmt->execute(['bm_q' => "%$bmSearch%"]);
} else {
    $bmStmt = $pdo->query("
        SELECT 
            b.bookmark_id,
            b.saved_at,
            u.user_id,
            u.name AS student_name,
            u.email AS student_email,
            uni.university_id,
            uni.university_name,
            uni.website_url,
            l.country,
            l.city,
            p.program_name,
            p.degree_level,
            up.tuition_fee,
            up.duration,
            up.intake,
            up.scholarship,
            r.reminder_id,
            r.deadline_title,
            r.deadline_date
        FROM bookmarks b
        JOIN users u ON b.user_id = u.user_id
        JOIN university_program up ON b.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN locations l ON uni.location_id = l.location_id
        JOIN program p ON up.program_id = p.program_id
        LEFT JOIN application_reminders r ON (r.user_id = b.user_id AND r.university_program_id = b.university_program_id)
        ORDER BY b.bookmark_id DESC
    ");
}
$allBookmarks = $bmStmt->fetchAll();

// Flag helper for admin display
function getCountryFlagAdmin($country) {
    switch (strtolower(trim($country))) {
        case 'canada': return '🇨🇦';
        case 'united kingdom': case 'uk': return '🇬🇧';
        case 'united states': case 'usa': case 'us': return '🇺🇸';
        case 'australia': return '🇦🇺';
        case 'germany': return '🇩🇪';
        case 'ireland': return '🇮🇪';
        case 'bangladesh': return '🇧🇩';
        default: return '🌐';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- ADMIN INNER BANNER -->
<section class="page-banner" style="padding: 2.5rem 0; background: linear-gradient(135deg, var(--navy-900) 0%, #0f172a 100%);">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div>
        <span class="badge badge-primary" style="margin-bottom: 0.5rem;"><i class="fas fa-user-shield"></i> Directory Management Portal</span>
        <h1 style="font-size: 2.25rem; color: var(--white); margin-bottom: 0.25rem;">Global University & Inquiries Control Panel</h1>
        <p style="color: var(--slate-300); font-size: 0.95rem;">Logged in as <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['user_email']); ?>)</p>
      </div>
      <div style="display: flex; gap: 0.75rem;">
        <button class="btn btn-secondary" onclick="openAddUniversityModal()">
          <i class="fas fa-plus-circle"></i> Add University
        </button>
        <a href="institutions.php" class="btn btn-outline" style="color: var(--white); border-color: rgba(255,255,255,0.3);">
          <i class="fas fa-university"></i> Institutions
        </a>
      </div>
    </div>
  </div>
</section>

<!-- MAIN DASHBOARD CONTENT -->
<section class="section-padding" style="background: var(--slate-100); min-height: 75vh;">
  <div class="container">

    <?php if (!empty($msg)): ?>
      <div style="background: rgba(13, 148, 136, 0.15); border: 1px solid var(--secondary); color: var(--navy-900); padding: 1rem 1.25rem; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-check-circle" style="color: var(--secondary); font-size: 1.25rem;"></i>
        <span><?php echo htmlspecialchars($msg); ?></span>
      </div>
    <?php endif; ?>

    <!-- DIRECTORY STATS METRICS GRID -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
      <div style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.85rem; color: var(--slate-500); font-weight: 600;">TOTAL SUBMISSIONS</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: var(--navy-900); margin-top: 0.25rem;"><?php echo $totalApps; ?></div>
        <div style="font-size: 0.75rem; color: var(--primary); margin-top: 0.5rem;"><i class="fas fa-paper-plane"></i> Program Inquiries Received</div>
      </div>

      <div style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.85rem; color: var(--slate-500); font-weight: 600;">CATALOGED UNIVERSITIES</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: var(--secondary); margin-top: 0.25rem;"><?php echo $totalUnis; ?></div>
        <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 0.5rem;"><i class="fas fa-university"></i> Active Partner Institutions</div>
      </div>

      <div style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.85rem; color: var(--slate-500); font-weight: 600;">LISTED PROGRAMS</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: #d97706; margin-top: 0.25rem;"><?php echo $totalProgs; ?></div>
        <div style="font-size: 0.75rem; color: #d97706; margin-top: 0.5rem;"><i class="fas fa-graduation-cap"></i> Available Degree Courses</div>
      </div>

      <div style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.85rem; color: var(--slate-500); font-weight: 600;">ADMIN TEAM MEMBERS</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-top: 0.25rem;"><?php echo count($adminsList); ?></div>
        <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.5rem;"><i class="fas fa-users-cog"></i> Authorized Admins</div>
      </div>

      <div style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.85rem; color: var(--slate-500); font-weight: 600;">SAVED BOOKMARKS</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: #e11d48; margin-top: 0.25rem;"><?php echo $totalBookmarks; ?></div>
        <div style="font-size: 0.75rem; color: #e11d48; margin-top: 0.5rem;"><i class="fas fa-bookmark"></i> Student Saved Programs</div>
      </div>
    </div>

    <!-- ADD NEW UNIVERSITY & OFFERED PROGRAM FORM CARD -->
    <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 2.5rem;" id="addUniversityFormSection">
      <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--slate-200); padding-bottom: 1rem;">
        <span class="badge badge-primary"><i class="fas fa-plus-circle"></i> Quick Admin Action</span>
        <h2 style="font-size: 1.5rem; margin-top: 0.35rem; color: var(--navy-900);">Add New University & Offered Program</h2>
        <p style="font-size: 0.875rem; color: var(--slate-500);">Fill out the university info along with its offered program, duration, next intake, scholarship, and total per year cost. It will instantly show up in the database and across the platform.</p>
      </div>

      <form action="api/manage_university.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_university">

        <!-- SECTION 1: UNIVERSITY DETAILS -->
        <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
          <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 1rem;"><i class="fas fa-university"></i> 1. University & Location Info</h4>
          
          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div class="input-field">
              <label>University / Institution Name *</label>
              <input type="text" name="university_name" required placeholder="e.g. Stanford University">
            </div>
            <div class="input-field">
              <label>Country *</label>
              <input type="text" name="country" required placeholder="e.g. United States">
            </div>
            <div class="input-field">
              <label>City / Location *</label>
              <input type="text" name="city" required placeholder="e.g. Stanford, California">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem;">
            <div class="input-field">
              <label>Website URL</label>
              <input type="url" name="website_url" placeholder="https://www.stanford.edu">
            </div>
            <div class="input-field">
              <label>Overview / Description</label>
              <input type="text" name="description" placeholder="Leading global research university in Silicon Valley...">
            </div>
          </div>
        </div>

        <!-- SECTION 2: OFFERED PROGRAM, DURATION, INTAKE, SCHOLARSHIP & TOTAL COST -->
        <div style="background: rgba(13, 148, 136, 0.05); border: 1px solid var(--secondary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
          <h4 style="font-size: 1rem; color: var(--secondary); margin-bottom: 1rem;"><i class="fas fa-graduation-cap"></i> 2. Offered Program, Duration, Intake, Scholarship & Cost</h4>

          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div class="input-field">
              <label>Offered Program Name *</label>
              <input type="text" name="program_name" required placeholder="e.g. M.Sc. in Computer Science & AI">
            </div>
            <div class="input-field">
              <label>Degree Level *</label>
              <select name="degree_level" required>
                <option value="Master">Master's Degree</option>
                <option value="Bachelor">Bachelor's Degree</option>
                <option value="PhD">Ph.D. / Doctorate</option>
                <option value="Diploma">Diploma / Pathway</option>
              </select>
            </div>
            <div class="input-field">
              <label>Field / Industry Tag *</label>
              <select name="field" required>
                <option value="STEM">STEM & IT</option>
                <option value="Business">Business & MBA</option>
                <option value="Health">Health Sciences</option>
                <option value="Arts">Arts & Humanities</option>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
            <div class="input-field">
              <label>Program Duration *</label>
              <input type="text" name="duration" required value="2 Years" placeholder="e.g. 2 Years">
            </div>
            <div class="input-field">
              <label>Next Intake *</label>
              <input type="text" name="intake" required value="Fall 2027" placeholder="e.g. Fall 2027">
            </div>
            <div class="input-field">
              <label>Scholarship Info</label>
              <input type="text" name="scholarship" value="Up to $15,000" placeholder="e.g. Up to $15,000">
            </div>
            <div class="input-field">
              <label>Total Cost Per Year ($) *</label>
              <input type="number" step="100" name="tuition_fee" required value="42000" placeholder="e.g. 42000">
            </div>
          </div>
        </div>

        <!-- SECTION 3: DOCUMENT REQUIREMENTS & SPECIFICATION FILE UPLOAD -->
        <div style="background: rgba(99, 102, 241, 0.05); border: 1px solid var(--primary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
          <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.75rem;"><i class="fas fa-file-alt"></i> 3. Document Requirements & Specification Upload</h4>
          <p style="font-size: 0.825rem; color: var(--slate-500); margin-bottom: 1rem;">Select required document types, provide application notes, and upload document requirement guidelines/brochure file for students.</p>

          <label style="font-size: 0.85rem; font-weight: 700; color: var(--navy-900); display: block; margin-bottom: 0.5rem;">Check Required Document Types for this Program:</label>
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; background: var(--white); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="Academic Transcripts" checked> 🎓 Academic Transcripts
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="Passport Copy" checked> 🛂 Valid Passport Copy
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="Statement of Purpose (SOP)" checked> 📝 Statement of Purpose (SOP)
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="Letters of Recommendation (LOR)" checked> ✉️ Recommendation Letters (LOR)
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="English Proficiency (IELTS/TOEFL)" checked> 🗣️ IELTS / TOEFL Certificate
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="CV / Resume"> 📄 Professional CV / Resume
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
              <input type="checkbox" name="doc_types[]" value="Bank Statement / Financial Proof"> 💰 Financial Guarantee / Bank Statement
            </label>
          </div>

          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
            <div class="input-field">
              <label>Custom Document Instructions & Eligibility Criteria</label>
              <input type="text" name="document_requirement" placeholder="e.g. Min 3.0 GPA, IELTS 6.5 Overall (no band below 6.0), 2 Academic LORs required.">
            </div>
            <div class="input-field">
              <label>Upload Document Guide / Spec (PDF/DOC)</label>
              <input type="file" name="doc_requirement_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" style="padding: 0.4rem; font-size: 0.8rem;">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%; justify-content: center; font-size: 1rem;">
          <i class="fas fa-plus-circle"></i> Save University, Program, Cost & Document Requirements
        </button>
      </form>
    </div>

    <!-- INQUIRIES MANAGEMENT TABLE -->
    <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 3rem;">
      
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <h2 style="font-size: 1.4rem;">Student Program Inquiries & Submissions</h2>
          <p style="font-size: 0.875rem; color: var(--slate-500);">Review program interest submissions and direct students to official university portals.</p>
        </div>

        <form method="GET" action="admin_dashboard.php" style="display: flex; gap: 0.5rem;">
          <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search student or uni..." style="padding: 0.6rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-300); font-size: 0.85rem; width: 220px;">
          <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1rem; font-size: 0.85rem;"><i class="fas fa-search"></i></button>
          <?php if (!empty($search)): ?>
            <a href="admin_dashboard.php" class="btn btn-outline" style="padding: 0.6rem 0.8rem; font-size: 0.85rem;">Clear</a>
          <?php endif; ?>
        </form>
      </div>

      <?php if (empty($applications)): ?>
        <div style="text-align: center; padding: 3rem 1rem;">
          <i class="fas fa-folder-open" style="font-size: 2.5rem; color: var(--slate-400); margin-bottom: 1rem;"></i>
          <h3>No Student Inquiries Found</h3>
          <p style="color: var(--slate-500); font-size: 0.9rem;">No student program submissions match your search criteria.</p>
        </div>
      <?php else: ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
              <tr style="background: var(--navy-900); color: var(--white);">
                <th style="padding: 1rem 1.25rem;">Inquiry ID</th>
                <th style="padding: 1rem 1.25rem;">Student Info</th>
                <th style="padding: 1rem 1.25rem;">Selected Program & Degree</th>
                <th style="padding: 1rem 1.25rem;">Submitted Documents & Score</th>
                <th style="padding: 1rem 1.25rem;">University & Est. Tuition</th>
                <th style="padding: 1rem 1.25rem;">Official Portal</th>
                <th style="padding: 1rem 1.25rem; text-align: right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($applications as $app): ?>
                <tr style="border-bottom: 1px solid var(--slate-200);">
                  <td style="padding: 1rem 1.25rem; font-weight: 700;">#<?php echo $app['application_id']; ?></td>
                  <td style="padding: 1rem 1.25rem;">
                    <strong style="color: var(--navy-900);"><?php echo htmlspecialchars($app['student_name']); ?></strong>
                    <div style="font-size: 0.8rem; color: var(--slate-500);"><?php echo htmlspecialchars($app['student_email']); ?></div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($app['program_name']); ?></div>
                    <div style="font-size: 0.8rem; color: var(--slate-500);"><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> <?php echo htmlspecialchars($app['degree_level']); ?> Degree</div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <div style="font-size: 0.78rem; color: var(--slate-600); margin-bottom: 0.35rem;">
                      <strong>GPA:</strong> <?php echo htmlspecialchars($app['gpa_score'] ?? 'N/A'); ?> • 
                      <strong><?php echo htmlspecialchars($app['english_test'] ?? 'IELTS'); ?>:</strong> <?php echo htmlspecialchars($app['english_score'] ?? 'N/A'); ?>
                    </div>
                    <div style="display: flex; gap: 0.3rem; flex-wrap: wrap;">
                      <?php if (!empty($app['transcript_file'])): ?>
                        <a href="<?php echo htmlspecialchars($app['transcript_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; color: var(--primary);" title="View Transcript">🎓 Transcript</a>
                      <?php endif; ?>
                      <?php if (!empty($app['passport_file'])): ?>
                        <a href="<?php echo htmlspecialchars($app['passport_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; color: var(--secondary);" title="View Passport">🛂 Passport</a>
                      <?php endif; ?>
                      <?php if (!empty($app['sop_file'])): ?>
                        <a href="<?php echo htmlspecialchars($app['sop_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.4rem; color: #d97706;" title="View Statement of Purpose">📝 SOP</a>
                      <?php endif; ?>
                      <?php if (!empty($app['english_cert_file'])): ?>
                        <a href="<?php echo htmlspecialchars($app['english_cert_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" title="View English Certificate">🗣️ Test Cert</a>
                      <?php endif; ?>
                      <?php if (!empty($app['cv_file'])): ?>
                        <a href="<?php echo htmlspecialchars($app['cv_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" title="View CV">📄 CV</a>
                      <?php endif; ?>
                      <?php if (empty($app['transcript_file']) && empty($app['passport_file']) && empty($app['sop_file'])): ?>
                        <span style="font-size: 0.75rem; color: var(--slate-400);">No files</span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($app['university_name']); ?></div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--secondary);">$<?php echo number_format($app['tuition_fee'], 2); ?> / yr</div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <?php if (!empty($app['website_url'])): ?>
                      <a href="<?php echo htmlspecialchars($app['website_url']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.78rem; padding: 0.35rem 0.7rem;" title="Visit University Official Application Page">
                        Visit Site <i class="fas fa-external-link-alt"></i>
                      </a>
                    <?php else: ?>
                      <span style="color: var(--slate-400); font-size: 0.8rem;">No Link</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding: 1rem 1.25rem; text-align: right;">
                    <div style="display: inline-flex; gap: 0.4rem;">
                      <a href="mailto:<?php echo htmlspecialchars($app['student_email']); ?>?subject=Regarding Your StudyAbroad360 Application for <?php echo urlencode($app['program_name']); ?>" class="btn btn-secondary" style="padding: 0.35rem 0.7rem; font-size: 0.78rem;" title="Email Student">
                        <i class="fas fa-envelope"></i> Contact
                      </a>

                      <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Remove inquiry #<?php echo $app['application_id']; ?>?');" style="margin: 0;">
                        <input type="hidden" name="action" value="delete_inquiry">
                        <input type="hidden" name="app_id" value="<?php echo $app['application_id']; ?>">
                        <button type="submit" class="btn btn-outline" style="padding: 0.35rem 0.7rem; font-size: 0.78rem; color: var(--accent-rose); border-color: var(--accent-rose);" title="Remove Inquiry">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- STUDENT BOOKMARKED UNIVERSITIES & PROGRAMS TABLE -->
    <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 3rem;" id="bookmarksTableSection">
      
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <span class="badge" style="background: rgba(225, 29, 72, 0.1); color: #e11d48; border: 1px solid rgba(225, 29, 72, 0.3); font-size: 0.78rem;"><i class="fas fa-bookmark"></i> Relational Bookmarks Table</span>
          <h2 style="font-size: 1.4rem; color: var(--navy-900); margin-top: 0.25rem;">Student Bookmarked Universities & Programs</h2>
          <p style="font-size: 0.875rem; color: var(--slate-500);">Live records of universities and degree programs saved by students with automated deadline monitoring.</p>
        </div>

        <form method="GET" action="admin_dashboard.php" style="display: flex; gap: 0.5rem;">
          <input type="text" name="bm_q" value="<?php echo htmlspecialchars($bmSearch); ?>" placeholder="Search student, university, country..." style="padding: 0.6rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-300); font-size: 0.85rem; width: 240px;">
          <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1rem; font-size: 0.85rem;"><i class="fas fa-search"></i></button>
          <?php if (!empty($bmSearch)): ?>
            <a href="admin_dashboard.php" class="btn btn-outline" style="padding: 0.6rem 0.8rem; font-size: 0.85rem;">Clear</a>
          <?php endif; ?>
        </form>
      </div>

      <?php if (empty($allBookmarks)): ?>
        <div style="text-align: center; padding: 3rem 1rem; background: var(--slate-50); border-radius: var(--radius-md); border: 1px dashed var(--slate-300);">
          <i class="far fa-bookmark" style="font-size: 2.5rem; color: var(--slate-400); margin-bottom: 1rem;"></i>
          <h3>No Bookmarked Universities Found</h3>
          <p style="color: var(--slate-500); font-size: 0.9rem; max-width: 460px; margin: 0.5rem auto;">
            <?php echo !empty($bmSearch) ? 'No student bookmarks match your search query.' : 'No students have bookmarked universities or programs yet.'; ?>
          </p>
          <?php if (!empty($bmSearch)): ?>
            <a href="admin_dashboard.php" class="btn btn-outline" style="margin-top: 0.75rem;">Reset Filter</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
              <tr style="background: var(--navy-900); color: var(--white);">
                <th style="padding: 1rem 1.25rem;">Bookmark ID</th>
                <th style="padding: 1rem 1.25rem;">Student Info</th>
                <th style="padding: 1rem 1.25rem;">Bookmarked University</th>
                <th style="padding: 1rem 1.25rem;">Program & Degree</th>
                <th style="padding: 1rem 1.25rem;">Est. Tuition & Intake</th>
                <th style="padding: 1rem 1.25rem;">Target Deadline</th>
                <th style="padding: 1rem 1.25rem;">Saved Date</th>
                <th style="padding: 1rem 1.25rem; text-align: right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allBookmarks as $bm): ?>
                <tr style="border-bottom: 1px solid var(--slate-200);">
                  <td style="padding: 1rem 1.25rem; font-weight: 700; color: var(--navy-900);">
                    #<?php echo $bm['bookmark_id']; ?>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <strong style="color: var(--navy-900);"><?php echo htmlspecialchars($bm['student_name']); ?></strong>
                    <div style="font-size: 0.8rem; color: var(--slate-500);"><?php echo htmlspecialchars($bm['student_email']); ?></div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <div style="font-weight: 700; color: var(--navy-900);"><?php echo htmlspecialchars($bm['university_name']); ?></div>
                    <div style="font-size: 0.8rem; color: var(--slate-500); margin-top: 2px;">
                      <?php echo getCountryFlagAdmin($bm['country']); ?> <?php echo htmlspecialchars($bm['country']); ?> • <?php echo htmlspecialchars($bm['city']); ?>
                    </div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($bm['program_name']); ?></div>
                    <div style="font-size: 0.78rem; color: var(--slate-500);"><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> <?php echo htmlspecialchars($bm['degree_level']); ?> Degree</div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <strong style="color: var(--secondary); font-size: 0.9rem;">$<?php echo number_format($bm['tuition_fee'], 2); ?>/yr</strong>
                    <div style="font-size: 0.78rem; color: var(--slate-500);"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($bm['intake'] ?? 'Fall 2027'); ?></div>
                  </td>
                  <td style="padding: 1rem 1.25rem;">
                    <?php if (!empty($bm['deadline_date'])): ?>
                      <div style="font-size: 0.825rem; font-weight: 600; color: #b45309;">
                        <i class="fas fa-clock"></i> <?php echo date('M j, Y', strtotime($bm['deadline_date'])); ?>
                      </div>
                      <div style="font-size: 0.75rem; color: var(--slate-500);"><?php echo htmlspecialchars($bm['deadline_title'] ?? 'Milestone Alert'); ?></div>
                    <?php else: ?>
                      <span style="font-size: 0.8rem; color: var(--slate-400);">Standard Intake</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding: 1rem 1.25rem; font-size: 0.8rem; color: var(--slate-600);">
                    <?php echo !empty($bm['saved_at']) ? date('M j, Y g:i A', strtotime($bm['saved_at'])) : 'N/A'; ?>
                  </td>
                  <td style="padding: 1rem 1.25rem; text-align: right;">
                    <div style="display: inline-flex; gap: 0.4rem;">
                      <a href="mailto:<?php echo htmlspecialchars($bm['student_email']); ?>?subject=Regarding Your Saved Program: <?php echo urlencode($bm['program_name']); ?>" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Email Student">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Remove bookmark #<?php echo $bm['bookmark_id']; ?>?');" style="margin: 0;">
                        <input type="hidden" name="action" value="delete_bookmark">
                        <input type="hidden" name="bookmark_id" value="<?php echo $bm['bookmark_id']; ?>">
                        <button type="submit" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; color: var(--accent-rose); border-color: var(--accent-rose);" title="Remove Bookmark">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- PARTNER APPLICATIONS RECEIVED TABLE -->
    <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 3rem;">
      <div style="margin-bottom: 1.5rem;">
        <span class="badge badge-success"><i class="fas fa-handshake"></i> B2B Network</span>
        <h2 style="font-size: 1.4rem; margin-top: 0.35rem; color: var(--navy-900);">Partner & Agency Registrations</h2>
        <p style="font-size: 0.875rem; color: var(--slate-500);">Review and manage recruitment agencies and university partnership applications submitted via the partner portal.</p>
      </div>

      <?php if (empty($partnerApps)): ?>
        <div style="text-align: center; padding: 2rem 1rem;">
          <i class="fas fa-handshake-slash" style="font-size: 2.2rem; color: var(--slate-400); margin-bottom: 0.75rem;"></i>
          <h4>No Partner Applications Found</h4>
          <p style="color: var(--slate-500); font-size: 0.85rem;">No agency or university partnership submissions currently in database.</p>
        </div>
      <?php else: ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
              <tr style="background: var(--navy-900); color: var(--white);">
                <th style="padding: 1rem 1.25rem;">ID</th>
                <th style="padding: 1rem 1.25rem;">Organization Name</th>
                <th style="padding: 1rem 1.25rem;">Partner Type</th>
                <th style="padding: 1rem 1.25rem;">Contact Person</th>
                <th style="padding: 1rem 1.25rem;">Work Email</th>
                <th style="padding: 1rem 1.25rem;">Location</th>
                <th style="padding: 1rem 1.25rem;">Student Volume</th>
                <th style="padding: 1rem 1.25rem;">Status</th>
                <th style="padding: 1rem 1.25rem; text-align: right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($partnerApps as $p): ?>
                <tr style="border-bottom: 1px solid var(--slate-200);">
                  <td style="padding: 1rem 1.25rem; font-weight: 700;">#<?php echo $p['partner_id']; ?></td>
                  <td style="padding: 1rem 1.25rem; font-weight: 700; color: var(--navy-900);"><?php echo htmlspecialchars($p['org_name']); ?></td>
                  <td style="padding: 1rem 1.25rem;"><span class="badge badge-primary" style="font-size: 0.75rem;"><?php echo htmlspecialchars($p['partner_type']); ?></span></td>
                  <td style="padding: 1rem 1.25rem;"><?php echo htmlspecialchars($p['contact_name']); ?></td>
                  <td style="padding: 1rem 1.25rem; color: var(--primary); font-weight: 600;"><?php echo htmlspecialchars($p['email']); ?></td>
                  <td style="padding: 1rem 1.25rem;"><?php echo htmlspecialchars($p['location']); ?></td>
                  <td style="padding: 1rem 1.25rem; font-size: 0.85rem; font-weight: 600; color: var(--secondary);"><?php echo htmlspecialchars($p['student_volume']); ?></td>
                  <td style="padding: 1rem 1.25rem;"><span class="badge badge-success" style="font-size: 0.75rem;"><?php echo htmlspecialchars($p['status']); ?></span></td>
                  <td style="padding: 1rem 1.25rem; text-align: right;">
                    <div style="display: inline-flex; gap: 0.4rem;">
                      <a href="mailto:<?php echo htmlspecialchars($p['email']); ?>?subject=Regarding Your StudyAbroad360 Partnership Application" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;" title="Email Partner">
                        <i class="fas fa-envelope"></i> Contact
                      </a>
                      <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Delete partner application #<?php echo $p['partner_id']; ?>?');" style="margin:0;">
                        <input type="hidden" name="action" value="delete_partner">
                        <input type="hidden" name="partner_id" value="<?php echo $p['partner_id']; ?>">
                        <button type="submit" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; color: var(--accent-rose); border-color: var(--accent-rose);" title="Remove Record">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- TEAM ADMIN ACCOUNTS SECTION -->
    <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); padding: 2rem; box-shadow: var(--shadow-sm);">
      <div style="margin-bottom: 1.25rem;">
        <span class="badge badge-primary">Authorized Team</span>
        <h3 style="font-size: 1.25rem; margin-top: 0.25rem;"><?php echo count($adminsList); ?> Team Admin Accounts</h3>
      </div>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
        <?php foreach ($adminsList as $index => $admin): ?>
          <div style="background: var(--slate-50); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--primary); color: var(--white); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
              A<?php echo ($index + 1); ?>
            </div>
            <div>
              <h4 style="font-size: 0.95rem; color: var(--navy-900);"><?php echo htmlspecialchars($admin['name']); ?></h4>
              <p style="font-size: 0.8rem; color: var(--slate-500);"><?php echo htmlspecialchars($admin['email']); ?></p>
              <span class="badge badge-success" style="font-size: 0.7rem; margin-top: 0.25rem;">Admin Role</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="script.js"></script>
</body>
</html>
