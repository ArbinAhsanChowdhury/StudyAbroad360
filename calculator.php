<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="StudyAbroad360 Cost Estimator - Calculate tuition fees, living costs, and eligible scholarships for Canada, UK, USA, Australia, and Germany.">
  <title>Cost & Scholarship Estimator | StudyAbroad360</title>
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom Design System Stylesheet -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

  <!-- INNER PAGE BANNER -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <span>Cost Estimator</span>
      </div>
      <h1 style="font-size: 2.75rem; color: var(--white); margin-bottom: 0.5rem;">Tuition & Living Cost Estimator</h1>
      <p style="color: var(--slate-300); font-size: 1.1rem; max-width: 650px;">
        Plan your budget with precision. Estimate total study abroad expenses and project potential entrance scholarship savings in real time.
      </p>
    </div>
  </section>

  <!-- CALCULATOR INTERACTIVE WIDGET -->
  <section class="section-padding">
    <div class="container">
      <div class="calc-card">
        <div>
          <h3 style="font-size: 1.6rem; margin-bottom: 1.5rem;">Customize Your Budget</h3>

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
            <label for="calcDegree">Select Academic Degree Level</label>
            <select id="calcDegree">
              <option value="Master">Master's Degree (1-2 Years)</option>
              <option value="Bachelor">Bachelor's Degree (3-4 Years)</option>
              <option value="Diploma">Diploma / Post-Grad Certificate</option>
            </select>
          </div>

          <div class="input-field">
            <label for="calcLiving">Living Expense Preference</label>
            <select id="calcLiving">
              <option value="Standard">Standard Student Shared Apartment ($1,000/mo)</option>
              <option value="Comfort">Comfort Private Campus Dorm ($1,400/mo)</option>
            </select>
          </div>
        </div>

        <div class="calc-result-box">
          <span class="badge badge-primary">Estimated Annual Expense</span>
          <div class="calc-amount" id="calcTotalAmount">$40,000</div>
          <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 1.25rem;">Includes estimated tuition fee + living expenses.</p>

          <div style="background: var(--white); padding: 1.25rem; border-radius: var(--radius-sm); width: 100%; border: 1px solid var(--slate-200);">
            <p style="font-size: 0.85rem; color: var(--slate-600);">Projected Entrance Scholarship:</p>
            <h4 style="font-size: 1.6rem; color: var(--secondary);" id="calcScholarshipAmount">$7,000</h4>
          </div>

          <button class="btn btn-primary trigger-eligibility-modal" style="margin-top: 1.5rem; width: 100%;">
            Apply for Scholarships <i class="fas fa-hand-holding-usd"></i>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- EXPENSE BREAKDOWN DETAILS -->
  <section class="section-padding" style="background: var(--slate-100);">
    <div class="container">
      <div class="section-header">
        <span class="badge badge-success">Financial Transparency</span>
        <h2 style="font-size: 2.2rem; margin-top: 0.5rem;">Sample Expense Breakdown</h2>
        <p class="section-subtitle">Average annual figures across popular study destinations.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
        <div style="background: var(--white); border-radius: var(--radius-md); padding: 2rem; border: 1px solid var(--slate-200);">
          <h3 style="font-size: 1.2rem; margin-bottom: 1rem;"><i class="fas fa-home" style="color: var(--primary);"></i> Accommodation & Utilities</h3>
          <p style="color: var(--slate-600); font-size: 0.9rem;">Shared student housing ranges from $600 to $1,200/month depending on city center proximity and utility inclusions (Wi-Fi, heating, electricity).</p>
        </div>

        <div style="background: var(--white); border-radius: var(--radius-md); padding: 2rem; border: 1px solid var(--slate-200);">
          <h3 style="font-size: 1.2rem; margin-bottom: 1rem;"><i class="fas fa-utensils" style="color: var(--secondary);"></i> Groceries & Food</h3>
          <p style="color: var(--slate-600); font-size: 0.9rem;">Cooking at home typically costs $250 - $400/month. Campus meal plans average around $350/month in the US and Canada.</p>
        </div>

        <div style="background: var(--white); border-radius: var(--radius-md); padding: 2rem; border: 1px solid var(--slate-200);">
          <h3 style="font-size: 1.2rem; margin-bottom: 1rem;"><i class="fas fa-bus" style="color: var(--accent-gold);"></i> Transport & Health Insurance</h3>
          <p style="color: var(--slate-600); font-size: 0.9rem;">Discounted student transit passes cost $60 - $120/month. Mandatory health insurance ranges from $600 to $1,200/year.</p>
        </div>
      </div>
    </div>
  </section>



<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

  <script src="script.js"></script>
</body>
</html>
