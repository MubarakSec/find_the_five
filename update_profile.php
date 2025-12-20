<?php
session_start();
include 'db.php';
require_once 'helpers.php';

// Redirect guests to login
if (empty($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
// يحفظ الاي دي كرقم
$userId = (int) $_SESSION['user_id'];
// يتجهز لأي غلط
$errors = [];
// حق رساله انه تم بعد الحفظ
$success = null;
$xssFlag = null;
$lang = currentLang();

// يتم التجهيز للمستخدم الحالي اذا كان مابش يرده
$userStmt = $connection->prepare('SELECT id, name, username, email, role FROM users WHERE id = ? LIMIT 1');
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult ? $userResult->fetch_assoc() : null;
if (!$user) {
  header('Location: logout.php');
  exit;
}

$achievements = getUserAchievements($connection, (int) $user['id']);
// يحمل المستخدم اذا كان جاهز
$profileStmt = $connection->prepare('SELECT bio, avatar_url FROM profiles WHERE user_id = ? LIMIT 1');
$profileStmt->bind_param('i', $userId);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profileRow = $profileResult ? $profileResult->fetch_assoc() : null;
$hasProfile = (bool) $profileRow;
if (!$profileRow) {
  $profileRow = ['bio' => '', 'avatar_url' => ''];
}

//تجهيز لحقل form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bioInput = trim($_POST['bio'] ?? '');
  $avatarInput = trim($_POST['avatar_url'] ?? '');
  $uploadedPath = $profileRow['avatar_url'] ?? '';

  if (strlen($bioInput) > 2000) {
    $errors[] = tr('Bio must be 2000 characters or less.', 'يجب ألا تتجاوز النبذة 2000 حرف.', $lang);
  }

  // Optional remote URL validation.
  $isLocalUpload = $avatarInput !== '' && preg_match('#^/?assets/uploads/#', $avatarInput);
  if ($avatarInput !== '' && !filter_var($avatarInput, FILTER_VALIDATE_URL) && !$isLocalUpload) {
    $errors[] = 'Avatar URL must be a valid URL or left blank.';
  }

  // Optional file upload for avatar.
  if (!empty($_FILES['avatar_file']['name'])) {
    $file = $_FILES['avatar_file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $file['tmp_name']);
      finfo_close($finfo);
      $allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
      if (!isset($allowedMimes[$mime])) {
        $errors[] = 'Avatar file must be JPG, PNG, GIF, or WEBP.';
      } else {
        $ext = $allowedMimes[$mime];
        $uploadDir = __DIR__ . '/assets/uploads';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }
        $filename = 'avatar_' . $userId . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetPath = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
          $uploadedPath = 'assets/uploads/' . $filename;
          $avatarInput = $uploadedPath;
        } else {
          $errors[] = tr('Could not save uploaded avatar.', 'تعذر حفظ الصورة.', $lang);
        }
      }
    } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
      $errors[] = tr('Error uploading avatar.', 'حدث خطأ أثناء رفع الصورة.', $lang);
    }
  }

  if (empty($errors)) {
    if ($hasProfile) {
      $stmt = $connection->prepare('UPDATE profiles SET bio = ?, avatar_url = ? WHERE user_id = ?');
      $stmt->bind_param('ssi', $bioInput, $avatarInput, $userId);
    } else {
      $stmt = $connection->prepare('INSERT INTO profiles (bio, avatar_url, user_id) VALUES (?, ?, ?)');
      $stmt->bind_param('ssi', $bioInput, $avatarInput, $userId);
      $hasProfile = true;
    }
    $stmt->execute();
    $success = tr('Profile updated.', 'تم تحديث الملف.', $lang);
    $profileRow['bio'] = $bioInput;
    $profileRow['avatar_url'] = $avatarInput;
    if (stripos($bioInput, '<script') !== false) {
      $xssFlag = getFlagValue($connection, 'xss', 'FLAG{STORED_XSS_OWNED}');
      unlockAchievement($connection, $userId, 'xss');
      $success = tr('Stored XSS payload detected. Flag unlocked.', 'تم اكتشاف حمولة XSS مخزّنة، فُتح العلم.', $lang);
    }
  }
}

