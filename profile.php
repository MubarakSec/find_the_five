<?php
session_start();
include 'db.php';

// Redirect guests to login
if (empty($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
// نحفظ الجلسة كرقم
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
//نجهز تحذير اذا يشتي يغير الاي دي
$warning = null;

// نحذر من IDOR
$requestedId = isset($_GET['id']) ? (int) $_GET['id'] : $currentUserId;
if ($requestedId !== $currentUserId) {
  $warning = 'You can only view your own profile.';
  $requestedId = $currentUserId;
}
//تحميل الواجهة للمستخدم 
$userStmt = $connection->prepare('SELECT id, name, username, email, role FROM users WHERE id = ? LIMIT 1');
$userStmt->bind_param('i', $requestedId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult ? $userResult->fetch_assoc() : null;

if (!$user) {
  // اذا الجلسة واقفة, اخرج
  header('Location: logout.php');
  exit;
}

//يحمل بيانات المستخدم اذا موجودة
$profileStmt = $connection->prepare('SELECT bio, avatar_url FROM profiles WHERE user_id = ? LIMIT 1');
$profileStmt->bind_param('i', $user['id']);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profileRow = ($profileResult ? $profileResult->fetch_assoc() : null) ?? ['bio' => '', 'avatar_url' => ''];


$displayName = $user['name'] ?: $user['username'];
$avatarUrl = !empty($profileRow['avatar_url'])
  ? $profileRow['avatar_url']
  : 'https://api.dicebear.com/7.x/identicon/svg?seed=' . urlencode($user['username'] ?? 'user');
$bioText = trim((string) $profileRow['bio']) !== ''
  ? $profileRow['bio']
  : "Hi! I'm exploring web security. This bio is editable and intentionally unfiltered in the XSS lab.";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Profile</title>
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
          <li class="nav-item"><a class="nav-link" href="profile.php?id=<?php echo htmlspecialchars((string) $user['id'], ENT_QUOTES); ?>" data-i18n="nav_profile">Profile</a></li>
          <?php if (($user['role'] ?? 'user') === 'admin'): ?>
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
        <div class="pill mb-2" data-i18n="profile_badge"><i class="fa-solid fa-user"></i> Profile viewer</div>
        <h2 class="mb-0" data-i18n="profile_title">Security Trainee Profile</h2>
        <small class="muted"><?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></small>
      </div>
      <a href="update_profile.php" class="btn btn-primary" data-i18n="profile_edit_btn">Edit profile</a>
    </div>
    <?php if ($warning): ?>
      <div class="alert alert-warning small"><?php echo htmlspecialchars($warning, ENT_QUOTES); ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card p-4 text-center">
          <div class="avatar-wrapper mb-3">
            <img src="<?php echo htmlspecialchars($avatarUrl, ENT_QUOTES); ?>" alt="avatar" class="avatar-circle">
          </div>
          <h5 class="mb-1"><?php echo htmlspecialchars($displayName, ENT_QUOTES); ?></h5>
          <div class="muted" id="profileIdLabel">Profile ID: <?php echo htmlspecialchars((string) $user['id'], ENT_QUOTES); ?></div>
          <div class="d-flex justify-content-center gap-2 mt-3">
            <span class="chip"><?php echo htmlspecialchars($user['role'] ?? 'user', ENT_QUOTES); ?></span>
            <span class="chip pill-warning">You</span>
          </div>
          <a href="flag_lab.php" class="d-block mt-3 small">Finish line</a>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card p-4 mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="profile_about_title">About</h5>
            <small class="muted" data-i18n="profile_about_note">Editable in update_profile.php</small>
          </div>
          <p id="bioText"><?php echo nl2br(htmlspecialchars($bioText, ENT_QUOTES, 'UTF-8')); ?></p>
        </div>
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="profile_recent_title">Recent labs</h5>
            <a class="small" href="dashboard.php" data-i18n="profile_view_all">View all</a>
          </div>
          <div class="d-grid gap-3">
            <div class="soft-card p-3 d-flex align-items-center">
              <i class="fa-solid fa-database text-primary me-3"></i>
              <div>
                <div class="fw-semibold" data-i18n="profile_recent_sqli">SQL Injection lab</div>
                <small class="muted" data-i18n-html="profile_recent_sqli_hint">Try `' OR '1'='1`</small>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-achievement="sqli" data-status>Locked</span>
            </div>
            <div class="soft-card p-3 d-flex align-items-center">
              <i class="fa-solid fa-code text-primary me-3"></i>
              <div>
                <div class="fw-semibold" data-i18n="profile_recent_xss">Stored XSS lab</div>
                <small class="muted" data-i18n="profile_recent_xss_hint">Update your bio unsafely</small>
              </div>
              <span class="ms-auto badge bg-light text-dark" data-achievement="xss" data-status>Locked</span>
            </div>
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