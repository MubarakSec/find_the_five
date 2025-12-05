<?php
session_start();
// TODO: Restrict this endpoint to authenticated users
// TODO: Validate role changes on the server
// TODO: Log escalation attempts and enforce RBAC
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Privilege Escalation Lab</title>
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
        <div class="pill mb-2"><i class="fa-solid fa-user-shield"></i> Privilege Escalation lab</div>
        <h2 class="mb-1">Override the requested role</h2>
        <p class="muted mb-0">The backend trusts a client-supplied role field. Modify the payload to promote yourself to admin and expose the flag.</p>
      </div>
      <span class="chip pill-warning">No server-side validation</span>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Role request</h5>
            <small class="muted">Editable JSON</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" id="roleSelect">
              <option value="user" selected>User</option>
              <option value="analyst">Analyst</option>
              <option value="viewer">Viewer</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Intercepted request body</label>
            <textarea class="form-control" rows="6" id="rolePayload">{ "id": 1, "role": "user", "note": "promotion-request" }</textarea>
          </div>
          <button class="btn btn-primary w-100" id="sendRoleRequest" type="button">Send request (UI only)</button>
          <div class="alert alert-info mt-3">
            <div class="fw-semibold">Hint</div>
            Change <code>role</code> to <code>admin</code> in the JSON or via the select dropdown then send. No verification happens server-side.
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Escalation result</h5>
            <span class="chip">Simulated</span>
          </div>
          <div class="border rounded p-3 bg-light" id="roleResult">Awaiting request...</div>
          <div class="flag hidden-flag" id="privescFlag">FLAG{ROLE_TAMPERING_SUCCESS}</div>
          <button class="btn btn-outline-primary w-100 mt-3 flag-submit" type="button" data-achievement="privesc" data-flag-target="#privescFlag">Submit flag (frontend)</button>
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
