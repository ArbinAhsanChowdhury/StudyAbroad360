// Expanded Sample Datasets for StudyAbroad360
const programsData = [
  {
    id: 1,
    title: "M.Sc. in Data Analytics & Artificial Intelligence",
    university: "University of Toronto",
    country: "Canada",
    degree: "Master",
    field: "STEM",
    tuition: 32500,
    duration: "2 Years",
    intake: "Fall 2027",
    acceptance: "88%",
    scholarship: "Up to $10,000",
    badge: "Top Ranked",
    icon: "UT"
  },
  {
    id: 2,
    title: "B.Sc. in Computer Science & Cyber Security",
    university: "University of Manchester",
    country: "United Kingdom",
    degree: "Bachelor",
    field: "STEM",
    tuition: 26000,
    duration: "3 Years",
    intake: "Sept 2027",
    acceptance: "92%",
    scholarship: "Up to £5,000",
    badge: "Popular",
    icon: "UM"
  },
  {
    id: 3,
    title: "MBA in International Business Strategy",
    university: "Northeastern University",
    country: "United States",
    degree: "Master",
    field: "Business",
    tuition: 41000,
    duration: "1.5 Years",
    intake: "Spring 2027",
    acceptance: "85%",
    scholarship: "Up to $15,000",
    badge: "STEM Designated",
    icon: "NU"
  },
  {
    id: 4,
    title: "Master of Information Technology",
    university: "University of Melbourne",
    country: "Australia",
    degree: "Master",
    field: "STEM",
    tuition: 38000,
    duration: "2 Years",
    intake: "Feb 2027",
    acceptance: "90%",
    scholarship: "Up to AUD 12,000",
    badge: "Group of 8",
    icon: "UM"
  },
  {
    id: 5,
    title: "M.Sc. in Automotive Engineering & Robotics",
    university: "Technical University of Munich",
    country: "Germany",
    degree: "Master",
    field: "STEM",
    tuition: 3500,
    duration: "2 Years",
    intake: "Winter 2027",
    acceptance: "80%",
    scholarship: "DAAD Eligible",
    badge: "Low Tuition",
    icon: "TUM"
  },
  {
    id: 6,
    title: "B.A. in Digital Marketing & Brand Management",
    university: "Trinity College Dublin",
    country: "Ireland",
    degree: "Bachelor",
    field: "Business",
    tuition: 22000,
    duration: "4 Years",
    intake: "Sept 2027",
    acceptance: "94%",
    scholarship: "Up to €6,000",
    badge: "Fast Track Admissions",
    icon: "TCD"
  },
  {
    id: 7,
    title: "M.Eng. in Biomedical Engineering",
    university: "McGill University",
    country: "Canada",
    degree: "Master",
    field: "STEM",
    tuition: 29500,
    duration: "2 Years",
    intake: "Fall 2027",
    acceptance: "86%",
    scholarship: "Up to $8,000",
    badge: "Top 50 Global",
    icon: "MU"
  },
  {
    id: 8,
    title: "B.Sc. in Software Engineering",
    university: "University of Waterloo",
    country: "Canada",
    degree: "Bachelor",
    field: "STEM",
    tuition: 34000,
    duration: "4 Years",
    intake: "Fall 2027",
    acceptance: "89%",
    scholarship: "Co-op Paid Terms",
    badge: "Top Co-op",
    icon: "UW"
  },
  {
    id: 9,
    title: "M.Sc. in Finance & Investment Management",
    university: "Imperial College London",
    country: "United Kingdom",
    degree: "Master",
    field: "Business",
    tuition: 39000,
    duration: "1 Year",
    intake: "Sept 2027",
    acceptance: "82%",
    scholarship: "Up to £10,000",
    badge: "Global Elite",
    icon: "IC"
  },
  {
    id: 10,
    title: "Master of Public Health (MPH)",
    university: "Johns Hopkins University",
    country: "United States",
    degree: "Master",
    field: "Health",
    tuition: 45000,
    duration: "2 Years",
    intake: "Fall 2027",
    acceptance: "78%",
    scholarship: "Up to $20,000",
    badge: "#1 Public Health",
    icon: "JHU"
  },
  {
    id: 11,
    title: "Bachelor of Business Administration (BBA)",
    university: "University of Sydney",
    country: "Australia",
    degree: "Bachelor",
    field: "Business",
    tuition: 35000,
    duration: "3 Years",
    intake: "July 2027",
    acceptance: "91%",
    scholarship: "Up to AUD 10,000",
    badge: "Group of 8",
    icon: "USYD"
  },
  {
    id: 12,
    title: "M.Sc. in Renewable Energy Systems",
    university: "RWTH Aachen University",
    country: "Germany",
    degree: "Master",
    field: "STEM",
    tuition: 2800,
    duration: "2 Years",
    intake: "Winter 2027",
    acceptance: "85%",
    scholarship: "State Funded",
    badge: "Tuition Free",
    icon: "RWTH"
  }
];

