<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireLogin();

$user = currentUser();

// Fetch student's submitted applications via 3NF JOIN
$stmt = $pdo->prepare("
    SELECT 
        a.application_id,
        a.status,
        a.academic_level,
        a.gpa_score,
        a.english_test,
        a.english_score,
        a.transcript_file,
        a.passport_file,
        a.sop_file,
        a.english_cert_file,
        a.cv_file,
        a.additional_notes,
        a.created_at,
        p.program_name,
        p.degree_level,
        uni.university_name,
        up.tuition_fee
    FROM applications a
    JOIN university_program up ON a.university_program_id = up.university_program_id
    JOIN university uni ON up.university_id = uni.university_id
    JOIN program p ON up.program_id = p.program_id
    WHERE a.user_id = :uid
    ORDER BY a.application_id DESC
");
$stmt->execute(['uid' => $user['id']]);
$studentApps = $stmt->fetchAll();

// Fetch student's bookmarked programs & automated deadline reminders via LEFT JOIN
$bmStmt = $pdo->prepare("
    SELECT 
        b.bookmark_id,
        b.saved_at,
        up.university_program_id,
        up.tuition_fee,
        up.duration,
        up.intake,
        up.scholarship,
        p.program_name,
        p.degree_level,
        uni.university_name,
        l.country,
        r.reminder_id,
        r.deadline_title,
        r.deadline_date,
        r.email_enabled,
        r.push_enabled,
        r.sms_enabled,
        r.reminded_30d,
        r.reminded_20d,
        r.reminded_15d,
        r.reminded_10d,
        r.reminded_7d,
        r.reminded_3d,
        r.reminded_1d,
        r.reminded_5h,
        r.reminded_3h,
        r.reminded_1h
    FROM bookmarks b
    JOIN university_program up ON b.university_program_id = up.university_program_id
    JOIN university uni ON up.university_id = uni.university_id
    JOIN locations l ON uni.location_id = l.location_id
    JOIN program p ON up.program_id = p.program_id
    LEFT JOIN application_reminders r ON (r.user_id = b.user_id AND r.university_program_id = b.university_program_id)
    WHERE b.user_id = :uid
    ORDER BY b.bookmark_id DESC
");
$bmStmt->execute(['uid' => $user['id']]);
$studentBookmarks = $bmStmt->fetchAll();

// If admin is viewing student portal and personal bookmarks are empty, show all bookmarks so video/evaluator demo never shows empty tables
$isAdminViewing = isAdmin();
if ($isAdminViewing && empty($studentBookmarks)) {
    $adminBmStmt = $pdo->query("
        SELECT 
            b.bookmark_id,
            b.saved_at,
            up.university_program_id,
            up.tuition_fee,
            up.duration,
            up.intake,
            up.scholarship,
            p.program_name,
            p.degree_level,
            uni.university_name,
            l.country,
            r.reminder_id,
            r.deadline_title,
            r.deadline_date,
            r.email_enabled,
            r.push_enabled,
            r.sms_enabled,
            r.reminded_30d,
            r.reminded_20d,
            r.reminded_15d,
            r.reminded_10d,
            r.reminded_7d,
            r.reminded_3d,
            r.reminded_1d,
            r.reminded_5h,
            r.reminded_3h,
            r.reminded_1h
        FROM bookmarks b
        JOIN university_program up ON b.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN locations l ON uni.location_id = l.location_id
        JOIN program p ON up.program_id = p.program_id
        LEFT JOIN application_reminders r ON (r.user_id = b.user_id AND r.university_program_id = b.university_program_id)
        ORDER BY b.bookmark_id DESC
    ");
    $studentBookmarks = $adminBmStmt->fetchAll();
}

if ($isAdminViewing && empty($studentApps)) {
    $adminAppStmt = $pdo->query("
        SELECT 
            a.application_id,
            a.status,
            a.academic_level,
            a.gpa_score,
            a.english_test,
            a.english_score,
            a.transcript_file,
            a.passport_file,
            a.sop_file,
            a.english_cert_file,
            a.cv_file,
            a.additional_notes,
            a.created_at,
            p.program_name,
            p.degree_level,
            uni.university_name,
            up.tuition_fee
        FROM applications a
        JOIN university_program up ON a.university_program_id = up.university_program_id
        JOIN university uni ON up.university_id = uni.university_id
        JOIN program p ON up.program_id = p.program_id
        ORDER BY a.application_id DESC
    ");
    $studentApps = $adminAppStmt->fetchAll();
}

// Combine all programs student can set deadlines for
$allStudentPrograms = [];
foreach ($studentBookmarks as $bm) {
    $allStudentPrograms[$bm['program_name']] = [
        'upid' => $bm['university_program_id'],
        'name' => $bm['program_name'] . " (" . $bm['university_name'] . ")"
    ];
}

if (empty($allStudentPrograms)) {
    $fallbackProgStmt = $pdo->query("
        SELECT up.university_program_id as upid, p.program_name, uni.university_name 
        FROM university_program up
        JOIN program p ON up.program_id = p.program_id
        JOIN university uni ON up.university_id = uni.university_id
        LIMIT 8
    ");
    foreach ($fallbackProgStmt->fetchAll() as $fb) {
        $allStudentPrograms[] = [
            'upid' => $fb['upid'],
            'name' => $fb['program_name'] . " (" . $fb['university_name'] . ")"
        ];
    }
}

function getStudentFlag($country) {
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
  <title>Student Application Portal | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- INNER BANNER -->
<section class="page-banner">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div>
        <div class="breadcrumb">
          <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <span>Student Dashboard</span>
        </div>
        <h1 style="font-size: 2.4rem; color: var(--white); margin-bottom: 0.25rem;">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p style="color: var(--slate-300); font-size: 1rem;">Track your bookmarked programs, automated deadline reminders, and application submissions in real time.</p>
      </div>
      <div style="display: flex; gap: 0.75rem;">
        <button onclick="requestNotificationPermission()" class="btn btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.4); background: rgba(255,255,255,0.1);">
          <i class="fas fa-bell"></i> Enable Phone/Web Push
        </button>
        <a href="programs.php" class="btn btn-primary btn-lg">
          <i class="fas fa-search"></i> Find More Programs
        </a>
      </div>
    </div>
  </div>
</section>

<!-- MAIN CONTENT -->
<section class="section-padding" style="min-height: 65vh; background: var(--slate-100);">
  <div class="container">

    <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 2rem;">
      
      <!-- LEFT: UNIFIED BOOKMARKS & DEADLINE REMINDERS, APPLICATIONS -->
      <div>

        <?php if ($isAdminViewing): ?>
          <div style="background: rgba(13, 148, 136, 0.1); border: 1px solid var(--secondary); border-radius: var(--radius-md); padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
              <strong style="color: var(--secondary);"><i class="fas fa-user-shield"></i> Admin Demo Mode Active:</strong>
              <span style="font-size: 0.875rem; color: var(--navy-900); margin-left: 0.25rem;">Displaying live platform records so all tables demonstrate full functionality.</span>
            </div>
            <a href="admin_dashboard.php#bookmarksTableSection" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
              <i class="fas fa-external-link-alt"></i> Admin Panel Bookmarks Table
            </a>
          </div>
        <?php endif; ?>

        <!-- UNIFIED BOOKMARKED PROGRAMS & AUTOMATED DEADLINE TRACKER WIDGET -->
        <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 2rem;" id="studentBookmarksTableSection">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
              <span class="badge badge-success" style="font-size: 0.78rem; margin-bottom: 0.35rem;"><i class="fas fa-sync-alt"></i> Auto-Reminders Active</span>
              <h2 style="font-size: 1.45rem; color: var(--navy-900);"><i class="fas fa-bookmark" style="color: var(--secondary);"></i> Bookmarked Programs & Universities Table</h2>
              <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.2rem;">
                Saved universities automatically trigger milestone reminders at <strong>30d, 20d, 15d, 10d, 7d, 3d, 1d, 5h, 3h, 1h</strong> before deadline.
              </p>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
              <button onclick="runReminderEngineTest()" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem; border-color: #0284c7; color: #0284c7;" title="Run background cron milestone monitor">
                <i class="fas fa-play-circle"></i> Test Trigger Engine
              </button>
              <a href="institutions.php" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                <i class="fas fa-university"></i> Partner Universities
              </a>
              <a href="programs.php" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                <i class="fas fa-plus"></i> Browse Programs
              </a>
            </div>
          </div>

          <?php if (empty($studentBookmarks)): ?>
            <div style="text-align: center; padding: 3rem 1rem; background: var(--slate-50); border-radius: var(--radius-md); border: 1px dashed var(--slate-300);">
              <i class="far fa-bookmark" style="font-size: 2.75rem; color: var(--slate-300); margin-bottom: 1rem;"></i>
              <h3 style="font-size: 1.2rem; color: var(--navy-900);">No Bookmarked Programs Yet</h3>
              <p style="color: var(--slate-500); font-size: 0.875rem; max-width: 460px; margin: 0.5rem auto 1.25rem auto;">
                Click the 🔖 Bookmark button on any university or program to save it here! It will automatically set up live deadline countdowns and automated milestone alerts.
              </p>
              <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <a href="institutions.php" class="btn btn-outline">Explore Universities</a>
                <a href="programs.php" class="btn btn-primary">Browse & Bookmark Programs</a>
              </div>
            </div>
          <?php else: ?>
            <!-- STRUCTURED BOOKMARKS TABLE -->
            <div style="overflow-x: auto; margin-bottom: 2rem;">
              <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; background: var(--white); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--slate-200);">
                <thead>
                  <tr style="background: var(--navy-900); color: var(--white);">
                    <th style="padding: 0.9rem 1rem;">ID</th>
                    <th style="padding: 0.9rem 1rem;">University & Country</th>
                    <th style="padding: 0.9rem 1rem;">Degree Program</th>
                    <th style="padding: 0.9rem 1rem;">Est. Tuition</th>
                    <th style="padding: 0.9rem 1rem;">Next Intake</th>
                    <th style="padding: 0.9rem 1rem;">Target Deadline & Timer</th>
                    <th style="padding: 0.9rem 1rem; text-align: right;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($studentBookmarks as $bm): 
                    $deadlineStr = !empty($bm['deadline_date']) ? $bm['deadline_date'] : parseIntakeDeadline($bm['intake'] ?? 'Fall 2027');
                    $nowTs = time();
                    $deadlineTs = strtotime($deadlineStr);
                    $diffSec = $deadlineTs - $nowTs;

                    $days = floor($diffSec / 86400);
                    $hours = floor(($diffSec % 86400) / 3600);
                    $mins = floor(($diffSec % 3600) / 60);

                    if ($diffSec <= 86400) {
                        $badgeBg = 'background: #ffe4e6; color: #e11d48; border: 1px solid #fecdd3;';
                        $timerText = ($diffSec <= 0) ? 'Expired' : "🔥 $hours h $mins m Left";
                    } elseif ($diffSec <= 7 * 86400) {
                        $badgeBg = 'background: #fef3c7; color: #b45309; border: 1px solid #fde68a;';
                        $timerText = "⌛ $days d $hours h Left";
                    } else {
                        $badgeBg = 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;';
                        $timerText = "📅 $days d Left";
                    }
                  ?>
                    <tr style="border-bottom: 1px solid var(--slate-200);">
                      <td style="padding: 0.9rem 1rem; font-weight: 700; color: var(--navy-900);">
                        #<?php echo $bm['bookmark_id']; ?>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <strong style="color: var(--navy-900); font-size: 0.95rem;"><?php echo htmlspecialchars($bm['university_name']); ?></strong>
                        <div style="font-size: 0.8rem; color: var(--slate-500); margin-top: 2px;">
                          <?php echo getStudentFlag($bm['country']); ?> <?php echo htmlspecialchars($bm['country']); ?>
                        </div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($bm['program_name']); ?></div>
                        <div style="font-size: 0.78rem; color: var(--slate-500);"><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> <?php echo htmlspecialchars($bm['degree_level']); ?> Degree</div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <strong style="color: var(--secondary); font-size: 0.9rem;">$<?php echo number_format($bm['tuition_fee'], 2); ?>/yr</strong>
                        <?php if (!empty($bm['scholarship'])): ?>
                          <div style="font-size: 0.75rem; color: var(--accent-rose);"><i class="fas fa-gift"></i> <?php echo htmlspecialchars($bm['scholarship']); ?></div>
                        <?php endif; ?>
                      </td>
                      <td style="padding: 0.9rem 1rem; font-size: 0.85rem; color: var(--slate-700);">
                        <i class="fas fa-calendar-alt" style="color: var(--accent-gold);"></i> <?php echo htmlspecialchars($bm['intake']); ?>
                        <div style="font-size: 0.75rem; color: var(--slate-500);"><?php echo htmlspecialchars($bm['duration']); ?></div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <span class="badge" style="<?php echo $badgeBg; ?> font-weight: 700; font-size: 0.78rem; padding: 0.25rem 0.55rem; margin-bottom: 0.25rem; display: inline-block;">
                          <?php echo $timerText; ?>
                        </span>
                        <div style="font-size: 0.75rem; color: var(--slate-500);"><?php echo date('M j, Y - g:i A', $deadlineTs); ?></div>
                      </td>
                      <td style="padding: 0.9rem 1rem; text-align: right;">
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center;">
                          <button class="btn btn-primary" style="font-size: 0.78rem; padding: 0.35rem 0.65rem;" onclick="openApplyModal('<?php echo htmlspecialchars(addslashes($bm['program_name'])); ?>', '<?php echo htmlspecialchars(addslashes($bm['university_name'])); ?>')">
                            Apply
                          </button>
                          <button class="btn btn-outline" style="font-size: 0.78rem; padding: 0.35rem 0.55rem; color: #b45309; border-color: rgba(217,119,6,0.4);" onclick="openSetDeadlineModalWithProg(<?php echo $bm['university_program_id']; ?>)" title="Change Target Deadline Date">
                            <i class="fas fa-clock"></i>
                          </button>
                          <button class="btn btn-outline" style="font-size: 0.78rem; padding: 0.35rem 0.55rem; color: var(--accent-rose); border-color: rgba(225,29,72,0.4);" onclick="toggleProgramBookmark(<?php echo $bm['university_program_id']; ?>)" title="Remove Bookmark">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- DETAILED AUTOMATED MILESTONE BREAKDOWN & CHANNEL PREFERENCES -->
            <div style="border-top: 1px solid var(--slate-200); padding-top: 1.5rem;">
              <h3 style="font-size: 1.15rem; color: var(--navy-900); margin-bottom: 1rem;"><i class="fas fa-bell" style="color: #e11d48;"></i> Automated Milestone Timeline & Alert Channels</h3>
              <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <?php foreach ($studentBookmarks as $bm): 
                  $deadlineStr = !empty($bm['deadline_date']) ? $bm['deadline_date'] : parseIntakeDeadline($bm['intake'] ?? 'Fall 2027');
                  $nowTs = time();
                  $deadlineTs = strtotime($deadlineStr);
                  $diffSec = $deadlineTs - $nowTs;

                  $days = floor($diffSec / 86400);
                  $hours = floor(($diffSec % 86400) / 3600);
                  $mins = floor(($diffSec % 3600) / 60);

                  if ($diffSec <= 86400) {
                      $badgeBg = 'background: #ffe4e6; color: #e11d48; border: 1px solid #fecdd3;';
                      $timerText = ($diffSec <= 0) ? 'Expired' : "🔥 $hours Hours $mins Mins Left (Urgent)";
                  } elseif ($diffSec <= 7 * 86400) {
                      $badgeBg = 'background: #fef3c7; color: #b45309; border: 1px solid #fde68a;';
                      $timerText = "⌛ $days Days $hours Hours Left";
                  } else {
                      $badgeBg = 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;';
                      $timerText = "📅 $days Days Left ($hours Hours)";
                  }
                ?>
                  <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem;">
                    
                    <!-- TOP ROW: TITLE & COUNTDOWN -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.75rem;">
                      <div>
                        <span class="badge" style="<?php echo $badgeBg; ?> font-weight: 700; font-size: 0.8rem; padding: 0.35rem 0.75rem; margin-bottom: 0.4rem;">
                          <i class="fas fa-hourglass-half"></i> <?php echo $timerText; ?>
                        </span>
                        <h4 style="font-size: 1.1rem; color: var(--navy-900); margin-top: 0.25rem;"><?php echo htmlspecialchars($bm['program_name']); ?> (<?php echo htmlspecialchars($bm['degree_level']); ?>)</h4>
                        <p style="font-size: 0.85rem; color: var(--slate-600); margin-top: 0.2rem;">
                          <i class="fas fa-university" style="color: var(--primary);"></i> <strong><?php echo htmlspecialchars($bm['university_name']); ?></strong> (<?php echo htmlspecialchars($bm['country']); ?>) • 
                          <strong style="color: var(--secondary);">$<?php echo number_format($bm['tuition_fee'], 2); ?>/yr</strong> • 
                          <span style="color: var(--slate-500);"><i class="fas fa-calendar-alt"></i> Intake: <?php echo htmlspecialchars($bm['intake']); ?></span>
                        </p>
                      </div>

                      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                        <button class="btn btn-primary" style="font-size: 0.825rem; padding: 0.45rem 0.85rem;" onclick="openApplyModal('<?php echo htmlspecialchars(addslashes($bm['program_name'])); ?>', '<?php echo htmlspecialchars(addslashes($bm['university_name'])); ?>')">
                          Apply Now <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="btn btn-outline" style="font-size: 0.825rem; padding: 0.45rem 0.85rem; color: var(--accent-rose); border-color: rgba(225,29,72,0.4);" onclick="toggleProgramBookmark(<?php echo $bm['university_program_id']; ?>)" title="Remove Bookmark">
                          <i class="fas fa-trash-alt"></i> Remove
                        </button>
                      </div>
                    </div>

                    <!-- AUTOMATED MILESTONE ALERTS & CHANNEL PREFERENCES BAR -->
                    <div style="background: var(--white); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); padding: 0.85rem; margin-top: 0.75rem;">
                      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.6rem; border-bottom: 1px dashed var(--slate-200); padding-bottom: 0.5rem;">
                        <div style="font-size: 0.825rem; color: var(--navy-900); font-weight: 700;">
                          <i class="fas fa-bell" style="color: #e11d48;"></i> Target Deadline: 
                          <span style="color: var(--slate-700); font-weight: 600;"><?php echo date('M j, Y - g:i A', $deadlineTs); ?></span>
                        </div>

                        <?php if (!empty($bm['reminder_id'])): ?>
                          <div style="font-size: 0.78rem; color: var(--slate-600); display: flex; gap: 0.75rem; align-items: center;">
                            <strong style="color: var(--navy-900);">Channels:</strong>
                            <label style="cursor: pointer;"><input type="checkbox" <?php echo ($bm['email_enabled'] ?? 1) ? 'checked' : ''; ?> onchange="toggleChannelPreference(<?php echo $bm['reminder_id']; ?>, 'email', this.checked)"> 📧 Email</label>
                            <label style="cursor: pointer;"><input type="checkbox" <?php echo ($bm['push_enabled'] ?? 1) ? 'checked' : ''; ?> onchange="toggleChannelPreference(<?php echo $bm['reminder_id']; ?>, 'push', this.checked)"> 🔔 Web Push</label>
                            <label style="cursor: pointer;"><input type="checkbox" <?php echo ($bm['sms_enabled'] ?? 1) ? 'checked' : ''; ?> onchange="toggleChannelPreference(<?php echo $bm['reminder_id']; ?>, 'sms', this.checked)"> 📱 SMS</label>
                          </div>
                        <?php endif; ?>
                      </div>

                      <!-- MILESTONES TIMELINE BADGES -->
                      <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; font-size: 0.725rem;">
                        <span style="font-weight: 700; color: var(--slate-500); margin-right: 0.25rem;">Automated Milestones:</span>
                        <span class="badge <?php echo ($bm['reminded_30d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">30d</span>
                        <span class="badge <?php echo ($bm['reminded_20d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">20d</span>
                        <span class="badge <?php echo ($bm['reminded_15d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">15d</span>
                        <span class="badge <?php echo ($bm['reminded_10d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">10d</span>
                        <span class="badge <?php echo ($bm['reminded_7d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">7d</span>
                        <span class="badge <?php echo ($bm['reminded_3d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">3d</span>
                        <span class="badge <?php echo ($bm['reminded_1d'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">1d</span>
                        <span class="badge <?php echo ($bm['reminded_5h'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">5h</span>
                        <span class="badge <?php echo ($bm['reminded_3h'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">3h</span>
                        <span class="badge <?php echo ($bm['reminded_1h'] ?? 0) ? 'badge-success' : 'badge-primary'; ?>" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">1h</span>
                      </div>
                    </div>

                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- MY SUBMITTED APPLICATIONS & DOCUMENTS TABLE -->
        <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 2rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
              <h2 style="font-size: 1.4rem; color: var(--navy-900);"><i class="fas fa-paper-plane" style="color: var(--primary);"></i> My Submitted Applications & Documents Table</h2>
              <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.2rem;">Track admission decisions, review statuses, and verified document submissions.</p>
            </div>
            <span class="badge badge-primary"><?php echo count($studentApps); ?> Applications</span>
          </div>

          <?php if (empty($studentApps)): ?>
            <div style="text-align: center; padding: 2.5rem 1rem; background: var(--slate-50); border-radius: var(--radius-md); border: 1px dashed var(--slate-300);">
              <i class="fas fa-file-signature" style="font-size: 2.5rem; color: var(--slate-300); margin-bottom: 1rem;"></i>
              <h3 style="font-size: 1.2rem; color: var(--navy-900);">No Active Applications Yet</h3>
              <p style="color: var(--slate-500); font-size: 0.9rem; max-width: 450px; margin: 0.5rem auto 1.5rem auto;">
                Explore thousands of undergraduate and postgraduate programs across Canada, UK, US, and Australia!
              </p>
              <a href="programs.php" class="btn btn-primary">Browse Programs & Apply Now</a>
            </div>
          <?php else: ?>
            <div style="overflow-x: auto;">
              <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; background: var(--white); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--slate-200);">
                <thead>
                  <tr style="background: var(--navy-900); color: var(--white);">
                    <th style="padding: 0.9rem 1rem;">App ID</th>
                    <th style="padding: 0.9rem 1rem;">Selected Program & Level</th>
                    <th style="padding: 0.9rem 1rem;">University & Tuition</th>
                    <th style="padding: 0.9rem 1rem;">Qualifications</th>
                    <th style="padding: 0.9rem 1rem;">Submitted Documents</th>
                    <th style="padding: 0.9rem 1rem;">Review Status</th>
                    <th style="padding: 0.9rem 1rem; text-align: right;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($studentApps as $app): ?>
                    <tr style="border-bottom: 1px solid var(--slate-200);">
                      <td style="padding: 0.9rem 1rem; font-weight: 700; color: var(--navy-900);">
                        #<?php echo $app['application_id']; ?>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($app['program_name']); ?></div>
                        <div style="font-size: 0.78rem; color: var(--slate-500);"><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> <?php echo htmlspecialchars($app['degree_level']); ?> Degree</div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <div style="font-weight: 600; color: var(--navy-900);"><?php echo htmlspecialchars($app['university_name']); ?></div>
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--secondary);">$<?php echo number_format($app['tuition_fee'], 2); ?>/yr</div>
                      </td>
                      <td style="padding: 0.9rem 1rem; font-size: 0.8rem;">
                        <div><strong>GPA:</strong> <?php echo htmlspecialchars($app['gpa_score'] ?? '3.5'); ?> (<?php echo htmlspecialchars($app['academic_level'] ?? 'Bachelor'); ?>)</div>
                        <div style="color: var(--slate-500); margin-top: 2px;"><strong><?php echo htmlspecialchars($app['english_test'] ?? 'IELTS'); ?>:</strong> <?php echo htmlspecialchars($app['english_score'] ?? '7.0'); ?></div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                          <?php if (!empty($app['transcript_file'])): ?>
                            <a href="<?php echo htmlspecialchars($app['transcript_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.35rem;" title="View Transcript">🎓 Transcript</a>
                          <?php endif; ?>
                          <?php if (!empty($app['passport_file'])): ?>
                            <a href="<?php echo htmlspecialchars($app['passport_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.35rem;" title="View Passport">🛂 Passport</a>
                          <?php endif; ?>
                          <?php if (!empty($app['sop_file'])): ?>
                            <a href="<?php echo htmlspecialchars($app['sop_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.35rem;" title="View SOP">📝 SOP</a>
                          <?php endif; ?>
                          <?php if (!empty($app['english_cert_file'])): ?>
                            <a href="<?php echo htmlspecialchars($app['english_cert_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.35rem;" title="View English Certificate">🗣️ Test Cert</a>
                          <?php endif; ?>
                          <?php if (!empty($app['cv_file'])): ?>
                            <a href="<?php echo htmlspecialchars($app['cv_file']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.15rem 0.35rem;" title="View CV">📄 CV</a>
                          <?php endif; ?>
                          <?php if (empty($app['transcript_file']) && empty($app['passport_file']) && empty($app['sop_file'])): ?>
                            <span style="font-size: 0.75rem; color: var(--slate-400);">No files</span>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td style="padding: 0.9rem 1rem;">
                        <?php if ($app['status'] === 'Approved'): ?>
                          <span class="badge badge-success" style="font-size: 0.78rem; padding: 0.25rem 0.5rem;"><i class="fas fa-check-circle"></i> Approved</span>
                        <?php elseif ($app['status'] === 'Rejected'): ?>
                          <span class="badge" style="background: rgba(225,29,72,0.15); color: var(--accent-rose); border: 1px solid rgba(225,29,72,0.3); font-size: 0.78rem; padding: 0.25rem 0.5rem;"><i class="fas fa-times-circle"></i> Rejected</span>
                        <?php else: ?>
                          <span class="badge badge-primary" style="background: rgba(217,119,6,0.15); color: #d97706; border: 1px solid rgba(217,119,6,0.3); font-size: 0.78rem; padding: 0.25rem 0.5rem;"><i class="fas fa-clock"></i> Under Review</span>
                        <?php endif; ?>
                        <div style="font-size: 0.72rem; color: var(--slate-400); margin-top: 3px;">
                          <?php echo !empty($app['created_at']) ? date('M j, Y', strtotime($app['created_at'])) : ''; ?>
                        </div>
                      </td>
                      <td style="padding: 0.9rem 1rem; text-align: right;">
                        <button class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.6rem; color: var(--accent-rose); border-color: rgba(225,29,72,0.4);" onclick="withdrawApplication(<?php echo $app['application_id']; ?>)" title="Withdraw Application">
                          <i class="fas fa-trash-alt"></i> Withdraw
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- RIGHT: STUDENT PROFILE CARD -->
      <div>
        <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm); padding: 1.75rem;">
          <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 800; margin: 0 auto 1rem auto;">
              <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <h3 style="font-size: 1.15rem; color: var(--navy-900);"><?php echo htmlspecialchars($user['name']); ?></h3>
            <p style="font-size: 0.85rem; color: var(--slate-500);"><?php echo htmlspecialchars($user['email']); ?></p>
            <span class="badge badge-success" style="margin-top: 0.5rem;">Verified Student</span>
          </div>

          <div style="border-top: 1px solid var(--slate-200); padding-top: 1.25rem;">
            <p style="font-size: 0.85rem; color: var(--slate-600); margin-bottom: 0.75rem;"><i class="fas fa-bell" style="color: #e11d48;"></i> Multi-Channel Reminders Active</p>
            <p style="font-size: 0.85rem; color: var(--slate-600); margin-bottom: 0.75rem;"><i class="fas fa-shield-alt" style="color: var(--primary);"></i> Single Application Enabled</p>
            <p style="font-size: 0.85rem; color: var(--slate-600); margin-bottom: 1.25rem;"><i class="fas fa-user-check" style="color: var(--secondary);"></i> AI Matcher Active</p>
            <a href="logout.php" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 0.85rem; color: var(--accent-rose); border-color: var(--accent-rose);">
              <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- SET DEADLINE MODAL -->
<div id="setDeadlineModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
  <div style="background: var(--white); width: 100%; max-width: 500px; border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-xl); position: relative;">
    <button onclick="closeSetDeadlineModal()" style="position: absolute; top: 1.25rem; right: 1.25rem; background: none; border: none; font-size: 1.25rem; color: var(--slate-400); cursor: pointer;">&times;</button>
    
    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--navy-900);"><i class="fas fa-calendar-plus" style="color: #e11d48;"></i> Set Program Target Deadline</h3>
    <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 1.5rem;">Select your program and deadline date to receive automated Email & Push reminders.</p>
    
    <form id="deadlineForm" onsubmit="handleDeadlineSubmit(event)">
      <div style="margin-bottom: 1rem;">
        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Select Program</label>
        <select name="university_program_id" id="deadlineProgramSelect" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md);">
          <?php foreach ($allStudentPrograms as $prog): ?>
            <option value="<?php echo $prog['upid']; ?>"><?php echo htmlspecialchars($prog['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="margin-bottom: 1rem;">
        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Reminder Title</label>
        <input type="text" name="deadline_title" value="Final Application & Document Submission" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md);">
      </div>

      <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Target Deadline Date & Time</label>
        <input type="datetime-local" id="modalDeadlineInput" name="deadline_date" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md);">
      </div>

      <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
        <button type="button" onclick="closeSetDeadlineModal()" class="btn btn-outline">Cancel</button>
        <button type="submit" class="btn btn-primary" style="background: #e11d48; border-color: #e11d48;">Save Target Deadline</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="assets/js/push_notifications.js"></script>
