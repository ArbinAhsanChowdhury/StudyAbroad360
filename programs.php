<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Find and search thousands of accredited global university programs in Canada, UK, USA, Australia, and Germany.">
  <title>Find Programs & Degrees | StudyAbroad360</title>
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom Design System Stylesheet -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="scroll-progress-bar" id="scrollProgressBar"></div>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

  <!-- INNER PAGE BANNER -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <span>Find Programs</span>
      </div>
      <h1 style="font-size: 2.75rem; color: var(--white); margin-bottom: 0.5rem;">Explore Global Academic Programs</h1>
      <p style="color: var(--slate-300); font-size: 1.1rem; max-width: 650px;">
        Search through accredited Bachelor's, Master's, and Diploma programs across top institutions with instant AI eligibility matching.
      </p>
    </div>
  </section>

  <!-- MAIN CONTENT: SEARCH & PROGRAM ENGINE -->
  <section class="section-padding">
    <div class="container">
      
      <!-- Top Search Form Bar -->
      <div class="search-card" style="margin-top: 0; margin-bottom: 2.5rem;">
        <form class="search-form-grid" id="heroFastSearchForm">
          <div class="input-field">
            <label for="heroCountrySelect">Country</label>
            <select id="heroCountrySelect">
              <option value="All">All Countries</option>
              <option value="Canada">Canada 🇨🇦</option>
              <option value="United Kingdom">United Kingdom 🇬🇧</option>
              <option value="United States">United States 🇺🇸</option>
              <option value="Australia">Australia 🇦🇺</option>
              <option value="Germany">Germany 🇩🇪</option>
              <option value="Ireland">Ireland 🇮🇪</option>
            </select>
          </div>

          <div class="input-field">
            <label for="heroDegreeSelect">Degree Level</label>
            <select id="heroDegreeSelect">
              <option value="All">All Degrees</option>
              <option value="Master">Master's Degree</option>
              <option value="Bachelor">Bachelor's Degree</option>
              <option value="PhD">Ph.D. / Doctorate</option>
              <option value="Diploma">Diploma / Pathway</option>
            </select>
          </div>

          <div class="input-field">
            <label for="heroIntakeSelect">Intake</label>
            <select id="heroIntakeSelect">
              <option value="All">All Intakes</option>
              <option value="Fall 2027">Fall 2027</option>
              <option value="Spring 2027">Spring 2027</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary" style="height: 48px; align-self: flex-end; padding: 0 1.5rem;">
            <i class="fas fa-search"></i> Search
          </button>
        </form>
      </div>

      <!-- Filter Controls Bar -->
      <div class="program-filter-bar">
        <div class="filter-tags">
          <button class="tag-btn active" data-filter="All">All Programs</button>
          <button class="tag-btn" data-filter="STEM">STEM & IT</button>
          <button class="tag-btn" data-filter="Business">Business & MBA</button>
          <button class="tag-btn" data-filter="Master">Master's</button>
          <button class="tag-btn" data-filter="Bachelor">Bachelor's</button>
          <button class="tag-btn" data-filter="PhD">Ph.D. & Doctorate</button>
        </div>

        <button class="btn btn-outline" style="font-size: 0.85rem;" onclick="resetAllFilters()">
          <i class="fas fa-undo"></i> Reset Filters
        </button>
      </div>

      <!-- Dynamic Cards Container -->
      <div class="program-cards-grid" id="programGridContainer">
        <!-- Rendered dynamically by script.js -->
      </div>
    </div>
  </section>

  <!-- AI MATCH BANNER CTA -->
  <section class="section-padding" style="background: var(--slate-100);">
    <div class="container">
      <div class="eligibility-cta-card">
        <div>
          <span class="badge badge-success" style="margin-bottom: 1rem;">Powered by StudyAbroad360 AI</span>
          <h2 class="eligibility-cta-title">Want Instant Program Recommendations?</h2>
          <p class="eligibility-cta-desc">
            Use our AI Eligibility Matcher to evaluate your GPA, English proficiency scores, and budget to find guaranteed admission programs!
          </p>
          <button class="btn btn-primary btn-lg trigger-eligibility-modal">
            Launch AI Matcher <i class="fas fa-magic"></i>
          </button>
        </div>

        <div style="text-align: center;">
          <div style="background: var(--glass-bg); backdrop-filter: blur(12px); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--glass-border); color: var(--navy-900);">
            <i class="fas fa-award" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
            <h3 style="font-size: 1.25rem;">Free Eligibility Audit</h3>
            <p style="font-size: 0.875rem; color: var(--slate-600); margin-top: 0.5rem;">
              Instant analysis with verified counselor guidance.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>



<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

  <script src="script.js"></script>
</body>
</html>
