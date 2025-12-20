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
$lang = currentLang();
if (($currentUser['role'] ?? 'user') !== 'admin') {
  header('Location: dashboard.php');
  exit;
}
$navProfileId = (int) $currentUser['id'];

$adminNotice = null;
$adminNoticeType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $targetId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
  if ($action === 'reset_all') {
    $stmt = $connection->prepare('UPDATE achievements SET sqli = 0, idor = 0, xss = 0, cookie = 0, privesc = 0, `final` = 0, completed_at = NULL, updated_at = CURRENT_TIMESTAMP');
    if ($stmt && $stmt->execute()) {
      $adminNotice = tr('All progress has been reset.', 'تمت إعادة تعيين التقدم للجميع.', $lang);
      logAuditEvent($connection, $userId, 'admin_reset_all', 'achievements');
    } else {
      $adminNotice = tr('Could not reset progress.', 'تعذر إعادة تعيين التقدم.', $lang);
      $adminNoticeType = 'danger';
    }
  } elseif ($action === 'reset_user') {
    if ($targetId <= 0) {
      $adminNotice = tr('Please choose a user.', 'الرجاء اختيار مستخدم.', $lang);
      $adminNoticeType = 'danger';
    } else {
      $stmt = $connection->prepare('UPDATE achievements SET sqli = 0, idor = 0, xss = 0, cookie = 0, privesc = 0, `final` = 0, completed_at = NULL, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?');
      if ($stmt) {
        $stmt->bind_param('i', $targetId);
        $stmt->execute();
        $adminNotice = tr('Progress reset for selected user.', 'تمت إعادة تعيين التقدم للمستخدم المحدد.', $lang);
        logAuditEvent($connection, $userId, 'admin_reset_user', (string) $targetId);
      } else {
        $adminNotice = tr('Could not reset progress.', 'تعذر إعادة تعيين التقدم.', $lang);
        $adminNoticeType = 'danger';
      }
    }
  } elseif ($action === 'delete_user') {
    if ($targetId <= 0) {
      $adminNotice = tr('Please choose a user.', 'الرجاء اختيار مستخدم.', $lang);
      $adminNoticeType = 'danger';
    } elseif ($targetId === $userId) {
      $adminNotice = tr('You cannot delete your own account.', 'لا يمكنك حذف حسابك.', $lang);
      $adminNoticeType = 'danger';
    } else {
      $stmt = $connection->prepare('DELETE FROM users WHERE id = ?');
      if ($stmt) {
        $stmt->bind_param('i', $targetId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
          $adminNotice = tr('User deleted.', 'تم حذف المستخدم.', $lang);
          logAuditEvent($connection, $userId, 'admin_delete_user', (string) $targetId);
        } else {
          $adminNotice = tr('User not found.', 'المستخدم غير موجود.', $lang);
          $adminNoticeType = 'danger';
        }
      } else {
        $adminNotice = tr('Could not delete user.', 'تعذر حذف المستخدم.', $lang);
        $adminNoticeType = 'danger';
      }
    }
  } else {
    $adminNotice = tr('Unknown action.', 'إجراء غير معروف.', $lang);
    $adminNoticeType = 'danger';
  }
}

