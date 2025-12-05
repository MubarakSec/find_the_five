<?php
session_start();
// TODO: Require authenticated user
// TODO: Verify that all achievements are completed before returning final flag
// TODO: Record completion in database
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Final Flag</title>
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
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-2"><i class="fa-solid fa-flag"></i> Final flag</div>
        <h2 class="mb-1">Finish line</h2>
        <p class="muted mb-0">You should now have 5 lab flags. Combine your knowledge or inspect the page to discover the master code that reveals the final trophy flag.</p>
      </div>
      <div class="col-lg-5 mt-3 mt-lg-0">
        <div class="soft-card p-3">
          <div class="fw-semibold mb-1">Completion check</div>
          <div class="progress mb-2" role="progressbar">
            <div class="progress-bar" id="finalProgressBar"></div>
          </div>
          <small class="muted">Progress is stored locally in your browser for demo purposes.</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Submit master code</h5>
            <span class="chip pill-warning">Client-side leak</span>
          </div>
          <div id="alertPlaceholder"></div>
          <p class="muted">Somewhere in the client (source, devtools, JS) there's a hardcoded master code. Enter it below to unlock the final flag. Completing all 5 achievements also auto-unlocks.</p>
          <form id="finalFlagForm">
            <div class="mb-3">
              <label class="form-label">Master code</label>
              <input type="text" class="form-control" id="finalCodeInput" placeholder="FTF-????-????">
            </div>
            <button class="btn btn-primary w-100" type="submit">Reveal final flag</button>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Final flag</h5>
            <span class="chip pill-success">Trophy</span>
          </div>
          <div class="flag hidden-flag" id="finalFlag" data-secret="FTF-MASTER-KEY-204">FLAG{FIND_THE_FIVE_COMPLETE}</div>
          <button class="btn btn-outline-primary w-100 mt-3" type="button" id="finalFlagSubmit">Submit flag (frontend)</button>
          <div class="alert alert-light border mt-3">
            <div class="fw-semibold">Note</div>
            This submission is cosmetic. Backend validation will be added later.
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
