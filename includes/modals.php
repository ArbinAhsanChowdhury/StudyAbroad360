<?php require_once __DIR__ . '/auth.php'; ?>
<!-- MODALS -->
<div class="modal-overlay" id="eligibilityModal">
  <div class="modal-content">
    <button class="modal-close" aria-label="Close modal">&times;</button>
    <div style="margin-bottom: 1.5rem;">
      <span class="badge badge-primary">AI Program Matcher</span>
      <h3 style="font-size: 1.5rem; margin-top: 0.5rem;">Check Your Admission Eligibility</h3>
    </div>
    <div class="wizard-steps">
      <div class="step-indicator active"></div>
      <div class="step-indicator"></div>
      <div class="step-indicator"></div>
    </div>
    <div class="wizard-step-panel active">
      <h4 style="margin-bottom: 1rem;">Step 1: Academic Background</h4>
      <div class="input-field" style="margin-bottom: 1rem;">
        <label>Highest Qualification Achieved</label>
        <select id="wizEdu">
          <option>Bachelor's Degree</option>
          <option>High School / 12th Grade</option>
          <option>Master's Degree</option>
        </select>
      </div>
      <div class="input-field" style="margin-bottom: 1.5rem;">
        <label>Academic Score / GPA (out of 4.0 or %)</label>
        <input type="number" id="wizGpa" step="0.1" value="3.6" placeholder="e.g. 3.5 GPA or 80%">
      </div>
      <button class="btn btn-primary" style="width: 100%;" onclick="nextWizardStep(2)">Continue to English Test <i class="fas fa-arrow-right"></i></button>
    </div>
    <div class="wizard-step-panel">
      <h4 style="margin-bottom: 1rem;">Step 2: Language Score & Intake</h4>
      <div class="input-field" style="margin-bottom: 1rem;">
        <label>English Test Score (IELTS / TOEFL / PTE)</label>
        <input type="number" id="wizIelts" step="0.5" value="7.0" placeholder="e.g. 7.0 Overall IELTS">
      </div>
      <div class="input-field" style="margin-bottom: 1.5rem;">
        <label>Target Field of Study</label>
        <select id="wizField">
          <option>Computer Science & IT</option>
          <option>Business Administration & MBA</option>
          <option>Engineering & Technology</option>
          <option>Health Sciences & Nursing</option>
        </select>
      </div>
      <div style="display: flex; gap: 1rem;">
        <button class="btn btn-outline" style="flex: 1;" onclick="prevWizardStep(1)">Back</button>
        <button class="btn btn-primary" style="flex: 2;" onclick="nextWizardStep(3)">Calculate Match Results <i class="fas fa-magic"></i></button>
      </div>
    </div>
    <div class="wizard-step-panel">
      <div id="wizResultBox"></div>
    </div>
  </div>
</div>

