<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="StudyAbroad360 - The global platform connecting students, recruitment partners, and top universities in Canada, UK, USA, Australia, and Europe.">
  <title>StudyAbroad360 | Global Study Abroad & University Admissions Platform</title>
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom Design System Stylesheet -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="scroll-progress-bar" id="scrollProgressBar"></div>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero-section">
  <div class="container">
    <div class="hero-grid">
      <!-- Hero Text Column -->
      <div class="hero-text-col">
        <div class="hero-badge-group">
          <span class="badge badge-primary">
            <i class="fas fa-sparkles"></i> #1 International Education Ecosystem
          </span>
        </div>

        <h1 class="hero-title">
          Study at Top Global Universities with <span class="text-gradient">1 Single Application</span>
        </h1>

        <p class="hero-subtitle">
          StudyAbroad360 connects international students, recruitment partners, and 10+ top partner colleges. Get
          instant AI program matching and expert application guidance.
        </p>

        <!-- Fast Program Search Card -->
        <div class="search-card">
          <div class="search-tabs">
            <button class="search-tab active"><i class="fas fa-search"></i> Search Programs</button>
            <button class="search-tab" onclick="window.location.href='institutions.php';"><i
                class="fas fa-university"></i> Explore Universities</button>
            <button class="search-tab trigger-eligibility-modal"><i class="fas fa-magic"></i> AI Matcher</button>
          </div>

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
      </div>

      <!-- Hero Visual Image Column -->
      <div class="hero-image-wrapper">
        <img src="assets/real_students_hero.png" alt="StudyAbroad360 Students studying on campus" class="hero-img-card">

        <!-- Floating Badges -->
        <div class="floating-badge badge-top-right">
          <div class="floating-icon icon-blue">
            <i class="fas fa-university"></i>
          </div>
          <div>
            <h4 style="font-size: 0.9rem; margin-bottom: 2px;">10+ Top Colleges</h4>
            <p style="font-size: 0.75rem; color: var(--slate-500);">Direct University Admission</p>
          </div>
        </div>

        <div class="floating-badge badge-bottom-left">
          <div class="floating-icon icon-teal">
            <i class="fas fa-user-shield"></i>
          </div>
          <div>
            <h4 style="font-size: 0.9rem; margin-bottom: 2px;">1-on-1 Guidance</h4>
            <p style="font-size: 0.75rem; color: var(--slate-500);">Certified Education Advisors</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- LIVE PROGRAM SEARCH & FILTER SECTION -->
<section class="section-padding" id="programs">
  <div class="container">
    <div class="section-header">
      <span class="badge badge-primary">Featured Programs</span>
      <h2 style="font-size: 2.4rem; margin-top: 0.5rem;">Explore Top Programs Worldwide</h2>
      <p class="section-subtitle">Filter through thousands of accredited undergraduate and postgraduate programs with
        instant eligibility insights.</p>
    </div>

    <!-- Filter Controls Bar -->
    <div class="program-filter-bar">
      <div class="filter-tags">
        <button class="tag-btn active" data-filter="All">All Programs</button>
        <button class="tag-btn" data-filter="STEM">STEM & IT</button>
        <button class="tag-btn" data-filter="Business">Business & MBA</button>
        <button class="tag-btn" data-filter="Master">Master's</button>
        <button class="tag-btn" data-filter="Bachelor">Bachelor's</button>
      </div>

      <div style="display: flex; gap: 0.5rem; align-items: center;">
        <a href="programs.php" class="btn btn-outline" style="font-size: 0.85rem;">View All Programs <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <!-- Dynamic Cards Container -->
    <div class="program-cards-grid" id="programGridContainer">
      <!-- Rendered dynamically by script.js -->
    </div>
  </div>
</section>