// Sample Institutions Dataset
const institutionsData = [
  { name: "University of Toronto", country: "Canada", flag: "🇨🇦", type: "Public University", ranking: "#21 Global", programsCount: 240, location: "Toronto, Ontario", icon: "UT" },
  { name: "University of Manchester", country: "United Kingdom", flag: "🇬🇧", type: "Public Research Univ", ranking: "#32 Global", programsCount: 180, location: "Manchester, UK", icon: "UM" },
  { name: "Northeastern University", country: "United States", flag: "🇺🇸", type: "Private Research Univ", ranking: "#44 US News", programsCount: 195, location: "Boston, Massachusetts", icon: "NU" },
  { name: "University of Melbourne", country: "Australia", flag: "🇦🇺", type: "Go8 University", ranking: "#14 Global", programsCount: 210, location: "Melbourne, Victoria", icon: "UM" },
  { name: "Technical University of Munich", country: "Germany", flag: "🇩🇪", type: "Excellence University", ranking: "#28 Global", programsCount: 150, location: "Munich, Bavaria", icon: "TUM" },
  { name: "Trinity College Dublin", country: "Ireland", flag: "🇮🇪", type: "Historic University", ranking: "#81 Global", programsCount: 130, location: "Dublin, Ireland", icon: "TCD" },
  { name: "McGill University", country: "Canada", flag: "🇨🇦", type: "Public Research Univ", ranking: "#30 Global", programsCount: 220, location: "Montreal, Quebec", icon: "MU" },
  { name: "University of Waterloo", country: "Canada", flag: "🇨🇦", type: "Public University", ranking: "#112 Global", programsCount: 160, location: "Waterloo, Ontario", icon: "UW" },
  { name: "Imperial College London", country: "United Kingdom", flag: "🇬🇧", type: "STEM Elite", ranking: "#6 Global", programsCount: 140, location: "London, UK", icon: "IC" },
  { name: "Johns Hopkins University", country: "United States", flag: "🇺🇸", type: "Private Research Univ", ranking: "#9 US News", programsCount: 175, location: "Baltimore, Maryland", icon: "JHU" },
  { name: "University of Sydney", country: "Australia", flag: "🇦🇺", type: "Go8 University", ranking: "#19 Global", programsCount: 205, location: "Sydney, NSW", icon: "USYD" },
  { name: "RWTH Aachen University", country: "Germany", flag: "🇩🇪", type: "Technical University", ranking: "#99 Global", programsCount: 125, location: "Aachen, Germany", icon: "RWTH" }
];

// Wait for DOM load
document.addEventListener("DOMContentLoaded", () => {
  initThemeToggle();
  setActiveNav();
  initProgramSearch();
  initEligibilityWizard();
  initCalculator();
  initPersonaTabs();
  initModals();
  initMobileMenu();
  initInstitutionsDirectory();
  initNewsletterSubscription();
});

/* ==========================================================================
   THEME TOGGLE ENGINE (DARK & LIGHT MODE)
   ========================================================================== */
function initThemeToggle() {
  const toggleBtn = document.getElementById("themeToggleBtn");
  const savedTheme = localStorage.getItem("theme") || "light";

  // Apply saved theme on page load immediately
  if (savedTheme === "dark") {
    document.documentElement.setAttribute("data-theme", "dark");
    if (toggleBtn) {
      toggleBtn.innerHTML = '<i class="fas fa-moon" style="color: #fde047;"></i>';
      toggleBtn.setAttribute("title", "Switch to Light Mode");
    }
  } else {
    document.documentElement.setAttribute("data-theme", "light");
    if (toggleBtn) {
      toggleBtn.innerHTML = '<i class="fas fa-sun" style="color: #f59e0b;"></i>';
      toggleBtn.setAttribute("title", "Switch to Dark Mode");
    }
  }

  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      const currentTheme = document.documentElement.getAttribute("data-theme");
      const nextTheme = (currentTheme === "dark") ? "light" : "dark";

      document.documentElement.setAttribute("data-theme", nextTheme);
      localStorage.setItem("theme", nextTheme);

      if (nextTheme === "dark") {
        toggleBtn.innerHTML = '<i class="fas fa-moon" style="color: #fde047;"></i>';
        toggleBtn.setAttribute("title", "Switch to Light Mode");
        if (typeof showToast === 'function') showToast('Dark Mode Activated 🌙', 'info');
      } else {
        toggleBtn.innerHTML = '<i class="fas fa-sun" style="color: #f59e0b;"></i>';
        toggleBtn.setAttribute("title", "Switch to Dark Mode");
        if (typeof showToast === 'function') showToast('Light Mode Activated ☀️', 'info');
      }
    });
  }
}

/* ==========================================================================
   PROFESSIONAL NEWSLETTER SUBSCRIPTION ENGINE
   ========================================================================== */
