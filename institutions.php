joinCOUNT<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$msg = $_GET['msg'] ?? '';
$feedbackText = '';
$feedbackClass = '';

if ($msg === 'uni_added') {
    $feedbackText = 'University added to database successfully!';
    $feedbackClass = 'background: rgba(13, 148, 136, 0.15); color: var(--secondary); border: 1px solid var(--secondary);';
} elseif ($msg === 'uni_deleted') {
    $feedbackText = 'University deleted from database successfully.';
    $feedbackClass = 'background: rgba(225, 29, 72, 0.15); color: var(--accent-rose); border: 1px solid var(--accent-rose);';
} elseif ($msg === 'cannot_delete') {
    $feedbackText = 'Cannot delete this university because active applications or programs are linked to it.';
    $feedbackClass = 'background: rgba(217, 119, 6, 0.15); color: #d97706; border: 1px solid #d97706;';
}

// Fetch Universities from MySQL Database (3NF JOIN with locations and university_program)
$searchQuery = trim($_GET['q'] ?? '');
$countryFilter = trim($_GET['country'] ?? 'All');

$sql = "
    SELECT 
        u.university_id,
        u.university_name,
        u.description,
        u.website_url,
        l.country,
        l.city,
        COUNT(up.university_program_id) AS programs_count,
        MIN(up.university_program_id) AS sample_program_id,
        MIN(up.tuition_fee) AS min_tuition,
        GROUP_CONCAT(DISTINCT p.program_name SEPARATOR ' | ') AS sample_programs
    FROM university u
    JOIN locations l ON u.location_id = l.location_id
    LEFT JOIN university_program up ON u.university_id = up.university_id
    LEFT JOIN program p ON up.program_id = p.program_id
    WHERE 1=1
";

$params = [];

if (!empty($searchQuery)) {
    $sql .= " AND (u.university_name LIKE :q OR l.city LIKE :q OR l.country LIKE :q OR p.program_name LIKE :q)";
    $params['q'] = "%$searchQuery%";
}

if ($countryFilter !== 'All' && !empty($countryFilter)) {
    $sql .= " AND l.country = :country";
    $params['country'] = $countryFilter;
}

$sql .= " GROUP BY u.university_id ORDER BY u.university_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$universities = $stmt->fetchAll();