<!-- DESTINATION EXPLORER SECTION -->
<section class="section-padding destinations-section" id="destinations">
  <div class="container">
    <div class="section-header">
      <span class="badge badge-success">Global Reach</span>
      <h2 style="font-size: 2.4rem; margin-top: 0.5rem;">Popular Study Abroad Destinations</h2>
      <p class="section-subtitle">Discover top-tier education hubs offering post-study work permits and vibrant
        international culture.</p>
    </div>

    <div class="destination-grid">
      <!-- Canada Card -->
      <a href="destinations.php" class="destination-card">
        <img src="assets/canada_dest.png" alt="Study in Canada" class="destination-img">
        <div class="destination-overlay">
          <span class="badge badge-primary" style="width: fit-content; margin-bottom: 0.5rem;">Most Popular</span>
          <h3 class="destination-title">Canada 🇨🇦</h3>
          <p style="font-size: 0.9rem; opacity: 0.9;">Home to world-class universities and up to 3 Years Post-Graduation
            Work Permit (PGWP).</p>
          <div class="destination-stats">
            <div><strong>150+</strong> Institutions</div>
            <div><strong>3 Yrs</strong> Work Permit</div>
            <div><strong>$18k-35k</strong> Avg Tuition</div>
          </div>
        </div>
      </a>

      <!-- UK Card -->
      <a href="destinations.php" class="destination-card">
        <img src="assets/uk_dest.png" alt="Study in UK" class="destination-img">
        <div class="destination-overlay">
          <span class="badge badge-success" style="width: fit-content; margin-bottom: 0.5rem;">1-Year Master's</span>
          <h3 class="destination-title">United Kingdom 🇬🇧</h3>
          <p style="font-size: 0.9rem; opacity: 0.9;">Accelerated 1-year Master's degrees and 2-Year Graduate Route Work
            Route.</p>
          <div class="destination-stats">
            <div><strong>120+</strong> Institutions</div>
            <div><strong>2 Yrs</strong> Work Route</div>
            <div><strong>£14k-28k</strong> Avg Tuition</div>
          </div>
        </div>
      </a>

      <!-- USA Card -->
      <a href="destinations.php" class="destination-card">
        <img src="assets/hero_banner.png" alt="Study in USA" class="destination-img">
        <div class="destination-overlay">
          <span class="badge badge-primary" style="width: fit-content; margin-bottom: 0.5rem;">3-Year OPT for
            STEM</span>
          <h3 class="destination-title">United States 🇺🇸</h3>
          <p style="font-size: 0.9rem; opacity: 0.9;">Lead global innovation with STEM OPT work authorization and
            massive scholarships.</p>
          <div class="destination-stats">
            <div><strong>350+</strong> Institutions</div>
            <div><strong>3 Yrs</strong> STEM OPT</div>
            <div><strong>$22k-45k</strong> Avg Tuition</div>
          </div>
        </div>
      </a>
    </div>

    <div style="text-align: center; margin-top: 2.5rem;">
      <a href="destinations.php" class="btn btn-secondary btn-lg">Explore All Destinations <i class="fas fa-globe-americas"></i></a>
    </div>
  </div>
</section>

<!-- AI ELIGIBILITY BANNER -->
<section class="section-padding">
  <div class="container">
    <div class="eligibility-cta-card">
      <div>
        <span class="badge badge-success" style="margin-bottom: 1rem;">Powered by StudyAbroad360 AI</span>
        <h2 class="eligibility-cta-title">Not Sure Which Program Fits Your Profile?</h2>
        <p class="eligibility-cta-desc">
          Use our AI Eligibility Checker. Answer 3 quick questions about your GPA, IELTS score, and budget to get an
          instant personalized university shortlist!
        </p>
        <button class="btn btn-primary btn-lg trigger-eligibility-modal">
          Launch AI Eligibility Checker <i class="fas fa-magic"></i>
        </button>
      </div>

      <div style="text-align: center;">
        <div
          style="background: var(--glass-bg); backdrop-filter: blur(12px); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--glass-border); color: var(--navy-900);">
          <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
          <h3 style="font-size: 1.25rem;">Free Profile Assessment</h3>
          <p style="font-size: 0.875rem; color: var(--slate-600); margin-top: 0.5rem;">
            No hidden fees. 100% transparent admission requirements.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- AUDIENCE SOLUTIONS SECTION -->