<div class="modal-overlay" id="applyModal">
  <div class="modal-content" style="max-width: 680px; max-height: 90vh; overflow-y: auto;">
    <button class="modal-close" onclick="closeApplyModal()">&times;</button>
    <div style="margin-bottom: 1.25rem;">
      <span class="badge badge-success"><i class="fas fa-graduation-cap"></i> University Admission Portal</span>
      <h3 style="font-size: 1.4rem; margin-top: 0.35rem;" id="applyModalProgramTitle">Program Name</h3>
      <p style="color: var(--slate-500); font-size: 0.9rem;" id="applyModalUniTitle">University Name</p>
    </div>

    <form id="applyModalForm" method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
      <input type="hidden" id="applyModalHiddenProgram" name="program_title">
      <input type="hidden" id="applyModalHiddenUni" name="university_title">
      
      <!-- SECTION 1: APPLICANT PERSONAL INFORMATION -->
      <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.25rem;">
        <div style="font-weight: 700; font-size: 0.9rem; color: var(--navy-900); margin-bottom: 0.75rem; border-bottom: 1px solid var(--slate-200); padding-bottom: 0.35rem;">
          <i class="fas fa-user-circle" style="color: var(--primary);"></i> 1. Applicant Contact Details
        </div>
        <div class="input-field" style="margin-bottom: 1rem;">
          <label>Full Name *</label>
          <input type="text" name="full_name" id="applyFullName" required value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" placeholder="e.g. John Doe">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="input-field">
            <label>Email Address *</label>
            <input type="email" name="email" id="applyEmail" required value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" placeholder="john@example.com">
          </div>
          <div class="input-field">
            <label>Phone Number (with Country Code) *</label>
            <input type="tel" name="phone" id="applyPhone" required placeholder="+1 234 567 8900">
          </div>
        </div>
      </div>

      <!-- SECTION 2: ACADEMIC & LANGUAGE QUALIFICATION -->
      <div style="background: rgba(13, 148, 136, 0.04); border: 1.5px solid var(--secondary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.25rem;">
        <div style="font-weight: 700; font-size: 0.9rem; color: var(--secondary); margin-bottom: 0.75rem; border-bottom: 1px solid var(--secondary); padding-bottom: 0.35rem;">
          <i class="fas fa-certificate"></i> 2. Academic & Language Score Details
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div class="input-field">
            <label>Highest Achieved Qualification *</label>
            <select name="academic_level" required>
              <option value="Bachelor">Bachelor's Degree</option>
              <option value="High School / HSC / A-Levels">High School / HSC / A-Levels</option>
              <option value="Master">Master's Degree</option>
              <option value="Diploma">Diploma / Associate Degree</option>
            </select>
          </div>
          <div class="input-field">
            <label>Academic Score / GPA (Out of 4.0 or %) *</label>
            <input type="text" name="gpa_score" required value="3.6" placeholder="e.g. 3.6 GPA or 85%">
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="input-field">
            <label>English Test Provided *</label>
            <select name="english_test" required>
              <option value="IELTS">IELTS</option>
              <option value="TOEFL">TOEFL</option>
              <option value="PTE">PTE Academic</option>
              <option value="Duolingo">Duolingo English Test</option>
              <option value="Medium of Instruction (MOI)">Medium of Instruction (MOI)</option>
            </select>
          </div>
          <div class="input-field">
            <label>Overall Test Score / Band *</label>
            <input type="text" name="english_score" required value="7.0" placeholder="e.g. 7.0 Overall (IELTS)">
          </div>
        </div>
      </div>

      <!-- SECTION 3: REQUIRED APPLICATION DOCUMENT UPLOADS -->
      <div style="background: rgba(99, 102, 241, 0.04); border: 1.5px solid var(--primary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.25rem;">
        <div style="font-weight: 700; font-size: 0.9rem; color: var(--primary); margin-bottom: 0.75rem; border-bottom: 1px solid var(--primary); padding-bottom: 0.35rem; display: flex; justify-content: space-between; align-items: center;">
          <span><i class="fas fa-file-upload"></i> 3. Document Submission (PDF / JPG / PNG)</span>
          <span style="font-size: 0.75rem; background: var(--primary); color: white; padding: 0.2rem 0.5rem; border-radius: 4px;">Strictly Required</span>
        </div>

        <!-- Document 1: Academic Transcripts -->
        <div class="input-field" style="margin-bottom: 1rem; background: white; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span>🎓 Academic Transcripts & Certificates <span style="color: var(--accent-rose);">*</span></span>
            <span style="font-size: 0.75rem; color: var(--slate-500);">PDF, JPG, PNG (Max 10MB)</span>
          </label>
          <input type="file" name="transcript_file" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" required style="padding: 0.4rem; font-size: 0.825rem;">
        </div>

        <!-- Document 2: Valid Passport Copy -->
        <div class="input-field" style="margin-bottom: 1rem; background: white; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span>🛂 Valid Passport / National ID Copy <span style="color: var(--accent-rose);">*</span></span>
            <span style="font-size: 0.75rem; color: var(--slate-500);">PDF, JPG, PNG (Max 10MB)</span>
          </label>
          <input type="file" name="passport_file" accept=".pdf,.png,.jpg,.jpeg" required style="padding: 0.4rem; font-size: 0.825rem;">
        </div>

        <!-- Document 3: Statement of Purpose (SOP) -->
        <div class="input-field" style="margin-bottom: 1rem; background: white; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span>📝 Statement of Purpose (SOP) / Motivation Letter <span style="color: var(--accent-rose);">*</span></span>
            <span style="font-size: 0.75rem; color: var(--slate-500);">PDF, DOC, DOCX, TXT</span>
          </label>
          <input type="file" name="sop_file" accept=".pdf,.doc,.docx,.txt" required style="padding: 0.4rem; font-size: 0.825rem;">
        </div>

        <!-- Document 4: English Scorecard (Optional) -->
        <div class="input-field" style="margin-bottom: 1rem; background: white; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span>🗣️ English Scorecard / Certificate <span style="color: var(--slate-500); font-size: 0.75rem;">(Optional)</span></span>
            <span style="font-size: 0.75rem; color: var(--slate-500);">IELTS/TOEFL PDF or Scan</span>
          </label>
          <input type="file" name="english_cert_file" accept=".pdf,.png,.jpg,.jpeg" style="padding: 0.4rem; font-size: 0.825rem;">
        </div>

        <!-- Document 5: Professional CV / Resume (Optional) -->
        <div class="input-field" style="background: white; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
            <span>📄 Professional CV / Resume <span style="color: var(--slate-500); font-size: 0.75rem;">(Optional)</span></span>
            <span style="font-size: 0.75rem; color: var(--slate-500);">PDF, DOC, DOCX</span>
          </label>
          <input type="file" name="cv_file" accept=".pdf,.doc,.docx" style="padding: 0.4rem; font-size: 0.825rem;">
        </div>
      </div>

      <!-- SECTION 4: ADDITIONAL NOTES -->
      <div class="input-field" style="margin-bottom: 1.5rem;">
        <label>Additional Notes / Scholarship Request (Optional)</label>
        <textarea name="additional_notes" rows="2" placeholder="Mention any extra achievements, scholarship request, or intake preference..."></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; font-size: 1.05rem;">
        Submit Application & Required Documents <i class="fas fa-paper-plane"></i>
      </button>
      <div id="applyModalFeedback" style="display: none; margin-top: 1rem; font-size: 0.85rem; padding: 0.75rem; border-radius: 6px;"></div>
    </form>
  </div>