$bioValue = (string) ($profileRow['bio'] ?? '');
if (!$xssFlag && stripos($bioValue, '<script') !== false) {
  $xssFlag = getFlagValue($connection, 'xss', 'FLAG{STORED_XSS_OWNED}');
  unlockAchievement($connection, $userId, 'xss');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — Update Profile / XSS Lab</title>
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

  <main class="container page-hero" style="max-width: 1000px;">
    <div class="row align-items-center mb-4">
      <div class="col-lg-7">
        <div class="pill mb-2" data-i18n="xss_badge"><i class="fa-solid fa-code"></i> Stored XSS lab</div>
        <h2 class="mb-1" data-i18n="xss_title">Update your bio (and break it)</h2>
        <p class="muted mb-0" data-i18n="xss_subtitle">Your bio is stored and rendered without sanitization. Insert a &lt;script&gt; tag to simulate stored XSS and unlock the flag.</p>
      </div>
      <div class="col-lg-5 mt-3 mt-lg-0">
        <div class="soft-card p-3">
          <div class="fw-semibold mb-1" data-i18n="xss_target_title">Target</div>
          <ul class="lab-steps">
            <li data-i18n="xss_step1">Write a script tag inside your bio</li>
            <li data-i18n="xss_step2">Save the bio</li>
            <li data-i18n="xss_step3">The flag appears after saving</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8 mx-auto">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="xss_form_title">Edit bio</h5>
            <span class="chip pill-warning" data-i18n="xss_form_chip">Unsanitized</span>
          </div>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $err): ?>
                <div><?php echo htmlspecialchars($err, ENT_QUOTES); ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
          <?php endif; ?>
          <form method="POST" action="update_profile.php" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
              <label class="form-label" data-i18n="xss_field_bio">Bio text</label>
              <textarea class="form-control" name="bio" rows="6" placeholder="Write about yourself..." data-i18n-placeholder="xss_placeholder_bio"><?php echo htmlspecialchars($profileRow['bio'] ?? '', ENT_QUOTES); ?></textarea>
            </div>
            <input type="hidden" class="form-control" name="avatar_url" value="<?php echo htmlspecialchars($profileRow['avatar_url'] ?? '', ENT_QUOTES); ?>">
            <div class="mb-3">
              <label class="form-label">Upload avatar (JPG, PNG, GIF, WEBP)</label>
              <input type="file" class="form-control" name="avatar_file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
            </div>
            <button class="btn btn-primary w-100" type="submit" data-i18n="xss_save_btn">Save bio</button>
          </form>
          <button class="btn btn-outline-primary btn-sm w-100 mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#xssHint" aria-expanded="false" aria-controls="xssHint" data-i18n="hint_toggle">Show hint</button>
          <div class="collapse mt-3" id="xssHint">
            <small class="muted" data-i18n="xss_hint">Psst: Stored XSS means the script will live in the database and run for anyone viewing your profile.</small>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#xssAnswer" aria-expanded="false" aria-controls="xssAnswer" data-i18n="answer_toggle">Show answer</button>
          <div class="collapse mt-3" id="xssAnswer">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="answer_title">Answer</div>
              <div class="small" data-i18n-html="xss_answer">Paste <code>&lt;script&gt;alert(1)&lt;/script&gt;</code> into the bio, save, and the flag will unlock.</div>
            </div>
          </div>
          <?php if ($xssFlag): ?>
            <div class="flag mt-3" id="xssFlag"><?php echo htmlspecialchars($xssFlag, ENT_QUOTES); ?></div>
          <?php else: ?>
            <div class="flag hidden-flag mt-3" id="xssFlag"></div>
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
