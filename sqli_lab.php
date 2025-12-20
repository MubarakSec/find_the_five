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

$inputUser = trim($_POST['sqli_input'] ?? '');
$inputPass = trim($_POST['sqli_password'] ?? '');
$queryString = null;
$queryError = null;
$resultRows = [];
$sqliFlag = null;
$sqliNotice = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Intentionally vulnerable concatenation for the lab.
  $queryString = "SELECT id, username, email, role FROM users WHERE username = '$inputUser' OR email = '$inputUser' AND password_hash = '$inputPass' LIMIT 5";
  try {
    $queryResult = $connection->query($queryString);
    if ($queryResult) {
      $resultRows = $queryResult->fetch_all(MYSQLI_ASSOC);
      $sqliNotice = $resultRows
        ? tr('Query executed. Check if an admin row slipped through.', 'تم تنفيذ الاستعلام، تحقق هل ظهر صف مدير بالخطأ.', $lang)
        : tr('0 rows returned.', '0 صفوف أُرجعت.', $lang);
      foreach ($resultRows as $row) {
        if (($row['role'] ?? '') === 'admin') {
          $sqliFlag = getFlagValue($connection, 'sqli', 'FLAG{SQLI_BYPASS_MASTER}');
          unlockAchievement($connection, $userId, 'sqli');
          $sqliNotice = tr('Admin record returned. Injection likely worked.', 'تم إرجاع سجل مدير، يبدو أن الحقن نجح.', $lang);
          break;
        }
      }
      // If no admin row was found but input looks injected, still reward the attempt.
      $payload = strtolower($inputUser);
      $looksInjected = strpos($payload, "' or") !== false || strpos($payload, '" or') !== false || strpos($payload, '1=1') !== false || strpos($payload, '--') !== false;
      if (!$sqliFlag && $looksInjected) {
        $sqliFlag = getFlagValue($connection, 'sqli', 'FLAG{SQLI_BYPASS_MASTER}');
        unlockAchievement($connection, $userId, 'sqli');
        $sqliNotice = tr('Payload looked injected. Flag unlocked for the lab.', 'الحمولة تبدو حقناً، تم فتح العلم للمختبر.', $lang);
      }
    }
  } catch (mysqli_sql_exception $e) {
    $queryError = $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find The Five — SQLi Lab</title>
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
        <div class="pill mb-2" data-i18n="sqli_badge"><i class="fa-solid fa-database"></i> SQL Injection lab</div>
        <h2 class="mb-1" data-i18n="sqli_title">Break the login query</h2>
        <p class="muted mb-0" data-i18n="sqli_subtitle">The query concatenates user input directly. Use a classic boolean-based injection to bypass the check and return the hidden admin flag.</p>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" data-i18n="sqli_form_title">Vulnerable search</h5>
            <span class="chip pill-warning" data-i18n="sqli_form_chip">String concatenation</span>
          </div>
          <form id="sqliServerForm" method="POST" action="sqli_lab.php" novalidate>
            <div class="mb-3">
              <label class="form-label" data-i18n="sqli_field_user">Username or email</label>
              <input type="text" class="form-control" id="sqliInput" name="sqli_input" value="<?php echo htmlspecialchars($inputUser, ENT_QUOTES); ?>" placeholder="Username or email" data-i18n-placeholder="sqli_placeholder_user">
            </div>
            <div class="mb-3">
              <label class="form-label" data-i18n="field_password">Password</label>
              <input type="password" class="form-control" name="sqli_password" placeholder="Password" data-i18n-placeholder="sqli_placeholder_pass">
            </div>
            <button class="btn btn-primary w-100" type="submit" data-i18n="sqli_run_btn">Run query</button>
          </form>
          <button class="btn btn-outline-primary btn-sm w-100 mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#sqliHint" aria-expanded="false" aria-controls="sqliHint" data-i18n="hint_toggle">Show hint</button>
          <div class="collapse mt-3" id="sqliHint">
            <div class="small muted" data-i18n-html="sqli_hint">Hint: The query is concatenated. Try changing the WHERE logic or commenting out the rest.</div>
          </div>
          <button class="btn btn-outline-primary btn-sm w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#sqliAnswer" aria-expanded="false" aria-controls="sqliAnswer" data-i18n="answer_toggle">Show answer</button>
          <div class="collapse mt-3" id="sqliAnswer">
            <div class="alert alert-info mb-0">
              <div class="fw-semibold" data-i18n="answer_title">Answer</div>
              <div class="small" data-i18n-html="sqli_answer">Use <code>' OR '1'='1 --</code> as the username/email, leave any password, then run the query to return the admin row.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0" data-i18n="sqli_result_title">Query output</h5>
            <span class="chip" data-i18n="sqli_result_chip">Live DB</span>
          </div>
          <div class="border rounded p-3 bg-light" id="sqliResult">
            <?php if ($queryString === null): ?>
              <div data-i18n="sqli_result_empty">No records yet.</div>
            <?php elseif ($queryError): ?>
              <div class="text-danger fw-semibold"><?php echo htmlspecialchars(tr('Query failed', 'فشل الاستعلام', $lang), ENT_QUOTES); ?></div>
              <div class="small muted"><?php echo htmlspecialchars($queryError, ENT_QUOTES); ?></div>
            <?php else: ?>
              <div class="small muted mb-1">Executed query:</div>
              <code class="d-block mb-2 text-break"><?php echo htmlspecialchars($queryString, ENT_QUOTES); ?></code>
              <?php if (empty($resultRows)): ?>
                <div class="text-danger fw-semibold"><?php echo htmlspecialchars(tr('0 rows returned.', '0 صفوف أُرجعت.', $lang), ENT_QUOTES); ?></div>
              <?php else: ?>
                <ul class="list-unstyled mb-0">
                  <?php foreach ($resultRows as $row): ?>
                    <li class="mb-1">
                      <span class="fw-semibold"><?php echo htmlspecialchars($row['username'] ?? $row['email'] ?? 'user', ENT_QUOTES); ?></span>
                      <span class="badge bg-light text-dark ms-1"><?php echo htmlspecialchars($row['role'] ?? 'user', ENT_QUOTES); ?></span>
                      <small class="muted ms-2"><?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES); ?></small>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            <?php endif; ?>
          </div>
          <?php if ($sqliNotice): ?>
            <div class="alert alert-info mt-2"><?php echo htmlspecialchars($sqliNotice, ENT_QUOTES); ?></div>
          <?php endif; ?>
          <?php if ($sqliFlag): ?>
            <div class="flag mt-3" id="sqliFlag"><?php echo htmlspecialchars($sqliFlag, ENT_QUOTES); ?></div>
          <?php else: ?>
            <div class="flag hidden-flag mt-3" id="sqliFlag"></div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    window.__serverAchievements = <?php echo json_encode($achievements); ?>;
    localStorage.setItem('ftf_achievements', JSON.stringify(window.__serverAchievements));
  </script>
  <script src="assets/js/app.js"></script>
</body>

</html>