function initNewsletterSubscription() {
  const forms = document.querySelectorAll(".newsletter-form");
  forms.forEach(form => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const emailInput = form.querySelector("input[type='email']");
      const email = emailInput ? emailInput.value.trim() : "";
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email || !emailRegex.test(email)) {
        alert("⚠️ Please enter a valid email address to subscribe.");
        if (emailInput) emailInput.focus();
        return;
      }
      
      const feedbackEl = form.querySelector(".newsletter-feedback");
      if (feedbackEl) {
        feedbackEl.style.display = "block";
        feedbackEl.innerHTML = `<i class="fas fa-check-circle" style="color: var(--secondary);"></i> Thank you! <strong>${email}</strong> is now subscribed to StudyAbroad360 Digest.`;
      } else {
        alert(`🎉 Thank you! ${email} has been successfully subscribed to StudyAbroad360 Scholarship Alerts.`);
      }
      
      if (emailInput) emailInput.value = "";
    });
  });
}

// Active nav highlight based on window location
function setActiveNav() {
  const currentPath = window.location.pathname.split("/").pop() || "index.php";
  const navLinks = document.querySelectorAll(".nav-menu .nav-link, .footer-links a");
  
  navLinks.forEach(link => {
    const href = link.getAttribute("href");
    if (href === currentPath || (currentPath === "" && href === "index.php") || (currentPath === "main.php" && href === "index.php") || (currentPath === "index.html" && href === "index.php")) {
      link.classList.add("active-page");
    }
  });
}

/* ==========================================================================
   PROGRAM SEARCH & FILTER ENGINE
   ========================================================================== */
