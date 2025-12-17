<?php
session_start();
include 'db.php';
require_once 'helpers.php';

// جلب الكود الرئيسي من جدول الأعلام مع وجود بديل من البيئة 
$expectedCode = getFlagValue(
  $connection,
  'final_code',
  trim((string) getenv('FTF_MASTER_CODE'))
);

$finalFlag = getFlagValue(
  $connection,
  'final_flag',
  trim((string) getenv('FTF_FINAL_FLAG'))
);
$flagConfigured = $expectedCode !== '' && $finalFlag !== '';

// تحقق من تسجيل الدخول
if (empty($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// تخزين معرف المستخدم المسجل كعدد صحيح 
$userId = (int) $_SESSION['user_id'];
$userRole = $_SESSION['role'] ?? 'user';
$navProfileId = $userId;

// تتبع حالة الفتح في الجلسة للبساطة.
$flagUnlocked = !empty($_SESSION['final_flag_unlocked']);
$progressPercent = $flagUnlocked ? 100 : 0;
$flagMessage = $flagUnlocked && $flagConfigured ? 'Final flag unlocked: ' . $finalFlag : null;
$flagType = $flagUnlocked ? 'success' : 'info';


// معالجة إرسال النموذج.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submitted = trim($_POST['master_code'] ?? '');

  if (!$flagConfigured) {
    $flagMessage = 'Final flag is not configured on the server. Please contact an admin.';
    $flagType = 'warning';
  } elseif ($submitted === '') {
    $flagMessage = 'Please enter the master code.';
    $flagType = 'danger';
  } elseif (!hash_equals(strtoupper($expectedCode), strtoupper($submitted))) {
    $flagMessage = 'Master code is incorrect. Keep looking.';
    $flagType = 'danger';
  } else {
    $_SESSION['final_flag_unlocked'] = true;
    $flagUnlocked = true;
    $progressPercent = 100;
    $flagMessage = 'Final flag unlocked: ' . $finalFlag;
    $flagType = 'success';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Final Flag</title>
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
          <?php if ($userRole === 'admin'): ?>
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
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-2" data-i18n="final_badge"><i class="fa-solid fa-flag"></i> Final flag</div>
        <h2 class="mb-1" data-i18n="final_title">Finish line</h2>
        <p class="muted mb-0" data-i18n="final_subtitle">You should now have 5 lab flags. Combine your knowledge or inspect the page to discover the master code that reveals the final trophy flag.</p>
      </div>
      <div class="col-lg-5 mt-3 mt-lg-0">
        <div class="soft-card p-3">
          <div class="fw-semibold mb-1" data-i18n="final_progress_title">Completion check</div>
          <div class="progress mb-2" role="progressbar">
            <div class="progress-bar" id="finalProgressBar"></div>
          </div>
          <small class="muted" data-i18n="final_progress_note">Progress is stored locally in your browser for demo purposes.</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="final_form_title">Submit master code</h5>
            <span class="chip pill-warning" data-i18n="final_form_chip">Server validation</span>
          </div>
          <?php if ($flagMessage): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flagType, ENT_QUOTES); ?>"><?php echo htmlspecialchars($flagMessage, ENT_QUOTES); ?></div>
          <?php endif; ?>
          <p class="muted" data-i18n="final_form_desc">Use the master code you earned by finishing the labs (or got from your instructor). The code is validated on the server, so page source or devtools will not reveal it.</p>
          <form id="finalFlagForm" method="POST" action="flag_lab.php" novalidate>
            <div class="mb-3">
              <label class="form-label" data-i18n="final_field_label">Master code</label>
              <input type="text" class="form-control" id="finalCodeInput" placeholder="FTF-????-????" data-i18n-placeholder="final_field_placeholder" name="master_code" required>
            </div>
            <button class="btn btn-primary w-100" type="submit" data-i18n="final_reveal_btn">Reveal final flag</button>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="final_flag_title">Final flag</h5>
            <span class="chip pill-success" data-i18n="final_flag_chip">Trophy</span>
          </div>
          <div class="flag hidden-flag" id="finalFlag" data-flag-key="final"></div>
          <button class="btn btn-outline-primary w-100 mt-3" type="button" id="finalFlagSubmit" data-i18n="final_submit_btn">Submit flag</button>
          <div class="alert alert-light border mt-3">
            <div class="fw-semibold" data-i18n="final_note_title">Note</div>
            <span data-i18n="final_note_text">Server checks the master code and only then returns the final flag; no secrets are embedded in the page.</span>
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