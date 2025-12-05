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

  <main class="container page-hero" style="max-width: 1000px;">
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-2"><i class="fa-solid fa-code"></i> Stored XSS lab</div>
        <h2 class="mb-1">Update your bio (and break it)</h2>
        <p class="muted mb-0">The preview below renders unescaped HTML. Insert a &lt;script&gt; tag to simulate a stored cross-site scripting payload and expose the flag.</p>
      </div>
      <div class="col-lg-5 mt-3 mt-lg-0">
        <div class="soft-card p-3">
          <div class="fw-semibold mb-1">Target</div>
          <ul class="lab-steps">
            <li>Write a script tag inside your bio</li>
            <li>Save and reload the preview</li>
            <li>The script executes and reveals the flag</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Edit bio</h5>
            <span class="chip pill-warning">Unsanitized</span>
          </div>
          <div id="alertPlaceholder"></div>
          <form id="bioForm">
            <div class="mb-3">
              <label class="form-label">Bio text</label>
              <textarea id="bioInput" class="form-control" rows="6" placeholder="Write about yourself..."></textarea>
            </div>
            <button class="btn btn-primary w-100" type="submit">Save bio (UI only)</button>
          </form>
          <div class="mt-3">
            <small class="muted">Psst: Stored XSS means the script will live in the database and run for anyone viewing your profile.</small>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card p-4 floating-flag">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Live preview</h5>
            <span class="chip">Bio output</span>
          </div>
          <div class="border rounded p-3" id="bioPreview">Your bio will appear here.</div>
          <div class="flag hidden-flag" id="xssFlag">FLAG{STORED_XSS_OWNED}</div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="xss" data-flag-target="#xssFlag">Submit flag (frontend)</button>
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