<section class="section-padding" id="solutions">
  <div class="container">
    <div class="section-header">
      <span class="badge badge-primary">Tailored Ecosystem</span>
      <h2 style="font-size: 2.4rem; margin-top: 0.5rem;">Built for Every Education Stakeholder</h2>
      <p class="section-subtitle">Empowering students, recruitment agents, and university admissions officers around the
        globe.</p>
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 3rem;">
      <a href="students.php" class="tag-btn active persona-tab" style="padding: 0.75rem 1.75rem; font-size: 1rem;">For Students</a>
      <a href="partners.php" class="tag-btn persona-tab" style="padding: 0.75rem 1.75rem; font-size: 1rem;">For Recruitment Partners</a>
      <a href="institutions.php" class="tag-btn persona-tab" style="padding: 0.75rem 1.75rem; font-size: 1rem;">For Partner Institutions</a>
    </div>

    <!-- Students Panel -->
    <div class="persona-panel" id="panelStudents"
      style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
      <div
        style="background: var(--white); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
        <div class="floating-icon icon-blue" style="margin-bottom: 1.25rem;"><i class="fas fa-file-alt"></i></div>
        <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;">1 Single Application</h3>
        <p style="color: var(--slate-600); font-size: 0.9rem;">Submit your profile once and apply to multiple top-tier
          universities without duplicating documentation.</p>
      </div>
      <div
        style="background: var(--white); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
        <div class="floating-icon icon-teal" style="margin-bottom: 1.25rem;"><i class="fas fa-robot"></i></div>
        <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;">AI Program Matching</h3>
        <p style="color: var(--slate-600); font-size: 0.9rem;">Instant match algorithms analyze your academic GPA, test
          scores, and budget to pinpoint high-acceptance programs.</p>
      </div>
      <div
        style="background: var(--white); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
        <div class="floating-icon icon-blue" style="margin-bottom: 1.25rem;"><i class="fas fa-plane-departure"></i></div>
        <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Travel & Housing Support</h3>
        <p style="color: var(--slate-600); font-size: 0.9rem;">End-to-end assistance with document validation,
          arrival preparation, and student housing accommodation.</p>
      </div>
    </div>
  </div>
</section>

<!-- COST ESTIMATOR PREVIEW -->
<section class="section-padding" style="background: var(--slate-100);">
  <div class="container">
    <div class="section-header">
      <span class="badge badge-success">Smart Planning</span>
      <h2 style="font-size: 2.4rem; margin-top: 0.5rem;">Tuition & Living Cost Estimator</h2>
      <p class="section-subtitle">Estimate total study abroad expenses and calculate your eligible scholarship savings in real-time.</p>
    </div>

    <div class="calc-card">
      <div>
        <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Customize Your Budget</h3>

        <div class="input-field" style="margin-bottom: 1.25rem;">
          <label for="calcCountry">Target Destination Country</label>
          <select id="calcCountry">
            <option value="Canada">Canada 🇨🇦</option>
            <option value="UK">United Kingdom 🇬🇧</option>
            <option value="USA">United States 🇺🇸</option>
            <option value="Australia">Australia 🇦🇺</option>
            <option value="Germany">Germany 🇩🇪</option>
          </select>
        </div>

        <div class="input-field" style="margin-bottom: 1.25rem;">
          <label for="calcDegree">Select Academic Degree</label>
          <select id="calcDegree">
            <option value="Master">Master's Degree (1-2 Years)</option>
            <option value="Bachelor">Bachelor's Degree (3-4 Years)</option>
            <option value="Diploma">Diploma / Post-Grad Certificate</option>
          </select>
        </div>

        <div class="input-field">
          <label for="calcLiving">Living Expense Preference</label>
          <select id="calcLiving">
            <option value="Standard">Standard Student Living ($1,000/mo)</option>
            <option value="Comfort">Comfort Campus Housing ($1,400/mo)</option>
          </select>
        </div>
      </div>

      <div class="calc-result-box">
        <span class="badge badge-primary">Estimated Annual Expense</span>
        <div class="calc-amount" id="calcTotalAmount">$40,000</div>
        <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 1.25rem;">Includes estimated tuition fee + living expenses.</p>

        <div style="background: var(--white); padding: 1rem; border-radius: var(--radius-sm); width: 100%; border: 1px solid var(--slate-200);">
          <p style="font-size: 0.85rem; color: var(--slate-600);">Estimated Scholarship Savings:</p>
          <h4 style="font-size: 1.4rem; color: var(--secondary);" id="calcScholarshipAmount">$7,000</h4>
        </div>

        <a href="calculator.php" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%;">
          Open Detailed Cost Calculator <i class="fas fa-calculator"></i>
        </a>
      </div>
    </div>
  </div>
</section>



<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="script.js"></script>
</body>
</html>
