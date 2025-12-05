<?php
session_start();
// TODO: Protect this page behind authentication
// TODO: Replace fake query with prepared statements
// TODO: Verify submitted flag server-side
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — SQLi Lab</title>
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
        <div class="pill mb-2"><i class="fa-solid fa-database"></i> SQL Injection lab</div>
        <h2 class="mb-1">Break the login query</h2>
        <p class="muted mb-0">The query concatenates user input directly. Use a classic boolean-based injection to bypass the check and return the hidden admin flag.</p>
      </div>
      <a href="flag_lab.php" class="btn btn-outline-primary">Final flag</a>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Vulnerable search</h5>
            <span class="chip pill-warning">String concatenation</span>
          </div>
          <div id="alertPlaceholder"></div>
          <form id="sqliForm">
            <div class="mb-3">
              <label class="form-label">Username or email</label>
              <input type="text" class="form-control" id="sqliInput" placeholder="admin' OR '1'='1">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="not needed when injecting">
            </div>
            <button class="btn btn-primary w-100" type="submit">Run query (UI only)</button>
          </form>
          <div class="mt-3 small muted">Hint: Classic payloads like <code>' OR '1'='1 --</code> should break the WHERE clause.</div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Query output</h5>
            <span class="chip">Simulated DB</span>
          </div>
          <div class="border rounded p-3 bg-light" id="sqliResult">No records yet.</div>
          <div class="flag hidden-flag mt-3" id="sqliFlag">FLAG{SQLI_BYPASS_MASTER}</div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="sqli" data-flag-target="#sqliFlag">Submit flag (frontend)</button>
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
