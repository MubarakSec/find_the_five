<?php
session_start();
// TODO: Implement login logic and session handling
// TODO: Validate credentials against MySQL
// TODO: Redirect authenticated users to dashboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Login</title>
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
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item ms-2"><a class="btn btn-primary" href="index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container" style="max-width: 960px;">
    <div class="row align-items-center page-hero">
      <div class="col-lg-6">
        <div class="pill mb-3"><i class="fa-solid fa-shield-halved"></i> Browser-only security labs</div>
        <h1 class="mb-3">Sign in to hunt all five flags</h1>
        <p class="muted">Discover vulnerabilities, unlock achievements, and level up your security mindset. No external tools required.</p>
        <div class="d-flex gap-3 mt-4">
          <div class="badge-lock unlocked"><i class="fa-solid fa-lock-open"></i> UI only — backend placeholder</div>
          <div class="badge-lock"><i class="fa-solid fa-database"></i> MySQL schema will be added later</div>
        </div>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card p-4 hero-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Login</h5>
            <span class="chip">Guest access</span>
          </div>
          <form>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <button type="button" class="btn btn-primary w-100">Login (UI only)</button>
          </form>
          <div class="text-center mt-3">
            <small class="muted">No account? <a href="register.php">Register here</a></small>
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