function initProgramSearch() {
  const container = document.getElementById("programGridContainer");
  const filterTags = document.querySelectorAll(".tag-btn");
  const searchInput = document.getElementById("programSearchInput");
  const countrySelect = document.getElementById("heroCountrySelect");
  const degreeSelect = document.getElementById("heroDegreeSelect");
  const searchForm = document.getElementById("heroFastSearchForm");

  let activeProgramsData = Array.isArray(programsData) ? [...programsData] : [];

  // Read URL query params if redirected from home search form
  const urlParams = new URLSearchParams(window.location.search);
  let currentTagFilter = urlParams.get("field") || "All";
  let currentCountryFilter = urlParams.get("country") || "All";
  let currentDegreeFilter = urlParams.get("degree") || "All";
  let currentSearchQuery = urlParams.get("q") || "";

  // Update input elements if query params present
  if (searchInput && currentSearchQuery) searchInput.value = currentSearchQuery;
  if (countrySelect && currentCountryFilter !== "All") countrySelect.value = currentCountryFilter;
  if (degreeSelect && currentDegreeFilter !== "All") degreeSelect.value = currentDegreeFilter;

  function normalizeStr(str) {
    if (!str) return "";
    return str.toLowerCase().replace(/[^a-z0-9]/g, "");
  }

  function renderPrograms() {
    if (!container) return;

    const filtered = activeProgramsData.filter(p => {
      const pTitleNorm   = normalizeStr(p.title);
      const pUniNorm     = normalizeStr(p.university);
      const pDegreeNorm  = normalizeStr(p.degree);
      const pFieldNorm   = normalizeStr(p.field);
      const pCountryNorm = normalizeStr(p.country);

      const qNorm = normalizeStr(currentSearchQuery);

      // 1. Tag Filter
      let matchTag = (currentTagFilter === "All");
      if (!matchTag) {
        const tNorm = normalizeStr(currentTagFilter);
        matchTag = pFieldNorm.includes(tNorm) || pDegreeNorm.includes(tNorm) || pTitleNorm.includes(tNorm);
        if (tNorm === "master" && (pDegreeNorm.includes("master") || pTitleNorm.includes("msc") || pTitleNorm.includes("mba"))) matchTag = true;
        if (tNorm === "bachelor" && (pDegreeNorm.includes("bachelor") || pTitleNorm.includes("bsc") || pTitleNorm.includes("ba"))) matchTag = true;
        if (tNorm === "phd" && (pDegreeNorm.includes("phd") || pDegreeNorm.includes("doctorate") || pTitleNorm.includes("phd"))) matchTag = true;
      }

      // 2. Country Filter
      const matchCountry = (currentCountryFilter === "All") || (pCountryNorm === normalizeStr(currentCountryFilter));

      // 3. Degree Level Select Filter
      let matchDegree = (currentDegreeFilter === "All");
      if (!matchDegree) {
        const dNorm = normalizeStr(currentDegreeFilter);
        if (dNorm === "master" && (pDegreeNorm.includes("master") || pTitleNorm.includes("msc") || pTitleNorm.includes("mba"))) matchDegree = true;
        else if (dNorm === "bachelor" && (pDegreeNorm.includes("bachelor") || pTitleNorm.includes("bsc") || pTitleNorm.includes("ba"))) matchDegree = true;
        else if (dNorm === "phd" && (pDegreeNorm.includes("phd") || pDegreeNorm.includes("doctorate") || pTitleNorm.includes("phd"))) matchDegree = true;
        else if (pDegreeNorm.includes(dNorm)) matchDegree = true;
      }

      // 4. Text Query Search Filter (supporting "bsc", "msc", "phd", "bachelor", "master", university names like "brac")
      let matchQuery = (qNorm === "");
      if (!matchQuery) {
        if (qNorm === "bsc" && (pTitleNorm.includes("bsc") || pDegreeNorm.includes("bachelor"))) matchQuery = true;
        else if (qNorm === "msc" && (pTitleNorm.includes("msc") || pDegreeNorm.includes("master"))) matchQuery = true;
        else if (qNorm === "phd" && (pTitleNorm.includes("phd") || pDegreeNorm.includes("doctorate") || pDegreeNorm.includes("phd"))) matchQuery = true;
        else if (qNorm === "bachelor" && (pDegreeNorm.includes("bachelor") || pTitleNorm.includes("bsc"))) matchQuery = true;
        else if (qNorm === "master" && (pDegreeNorm.includes("master") || pTitleNorm.includes("msc") || pTitleNorm.includes("mba"))) matchQuery = true;
        else if (pTitleNorm.includes(qNorm) || pUniNorm.includes(qNorm) || pDegreeNorm.includes(qNorm) || pCountryNorm.includes(qNorm)) matchQuery = true;
      }

      return matchTag && matchCountry && matchDegree && matchQuery;
    });

    if (filtered.length === 0) {
      container.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem; background: var(--white); border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
          <i class="fas fa-search" style="font-size: 2.5rem; color: var(--slate-400); margin-bottom: 1rem;"></i>
          <h3>No matching programs found</h3>
          <p style="color: var(--slate-500); margin-top: 0.5rem;">Try adjusting your search criteria or clearing filters.</p>
          <button class="btn btn-outline" style="margin-top: 1.5rem;" onclick="resetAllFilters()">Reset All Filters</button>
        </div>
      `;
      return;
    }

    container.innerHTML = filtered.map(p => `
      <div class="program-card">
        <div>
          <div class="program-card-header">
            <div class="university-meta">
              <div class="university-icon-box">${p.icon}</div>
              <div>
                <h4 style="font-size: 0.95rem; font-weight: 700;">${p.university}</h4>
                <p style="font-size: 0.8rem; color: var(--slate-500);"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> ${p.country}</p>
              </div>
            </div>
            <span class="badge badge-primary">${p.badge}</span>
          </div>

          <h3 class="program-title">${p.title}</h3>

          <ul class="program-details-list">
            <li><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> Degree: <strong>${p.degree} Degree</strong></li>
            <li><i class="fas fa-clock" style="color: var(--secondary);"></i> Duration: <strong>${p.duration}</strong></li>
            <li><i class="fas fa-calendar-alt" style="color: var(--accent-gold);"></i> Next Intake: <strong>${p.intake}</strong></li>
            <li><i class="fas fa-gift" style="color: var(--accent-rose);"></i> Scholarship: <strong>${p.scholarship}</strong></li>
            ${p.document_requirement ? `<li><i class="fas fa-file-alt" style="color: var(--primary);"></i> Docs Required: <strong style="font-size: 0.78rem;">${p.document_requirement}</strong></li>` : ''}
          </ul>
        </div>

        <div class="program-footer">
          <div class="tuition-tag">
            $${Number(p.tuition).toLocaleString()} <span>/ year</span>
          </div>
          <div style="display: flex; gap: 0.4rem;">
            <button class="btn btn-outline bookmark-btn-${p.id}" style="padding: 0.45rem 0.75rem; font-size: 0.825rem;" onclick="toggleProgramBookmark(${p.id}, this)" title="Save / Bookmark Program">
              <i class="far fa-bookmark"></i>
            </button>
            <button class="btn btn-primary" onclick="openApplyModal('${p.title.replace(/'/g, "\\'")}', '${p.university.replace(/'/g, "\\'")}')">
              Apply Now <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        </div>
      </div>
    `).join("");
  }

  // Universal Toast Notification Fallback
  if (typeof window.showToast !== 'function') {
    window.showToast = function(message, type = 'info') {
      let container = document.getElementById('globalToastContainer');
      if (!container) {
        container = document.createElement('div');
        container.id = 'globalToastContainer';
        container.style.cssText = 'position:fixed;top:1.5rem;right:1.5rem;z-index:999999;display:flex;flex-direction:column;gap:0.75rem;pointer-events:none;';
        document.body.appendChild(container);
      }
      const toast = document.createElement('div');
      const bg = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#0284c7';
      const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';
      toast.style.cssText = `background:#0f172a;color:#fff;border-left:4px solid ${bg};padding:0.85rem 1.25rem;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.35);display:flex;align-items:center;gap:0.75rem;font-size:0.9rem;font-weight:500;pointer-events:auto;transition:all 0.3s ease;`;
      toast.innerHTML = `<i class="fas ${icon}" style="color:${bg};font-size:1.1rem;"></i> <span>${message}</span>`;
      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    };
  }

  window.toggleProgramBookmark = function(programId, btnElement) {
    const btn = btnElement || document.querySelector(`.bookmark-btn-${programId}`) || (event && event.target ? event.target.closest('button') : null);
    const formData = new FormData();
    formData.append("university_program_id", programId);

    fetch("api/toggle_bookmark.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.require_login) {
        showToast("⚠️ " + data.message, "error");
        setTimeout(() => {
          window.location.href = "login.php?msg=please_login";
        }, 1200);
      } else if (data.status === 'success') {
        showToast("🌟 " + data.message, "success");
        if (btn) {
          btn.innerHTML = '<i class="fas fa-bookmark" style="color: #e11d48;"></i>';
          btn.style.borderColor = '#e11d48';
        }
        if (window.location.pathname.includes("student_dashboard.php") || window.location.pathname.includes("admin_dashboard.php")) {
          setTimeout(() => window.location.reload(), 650);
        }
      } else if (data.status === 'removed') {
        showToast(data.message, "info");
        if (btn) {
          btn.innerHTML = '<i class="far fa-bookmark"></i>';
          btn.style.borderColor = '';
        }
        if (window.location.pathname.includes("student_dashboard.php") || window.location.pathname.includes("admin_dashboard.php")) {
          setTimeout(() => window.location.reload(), 650);
        }
      } else {
        showToast(data.message || 'Bookmark action updated', "info");
      }
    })
    .catch(err => {
      showToast("⚠️ Error updating bookmark.", "error");
    });
  };

  // Fetch live programs from MySQL Database API
  fetch("api/get_programs.php")
    .then(res => res.json())
    .then(data => {
      if (data.success && Array.isArray(data.programs) && data.programs.length > 0) {
        activeProgramsData = data.programs;
        renderPrograms();
      }
    })
    .catch(err => {
      console.warn("Could not fetch API programs, using fallback:", err);
    });

  // Event Listeners for Tags
  filterTags.forEach(tag => {
    tag.addEventListener("click", () => {
      filterTags.forEach(t => t.classList.remove("active"));
      tag.classList.add("active");
      currentTagFilter = tag.getAttribute("data-filter") || "All";
      renderPrograms();
    });
  });

  // Fast Search Form Submit
  if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
      const isProgramsPage = window.location.pathname.includes("programs.php") || window.location.pathname.includes("programs.html");
      const cVal = countrySelect ? countrySelect.value : "All";
      const dVal = degreeSelect ? degreeSelect.value : "All";
      const qVal = searchInput ? searchInput.value : currentSearchQuery;

      if (!isProgramsPage) {
        e.preventDefault();
        window.location.href = `programs.php?country=${encodeURIComponent(cVal)}&degree=${encodeURIComponent(dVal)}&q=${encodeURIComponent(qVal)}`;
        return;
      }

      e.preventDefault();
      currentCountryFilter = cVal;
      currentDegreeFilter = dVal;
      currentSearchQuery = qVal;
      renderPrograms();

      const section = document.getElementById("programs") || container;
      if (section) section.scrollIntoView({ behavior: "smooth" });
    });
  }

  window.resetAllFilters = function() {
    currentTagFilter = "All";
    currentCountryFilter = "All";
    currentDegreeFilter = "All";
    currentSearchQuery = "";
    if (searchInput) searchInput.value = "";
    if (countrySelect) countrySelect.value = "All";
    if (degreeSelect) degreeSelect.value = "All";
    filterTags.forEach(t => t.classList.remove("active"));
    if (filterTags[0]) filterTags[0].classList.add("active");
    renderPrograms();
  };

  // Initial render
  renderPrograms();
}

