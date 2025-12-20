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
$lang = currentLang();

$requestedId = trim($_GET['id'] ?? (string) $navProfileId);
$idorRecord = null;
$idorFlag = null;
$idorNotice = null;

if ($requestedId !== '') {
  if (ctype_digit($requestedId)) {
    $stmt = $connection->prepare('SELECT u.id, u.name, u.username, u.email, u.role, p.bio FROM users u LEFT JOIN profiles p ON p.user_id = u.id WHERE u.id = ? LIMIT 1');
    $idInt = (int) $requestedId;
    $stmt->bind_param('i', $idInt);
  } else {
    $stmt = $connection->prepare('SELECT u.id, u.name, u.username, u.email, u.role, p.bio FROM users u LEFT JOIN profiles p ON p.user_id = u.id WHERE u.username = ? LIMIT 1');
    $stmt->bind_param('s', $requestedId);
  }
  if ($stmt) {
    $stmt->execute();
    $recordResult = $stmt->get_result();
    $idorRecord = $recordResult ? $recordResult->fetch_assoc() : null;
  }
}

if ($idorRecord) {
  if ((int) ($idorRecord['id'] ?? 0) !== $navProfileId) {
    $idorFlag = getFlagValue($connection, 'idor', 'FLAG{IDOR_UNLOCKED_PROFILE}');
    unlockAchievement($connection, $navProfileId, 'idor');
    $idorNotice = tr("You viewed another user's data without authorization.", 'قرأت بيانات مستخدم آخر بدون صلاحية.', $lang);
  } else {
    $idorNotice = tr('This is your own profile. Change the id query to target another user.', 'هذا ملفك أنت. غيّر قيمة id لاستهداف مستخدم آخر.', $lang);
  }
} else {
  $idorNotice = tr('No profile found for that id.', 'لا يوجد ملف بهذا المعرّف.', $lang);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — IDOR Lab</title>
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
        <div class="pill mb-2" data-i18n="idor_badge"><i class="fa-solid fa-link-slash"></i> Insecure Direct Object Reference</div>
        <h2 class="mb-1" data-i18n="idor_title">Change the ID in the URL</h2>
        <p class="muted mb-0" data-i18n-html="idor_subtitle">This page fetches profile details solely by the <code>id</code> parameter with no authorization. Modify the id to read another user's data and uncover the flag.</p>
      </div>
      <div class="chip pill-warning" data-i18n="idor_chip">Access control missing</div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="idor_form_title">Profile payload</h5>
            <small class="muted" data-i18n="idor_form_subtitle">Query string driven</small>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#idorHint" aria-expanded="false" aria-controls="idorHint" data-i18n="hint_toggle">Show hint</button>
          <div class="collapse" id="idorHint">
            <div class="alert alert-info mb-0">
              <span data-i18n-html="idor_alert">Try changing the <code>id</code> value in the URL to a different user. In a real app this would be blocked server-side.</span>
            </div>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#idorAnswer" aria-expanded="false" aria-controls="idorAnswer" data-i18n="answer_toggle">Show answer</button>
          <div class="collapse mt-3" id="idorAnswer">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="answer_title">Answer</div>
              <div class="small" data-i18n-html="idor_answer">Change the URL to another id (for example <code>?id=2</code>), reload, and the flag will appear in the unauthorized data panel.</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="muted" data-i18n="idor_current">Current id:</span>
            <span class="badge bg-primary" id="idorCurrentId"><?php echo htmlspecialchars($requestedId, ENT_QUOTES); ?></span>
          </div>
          <div class="border rounded p-3 bg-light" id="idorRecordServer">
            <?php if ($idorRecord): ?>
              <div class="fw-semibold"><?php echo htmlspecialchars($idorRecord['name'] ?: $idorRecord['username'], ENT_QUOTES); ?> (id: <?php echo htmlspecialchars((string) $idorRecord['id'], ENT_QUOTES); ?>)</div>
              <div class="muted small">Email: <?php echo htmlspecialchars($idorRecord['email'] ?? tr('unknown', 'غير معروف', $lang), ENT_QUOTES); ?> — Role: <?php echo htmlspecialchars($idorRecord['role'] ?? 'user', ENT_QUOTES); ?></div>
              <div class="mt-2 small">Bio: <?php echo nl2br(htmlspecialchars($idorRecord['bio'] ?? tr('No bio saved.', 'لا توجد نبذة.', $lang), ENT_QUOTES)); ?></div>
            <?php else: ?>
              <div class="text-danger fw-semibold"><?php echo htmlspecialchars(tr('No records yet.', 'لا توجد سجلات.', $lang), ENT_QUOTES); ?></div>
            <?php endif; ?>
          </div>
          <?php if ($idorNotice): ?>
            <div class="alert alert-info mt-2"><?php echo htmlspecialchars($idorNotice, ENT_QUOTES); ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="idor_flag_title">Unauthorized data</h5>
            <span class="chip" data-i18n="idor_flag_chip">Sensitive</span>
          </div>
          <p class="muted small mb-2" data-i18n="idor_flag_desc">If you can read another user's profile or progress without permission, you've exploited the IDOR.</p>
          <?php if ($idorFlag): ?>
            <div class="flag" id="idorFlag"><?php echo htmlspecialchars($idorFlag, ENT_QUOTES); ?></div>
          <?php else: ?>
            <div class="flag hidden-flag" id="idorFlag"></div>
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
