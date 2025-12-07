<?php
session_start();
// TODO: Authorize access to profiles by ownership
// TODO: Enforce access control on the backend
// TODO: Fetch profile data by id securely
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — IDOR Lab</title>
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
        <div class="pill mb-2" data-i18n="idor_badge"><i class="fa-solid fa-link-slash"></i> Insecure Direct Object Reference</div>
        <h2 class="mb-1" data-i18n="idor_title">Change the ID in the URL</h2>
        <p class="muted mb-0" data-i18n-html="idor_subtitle">This page fetches profile details solely by the <code>id</code> parameter with no authorization. Modify the id to read another user's data and uncover the flag.</p>
      </div>
      <div class="chip pill-warning" data-i18n="idor_chip">Access control missing</div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="idor_form_title">Profile payload</h5>
            <small class="muted" data-i18n="idor_form_subtitle">Query string driven</small>
          </div>
          <div class="alert alert-info">
            <span data-i18n-html="idor_alert">Try <code>?id=2</code> or <code>?id=admin</code> to fetch another user's record. In a real app this would be blocked server-side.</span>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="muted" data-i18n="idor_current">Current id:</span>
            <span class="badge bg-primary" id="idorCurrentId">1</span>
          </div>
          <div class="border rounded p-3 bg-light" id="idorRecord" data-i18n="idor_loading">Loading profile...</div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="idor_flag_title">Unauthorized data</h5>
            <span class="chip" data-i18n="idor_flag_chip">Sensitive</span>
          </div>
          <p class="muted small mb-2" data-i18n="idor_flag_desc">If you can read another user's profile or progress without permission, you've exploited the IDOR.</p>
          <div class="flag hidden-flag" id="idorFlag" data-flag-key="idor"></div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="idor" data-flag-target="#idorFlag" data-i18n="idor_submit_btn">Submit flag (frontend)</button>
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