/* ==========================================================================
   INSTITUTIONS DIRECTORY
   ========================================================================== */
function initInstitutionsDirectory() {
  const container = document.getElementById("institutionsGridContainer");
  if (!container) return;

  // Prevent overwriting live PHP database records on institutions.php
  if (container.dataset.phpDriven === "true" || window.location.pathname.includes("institutions.php")) {
    return;
  }

  const searchInput = document.getElementById("institutionSearchInput");
  const countrySelect = document.getElementById("institutionCountrySelect");

  function renderInstitutions() {
    const q = searchInput ? searchInput.value.toLowerCase() : "";
    const country = countrySelect ? countrySelect.value : "All";

    const filtered = institutionsData.filter(inst => {
      const matchCountry = country === "All" || inst.country.toLowerCase() === country.toLowerCase();
      const matchQuery = q === "" || inst.name.toLowerCase().includes(q) || inst.location.toLowerCase().includes(q);
      return matchCountry && matchQuery;
    });

    if (filtered.length === 0) {
      container.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: var(--white); border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
          <i class="fas fa-university" style="font-size: 2.5rem; color: var(--slate-400); margin-bottom: 1rem;"></i>
          <h3>No institutions found</h3>
          <p style="color: var(--slate-500);">Try searching for another university or selecting all countries.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = filtered.map(inst => `
      <div class="institution-card">
        <div class="institution-card-header">
          <div class="university-icon-box" style="width: 50px; height: 50px; font-size: 1.1rem;">${inst.icon}</div>
          <div>
            <h3 style="font-size: 1.1rem; color: var(--navy-900);">${inst.name}</h3>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 2px;">
              <span>${inst.flag} ${inst.country}</span> • <span>${inst.location}</span>
            </p>
          </div>
        </div>
        <div class="institution-body">
          <div class="inst-pill"><i class="fas fa-award" style="color: var(--accent-gold);"></i> ${inst.ranking}</div>
          <div class="inst-pill"><i class="fas fa-book-open" style="color: var(--primary);"></i> ${inst.programsCount}+ Programs</div>
          <div class="inst-pill"><i class="fas fa-building" style="color: var(--secondary);"></i> ${inst.type}</div>
        </div>
        <div style="margin-top: 1.25rem; display: flex; gap: 0.5rem;">
          <a href="programs.php?q=${encodeURIComponent(inst.name)}" class="btn btn-primary" style="flex: 1; text-align: center; font-size: 0.85rem;">
            View Programs <i class="fas fa-arrow-right"></i>
          </a>
          <button class="btn btn-outline trigger-eligibility-modal" style="font-size: 0.85rem;">
            Check Eligibility
          </button>
        </div>
      </div>
    `).join("");
  }

  if (searchInput) searchInput.addEventListener("input", renderInstitutions);
  if (countrySelect) countrySelect.addEventListener("change", renderInstitutions);

  renderInstitutions();
}

/* ==========================================================================
   AI ELIGIBILITY WIZARD MODAL
   ========================================================================== */
let currentWizardStep = 1;

function initEligibilityWizard() {
  const modal = document.getElementById("eligibilityModal");
  if (!modal) return;

  const btnOpen = document.querySelectorAll(".trigger-eligibility-modal");
  const btnClose = modal.querySelector(".modal-close");
  const stepIndicators = modal.querySelectorAll(".step-indicator");
  const stepPanels = modal.querySelectorAll(".wizard-step-panel");

  btnOpen.forEach(b => b.addEventListener("click", () => {
    modal.classList.add("active");
    setWizardStep(1);
  }));

  if (btnClose) {
    btnClose.addEventListener("click", () => {
      modal.classList.remove("active");
    });
  }

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.classList.remove("active");
  });

  window.nextWizardStep = function(step) {
    setWizardStep(step);
  };

  window.prevWizardStep = function(step) {
    setWizardStep(step);
  };

  function setWizardStep(step) {
    currentWizardStep = step;
    stepIndicators.forEach((ind, i) => {
      if (i < step) ind.classList.add("active");
      else ind.classList.remove("active");
    });

    stepPanels.forEach((panel, i) => {
      if (i === (step - 1)) panel.classList.add("active");
      else panel.classList.remove("active");
    });

    if (step === 3) {
      calculateEligibilityResult();
    }
  }

  function calculateEligibilityResult() {
    const gpa = parseFloat(document.getElementById("wizGpa")?.value || "3.5");
    const ielts = parseFloat(document.getElementById("wizIelts")?.value || "7.0");

    const matchPercent = Math.min(98, Math.max(70, Math.round((gpa / 4.0) * 50 + (ielts / 9.0) * 45)));
    
    const resultBox = document.getElementById("wizResultBox");
    if (resultBox) {
      resultBox.innerHTML = `
        <div style="text-align: center; padding: 1rem 0;">
          <div style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; margin: 0 auto 1.25rem auto; box-shadow: var(--shadow-glow);">
            ${matchPercent}%
          </div>
          <h3 style="font-size: 1.5rem; color: var(--navy-900);">High Admission Eligibility!</h3>
          <p style="color: var(--slate-600); margin-top: 0.5rem; max-width: 450px; margin-left: auto; margin-right: auto;">
            Based on your academic profile, you qualify for <strong>140+ top programs</strong> in Canada, USA, and UK with scholarship options up to <strong>$12,000/year</strong>.
          </p>

          <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; margin: 1.5rem 0; text-align: left;">
            <p style="font-size: 0.85rem; font-weight: 700; color: var(--navy-900); margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--secondary);"></i> Recommended Next Actions:</p>
            <ul style="font-size: 0.85rem; color: var(--slate-600); padding-left: 1.25rem;">
              <li>Get assigned a dedicated StudyAbroad360 Senior Advisor</li>
              <li>Receive 1-on-1 Document Review (SOP & LOR validation)</li>
              <li>Apply to 3 Universities with 1 Single Application</li>
            </ul>
          </div>

          <button class="btn btn-primary btn-lg" style="width: 100%;" onclick="finishEligibilityWizard()">
            Claim Free Advisor Call & Match List <i class="fas fa-phone-alt"></i>
          </button>
        </div>
      `;
    }
  }

  window.finishEligibilityWizard = function() {
    alert("🎉 Congratulations! Your profile has been submitted. A StudyAbroad360 advisor will contact you within 2 hours.");
    modal.classList.remove("active");
  };
}

