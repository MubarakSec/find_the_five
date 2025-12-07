<?php
session_start();
// TODO: Ensure user is authenticated
// TODO: Fetch profile details by id parameter
// TODO: Protect against IDOR when loading another profile
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Profile</title>
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

  <main class="container page-hero" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2" data-i18n="profile_badge"><i class="fa-solid fa-user"></i> Profile viewer</div>
        <h2 class="mb-0" data-i18n="profile_title">Security Trainee Profile</h2>
        <small class="muted" data-i18n="profile_subtitle">Profile data is static for now — backend to be wired later.</small>
      </div>
      <a href="update_profile.php" class="btn btn-primary" data-i18n="profile_edit_btn">Edit profile</a>
    </div>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card p-4 text-center">
          <img src="https://api.dicebear.com/7.x/identicon/svg?seed=trainee" alt="avatar" class="rounded-circle mb-3" width="120" height="120">
          <h5 class="mb-1">Trainee User</h5>
          <div class="muted" id="profileIdLabel">Profile ID: 1</div>
          <div class="d-flex justify-content-center gap-2 mt-3">
            <span class="chip">Learner</span>
            <span class="chip pill-warning">Sandbox</span>
          </div>
          <a href="flag_lab.php" class="d-block mt-3 small">Finish line</a>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card p-4 mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="profile_about_title">About</h5>
            <small class="muted" data-i18n="profile_about_note">Editable in update_profile.php</small>
          </div>
          <p id="bioText" data-i18n="profile_bio_text">Hi! I'm exploring web security. This bio is editable and intentionally unfiltered in the XSS lab.</p>
          <div class="flag hidden-flag" id="profileFlag" data-flag-key="profile"></div>
        </div>
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="profile_recent_title">Recent labs</h5>
            <a class="small" href="dashboard.php" data-i18n="profile_view_all">View all</a>
          </div>
          <div class="d-grid gap-3">
            <div class="soft-card p-3 d-flex align-items-center">
              <i class="fa-solid fa-database text-primary me-3"></i>
              <div>
                <div class="fw-semibold" data-i18n="profile_recent_sqli">SQL Injection lab</div>
                <small class="muted" data-i18n-html="profile_recent_sqli_hint">Try `' OR '1'='1`</small>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-achievement="sqli" data-status>Locked</span>
            </div>
            <div class="soft-card p-3 d-flex align-items-center">
              <i class="fa-solid fa-code text-primary me-3"></i>
              <div>
                <div class="fw-semibold" data-i18n="profile_recent_xss">Stored XSS lab</div>
                <small class="muted" data-i18n="profile_recent_xss_hint">Update your bio unsafely</small>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-achievement="xss" data-status>Locked</span>
            </div>
          </div>
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
