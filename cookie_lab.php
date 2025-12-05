<?php
session_start();
// TODO: Validate session before showing page
// TODO: Move authorization out of cookies and into server-side checks
// TODO: Verify flag submissions against database
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Cookie Tampering Lab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="dashboard.php">Find The Five</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php?id=1">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
          <li class="nav-item ms-3"><a class="btn btn-outline-primary" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container page-hero" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2"><i class="fa-solid fa-cookie-bite"></i> Cookie tampering lab</div>
        <h2 class="mb-1">Privilege stored in a cookie</h2>
        <p class="muted mb-0">The application trusts the <code>access_level</code> cookie. Edit it to escalate your privileges and expose the admin-only flag.</p>
      </div>
      <span class="chip pill-warning">Client-side trust</span>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Cookie inspector</h5>
            <small class="muted">Default: learner</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Current cookies</label>
            <textarea class="form-control" rows="4" readonly id="cookieBox"></textarea>
          </div>
          <button class="btn btn-outline-primary w-100" id="refreshCookies" type="button">Refresh cookie view</button>
          <div class="alert alert-info mt-3">
            <div class="fw-semibold">Goal</div>
            Set <code>access_level=elite</code> or <code>access_level=admin</code> then refresh. You can use browser devtools to edit cookies.
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Admin flag</h5>
            <span class="chip">Hidden</span>
          </div>
          <p class="muted small mb-2">When the cookie indicates admin access, the restricted flag appears.</p>
          <div class="flag hidden-flag" id="cookieFlag">FLAG{COOKIE_TRUST_IS_BAD}</div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="cookie" data-flag-target="#cookieFlag">Submit flag (frontend)</button>
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
