<?php
session_start();
// TODO: Implement registration logic
// TODO: Hash passwords and persist to MySQL
// TODO: Auto-login and redirect after successful signup
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Register</title>
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
          <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
          <li class="nav-item ms-2"><a class="btn btn-primary" href="register.php">Register</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container" style="max-width: 960px;">
    <div class="row align-items-center page-hero">
      <div class="col-lg-5">
        <div class="pill mb-3"><i class="fa-solid fa-user-plus"></i> Create your lab account</div>
        <h1 class="mb-3">Join the security challenge</h1>
        <p class="muted">Progress, achievements, and leaderboard are simulated locally. Backend will be implemented later by students.</p>
        <ul class="lab-steps mt-3">
          <li>Complete 5 labs to earn <span class="mini-flag">Certified Hacker</span></li>
          <li>Use only your browser — no external interceptors</li>
          <li>All data is placeholder; feel free to experiment</li>
        </ul>
      </div>
      <div class="col-lg-7 mt-4 mt-lg-0">
        <div class="card p-4 hero-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Register</h5>
            <span class="chip">UI only</span>
          </div>
          <form>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" placeholder="Ada Lovelace">
              </div>
              <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="ada">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com">
            </div>
            <div class="row g-3 mt-3">
              <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
              </div>
              <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
              </div>
            </div>
            <button type="button" class="btn btn-primary w-100 mt-4">Create account (UI only)</button>
          </form>
          <div class="text-center mt-3">
            <small class="muted">Already registered? <a href="index.php">Login</a></small>
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
