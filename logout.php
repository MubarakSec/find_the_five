<?php
session_start();
// TODO: Destroy session and redirect to login
// TODO: Clear authentication cookies
// TODO: Log logout event
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Logout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand fw-bold" href="dashboard.php" data-i18n="brand">Find The Five</a>
      <div class="ms-auto">
        <a class="btn btn-outline-primary" href="index.php" data-i18n="logout_return">Return to login</a>
        <div class="d-inline-flex gap-1 ms-2">
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="en">EN</button>
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="ar">ع</button>
        </div>
      </div>
    </div>
  </nav>

  <main class="container page-hero" style="max-width: 720px;">
    <div class="card p-4 text-center">
      <div class="pill mb-3 mx-auto" data-i18n="logout_badge"><i class="fa-solid fa-right-from-bracket"></i> Logout placeholder</div>
      <h3 data-i18n="logout_title">Session cleared (frontend only)</h3>
      <p class="muted" data-i18n="logout_subtitle">In the real build, this page will destroy the PHP session and remove cookies, then redirect to the login page.</p>
      <div class="d-flex justify-content-center gap-2 mt-3">
        <a class="btn btn-primary" href="index.php" data-i18n="logout_back">Back to login</a>
        <a class="btn btn-outline-primary" href="dashboard.php" data-i18n="logout_cancel">Cancel</a>
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