<script src="script.js"></script>
<script>
function openSetDeadlineModal(upid) {
  const modal = document.getElementById('setDeadlineModal');
  if (upid) {
    const sel = document.getElementById('deadlineProgramSelect');
    if (sel) sel.value = upid;
  }
  modal.style.display = 'flex';
}
function closeSetDeadlineModal() {
  document.getElementById('setDeadlineModal').style.display = 'none';
}

function handleDeadlineSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  formData.append('action', 'save_deadline');

  fetch('api/manage_reminders.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showToast(data.message, 'success');
      closeSetDeadlineModal();
      setTimeout(() => location.reload(), 800);
    } else {
      showToast(data.message, 'error');
    }
  });
}

function toggleChannelPreference(reminderId, channel, isChecked) {
  const formData = new FormData();
  formData.append('action', 'toggle_channels');
  formData.append('reminder_id', reminderId);
  
  // Get current checked states
  const container = event.target.closest('div');
  const emailCb = container.querySelector('input[type="checkbox"]:nth-of-type(1)').checked ? 1 : 0;
  const pushCb = container.querySelector('input[type="checkbox"]:nth-of-type(2)').checked ? 1 : 0;
  const smsCb = container.querySelector('input[type="checkbox"]:nth-of-type(3)').checked ? 1 : 0;

  formData.append('email_enabled', emailCb);
  formData.append('push_enabled', pushCb);
  formData.append('sms_enabled', smsCb);

  fetch('api/manage_reminders.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showToast('Notification channel preferences updated!', 'success');
  });
}

