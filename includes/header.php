<?php
require_once __DIR__ . '/auth.php';
?>
  <!-- HEADER NAVIGATION -->
  <header class="header-nav" id="navbar">
    <div class="container nav-container">
      <a href="index.php" class="brand-logo">
        <div class="brand-icon">
          <i class="fas fa-globe"></i>
        </div>
        <span>StudyAbroad<span class="text-gradient">360</span></span>
      </a>

      <ul class="nav-menu" id="headerNavMenu">
        <li><a href="programs.php" class="nav-link">Find Programs</a></li>
        <li><a href="students.php" class="nav-link">For Students</a></li>
        <li><a href="partners.php" class="nav-link">For Partners</a></li>
        <li><a href="institutions.php" class="nav-link">Institutions</a></li>
        <?php if (isAdmin()): ?>
          <li><a href="admin_dashboard.php" class="nav-link" style="color: var(--secondary); font-weight: 700;"><i class="fas fa-user-shield"></i> Admin Panel</a></li>
        <?php elseif (isStudent()): ?>
          <li><a href="student_dashboard.php" class="nav-link" style="color: var(--primary); font-weight: 700;"><i class="fas fa-user-graduate"></i> My Dashboard</a></li>
        <?php endif; ?>
      </ul>

      <div class="nav-actions">
        <?php if (isAdmin()): ?>
          <a href="admin_dashboard.php" class="btn btn-secondary" style="font-size: 0.85rem;">
            <i class="fas fa-user-shield"></i> Admin Panel
          </a>
          <a href="logout.php" class="btn btn-outline" style="font-size: 0.85rem;" title="Sign Out">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        <?php elseif (isStudent()): ?>
          <a href="student_dashboard.php" class="btn btn-outline" style="font-size: 0.85rem;">
            <i class="fas fa-user-graduate"></i> Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Student'); ?>
          </a>
          <a href="logout.php" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 0.85rem;" title="Sign Out">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline" style="font-size: 0.85rem;">
            <i class="fas fa-sign-in-alt"></i> Login
          </a>
          <a href="register.php" class="btn btn-primary" style="font-size: 0.85rem;">
            <i class="fas fa-user-plus"></i> Register
          </a>
        <?php endif; ?>

        <button class="mobile-toggle" id="mobileToggleBtn" aria-label="Toggle Menu">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>
