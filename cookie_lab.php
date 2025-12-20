<?php
session_start();
include 'db.php';
require_once 'helpers.php';

if (empty($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$userId = (int) $_SESSION['user_id'];
$expectedFlag = getFlagValue($connection, 'cookie', 'FLAG{COOKIE_TRUST_IS_BAD}');
$flagMessage = null;
$flagType = 'info';
$cookieFlag = null;
$lang = currentLang();

if (!isset($_COOKIE['access_level'])) {
  setcookie('access_level', 'learner', time() + 86400, '/');
  $_COOKIE['access_level'] = 'learner';
}
$accessLevel = strtolower((string) ($_COOKIE['access_level'] ?? 'learner'));
$hasAdminCookie = in_array($accessLevel, ['admin', 'elite'], true);

// Load current user for nav.
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submitted = trim($_POST['flag'] ?? '');
  if ($submitted === '') {
    $flagMessage = tr('Please enter a flag value.', 'الرجاء إدخال قيمة العلم.', $lang);
    $flagType = 'danger';
  } elseif ($submitted !== $expectedFlag) {
    $flagMessage = tr('Flag is incorrect. Check the access_level cookie.', 'العلم غير صحيح، تحقق من كوكي access_level.', $lang);
    $flagType = 'danger';
  } else {
    unlockAchievement($connection, $userId, 'cookie');
    $flagMessage = tr('Cookie flag accepted. Achievement unlocked.', 'تم قبول العلم وفتح الإنجاز.', $lang);
    $flagType = 'success';
    $cookieFlag = $expectedFlag;
  }
}

if ($hasAdminCookie) {
  $cookieFlag = $expectedFlag;
  unlockAchievement($connection, $userId, 'cookie');
  if (!$flagMessage) {
    $flagMessage = tr('Cookie indicates elevated access. Flag unlocked.', 'الكوكي تشير لصلاحية مرتفعة، تم فتح العلم.', $lang);
    $flagType = 'success';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Cookie Tampering Lab</title>
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
        <div class="pill mb-2" data-i18n="cookie_badge"><i class="fa-solid fa-cookie-bite"></i> Cookie tampering lab</div>
        <h2 class="mb-1" data-i18n="cookie_title">Privilege stored in a cookie</h2>
        <p class="muted mb-0" data-i18n-html="cookie_subtitle">The application trusts the <code>access_level</code> cookie. Edit it to escalate your privileges and expose the admin-only flag.</p>
      </div>
      <span class="chip pill-warning" data-i18n="cookie_chip">Client-side trust</span>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="cookie_form_title">Cookie inspector</h5>
            <small class="muted" data-i18n="cookie_form_subtitle">Default: learner</small>
          </div>
          <div class="mb-3">
            <label class="form-label" data-i18n="cookie_current_label">Current cookies</label>
            <div class="small muted mb-1"><?php echo htmlspecialchars(tr('Detected', 'القيمة الحالية', $lang), ENT_QUOTES); ?> <code>access_level</code>: <span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($accessLevel, ENT_QUOTES); ?></span></div>
            <textarea class="form-control" rows="4" readonly><?php echo htmlspecialchars(http_build_query($_COOKIE, '', '; ') ?: tr('No cookies found.', 'لا توجد كوكيز.', $lang), ENT_QUOTES); ?></textarea>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#cookieHint" aria-expanded="false" aria-controls="cookieHint" data-i18n="hint_toggle">Show hint</button>
          <div class="collapse mt-3" id="cookieHint">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="cookie_goal_title">Goal</div>
              <span data-i18n-html="cookie_goal_text">Change the <code>access_level</code> cookie to a higher privilege value, then refresh. You can use browser devtools to edit cookies.</span>
            </div>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#cookieAnswer" aria-expanded="false" aria-controls="cookieAnswer" data-i18n="answer_toggle">Show answer</button>
          <div class="collapse mt-3" id="cookieAnswer">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="answer_title">Answer</div>
              <div class="small" data-i18n-html="cookie_answer">Open browser devtools, edit the <code>access_level</code> cookie to <code>admin</code>, refresh the page, and the flag will appear.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="cookie_flag_title">Admin flag</h5>
            <span class="chip" data-i18n="cookie_flag_chip">Hidden</span>
          </div>
          <p class="muted small mb-2" data-i18n="cookie_flag_desc">When the cookie indicates admin access, the restricted flag appears.</p>
          <?php if ($cookieFlag): ?>
            <div class="flag mb-2" id="cookieFlag"><?php echo htmlspecialchars($cookieFlag, ENT_QUOTES); ?></div>
          <?php else: ?>
            <div class="flag hidden-flag mb-2" id="cookieFlag"></div>
          <?php endif; ?>
          <?php if ($flagMessage): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flagType, ENT_QUOTES); ?> mt-3"><?php echo htmlspecialchars($flagMessage, ENT_QUOTES); ?></div>
          <?php endif; ?>
          <form class="mt-3" method="POST" action="cookie_lab.php" novalidate>
            <label class="form-label">Submit flag (server)</label>
            <input type="text" name="flag" class="form-control" placeholder="FLAG{...}" required>
            <button class="btn btn-outline-primary w-100 mt-2" type="submit" data-i18n="cookie_submit_btn">Submit flag</button>
          </form>
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