/* ==========================================================================
   SCHOLARSHIP & COST CALCULATOR
   ========================================================================== */
function initCalculator() {
  const countryInput = document.getElementById("calcCountry");
  const degreeInput = document.getElementById("calcDegree");
  const livingInput = document.getElementById("calcLiving");
  const displayTotal = document.getElementById("calcTotalAmount");
  const displayScholarship = document.getElementById("calcScholarshipAmount");

  if (!countryInput || !degreeInput || !displayTotal) return;

  const baseCosts = {
    Canada: { Master: 28000, Bachelor: 24000, Diploma: 16000 },
    UK: { Master: 26000, Bachelor: 22000, Diploma: 15000 },
    USA: { Master: 38000, Bachelor: 32000, Diploma: 20000 },
    Australia: { Master: 34000, Bachelor: 29000, Diploma: 18000 },
    Germany: { Master: 3000, Bachelor: 2500, Diploma: 1500 }
  };

  function updateCalc() {
    const country = countryInput.value;
    const degree = degreeInput.value;
    const livingTier = livingInput ? livingInput.value : "Standard";
    
    const base = (baseCosts[country] && baseCosts[country][degree]) ? baseCosts[country][degree] : 25000;
    const living = livingTier === "Comfort" ? 16800 : 12000;
    const totalEst = base + living;
    const scholarEst = Math.round(base * 0.25);

    displayTotal.textContent = `$${totalEst.toLocaleString()}`;
    if (displayScholarship) displayScholarship.textContent = `$${scholarEst.toLocaleString()}`;
  }

  countryInput.addEventListener("change", updateCalc);
  degreeInput.addEventListener("change", updateCalc);
  if (livingInput) livingInput.addEventListener("change", updateCalc);
  updateCalc();
}

