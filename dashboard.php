<?php
session_start();
// TODO: Check login session and restrict to authenticated users
// TODO: Load achievements from database
// TODO: Persist progress updates
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Dashboard</title>
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

  <main class="container page-hero">
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-3" data-i18n="dash_badge"><i class="fa-solid fa-location-crosshairs"></i> Track your lab achievements</div>
        <h1 class="mb-2" data-i18n="dash_title">Find all five vulnerabilities</h1>
        <p class="muted mb-0" data-i18n="dash_subtitle">Each lab is intentionally misconfigured. Exploit the weakness to reveal its flag, submit it, and watch your rank improve. All progress is simulated on the frontend for now.</p>
      </div>
      <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="soft-card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <div class="fw-semibold" data-i18n="progress_label">Progress</div>
              <small class="muted" id="achievementProgressText">0/5 achievements</small>
            </div>
            <span class="chip pill-success" id="rankLabel">Casual User</span>
          </div>
          <div class="progress" role="progressbar" aria-label="Achievement progress">
            <div class="progress-bar" id="achievementProgressBar" style="width: 0%;"></div>
          </div>
          <div class="mt-3 d-flex justify-content-between">
            <span class="muted" data-i18n="progress_hint">Unlock each flag to level up</span>
            <a href="flag_lab.php" class="fw-semibold" data-i18n="final_flag_link">Final Flag</a>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="achievements_title">Achievements</h5>
            <small class="muted" data-i18n="achievements_note">UI only — will sync to MySQL later</small>
          </div>
          <div class="d-grid gap-3">
            <div class="achievement locked" data-achievement="sqli">
              <div class="status-dot"></div>
              <div>
                <div class="fw-semibold" data-i18n="ach_sqli_title">SQL Injection (sqli_lab.php)</div>
                <div class="muted small" data-i18n="ach_sqli_desc">Bypass the login/search query to leak flag</div>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-status>Locked</span>
            </div>
            <div class="achievement locked" data-achievement="idor">
              <div class="status-dot"></div>
              <div>
                <div class="fw-semibold" data-i18n="ach_idor_title">IDOR (idor_lab.php)</div>
                <div class="muted small" data-i18n="ach_idor_desc">Change the URL id to access hidden profile section</div>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-status>Locked</span>
            </div>
            <div class="achievement locked" data-achievement="xss">
              <div class="status-dot"></div>
              <div>
                <div class="fw-semibold" data-i18n="ach_xss_title">Stored XSS (update_profile.php)</div>
                <div class="muted small" data-i18n="ach_xss_desc">Inject script into bio to trigger the flag</div>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-status>Locked</span>
            </div>
            <div class="achievement locked" data-achievement="cookie">
              <div class="status-dot"></div>
              <div>
                <div class="fw-semibold" data-i18n="ach_cookie_title">Cookie Tampering (cookie_lab.php)</div>
                <div class="muted small" data-i18n="ach_cookie_desc">Edit your cookie to elevate privileges</div>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-status>Locked</span>
            </div>
            <div class="achievement locked" data-achievement="privesc">
              <div class="status-dot"></div>
              <div>
                <div class="fw-semibold" data-i18n="ach_privesc_title">Privilege Escalation (privesc_lab.php)</div>
                <div class="muted small" data-i18n="ach_privesc_desc">Modify the role request to unlock the last flag</div>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-status>Locked</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card p-4 h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="start_lab_title">Start a lab</h5>
            <i class="fa-solid fa-flask text-primary"></i>
          </div>
          <div class="d-grid gap-3">
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="sqli_lab.php">
              <div class="me-3"><i class="fa-solid fa-database fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_sqli_title">SQL Injection</div>
                <small class="muted" data-i18n="lab_sqli_desc">Weak query string</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="idor_lab.php?id=1">
              <div class="me-3"><i class="fa-solid fa-link-slash fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_idor_title">IDOR</div>
                <small class="muted" data-i18n="lab_idor_desc">User IDs exposed</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="update_profile.php">
              <div class="me-3"><i class="fa-solid fa-code fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_xss_title">Stored XSS</div>
                <small class="muted" data-i18n="lab_xss_desc">Unescaped bio</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="cookie_lab.php">
              <div class="me-3"><i class="fa-solid fa-cookie-bite fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_cookie_title">Cookie Tampering</div>
                <small class="muted" data-i18n="lab_cookie_desc">Privilege in cookies</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="privesc_lab.php">
              <div class="me-3"><i class="fa-solid fa-user-shield fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_privesc_title">Privilege Escalation</div>
                <small class="muted" data-i18n="lab_privesc_desc">Role override</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
            <a class="soft-card p-3 text-decoration-none d-flex align-items-center lab-card" href="flag_lab.php">
              <div class="me-3"><i class="fa-solid fa-flag fa-lg text-primary"></i></div>
              <div>
                <div class="fw-semibold text-dark" data-i18n="lab_final_title">Final Flag</div>
                <small class="muted" data-i18n="lab_final_desc">Finish line</small>
              </div>
              <i class="fa-solid fa-arrow-right ms-auto text-primary"></i>
            </a>
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
