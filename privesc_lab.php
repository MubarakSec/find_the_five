<?php
session_start();
include 'db.php';
require_once 'helpers.php';
if (empty($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$userId = (int) $_SESSION['user_id'];
$userStmt = $connection->prepare('SELECT id, name, username, email, role FROM users WHERE id = ? LIMIT 1');
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$currentUser = $userResult ? $userResult->fetch_assoc() : null;
if (!$currentUser) {
  header('Location: logout.php');
  exit;
}
$navProfileId = (int) $currentUser['id'];
$achievements = getUserAchievements($connection, $userId);
$lang = currentLang();

$requestedRole = null;
$privescFlag = null;
$privescMessage = null;
$privescType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $requestedRole = trim($_POST['role'] ?? '');
  $payloadJson = trim($_POST['payload_json'] ?? '');
  if ($payloadJson !== '') {
    $decoded = json_decode($payloadJson, true);
    if (is_array($decoded) && isset($decoded['role'])) {
      $requestedRole = (string) $decoded['role'];
    }
  }
  if ($requestedRole === '') {
    $requestedRole = 'user';
  }

  // Intentionally no validation — trusts the client supplied role.
  $update = $connection->prepare('UPDATE users SET role = ? WHERE id = ?');
  if ($update) {
    $update->bind_param('si', $requestedRole, $userId);
    $update->execute();
    $currentUser['role'] = $requestedRole;
    $privescMessage = tr("Role updated to {$requestedRole} without verification.", "تم تحديث الدور إلى {$requestedRole} بدون تحقق.", $lang);
    $privescType = 'success';
  } else {
    $privescMessage = tr('Could not update role.', 'تعذر تحديث الدور.', $lang);
    $privescType = 'danger';
  }

  if (strtolower($requestedRole) === 'admin') {
    $privescFlag = getFlagValue($connection, 'privesc', 'FLAG{ROLE_TAMPERING_SUCCESS}');
    unlockAchievement($connection, $userId, 'privesc');
  }
}
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
      <a class="navbar-brand fw-bold" href="dashboard.php" data-i18n="brand">Find The Five</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="dashboard.php" data-i18n="nav_dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php?id=<?php echo htmlspecialchars((string) $navProfileId, ENT_QUOTES); ?>" data-i18n="nav_profile">Profile</a></li>
          <?php if (($currentUser['role'] ?? 'user') === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="admin.php" data-i18n="nav_admin">Admin</a></li>
          <?php endif; ?>
          <li class="nav-item ms-3"><a class="btn btn-outline-primary" href="logout.php" data-i18n="nav_logout">Logout</a></li>
        </ul>
        <div class="ms-3 d-flex gap-1">
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="en">EN</button>
          <button class="btn btn-sm btn-outline-secondary" type="button" data-lang-select="ar">ع</button>
        </div>
      </div>
    </div>
  </nav>

  <main class="container page-hero" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2" data-i18n="privesc_badge"><i class="fa-solid fa-user-shield"></i> Privilege Escalation lab</div>
        <h2 class="mb-1" data-i18n="privesc_title">Override the requested role</h2>
        <p class="muted mb-0" data-i18n="privesc_subtitle">The backend trusts a client-supplied role field. Modify the payload to promote yourself to admin and expose the flag.</p>
      </div>
      <span class="chip pill-warning" data-i18n="privesc_chip">No server-side validation</span>
    </div>

      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0" data-i18n="privesc_form_title">Role request</h5>
              <small class="muted" data-i18n="privesc_form_subtitle">Editable JSON</small>
            </div>
          <form method="POST" action="privesc_lab.php" id="privescForm" novalidate>
            <div class="mb-3">
              <label class="form-label" data-i18n="privesc_payload_label">Intercepted request body</label>
              <textarea class="form-control" rows="6" id="rolePayload" name="payload_json">{ "id": <?php echo htmlspecialchars((string) $navProfileId, ENT_QUOTES); ?>, "role": "<?php echo htmlspecialchars($requestedRole ?? 'user', ENT_QUOTES); ?>", "note": "promotion-request" }</textarea>
            </div>
            <button class="btn btn-primary w-100" id="sendRoleRequest" type="submit" data-i18n="privesc_send_btn">Send request</button>
          </form>
          <button class="btn btn-outline-primary btn-sm w-100 mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#privescHint" aria-expanded="false" aria-controls="privescHint" data-i18n="hint_toggle">Show hint</button>
          <div class="collapse mt-3" id="privescHint">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="privesc_hint_title">Hint</div>
              <span data-i18n-html="privesc_hint_text">Modify the <code>role</code> field in the JSON payload to a higher-privilege value and submit. No verification happens server-side.</span>
            </div>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#privescAnswer" aria-expanded="false" aria-controls="privescAnswer" data-i18n="answer_toggle">Show answer</button>
          <div class="collapse mt-3" id="privescAnswer">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="answer_title">Answer</div>
              <div class="small" data-i18n-html="privesc_answer">Edit the JSON payload so <code>role</code> becomes <code>admin</code>, submit the request, and the flag will appear.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="privesc_result_title">Escalation result</h5>
            <span class="chip" data-i18n="privesc_result_chip">Server response</span>
          </div>
          <div class="border rounded p-3 bg-light" id="roleResult">
            <?php if ($requestedRole === null): ?>
              <div data-i18n="privesc_result_empty">Awaiting request...</div>
            <?php else: ?>
              <div class="fw-semibold">Requested role: <?php echo htmlspecialchars($requestedRole, ENT_QUOTES); ?></div>
              <div class="muted small">Server trusted this value and updated your account.</div>
            <?php endif; ?>
          </div>
          <?php if ($privescMessage): ?>
            <div class="alert alert-<?php echo htmlspecialchars($privescType, ENT_QUOTES); ?> mt-2"><?php echo htmlspecialchars($privescMessage, ENT_QUOTES); ?></div>
          <?php endif; ?>
          <?php if ($privescFlag): ?>
            <div class="flag mt-3" id="privescFlag"><?php echo htmlspecialchars($privescFlag, ENT_QUOTES); ?></div>
          <?php else: ?>
            <div class="flag hidden-flag" id="privescFlag"></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer text-center">
    <div class="container">
      Find The Five — Security Training App v1.0
    </div>
  </footer>

  <script>
    window.__serverAchievements = <?php echo json_encode($achievements); ?>;
    localStorage.setItem('ftf_achievements', JSON.stringify(window.__serverAchievements));
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
</body>

</html>