/* ==========================================================================
   AUDIENCE PERSONA TABS
   ========================================================================== */
function initPersonaTabs() {
  const tabs = document.querySelectorAll(".persona-tab");
  const panels = document.querySelectorAll(".persona-panel");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      const target = tab.getAttribute("data-target");

      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");

      panels.forEach(p => {
        if (p.id === target) p.style.display = "grid";
        else p.style.display = "none";
      });
    });
  });
}

/* ==========================================================================
   MODAL & DIALOG SYSTEM
   ========================================================================== */
function initModals() {
  window.openApplyModal = function(programTitle, universityName) {
    const modal = document.getElementById("applyModal");
    if (!modal) return;

    const titleEl = document.getElementById("applyModalProgramTitle");
    const uniEl = document.getElementById("applyModalUniTitle");
    const hiddenProg = document.getElementById("applyModalHiddenProgram");
    const hiddenUni = document.getElementById("applyModalHiddenUni");

    if (titleEl) titleEl.textContent = programTitle;
    if (uniEl) uniEl.textContent = universityName;
    if (hiddenProg) hiddenProg.value = programTitle;
    if (hiddenUni) hiddenUni.value = universityName;

    modal.classList.add("active");
  };

  window.closeApplyModal = function() {
    const modal = document.getElementById("applyModal");
    if (modal) modal.classList.remove("active");
  };

  window.openAddUniversityModal = function() {
    const modal = document.getElementById("addUniversityModal");
    if (modal) modal.classList.add("active");
  };

  window.closeAddUniversityModal = function() {
    const modal = document.getElementById("addUniversityModal");
    if (modal) modal.classList.remove("active");
  };

  const applyModal = document.getElementById("applyModal");
  if (applyModal) {
    applyModal.addEventListener("click", (e) => {
      if (e.target === applyModal) applyModal.classList.remove("active");
    });
  }

  const applyForm = document.getElementById("applyModalForm");
  if (applyForm) {
    applyForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(applyForm);
      const feedback = document.getElementById("applyModalFeedback");

      fetch("submit_application.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (feedback) {
          feedback.style.display = "block";
          if (data.success) {
            feedback.style.background = "rgba(13, 148, 136, 0.2)";
            feedback.style.color = "var(--secondary)";
            feedback.style.border = "1px solid var(--secondary)";
            feedback.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
            setTimeout(() => {
              closeApplyModal();
              feedback.style.display = "none";
              window.location.href = "student_dashboard.php";
            }, 1500);
          } else {
            feedback.style.background = "rgba(225, 29, 72, 0.2)";
            feedback.style.color = "var(--accent-rose)";
            feedback.style.border = "1px solid var(--accent-rose)";
            feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
          }
        } else {
          alert(data.message);
          closeApplyModal();
        }
      })
      .catch(err => {
        alert("Application submitted! Thank you.");
        closeApplyModal();
      });
    });
  }
}

/* ==========================================================================
   MOBILE MENU TOGGLE
   ========================================================================== */
function initMobileMenu() {
  const toggleBtn = document.getElementById("mobileToggleBtn");
  const navMenu = document.getElementById("headerNavMenu");

  if (toggleBtn && navMenu) {
    toggleBtn.addEventListener("click", () => {
      const isVisible = navMenu.style.display === "flex";
      navMenu.style.display = isVisible ? "none" : "flex";
      navMenu.style.flexDirection = "column";
      navMenu.style.position = "absolute";
      navMenu.style.top = "80px";
      navMenu.style.left = "0";
      navMenu.style.right = "0";
      navMenu.style.background = "var(--white)";
      navMenu.style.padding = "1.5rem";
      navMenu.style.boxShadow = "var(--shadow-lg)";
      navMenu.style.zIndex = "1000";
    });
  }
}

/* ==========================================================================
   INTERACTIVE WIDGET HANDLERS (SCROLL, CHAT, CURRENCY, THEME)
   ========================================================================== */