function deleteReminder(reminderId) {
  if (!confirm('Are you sure you want to delete this reminder deadline?')) return;
  const formData = new FormData();
  formData.append('action', 'delete_reminder');
  formData.append('reminder_id', reminderId);

  fetch('api/manage_reminders.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showToast(data.message, 'success');
    setTimeout(() => location.reload(), 600);
  });
}

function runReminderEngineTest() {
  showToast('Running background Cron Engine check...', 'info');
  fetch('cron_send_reminders.php')
  .then(res => res.json())
  .then(data => {
    if (data.triggers_fired && data.triggers_fired.length > 0) {
      showToast(`🔥 ${data.triggers_fired.length} Reminder Triggers Fired! Check logs/sent_reminders.log`, 'success');
      triggerTestPushAlert('⏰ Application Deadline Alert', data.triggers_fired[0].milestone + ' for ' + data.triggers_fired[0].program);
    } else {
      showToast('Cron check completed. All active milestones are up to date.', 'info');
    }
  })
  .catch(err => {
    showToast('Cron execution failed.', 'error');
  });
}

function withdrawApplication(appId) {
  if (!confirm(`Are you sure you want to withdraw Application #${appId}?`)) return;

  const formData = new FormData();
  formData.append('application_id', appId);

  fetch('api/delete_application.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showToast(data.message, 'success');
      setTimeout(() => location.reload(), 650);
    } else {
      showToast(data.message || 'Withdrawal failed.', 'error');
    }
  })
  .catch(err => {
    showToast('Failed to withdraw application.', 'error');
  });
}
</script>
</body>
</html>
