<?php
session_start();
// TODO: Enforce admin-only access
// TODO: Load user list and achievement counts from MySQL
// TODO: Implement reset progress and delete user actions
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Admin Panel</title>
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
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin.php">Admin</a></li>
          <li class="nav-item ms-3"><a class="btn btn-outline-primary" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container page-hero">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2"><i class="fa-solid fa-user-gear"></i> Security Supervisor Panel</div>
        <h2 class="mb-1">User management (mock)</h2>
        <p class="muted mb-0">This panel is for admins only. Buttons are placeholders until backend logic is added.</p>
      </div>
      <span class="chip pill-warning">Admin view</span>
    </div>

    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Users</h5>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-danger btn-sm" type="button">Reset all progress</button>
          <button class="btn btn-outline-secondary btn-sm" type="button">Export CSV</button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th scope="col">User</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col">Achievements</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="fw-semibold">Trainee</td>
              <td>student@example.com</td>
              <td><span class="chip">user</span></td>
              <td><span class="chip pill-success">2 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button">Delete</button>
              </td>
            </tr>
            <tr>
              <td class="fw-semibold">Analyst</td>
              <td>analyst@example.com</td>
              <td><span class="chip">user</span></td>
              <td><span class="chip pill-warning">3 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button">Delete</button>
              </td>
            </tr>
            <tr>
              <td class="fw-semibold">Admin</td>
              <td>admin@example.com</td>
              <td><span class="chip pill-success">admin</span></td>
              <td><span class="chip pill-success">5 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
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