// Map bookmarked universities for currently logged-in user
$userBookmarksMap = [];
if (isLoggedIn()) {
    $curUid = $_SESSION['user_id'];
    $ubmStmt = $pdo->prepare("
        SELECT up.university_id 
        FROM bookmarks b 
        JOIN university_program up ON b.university_program_id = up.university_program_id 
        WHERE b.user_id = :uid
    ");
    $ubmStmt->execute(['uid' => $curUid]);
    $bookmarkedRows = $ubmStmt->fetchAll();
    foreach ($bookmarkedRows as $br) {
        $userBookmarksMap[$br['university_id']] = true;
    }
}

// Country flag mapper
function getCountryFlag($country) {
    switch (strtolower(trim($country))) {
        case 'canada': return '🇨🇦';
        case 'united kingdom': case 'uk': return '🇬🇧';
        case 'united states': case 'usa': case 'us': return '🇺🇸';
        case 'australia': return '🇦🇺';
        case 'germany': return '🇩🇪';
        case 'ireland': return '🇮🇪';
        default: return '🌐';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Explore top partner universities and colleges connected to StudyAbroad360.">
  <title>Partner Institutions & Colleges | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

  <!-- INNER PAGE BANNER -->
  <section class="page-banner">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
          <div class="breadcrumb">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <span>Partner Institutions</span>
          </div>
          <h1 style="font-size: 2.75rem; color: var(--white); margin-bottom: 0.5rem;">Global Partner Universities & Colleges</h1>
          <p style="color: var(--slate-300); font-size: 1.1rem; max-width: 650px;">
            Browse world-class academic partners connected directly to the StudyAbroad360 single application platform.
          </p>
        </div>

        <?php if (isAdmin()): ?>
        <div>
          <button class="btn btn-secondary btn-lg" onclick="openAddUniversityModal()">
            <i class="fas fa-plus-circle"></i> Add New University
          </button>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- SEARCH & INSTITUTIONS GRID -->
  <section class="section-padding" style="min-height: 60vh;">
    <div class="container">
      
      <?php if (!empty($feedbackText)): ?>
        <div style="<?php echo $feedbackClass; ?> padding: 1rem 1.25rem; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
          <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($feedbackText); ?>
        </div>
      <?php endif; ?>

      <!-- Filter Bar for Institutions -->
      <form method="GET" action="institutions.php" style="background: var(--white); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: flex-end;">
        <div class="input-field">
          <label for="institutionSearchInput">Search Institution Name or City</label>
          <input type="text" name="q" id="institutionSearchInput" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="e.g. University of Toronto, London, Melbourne">
        </div>
        <div class="input-field">
          <label for="institutionCountrySelect">Filter by Country</label>
          <select name="country" id="institutionCountrySelect" onchange="this.form.submit()">
            <option value="All" <?php echo $countryFilter === 'All' ? 'selected' : ''; ?>>All Countries</option>
            <option value="Canada" <?php echo $countryFilter === 'Canada' ? 'selected' : ''; ?>>Canada 🇨🇦</option>
            <option value="United Kingdom" <?php echo $countryFilter === 'United Kingdom' ? 'selected' : ''; ?>>United Kingdom 🇬🇧</option>
            <option value="United States" <?php echo $countryFilter === 'United States' ? 'selected' : ''; ?>>United States 🇺🇸</option>
            <option value="Australia" <?php echo $countryFilter === 'Australia' ? 'selected' : ''; ?>>Australia 🇦🇺</option>
            <option value="Germany" <?php echo $countryFilter === 'Germany' ? 'selected' : ''; ?>>Germany 🇩🇪</option>
            <option value="Ireland" <?php echo $countryFilter === 'Ireland' ? 'selected' : ''; ?>>Ireland 🇮🇪</option>
          </select>
        </div>
        <div>
          <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 1.5rem;"><i class="fas fa-search"></i> Search</button>
        </div>
      </form>

      <!-- Institutions Directory Grid -->
      <div class="institutions-grid" id="institutionsGridContainer" data-php-driven="true">
        <?php if (empty($universities)): ?>
          <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem; background: var(--white); border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
            <i class="fas fa-university" style="font-size: 3rem; color: var(--slate-300); margin-bottom: 1rem;"></i>
            <h3>No Universities Found</h3>
            <p style="color: var(--slate-500); font-size: 0.9rem; margin-top: 0.5rem;">Try adjusting your query or selecting "All Countries".</p>
            <a href="institutions.php" class="btn btn-outline" style="margin-top: 1rem;">Reset Filters</a>
          </div>
        <?php else: ?>
          <?php foreach ($universities as $uni): ?>
            <div class="institution-card" style="display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <div class="institution-card-header">
                  <div class="university-icon-box" style="width: 50px; height: 50px; font-size: 1.1rem; background: var(--primary-subtle); color: var(--primary);">
                    <?php echo strtoupper(substr($uni['university_name'], 0, 2)); ?>
                  </div>
                  <div>
                    <h3 style="font-size: 1.1rem; color: var(--navy-900);"><?php echo htmlspecialchars($uni['university_name']); ?></h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 2px;">
                      <span><?php echo getCountryFlag($uni['country']); ?> <?php echo htmlspecialchars($uni['country']); ?></span> • <span><?php echo htmlspecialchars($uni['city']); ?></span>
                    </p>
                  </div>
                </div>
                <div class="institution-body">
                  <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 0.75rem;">
                    <?php echo htmlspecialchars($uni['description']); ?>
                  </p>
                  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                    <div class="inst-pill" style="font-size: 0.78rem;"><i class="fas fa-graduation-cap" style="color: var(--secondary);"></i> <?php echo intval($uni['programs_count']) > 0 ? intval($uni['programs_count']) . ' Program(s)' : 'Direct Partner'; ?></div>
                    <?php if (!empty($uni['min_tuition'])): ?>
                      <div class="inst-pill" style="font-size: 0.78rem;"><i class="fas fa-tag" style="color: var(--accent-gold);"></i> From $<?php echo number_format($uni['min_tuition']); ?>/yr</div>
                    <?php endif; ?>
                  </div>
                  <div class="inst-pill"><i class="fas fa-globe" style="color: var(--primary);"></i> <a href="<?php echo htmlspecialchars($uni['website_url']); ?>" target="_blank" style="color: inherit; text-decoration: underline;">Official Website</a></div>
                </div>
              </div>

              <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--slate-200); display: flex; gap: 0.5rem; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <a href="programs.php?q=<?php echo urlencode($uni['university_name']); ?>" class="btn btn-primary" style="flex: 1; text-align: center; font-size: 0.85rem; min-width: 120px;">
                  View Programs <i class="fas fa-arrow-right"></i>
                </a>

                <button type="button" 
                        class="btn <?php echo !empty($userBookmarksMap[$uni['university_id']]) ? 'btn-secondary' : 'btn-outline'; ?>" 
                        style="font-size: 0.85rem; padding: 0.45rem 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem;" 
                        onclick="toggleUniversityBookmark(<?php echo $uni['university_id']; ?>, <?php echo intval($uni['sample_program_id'] ?? 0); ?>, this)" 
                        title="<?php echo !empty($userBookmarksMap[$uni['university_id']]) ? 'Remove Bookmark' : 'Bookmark University'; ?>">
                  <i class="<?php echo !empty($userBookmarksMap[$uni['university_id']]) ? 'fas fa-bookmark' : 'far fa-bookmark'; ?>"></i>
                  <span><?php echo !empty($userBookmarksMap[$uni['university_id']]) ? 'Bookmarked' : 'Bookmark'; ?></span>
                </button>

                <?php if (isAdmin()): ?>
                <form action="api/manage_university.php" method="POST" onsubmit="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($uni['university_name'])); ?> from MySQL database?');" style="margin: 0;">
                  <input type="hidden" name="action" value="delete_university">
                  <input type="hidden" name="university_id" value="<?php echo $uni['university_id']; ?>">
                  <button type="submit" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.45rem 0.65rem; color: var(--accent-rose); border-color: var(--accent-rose); background: rgba(225, 29, 72, 0.05);" title="Delete University">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

  <script src="script.js"></script>
  <script>
  function toggleUniversityBookmark(uniId, progId, btn) {
    const formData = new FormData();
    formData.append('university_id', uniId);
    if (progId > 0) {
      formData.append('university_program_id', progId);
    }

    fetch('api/toggle_bookmark.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.require_login) {
        if (typeof showToast === 'function') {
          showToast('Please log in to bookmark universities.', 'warning');
        } else {
          alert('Please log in to bookmark universities.');
        }
        setTimeout(() => { window.location.href = 'login.php?msg=please_login'; }, 1000);
        return;
      }

      if (data.success) {
        if (typeof showToast === 'function') {
          showToast(data.message, data.bookmarked ? 'success' : 'info');
        } else {
          alert(data.message);
        }
        
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        if (data.bookmarked) {
          btn.classList.remove('btn-outline');
          btn.classList.add('btn-secondary');
          if (icon) icon.className = 'fas fa-bookmark';
          if (span) span.textContent = 'Bookmarked';
          btn.setAttribute('title', 'Remove Bookmark');
        } else {
          btn.classList.remove('btn-secondary');
          btn.classList.add('btn-outline');
          if (icon) icon.className = 'far fa-bookmark';
          if (span) span.textContent = 'Bookmark';
          btn.setAttribute('title', 'Bookmark University');
        }
      } else {
        if (typeof showToast === 'function') {
          showToast(data.message || 'Error updating bookmark.', 'error');
        } else {
          alert(data.message || 'Error updating bookmark.');
        }
      }
    })
    .catch(err => {
      if (typeof showToast === 'function') {
        showToast('Network error while toggling bookmark.', 'error');
      } else {
        alert('Network error while toggling bookmark.');
      }
    });
  }
  </script>
</body>
</html>
