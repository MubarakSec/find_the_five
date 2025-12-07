<?php
session_start();
// TODO: Authorize user before allowing updates
// TODO: Sanitize and persist bio changes to MySQL
// TODO: Prevent stored XSS by escaping output
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Update Profile / XSS Lab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="dashboard.php" data-i18n="brand">Find The Five</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="dashboard.php" data-i18n="nav_dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php?id=1" data-i18n="nav_profile">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="admin.php" data-i18n="nav_admin">Admin</a></li>
          <li class="nav-item ms-3"><a class="btn btn-outline-primary" href="logout.php" data-i18n="nav_logout">Logout</a></li>
        </ul>
        <div class="ms-3 d-flex gap-1">
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="en">EN</button>
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="ar">ع</button>
        </div>
      </div>
    </div>
  </nav>

  <main class="container page-hero" style="max-width: 1000px;">
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-2" data-i18n="xss_badge"><i class="fa-solid fa-code"></i> Stored XSS lab</div>
        <h2 class="mb-1" data-i18n="xss_title">Update your bio (and break it)</h2>
        <p class="muted mb-0" data-i18n="xss_subtitle">The preview below renders unescaped HTML. Insert a &lt;script&gt; tag to simulate a stored cross-site scripting payload and expose the flag.</p>
      </div>
      <div class="col-lg-5 mt-3 mt-lg-0">
        <div class="soft-card p-3">
          <div class="fw-semibold mb-1" data-i18n="xss_target_title">Target</div>
          <ul class="lab-steps">
            <li data-i18n="xss_step1">Write a script tag inside your bio</li>
            <li data-i18n="xss_step2">Save and reload the preview</li>
            <li data-i18n="xss_step3">The script executes and reveals the flag</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="xss_form_title">Edit bio</h5>
            <span class="chip pill-warning" data-i18n="xss_form_chip">Unsanitized</span>
          </div>
          <div id="alertPlaceholder"></div>
          <form id="bioForm">
            <div class="mb-3">
              <label class="form-label" data-i18n="xss_field_bio">Bio text</label>
              <textarea id="bioInput" class="form-control" rows="6" placeholder="Write about yourself..." data-i18n-placeholder="xss_placeholder_bio"></textarea>
            </div>
            <button class="btn btn-primary w-100" type="submit" data-i18n="xss_save_btn">Save bio (UI only)</button>
          </form>
          <div class="mt-3">
            <small class="muted" data-i18n="xss_hint">Psst: Stored XSS means the script will live in the database and run for anyone viewing your profile.</small>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card p-4 floating-flag">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="xss_preview_title">Live preview</h5>
            <span class="chip" data-i18n="xss_preview_chip">Bio output</span>
          </div>
          <div class="border rounded p-3" id="bioPreview" data-i18n="xss_preview_empty">Your bio will appear here.</div>
          <div class="flag hidden-flag" id="xssFlag" data-flag-key="xss"></div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="xss" data-flag-target="#xssFlag" data-i18n="xss_submit_btn">Submit flag (frontend)</button>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer text-center">
    <div class="container">
      Find The Five — Security Training App v1.0
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>
</html>