// Simple user list with achievement totals (no restrictions to keep it approachable).
$users = [];
$hasFinalColumn = true;
$usersStmt = $connection->query('SELECT u.id, u.name, u.username, u.email, u.role,
  COALESCE(a.sqli, 0) AS sqli,
  COALESCE(a.idor, 0) AS idor,
  COALESCE(a.xss, 0) AS xss,
  COALESCE(a.cookie, 0) AS cookie,
  COALESCE(a.privesc, 0) AS privesc,
  COALESCE(a.`final`, 0) AS final
  FROM users u
  LEFT JOIN achievements a ON a.user_id = u.id
  ORDER BY u.id ASC
  LIMIT 50');
if (!$usersStmt) {
  $hasFinalColumn = false;
  $usersStmt = $connection->query('SELECT u.id, u.name, u.username, u.email, u.role,
    COALESCE(a.sqli, 0) AS sqli,
    COALESCE(a.idor, 0) AS idor,
    COALESCE(a.xss, 0) AS xss,
    COALESCE(a.cookie, 0) AS cookie,
    COALESCE(a.privesc, 0) AS privesc
    FROM users u
    LEFT JOIN achievements a ON a.user_id = u.id
    ORDER BY u.id ASC
    LIMIT 50');
}
if ($usersStmt) {
  $users = $usersStmt->fetch_all(MYSQLI_ASSOC);
}
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
          <li class="nav-item"><a class="nav-link" href="profile.php?id=<?php echo htmlspecialchars((string) $navProfileId, ENT_QUOTES); ?>" data-i18n="nav_profile">Profile</a></li>
          <?php if (($currentUser['role'] ?? 'user') === 'admin'): ?>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin.php" data-i18n="nav_admin">Admin</a></li>
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

  <main class="container page-hero">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="pill mb-2" data-i18n="admin_badge"><i class="fa-solid fa-user-gear"></i> Security Supervisor Panel</div>
        <h2 class="mb-1" data-i18n="admin_title">User management</h2>
        <p class="muted mb-0" data-i18n="admin_subtitle">Manage users and lab progress.</p>
      </div>
      <span class="chip pill-warning" data-i18n="admin_chip">Admin view</span>
    </div>

    <?php if ($adminNotice): ?>
      <div class="alert alert-<?php echo htmlspecialchars($adminNoticeType, ENT_QUOTES); ?> mb-3"><?php echo htmlspecialchars($adminNotice, ENT_QUOTES); ?></div>
    <?php endif; ?>

    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" data-i18n="admin_table_title">Users</h5>
        <div class="d-flex gap-2">
          <form method="POST" action="admin.php" class="m-0">
            <input type="hidden" name="action" value="reset_all">
            <button class="btn btn-outline-danger btn-sm" type="submit" data-i18n="admin_reset_all">Reset all progress</button>
          </form>
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
              <th scope="col" data-i18n="admin_col_final">Final</th>
              <th scope="col" data-i18n="admin_col_actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr><td colspan="6" class="text-center text-muted"><?php echo htmlspecialchars(tr('No users found.', 'لا يوجد مستخدمون.', currentLang()), ENT_QUOTES); ?></td></tr>
            <?php else: ?>
              <?php foreach ($users as $u): ?>
                <?php
                  $achCount = (int) ($u['sqli'] ?? 0) + (int) ($u['idor'] ?? 0) + (int) ($u['xss'] ?? 0) + (int) ($u['cookie'] ?? 0) + (int) ($u['privesc'] ?? 0);
                  $achLabel = $achCount . ' / 5';
                  $finalDone = $hasFinalColumn ? ((int) ($u['final'] ?? 0) === 1) : ($achCount >= 5);
                ?>
                <tr>
                  <td class="fw-semibold"><?php echo htmlspecialchars($u['name'] ?: $u['username'], ENT_QUOTES); ?></td>
                  <td><?php echo htmlspecialchars($u['email'] ?? 'unknown', ENT_QUOTES); ?></td>
                  <td><span class="chip <?php echo ($u['role'] === 'admin') ? 'pill-success' : ''; ?>"><?php echo htmlspecialchars($u['role'] ?? 'user', ENT_QUOTES); ?></span></td>
                  <td><span class="chip <?php echo $achCount >= 5 ? 'pill-success' : 'pill-warning'; ?>"><?php echo htmlspecialchars($achLabel, ENT_QUOTES); ?></span></td>
                  <td><span class="chip <?php echo $finalDone ? 'pill-success' : 'pill-warning'; ?>" data-i18n="<?php echo $finalDone ? 'status_unlocked' : 'status_locked'; ?>"><?php echo $finalDone ? 'Unlocked' : 'Locked'; ?></span></td>
                  <td>
                    <div class="d-flex gap-2">
                      <form method="POST" action="admin.php" class="m-0">
                        <input type="hidden" name="action" value="reset_user">
                        <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
                        <button class="btn btn-outline-primary btn-sm" type="submit" data-i18n="admin_reset_btn">Reset progress</button>
                      </form>
                      <form method="POST" action="admin.php" class="m-0">
                        <input type="hidden" name="action" value="delete_user">
                        <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
                        <button class="btn btn-outline-danger btn-sm" type="submit" data-i18n="admin_delete_btn">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
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