</div>

<!-- ADMIN: ADD NEW UNIVERSITY & PROGRAM MODAL -->
<div class="modal-overlay" id="addUniversityModal">
  <div class="modal-content" style="max-width: 700px; max-height: 88vh; overflow-y: auto;">
    <button class="modal-close" onclick="closeAddUniversityModal()">&times;</button>
    <div style="margin-bottom: 1.25rem;">
      <span class="badge badge-primary"><i class="fas fa-user-shield"></i> Admin Tool</span>
      <h3 style="font-size: 1.5rem; margin-top: 0.25rem;">Add New University & Offered Program</h3>
      <p style="font-size: 0.85rem; color: var(--slate-500); margin-top: 0.25rem;">Add a university and its offered program details (duration, intake, scholarship, per year cost) to show on Find Programs & Institutions.</p>
    </div>

    <form action="api/manage_university.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add_university">
      
      <!-- SECTION 1: UNIVERSITY DETAILS -->
      <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.25rem;">
        <div style="font-weight: 700; font-size: 0.95rem; color: var(--primary); margin-bottom: 0.75rem; border-bottom: 1px solid var(--slate-200); padding-bottom: 0.35rem;">
          <i class="fas fa-university"></i> 1. University & Campus Details
        </div>

        <div class="input-field" style="margin-bottom: 1rem;">
          <label>University / Institution Name *</label>
          <input type="text" name="university_name" required placeholder="e.g. University of British Columbia">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div class="input-field">
            <label>Country *</label>
            <input type="text" name="country" required placeholder="e.g. Canada">
          </div>
          <div class="input-field">
            <label>City / Campus Location *</label>
            <input type="text" name="city" required placeholder="e.g. Vancouver">
          </div>
        </div>

        <div class="input-field" style="margin-bottom: 1rem;">
          <label>Website URL</label>
          <input type="url" name="website_url" placeholder="https://www.ubc.ca">
        </div>

        <div class="input-field">
          <label>University Description / Overview</label>
          <textarea name="description" rows="2" placeholder="Overview of the university, rankings, campus features..."></textarea>
        </div>
      </div>

      <!-- SECTION 2: OFFERED PROGRAM, DURATION, INTAKE, SCHOLARSHIP & TOTAL COST -->
      <div style="background: rgba(13, 148, 136, 0.05); border: 1.5px solid var(--secondary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="font-weight: 700; font-size: 0.95rem; color: var(--secondary); margin-bottom: 0.75rem; border-bottom: 1px solid var(--secondary); padding-bottom: 0.35rem;">
          <i class="fas fa-graduation-cap"></i> 2. Offered Program, Duration, Intake, Scholarship & Total Cost
        </div>

        <div class="input-field" style="margin-bottom: 1rem;">
          <label>University Offered Program Name *</label>
          <input type="text" name="program_name" required placeholder="e.g. M.Sc. in Data Analytics & Artificial Intelligence">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div class="input-field">
            <label>Program Duration *</label>
            <input type="text" name="duration" required value="2 Years" placeholder="e.g. 2 Years, 3 Years">
          </div>
          <div class="input-field">
            <label>Next Intake *</label>
            <input type="text" name="intake" required value="Fall 2027" placeholder="e.g. Fall 2027, Sept 2027">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="input-field">
            <label>Scholarship Opportunities</label>
            <input type="text" name="scholarship" value="Up to $10,000" placeholder="e.g. Up to $10,000, DAAD Eligible">
          </div>
          <div class="input-field">
            <label>Total Cost Per Year ($) *</label>
            <input type="number" step="100" name="tuition_fee" required value="32500" placeholder="e.g. 32500">
          </div>
        </div>
      </div>

      <!-- SECTION 3: DOCUMENT REQUIREMENTS & SPECIFICATION FILE UPLOAD -->
      <div style="background: rgba(99, 102, 241, 0.05); border: 1.5px solid var(--primary); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="font-weight: 700; font-size: 0.95rem; color: var(--primary); margin-bottom: 0.75rem; border-bottom: 1px solid var(--primary); padding-bottom: 0.35rem;">
          <i class="fas fa-file-alt"></i> 3. Document Requirements & Specification Upload
        </div>

        <label style="font-size: 0.85rem; font-weight: 700; color: var(--navy-900); display: block; margin-bottom: 0.5rem;">Select Required Document Types:</label>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; background: var(--white); padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200);">
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="Academic Transcripts" checked> 🎓 Academic Transcripts
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="Passport Copy" checked> 🛂 Valid Passport Copy
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="Statement of Purpose (SOP)" checked> 📝 Statement of Purpose (SOP)
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="Letters of Recommendation (LOR)" checked> ✉️ Recommendation Letters (LOR)
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="English Proficiency (IELTS/TOEFL)" checked> 🗣️ IELTS / TOEFL Certificate
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="CV / Resume"> 📄 Professional CV / Resume
          </label>
          <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; cursor: pointer;">
            <input type="checkbox" name="doc_types[]" value="Bank Statement / Financial Proof"> 💰 Financial Guarantee / Bank Statement
          </label>
        </div>

        <div class="input-field" style="margin-bottom: 1rem;">
          <label>Custom Document Instructions & Criteria</label>
          <input type="text" name="document_requirement" placeholder="e.g. Min 3.0 GPA, IELTS 6.5, 2 LORs required.">
        </div>

        <div class="input-field">
          <label>Upload Document Guide / Spec (PDF/DOC/Image)</label>
          <input type="file" name="doc_requirement_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" style="padding: 0.4rem; font-size: 0.8rem;">
        </div>
      </div>

      <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%; justify-content: center; font-size: 1rem;">
        <i class="fas fa-plus-circle"></i> Save University, Program, Cost & Document Requirements
      </button>
    </form>
  </div>
</div>

<!-- FLOATING BACK TO TOP BUTTON -->
<button class="back-to-top-btn" id="backToTopBtn" aria-label="Back to top" title="Back to Top">
  <i class="fas fa-arrow-up"></i>
</button>


