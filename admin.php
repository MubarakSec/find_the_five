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
      <a class="navbar-brand fw-bold" href="dashboard.php" data-i18n="brand">Find The Five</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="dashboard.php" data-i18n="nav_dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php?id=1" data-i18n="nav_profile">Profile</a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin.php" data-i18n="nav_admin">Admin</a></li>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2" data-i18n="admin_badge"><i class="fa-solid fa-user-gear"></i> Security Supervisor Panel</div>
        <h2 class="mb-1" data-i18n="admin_title">User management (mock)</h2>
        <p class="muted mb-0" data-i18n="admin_subtitle">This panel is for admins only. Buttons are placeholders until backend logic is added.</p>
      </div>
      <span class="chip pill-warning" data-i18n="admin_chip">Admin view</span>
    </div>

    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" data-i18n="admin_table_title">Users</h5>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-danger btn-sm" type="button" data-i18n="admin_reset_all">Reset all progress</button>
          <button class="btn btn-outline-secondary btn-sm" type="button" data-i18n="admin_export">Export CSV</button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th scope="col" data-i18n="admin_col_user">User</th>
              <th scope="col" data-i18n="admin_col_email">Email</th>
              <th scope="col" data-i18n="admin_col_role">Role</th>
              <th scope="col" data-i18n="admin_col_achievements">Achievements</th>
              <th scope="col" data-i18n="admin_col_actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="fw-semibold">Trainee</td>
              <td>student@example.com</td>
              <td><span class="chip">user</span></td>
              <td><span class="chip pill-success">2 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button" data-i18n="admin_reset_btn">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button" data-i18n="admin_delete_btn">Delete</button>
              </td>
            </tr>
            <tr>
              <td class="fw-semibold">Analyst</td>
              <td>analyst@example.com</td>
              <td><span class="chip">user</span></td>
              <td><span class="chip pill-warning">3 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button" data-i18n="admin_reset_btn">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button" data-i18n="admin_delete_btn">Delete</button>
              </td>
            </tr>
            <tr>
              <td class="fw-semibold">Admin</td>
              <td>admin@example.com</td>
              <td><span class="chip pill-success">admin</span></td>
              <td><span class="chip pill-success">5 / 5</span></td>
              <td class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button" data-i18n="admin_reset_btn">Reset progress</button>
                <button class="btn btn-outline-danger btn-sm" type="button" data-i18n="admin_delete_btn">Delete</button>
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