// 1. Scroll Progress & Back-to-Top Button
function initScrollWidgets() {
  const progressBar = document.getElementById("scrollProgressBar");
  const backToTopBtn = document.getElementById("backToTopBtn");

  window.addEventListener("scroll", () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

    if (progressBar) {
      progressBar.style.width = `${scrollPercent}%`;
    }

    if (backToTopBtn) {
      if (scrollTop > 300) {
        backToTopBtn.classList.add("visible");
      } else {
        backToTopBtn.classList.remove("visible");
      }
    }
  });

  if (backToTopBtn) {
    backToTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
}

// 2. AI Student Assistant Floating Chatbot Widget
function initAIChatWidget() {
  const chatWidget = document.getElementById("aiChatWidget");
  const chatBtn = document.getElementById("chatTriggerBtn");
  const chatWindow = document.getElementById("chatWindow");
  const closeBtn = document.getElementById("chatCloseBtn");
  const chatInput = document.getElementById("chatInput");
  const sendBtn = document.getElementById("chatSendBtn");
  const chatBody = document.getElementById("chatBody");

  if (!chatBtn || !chatWindow) return;

  chatBtn.addEventListener("click", () => {
    chatWindow.classList.toggle("open");
  });

  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      chatWindow.classList.remove("open");
    });
  }

  function appendMsg(text, isUser = false) {
    if (!chatBody) return;
    const msgDiv = document.createElement("div");
    msgDiv.className = `chat-msg ${isUser ? "user" : "bot"}`;
    msgDiv.textContent = text;
    chatBody.appendChild(msgDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function handleUserMessage(userText) {
    if (!userText.trim()) return;
    appendMsg(userText, true);

    if (chatInput) chatInput.value = "";

    // Simulated AI response
    setTimeout(() => {
      const lower = userText.toLowerCase();
      let reply = "I can help you explore programs, check admission eligibility, and estimate tuition fees across Canada, UK, USA, Australia, and Germany! Try using our Cost Estimator or AI Matcher.";
      
      if (lower.includes("canada") || lower.includes("pgwp")) {
        reply = "Canada is a top destination! You can apply to programs with up to 3-Year PGWP work permits. Try filtering programs by Canada on the Find Programs page.";
      } else if (lower.includes("scholarship") || lower.includes("cost") || lower.includes("fee")) {
        reply = "We offer up to $15,000 in university scholarship matches! Use our Cost Estimator page to project your total living and tuition expenses.";
      } else if (lower.includes("uk") || lower.includes("master")) {
        reply = "The UK offers 1-Year Master's degrees with 2-Year Graduate Work routes. Would you like to check admission requirements for UK universities?";
      }

      appendMsg(reply, false);
    }, 600);
  }

  if (sendBtn && chatInput) {
    sendBtn.addEventListener("click", () => handleUserMessage(chatInput.value));
    chatInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") handleUserMessage(chatInput.value);
    });
  }

  // Predefined quick prompt chips
  document.querySelectorAll(".quick-prompt-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      handleUserMessage(btn.textContent.trim());
    });
  });
}

// 3. Live Currency Switcher
function initCurrencySwitcher() {
  const currencySelect = document.getElementById("currencySelect");
  if (!currencySelect) return;

  const rates = {
    USD: { rate: 1.0, symbol: "$" },
    CAD: { rate: 1.35, symbol: "CAD $" },
    GBP: { rate: 0.79, symbol: "£" },
    EUR: { rate: 0.92, symbol: "€" },
    AUD: { rate: 1.52, symbol: "AUD $" }
  };

  currencySelect.addEventListener("change", (e) => {
    const selected = e.target.value;
    const curr = rates[selected] || rates["USD"];

    // Update all elements with data-currency-usd attribute
    document.querySelectorAll("[data-currency-usd]").forEach((el) => {
      const baseUsd = parseFloat(el.getAttribute("data-currency-usd"));
      if (!isNaN(baseUsd)) {
        const converted = Math.round(baseUsd * curr.rate);
        el.textContent = `${curr.symbol}${converted.toLocaleString()}`;
      }
    });
  });
}

// 4. Theme Switcher (Dark/Light)
function initThemeToggle() {
  const toggleBtn = document.getElementById("themeToggleBtn");
  if (!toggleBtn) return;

  const savedTheme = localStorage.getItem("sa360_theme");
  if (savedTheme === "light") {
    document.body.classList.add("light-theme");
    toggleBtn.innerHTML = `<i class="fas fa-moon"></i>`;
  }

  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("light-theme");
    const isLight = document.body.classList.contains("light-theme");
    localStorage.setItem("sa360_theme", isLight ? "light" : "dark");
    toggleBtn.innerHTML = isLight ? `<i class="fas fa-moon"></i>` : `<i class="fas fa-sun"></i>`;
  });
}

// Global Startup Initializer
document.addEventListener("DOMContentLoaded", () => {
  initScrollWidgets();
  initAIChatWidget();
  initCurrencySwitcher();
  initThemeToggle();
});


