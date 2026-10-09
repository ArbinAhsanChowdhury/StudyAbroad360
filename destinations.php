<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Explore top international study destinations: Canada, UK, USA, Australia, Germany, and Ireland. Learn about tuition, work permits, and PGWP.">
  <title>Study Abroad Destinations | StudyAbroad360</title>
  
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
        <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <span>Destinations</span>
      </div>
      <h1 style="font-size: 2.75rem; color: var(--white); margin-bottom: 0.5rem;">Explore World-Class Destinations</h1>
      <p style="color: var(--slate-300); font-size: 1.1rem; max-width: 650px;">
        Compare global study hubs, post-study work permits, living costs, and university options to choose your perfect country.
      </p>
    </div>
  </section>

  <!-- DESTINATION CARDS GRID -->
  <section class="section-padding">
    <div class="container">
      <div class="section-header">
        <span class="badge badge-success">Global Reach</span>
        <h2 style="font-size: 2.4rem; margin-top: 0.5rem;">Choose Your Study Destination</h2>
        <p class="section-subtitle">Discover top-tier education hubs offering post-study work permits and vibrant international culture.</p>
      </div>

      <div class="destination-grid" style="grid-template-columns: repeat(3, 1fr); gap: 2rem;">
        <!-- Canada -->
        <div class="destination-card">
          <img src="assets/canada_dest.png" alt="Study in Canada" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-primary" style="width: fit-content; margin-bottom: 0.5rem;">#1 Choice for Immigrants</span>
            <h3 class="destination-title">Canada 🇨🇦</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">World-renowned education system with up to 3-Year Post-Graduation Work Permit (PGWP) & Express Entry PR points.</p>
            <div class="destination-stats">
              <div><strong>150+</strong> Partner Colleges</div>
              <div><strong>3 Yrs</strong> PGWP Permit</div>
              <div><strong>$18k-35k</strong> Avg Tuition</div>
            </div>
            <a href="programs.php?country=Canada" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View Canada Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- UK -->
        <div class="destination-card">
          <img src="assets/uk_dest.png" alt="Study in UK" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-success" style="width: fit-content; margin-bottom: 0.5rem;">1-Year Master's</span>
            <h3 class="destination-title">United Kingdom 🇬🇧</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">Accelerated 1-Year Master's degrees, rich academic history, and 2-Year Graduate Work Route.</p>
            <div class="destination-stats">
              <div><strong>120+</strong> Institutions</div>
              <div><strong>2 Yrs</strong> Work Route</div>
              <div><strong>£14k-28k</strong> Avg Tuition</div>
            </div>
            <a href="programs.php?country=United%20Kingdom" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View UK Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- USA -->
        <div class="destination-card">
          <img src="assets/hero_banner.png" alt="Study in USA" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-primary" style="width: fit-content; margin-bottom: 0.5rem;">3-Year OPT for STEM</span>
            <h3 class="destination-title">United States 🇺🇸</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">Global innovation leader offering 3-Year STEM OPT work authorization and generous institutional scholarships.</p>
            <div class="destination-stats">
              <div><strong>350+</strong> Institutions</div>
              <div><strong>3 Yrs</strong> STEM OPT</div>
              <div><strong>$22k-45k</strong> Avg Tuition</div>
            </div>
            <a href="programs.php?country=United%20States" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View USA Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- Australia -->
        <div class="destination-card">
          <img src="assets/canada_dest.png" alt="Study in Australia" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-success" style="width: fit-content; margin-bottom: 0.5rem;">High Lifestyle & Pay</span>
            <h3 class="destination-title">Australia 🇦🇺</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">Group of Eight universities, sunny lifestyle, and 2 to 4 years post-study work rights in regional areas.</p>
            <div class="destination-stats">
              <div><strong>40+</strong> Institutions</div>
              <div><strong>2-4 Yrs</strong> Work Permit</div>
              <div><strong>AUD 30k-45k</strong> Tuition</div>
            </div>
            <a href="programs.php?country=Australia" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View Australia Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- Germany -->
        <div class="destination-card">
          <img src="assets/uk_dest.png" alt="Study in Germany" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-primary" style="width: fit-content; margin-bottom: 0.5rem;">Zero Tuition Fees</span>
            <h3 class="destination-title">Germany 🇩🇪</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">Public universities offer tuition-free degree programs in English with an 18-month job seeker period post graduation.</p>
            <div class="destination-stats">
              <div><strong>60+</strong> Public Unis</div>
              <div><strong>18 Mos</strong> Job Seeker</div>
              <div><strong>€0 - €3k</strong> Low Tuition</div>
            </div>
            <a href="programs.php?country=Germany" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View Germany Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- Ireland -->
        <div class="destination-card">
          <img src="assets/hero_banner.png" alt="Study in Ireland" class="destination-img">
          <div class="destination-overlay">
            <span class="badge badge-success" style="width: fit-content; margin-bottom: 0.5rem;">European Tech Hub</span>
            <h3 class="destination-title">Ireland 🇮🇪</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">European headquarters for Google, Apple, and Meta offering 2-Year Stay Back option for Master's graduates.</p>
            <div class="destination-stats">
              <div><strong>25+</strong> Institutions</div>
              <div><strong>2 Yrs</strong> Stay Back</div>
              <div><strong>€12k-25k</strong> Avg Tuition</div>
            </div>
            <a href="programs.php?country=Ireland" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">View Ireland Programs <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- COMPARISON TABLE SECTION -->
  <section class="section-padding" style="background: var(--slate-100);">
    <div class="container">
      <div class="section-header">
        <span class="badge badge-primary">Quick Overview</span>
        <h2 style="font-size: 2.2rem; margin-top: 0.5rem;">At-a-Glance Destination Comparison</h2>
      </div>

      <div style="overflow-x: auto; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-md);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
          <thead>
            <tr style="background: var(--navy-900); color: var(--white);">
              <th style="padding: 1.25rem 1.5rem;">Destination</th>
              <th style="padding: 1.25rem 1.5rem;">Average Tuition / Year</th>
              <th style="padding: 1.25rem 1.5rem;">Living Expenses / Month</th>
              <th style="padding: 1.25rem 1.5rem;">Post-Study Work Permit</th>
              <th style="padding: 1.25rem 1.5rem;">Action</th>
            </tr>
          </thead>
          <tbody style="color: var(--navy-900);">
            <tr style="border-bottom: 1px solid var(--slate-200);">
              <td style="padding: 1.25rem 1.5rem; font-weight: 700;">Canada 🇨🇦</td>
              <td style="padding: 1.25rem 1.5rem;">CAD $20,000 - $35,000</td>
              <td style="padding: 1.25rem 1.5rem;">CAD $1,200 - $1,800</td>
              <td style="padding: 1.25rem 1.5rem;"><span class="badge badge-primary">Up to 3 Years (PGWP)</span></td>
              <td style="padding: 1.25rem 1.5rem;"><a href="programs.php?country=Canada" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Explore</a></td>
            </tr>
            <tr style="border-bottom: 1px solid var(--slate-200);">
              <td style="padding: 1.25rem 1.5rem; font-weight: 700;">United Kingdom 🇬🇧</td>
              <td style="padding: 1.25rem 1.5rem;">£14,000 - £28,000</td>
              <td style="padding: 1.25rem 1.5rem;">£1,100 - £1,500</td>
              <td style="padding: 1.25rem 1.5rem;"><span class="badge badge-success">2 Years Graduate Route</span></td>
              <td style="padding: 1.25rem 1.5rem;"><a href="programs.php?country=United%20Kingdom" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Explore</a></td>
            </tr>
            <tr style="border-bottom: 1px solid var(--slate-200);">
              <td style="padding: 1.25rem 1.5rem; font-weight: 700;">United States 🇺🇸</td>
              <td style="padding: 1.25rem 1.5rem;">$22,000 - $45,000</td>
              <td style="padding: 1.25rem 1.5rem;">$1,300 - $2,000</td>
              <td style="padding: 1.25rem 1.5rem;"><span class="badge badge-primary">1 to 3 Years (STEM OPT)</span></td>
              <td style="padding: 1.25rem 1.5rem;"><a href="programs.php?country=United%20States" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Explore</a></td>
            </tr>
            <tr style="border-bottom: 1px solid var(--slate-200);">
              <td style="padding: 1.25rem 1.5rem; font-weight: 700;">Australia 🇦🇺</td>
              <td style="padding: 1.25rem 1.5rem;">AUD $30,000 - $45,000</td>
              <td style="padding: 1.25rem 1.5rem;">AUD $1,400 - $2,000</td>
              <td style="padding: 1.25rem 1.5rem;"><span class="badge badge-success">2 to 4 Years Subclass 485</span></td>
              <td style="padding: 1.25rem 1.5rem;"><a href="programs.php?country=Australia" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Explore</a></td>
            </tr>
            <tr>
              <td style="padding: 1.25rem 1.5rem; font-weight: 700;">Germany 🇩🇪</td>
              <td style="padding: 1.25rem 1.5rem;">€0 - €3,000 (State Subsidized)</td>
              <td style="padding: 1.25rem 1.5rem;">€934 (Blocked Account)</td>
              <td style="padding: 1.25rem 1.5rem;"><span class="badge badge-primary">18 Months Job Seeker</span></td>
              <td style="padding: 1.25rem 1.5rem;"><a href="programs.php?country=Germany" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Explore</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>



<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

  <script src="script.js"></script>
</body>
</html>
